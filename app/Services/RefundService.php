<?php

namespace App\Services;

use App\Models\CreditNote;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Refund;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class RefundService
{
    public function __construct(
        protected AuditService $auditService
    ) {}

    /**
     * Get paginated refunds with filters.
     */
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Refund::with(['payment', 'payment.invoice', 'payment.invoice.patient', 'creditNote', 'requestedBy', 'approvedBy']);

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('refund_number', 'like', "%{$search}%")
                    ->orWhereHas('payment', function ($q) use ($search) {
                        $q->where('payment_number', 'like', "%{$search}%");
                    })
                    ->orWhereHas('payment.invoice.patient', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('mrn', 'like', "%{$search}%");
                    });
            });
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['refund_method'])) {
            $query->where('refund_method', $filters['refund_method']);
        }

        if (! empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (! empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Request refund.
     */
    public function requestRefund(Payment $payment, array $data, int $requestedBy): Refund
    {
        return DB::transaction(function () use ($payment, $data, $requestedBy) {
            $amount = (float) $data['amount'];

            // Validate
            if ($payment->status !== Payment::STATUS_COMPLETED) {
                throw new \Exception('Hanya pembayaran yang selesai boleh dipulangkan');
            }

            if ($amount <= 0) {
                throw new \Exception('Jumlah pulangan tidak sah');
            }

            $refundableAmount = $this->getRefundableAmount($payment);
            if ($amount > $refundableAmount) {
                throw new \Exception("Jumlah maksimum boleh dipulangkan: RM {$refundableAmount}");
            }

            // Check if approval needed
            $requiresApproval = Refund::requiresApproval($amount);

            $refund = Refund::create([
                'refund_number' => Refund::generateRefundNumber(),
                'payment_id' => $payment->id,
                'amount' => $amount,
                'reason' => $data['reason'],
                'refund_method' => $data['refund_method'] ?? $payment->payment_method,
                'status' => $requiresApproval ? Refund::STATUS_PENDING : Refund::STATUS_APPROVED,
                'requested_by' => $requestedBy,
            ]);

            // Auto-approve if doesn't need approval
            if (! $requiresApproval) {
                $refund->approved_by = $requestedBy;
                $refund->approved_at = now();
                $refund->save();
            }

            $this->auditService->log(
                'refund',
                'request',
                $refund,
                [
                    'payment_id' => $payment->id,
                    'amount' => $amount,
                    'requires_approval' => $requiresApproval,
                ]
            );

            return $refund->fresh(['payment', 'requestedBy']);
        });
    }

    /**
     * Approve refund.
     */
    public function approveRefund(Refund $refund, int $approvedBy): Refund
    {
        if (! $refund->isPending()) {
            throw new \Exception('Hanya pulangan yang menunggu kelulusan boleh diluluskan');
        }

        $refund->approve($approvedBy);

        $this->auditService->log(
            'refund',
            'approve',
            $refund,
            ['approved_by' => $approvedBy]
        );

        return $refund;
    }

    /**
     * Reject refund.
     */
    public function rejectRefund(Refund $refund, string $reason, int $rejectedBy): Refund
    {
        if (! $refund->isPending()) {
            throw new \Exception('Hanya pulangan yang menunggu kelulusan boleh ditolak');
        }

        $refund->reject($rejectedBy, $reason);

        $this->auditService->log(
            'refund',
            'reject',
            $refund,
            ['rejected_by' => $rejectedBy, 'reason' => $reason]
        );

        return $refund;
    }

    /**
     * Process approved refund.
     */
    public function processRefund(Refund $refund, array $data, int $processedBy): Refund
    {
        return DB::transaction(function () use ($refund, $data, $processedBy) {
            if (! $refund->isApproved()) {
                throw new \Exception('Hanya pulangan yang diluluskan boleh diproses');
            }

            // Update refund
            $refund->markAsProcessed(
                $processedBy,
                $data['reference_number'] ?? null
            );

            // Update invoice balance
            $invoice = $refund->payment->invoice;
            $invoice->balance_owed += $refund->amount;
            $invoice->paid_amount -= $refund->amount;

            if ($invoice->balance_owed >= $invoice->total_amount) {
                $invoice->status = Invoice::STATUS_ISSUED;
            } elseif ($invoice->balance_owed > 0) {
                $invoice->status = Invoice::STATUS_PARTIAL;
            }

            $invoice->save();

            // Create credit note
            $creditNote = $this->createCreditNote($refund);

            $this->auditService->log(
                'refund',
                'process',
                $refund,
                ['processed_by' => $processedBy, 'credit_note_id' => $creditNote->id]
            );

            return $refund->fresh(['payment', 'creditNote']);
        });
    }

    /**
     * Create credit note for refund.
     */
    protected function createCreditNote(Refund $refund): CreditNote
    {
        return CreditNote::create([
            'credit_note_number' => CreditNote::generateCreditNoteNumber(),
            'refund_id' => $refund->id,
            'invoice_id' => $refund->payment->invoice_id,
            'amount' => $refund->amount,
            'reason' => $refund->reason,
            'credit_note_date' => now(),
            'issued_by' => $refund->processed_by,
        ]);
    }

    /**
     * Get refundable amount for payment.
     */
    public function getRefundableAmount(Payment $payment): float
    {
        $totalRefunded = Refund::where('payment_id', $payment->id)
            ->whereIn('status', [Refund::STATUS_PENDING, Refund::STATUS_APPROVED, Refund::STATUS_PROCESSED])
            ->sum('amount');

        return max(0, $payment->amount - $totalRefunded);
    }

    /**
     * Get pending refunds count.
     */
    public function getPendingCount(): int
    {
        return Refund::pending()->count();
    }

    /**
     * Get refund statistics for period.
     */
    public function getStatistics($startDate, $endDate): array
    {
        $refunds = Refund::whereBetween('created_at', [$startDate, $endDate])->get();

        $processed = $refunds->where('status', Refund::STATUS_PROCESSED);

        return [
            'total_requests' => $refunds->count(),
            'pending' => $refunds->where('status', Refund::STATUS_PENDING)->count(),
            'approved' => $refunds->where('status', Refund::STATUS_APPROVED)->count(),
            'processed' => $processed->count(),
            'rejected' => $refunds->where('status', Refund::STATUS_REJECTED)->count(),
            'total_amount_requested' => $refunds->sum('amount'),
            'total_amount_processed' => $processed->sum('amount'),
            'by_method' => $processed->groupBy('refund_method')->map->sum('amount')->toArray(),
        ];
    }

    /**
     * Get refund reasons summary.
     */
    public function getReasonsSummary($startDate, $endDate): array
    {
        return Refund::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', Refund::STATUS_PROCESSED)
            ->get()
            ->groupBy('reason')
            ->map(function ($group, $reason) {
                return [
                    'reason' => $reason,
                    'count' => $group->count(),
                    'total_amount' => $group->sum('amount'),
                ];
            })
            ->sortByDesc('count')
            ->values()
            ->toArray();
    }
}
