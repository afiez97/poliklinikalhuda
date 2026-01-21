<?php

namespace App\Services;

use App\Models\BillingSetting;
use App\Models\Deposit;
use App\Models\Encounter;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Patient;
use App\Models\PatientVisit;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
    public function __construct(
        protected AuditService $auditService
    ) {}

    /**
     * Get paginated invoices with filters.
     */
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Invoice::with(['patient', 'items', 'payments']);

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhereHas('patient', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('ic_number', 'like', "%{$search}%")
                            ->orWhere('mrn', 'like', "%{$search}%");
                    });
            });
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['patient_id'])) {
            $query->where('patient_id', $filters['patient_id']);
        }

        if (! empty($filters['date_from'])) {
            $query->whereDate('invoice_date', '>=', $filters['date_from']);
        }

        if (! empty($filters['date_to'])) {
            $query->whereDate('invoice_date', '<=', $filters['date_to']);
        }

        if (! empty($filters['payment_status'])) {
            match ($filters['payment_status']) {
                'paid' => $query->where('balance', 0),
                'partial' => $query->where('balance', '>', 0)->where('balance', '<', DB::raw('grand_total')),
                'unpaid' => $query->where('balance', DB::raw('grand_total')),
                default => null,
            };
        }

        return $query->latest('invoice_date')->paginate($perPage);
    }

    /**
     * Create invoice from encounter.
     */
    public function createFromEncounter(Encounter $encounter, ?int $createdBy = null): Invoice
    {
        return DB::transaction(function () use ($encounter, $createdBy) {
            $patient = $encounter->patient;
            $visit = $encounter->patientVisit;

            $invoice = Invoice::create([
                'invoice_number' => Invoice::generateInvoiceNumber(),
                'patient_id' => $patient->id,
                'patient_visit_id' => $visit?->id,
                'encounter_id' => $encounter->id,
                'invoice_date' => now(),
                'due_date' => now()->addDays(BillingSetting::getPaymentTermsDays()),
                'status' => Invoice::STATUS_DRAFT,
                'created_by' => $createdBy,
            ]);

            // Add consultation fee
            $this->addConsultationFee($invoice, $encounter);

            // Add procedure fees
            $this->addProcedureFees($invoice, $encounter);

            // Add prescription fees
            $this->addPrescriptionFees($invoice, $encounter);

            // Calculate totals
            $invoice->calculateTotals();

            $this->auditService->log(
                'invoice',
                'create',
                $invoice,
                ['encounter_id' => $encounter->id]
            );

            return $invoice->fresh(['patient', 'items', 'payments']);
        });
    }

    /**
     * Create manual invoice.
     */
    public function createManual(array $data, ?int $createdBy = null): Invoice
    {
        return DB::transaction(function () use ($data, $createdBy) {
            $invoice = Invoice::create([
                'invoice_number' => Invoice::generateInvoiceNumber(),
                'patient_id' => $data['patient_id'],
                'invoice_date' => $data['invoice_date'] ?? now(),
                'due_date' => $data['due_date'] ?? now()->addDays(BillingSetting::getPaymentTermsDays()),
                'notes' => $data['notes'] ?? null,
                'status' => Invoice::STATUS_DRAFT,
                'created_by' => $createdBy,
            ]);

            // Add items
            if (! empty($data['items'])) {
                foreach ($data['items'] as $item) {
                    $invoice->items()->create([
                        'item_type' => $item['item_type'],
                        'item_name' => $item['item_name'],
                        'item_code' => $item['item_code'] ?? null,
                        'quantity' => $item['quantity'] ?? 1,
                        'unit_price' => $item['unit_price'],
                        'discount_amount' => $item['discount_amount'] ?? 0,
                        'is_taxable' => $item['is_taxable'] ?? true,
                    ]);
                }
            }

            $invoice->calculateTotals();

            $this->auditService->log('invoice', 'create', $invoice);

            return $invoice->fresh(['patient', 'items']);
        });
    }

    /**
     * Add item to invoice.
     */
    public function addItem(Invoice $invoice, array $data): InvoiceItem
    {
        $item = $invoice->items()->create([
            'item_type' => $data['item_type'],
            'item_name' => $data['item_name'],
            'item_code' => $data['item_code'] ?? null,
            'quantity' => $data['quantity'] ?? 1,
            'unit_price' => $data['unit_price'],
            'discount_amount' => $data['discount_amount'] ?? 0,
            'is_taxable' => $data['is_taxable'] ?? true,
            'billable_type' => $data['billable_type'] ?? null,
            'billable_id' => $data['billable_id'] ?? null,
        ]);

        $invoice->calculateTotals();

        return $item;
    }

    /**
     * Remove item from invoice.
     */
    public function removeItem(Invoice $invoice, int $itemId): bool
    {
        $item = $invoice->items()->find($itemId);
        if (! $item) {
            return false;
        }

        $item->delete();
        $invoice->calculateTotals();

        return true;
    }

    /**
     * Apply discount to invoice.
     */
    public function applyDiscount(Invoice $invoice, string $type, float $value, ?int $approvedBy = null): Invoice
    {
        return DB::transaction(function () use ($invoice, $type, $value, $approvedBy) {
            $subtotal = $invoice->subtotal;

            if ($type === 'percentage') {
                $discountAmount = $subtotal * ($value / 100);
            } else {
                $discountAmount = $value;
            }

            $invoice->discount_type = $type;
            $invoice->discount_value = $value;
            $invoice->discount_amount = $discountAmount;
            $invoice->save();

            $invoice->calculateTotals();

            $this->auditService->log(
                'invoice',
                'apply_discount',
                $invoice,
                [
                    'discount_type' => $type,
                    'discount_value' => $value,
                    'discount_amount' => $discountAmount,
                    'approved_by' => $approvedBy,
                ]
            );

            return $invoice;
        });
    }

    /**
     * Apply promo code.
     */
    public function applyPromoCode(Invoice $invoice, string $code): Invoice|string
    {
        $promo = \App\Models\PromoCode::where('code', $code)->first();

        if (! $promo) {
            return 'Kod promo tidak dijumpai';
        }

        if (! $promo->isValid()) {
            return 'Kod promo tidak sah atau telah tamat tempoh';
        }

        if ($promo->min_purchase && $invoice->subtotal < $promo->min_purchase) {
            return 'Jumlah pembelian minimum tidak dicapai (RM '.number_format($promo->min_purchase, 2).')';
        }

        $discountAmount = $promo->calculateDiscount($invoice->subtotal);

        $invoice->promo_code_id = $promo->id;
        $invoice->discount_type = $promo->discount_type;
        $invoice->discount_value = $promo->discount_value;
        $invoice->discount_amount = $discountAmount;
        $invoice->save();

        $promo->incrementUsage();
        $invoice->calculateTotals();

        $this->auditService->log(
            'invoice',
            'apply_promo',
            $invoice,
            ['promo_code' => $code, 'discount_amount' => $discountAmount]
        );

        return $invoice;
    }

    /**
     * Finalize invoice.
     */
    public function finalize(Invoice $invoice, ?int $userId = null): Invoice
    {
        $invoice->status = Invoice::STATUS_ISSUED;
        $invoice->save();

        $this->auditService->log('invoice', 'finalize', $invoice);

        return $invoice;
    }

    /**
     * Void invoice.
     */
    public function void(Invoice $invoice, string $reason, ?int $userId = null): Invoice
    {
        if ($invoice->paid_amount > 0) {
            throw new \Exception('Tidak boleh batalkan invois yang telah dibayar');
        }

        $invoice->status = Invoice::STATUS_VOID;
        $invoice->void_reason = $reason;
        $invoice->voided_at = now();
        $invoice->voided_by = $userId;
        $invoice->save();

        $this->auditService->log(
            'invoice',
            'void',
            $invoice,
            ['reason' => $reason]
        );

        return $invoice;
    }

    /**
     * Get patient outstanding invoices.
     */
    public function getPatientOutstanding(int $patientId): Collection
    {
        return Invoice::where('patient_id', $patientId)
            ->where('balance', '>', 0)
            ->whereIn('status', [Invoice::STATUS_ISSUED, Invoice::STATUS_PARTIAL])
            ->orderBy('due_date')
            ->get();
    }

    /**
     * Get outstanding summary.
     */
    public function getOutstandingSummary(): array
    {
        $invoices = Invoice::where('balance', '>', 0)
            ->whereIn('status', [Invoice::STATUS_ISSUED, Invoice::STATUS_PARTIAL])
            ->get();

        $current = $invoices->filter(fn ($i) => $i->days_overdue <= 0)->sum('balance');
        $overdue30 = $invoices->filter(fn ($i) => $i->days_overdue > 0 && $i->days_overdue <= 30)->sum('balance');
        $overdue60 = $invoices->filter(fn ($i) => $i->days_overdue > 30 && $i->days_overdue <= 60)->sum('balance');
        $overdue90 = $invoices->filter(fn ($i) => $i->days_overdue > 60 && $i->days_overdue <= 90)->sum('balance');
        $overdue90Plus = $invoices->filter(fn ($i) => $i->days_overdue > 90)->sum('balance');

        return [
            'total' => $invoices->sum('balance'),
            'count' => $invoices->count(),
            'current' => $current,
            'overdue_30' => $overdue30,
            'overdue_60' => $overdue60,
            'overdue_90' => $overdue90,
            'overdue_90_plus' => $overdue90Plus,
        ];
    }

    /**
     * Add consultation fee to invoice.
     */
    protected function addConsultationFee(Invoice $invoice, Encounter $encounter): void
    {
        // Default consultation fee - can be made configurable
        $consultationFee = 50.00;

        $invoice->items()->create([
            'item_type' => 'consultation',
            'item_name' => 'Yuran Konsultasi',
            'quantity' => 1,
            'unit_price' => $consultationFee,
            'is_taxable' => false,
            'billable_type' => Encounter::class,
            'billable_id' => $encounter->id,
        ]);
    }

    /**
     * Add procedure fees to invoice.
     */
    protected function addProcedureFees(Invoice $invoice, Encounter $encounter): void
    {
        // Add procedures from encounter if any
        // This would be linked to procedures/treatments model
    }

    /**
     * Add prescription fees to invoice.
     */
    protected function addPrescriptionFees(Invoice $invoice, Encounter $encounter): void
    {
        // Add medications from prescriptions
        foreach ($encounter->prescriptions as $prescription) {
            foreach ($prescription->items as $item) {
                $invoice->items()->create([
                    'item_type' => 'medication',
                    'item_name' => $item->medication_name,
                    'item_code' => $item->medication_code ?? null,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price ?? 0,
                    'is_taxable' => false,
                    'billable_type' => get_class($item),
                    'billable_id' => $item->id,
                ]);
            }
        }
    }

    /**
     * Use patient deposit for invoice.
     */
    public function useDeposit(Invoice $invoice, int $depositId): float
    {
        $deposit = Deposit::findOrFail($depositId);

        if ($deposit->patient_id !== $invoice->patient_id) {
            throw new \Exception('Deposit tidak milik pesakit ini');
        }

        $amountUsed = $deposit->useAmount($invoice->balance);

        if ($amountUsed > 0) {
            $invoice->recordPayment($amountUsed, 'deposit', "Deposit #{$deposit->deposit_number}");

            $this->auditService->log(
                'invoice',
                'use_deposit',
                $invoice,
                ['deposit_id' => $depositId, 'amount' => $amountUsed]
            );
        }

        return $amountUsed;
    }
}
