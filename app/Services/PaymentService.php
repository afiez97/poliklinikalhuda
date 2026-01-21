<?php

namespace App\Services;

use App\Models\BillingSetting;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Receipt;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    public function __construct(
        protected AuditService $auditService
    ) {}

    /**
     * Get paginated payments with filters.
     */
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Payment::with(['invoice', 'invoice.patient', 'receipt', 'receivedBy']);

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('payment_number', 'like', "%{$search}%")
                    ->orWhereHas('invoice', function ($q) use ($search) {
                        $q->where('invoice_number', 'like', "%{$search}%");
                    })
                    ->orWhereHas('invoice.patient', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('mrn', 'like', "%{$search}%");
                    });
            });
        }

        if (! empty($filters['payment_method'])) {
            $query->where('payment_method', $filters['payment_method']);
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['date_from'])) {
            $query->whereDate('payment_date', '>=', $filters['date_from']);
        }

        if (! empty($filters['date_to'])) {
            $query->whereDate('payment_date', '<=', $filters['date_to']);
        }

        if (! empty($filters['received_by'])) {
            $query->where('received_by', $filters['received_by']);
        }

        return $query->latest('payment_date')->paginate($perPage);
    }

    /**
     * Process payment for invoice.
     */
    public function processPayment(Invoice $invoice, array $data, ?int $receivedBy = null): Payment
    {
        return DB::transaction(function () use ($invoice, $data, $receivedBy) {
            $amount = (float) $data['amount'];

            // Validate amount
            if ($amount <= 0) {
                throw new \Exception('Jumlah bayaran tidak sah');
            }

            if ($amount > $invoice->balance) {
                throw new \Exception('Jumlah bayaran melebihi baki');
            }

            // Create payment
            $payment = Payment::create([
                'payment_number' => Payment::generatePaymentNumber(),
                'invoice_id' => $invoice->id,
                'payment_method' => $data['payment_method'],
                'amount' => $amount,
                'payment_date' => $data['payment_date'] ?? now(),
                'reference_number' => $data['reference_number'] ?? null,
                'card_type' => $data['card_type'] ?? null,
                'card_last_four' => $data['card_last_four'] ?? null,
                'bank_name' => $data['bank_name'] ?? null,
                'notes' => $data['notes'] ?? null,
                'status' => Payment::STATUS_COMPLETED,
                'received_by' => $receivedBy,
            ]);

            // Update invoice
            $invoice->recordPayment($amount, $data['payment_method'], $payment->payment_number);

            // Generate receipt
            $receipt = $this->generateReceipt($payment);

            $this->auditService->log(
                'payment',
                'process',
                $payment,
                [
                    'invoice_id' => $invoice->id,
                    'amount' => $amount,
                    'method' => $data['payment_method'],
                ]
            );

            return $payment->fresh(['invoice', 'receipt']);
        });
    }

    /**
     * Process split payment (multiple methods).
     */
    public function processSplitPayment(Invoice $invoice, array $payments, ?int $receivedBy = null): Collection
    {
        return DB::transaction(function () use ($invoice, $payments, $receivedBy) {
            $totalAmount = array_sum(array_column($payments, 'amount'));

            if ($totalAmount > $invoice->balance) {
                throw new \Exception('Jumlah bayaran melebihi baki');
            }

            $processedPayments = collect();

            foreach ($payments as $paymentData) {
                if ($paymentData['amount'] <= 0) {
                    continue;
                }

                $payment = $this->processPayment($invoice, $paymentData, $receivedBy);
                $processedPayments->push($payment);
            }

            return $processedPayments;
        });
    }

    /**
     * Void payment.
     */
    public function voidPayment(Payment $payment, string $reason, ?int $userId = null): Payment
    {
        return DB::transaction(function () use ($payment, $reason, $userId) {
            if ($payment->status === Payment::STATUS_VOIDED) {
                throw new \Exception('Pembayaran sudah dibatalkan');
            }

            $invoice = $payment->invoice;
            $amount = $payment->amount;

            // Update payment status
            $payment->status = Payment::STATUS_VOIDED;
            $payment->void_reason = $reason;
            $payment->voided_at = now();
            $payment->voided_by = $userId;
            $payment->save();

            // Reverse invoice payment
            $invoice->paid_amount -= $amount;
            $invoice->balance += $amount;

            if ($invoice->balance >= $invoice->grand_total) {
                $invoice->status = Invoice::STATUS_ISSUED;
            } elseif ($invoice->balance > 0) {
                $invoice->status = Invoice::STATUS_PARTIAL;
            }

            $invoice->save();

            // Void receipt if exists
            if ($payment->receipt) {
                $payment->receipt->update(['voided_at' => now()]);
            }

            $this->auditService->log(
                'payment',
                'void',
                $payment,
                ['reason' => $reason, 'amount' => $amount]
            );

            return $payment;
        });
    }

    /**
     * Generate receipt for payment.
     */
    public function generateReceipt(Payment $payment): Receipt
    {
        return Receipt::create([
            'receipt_number' => Receipt::generateReceiptNumber(),
            'payment_id' => $payment->id,
            'invoice_id' => $payment->invoice_id,
            'receipt_date' => now(),
            'amount' => $payment->amount,
            'payment_method' => $payment->payment_method,
            'issued_by' => $payment->received_by,
        ]);
    }

    /**
     * Get daily collection summary.
     */
    public function getDailySummary($date = null): array
    {
        $date = $date ?? today();

        $payments = Payment::whereDate('payment_date', $date)
            ->where('status', Payment::STATUS_COMPLETED)
            ->get();

        return [
            'date' => $date->format('Y-m-d'),
            'total_amount' => $payments->sum('amount'),
            'total_transactions' => $payments->count(),
            'by_method' => [
                'cash' => $payments->where('payment_method', 'cash')->sum('amount'),
                'card' => $payments->where('payment_method', 'card')->sum('amount'),
                'qr' => $payments->where('payment_method', 'qr')->sum('amount'),
                'ewallet' => $payments->where('payment_method', 'ewallet')->sum('amount'),
                'transfer' => $payments->where('payment_method', 'transfer')->sum('amount'),
                'panel' => $payments->where('payment_method', 'panel')->sum('amount'),
            ],
            'transaction_count' => [
                'cash' => $payments->where('payment_method', 'cash')->count(),
                'card' => $payments->where('payment_method', 'card')->count(),
                'qr' => $payments->where('payment_method', 'qr')->count(),
                'ewallet' => $payments->where('payment_method', 'ewallet')->count(),
                'transfer' => $payments->where('payment_method', 'transfer')->count(),
                'panel' => $payments->where('payment_method', 'panel')->count(),
            ],
        ];
    }

    /**
     * Get monthly collection summary.
     */
    public function getMonthlySummary($year = null, $month = null): array
    {
        $year = $year ?? now()->year;
        $month = $month ?? now()->month;

        $payments = Payment::whereYear('payment_date', $year)
            ->whereMonth('payment_date', $month)
            ->where('status', Payment::STATUS_COMPLETED)
            ->get();

        $dailyTotals = $payments->groupBy(fn ($p) => $p->payment_date->format('Y-m-d'))
            ->map(fn ($group) => $group->sum('amount'));

        return [
            'year' => $year,
            'month' => $month,
            'total_amount' => $payments->sum('amount'),
            'total_transactions' => $payments->count(),
            'average_daily' => $dailyTotals->avg() ?? 0,
            'by_method' => [
                'cash' => $payments->where('payment_method', 'cash')->sum('amount'),
                'card' => $payments->where('payment_method', 'card')->sum('amount'),
                'qr' => $payments->where('payment_method', 'qr')->sum('amount'),
                'ewallet' => $payments->where('payment_method', 'ewallet')->sum('amount'),
                'transfer' => $payments->where('payment_method', 'transfer')->sum('amount'),
                'panel' => $payments->where('payment_method', 'panel')->sum('amount'),
            ],
            'daily_totals' => $dailyTotals->toArray(),
        ];
    }

    /**
     * Get payment methods summary for period.
     */
    public function getPaymentMethodsSummary($startDate, $endDate): Collection
    {
        return Payment::whereBetween('payment_date', [$startDate, $endDate])
            ->where('status', Payment::STATUS_COMPLETED)
            ->get()
            ->groupBy('payment_method')
            ->map(function ($group, $method) {
                return [
                    'method' => $method,
                    'label' => Payment::getMethodLabel($method),
                    'count' => $group->count(),
                    'total' => $group->sum('amount'),
                    'percentage' => 0, // Will be calculated after
                ];
            });
    }

    /**
     * Calculate change for cash payment.
     */
    public function calculateChange(float $amount, float $tendered): array
    {
        $change = $tendered - $amount;

        // Apply rounding
        $roundedAmount = BillingSetting::applyRounding($amount);
        $roundingAdjustment = $roundedAmount - $amount;
        $actualChange = $tendered - $roundedAmount;

        return [
            'original_amount' => $amount,
            'rounding_adjustment' => $roundingAdjustment,
            'rounded_amount' => $roundedAmount,
            'tendered' => $tendered,
            'change' => max(0, $actualChange),
        ];
    }
}
