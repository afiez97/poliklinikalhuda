<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Refund extends Model
{
    use HasFactory;

    // Status constants
    public const STATUS_PENDING = 'pending_approval';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_REJECTED = 'rejected';

    public const STATUS_PROCESSED = 'processed';

    protected $fillable = [
        'refund_number',
        'invoice_id',
        'payment_id',
        'patient_id',
        'amount',
        'refund_method',
        'reason',
        'status',
        'requested_by',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'processed_by',
        'processed_at',
        'reference_number',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'approved_at' => 'datetime',
        'processed_at' => 'datetime',
    ];

    /**
     * Get the invoice.
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Get the payment.
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Get the patient.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the requester.
     */
    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    /**
     * Get the approver.
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the processor.
     */
    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Get the credit note.
     */
    public function creditNote(): HasOne
    {
        return $this->hasOne(CreditNote::class);
    }

    /**
     * Scope for pending approval.
     */
    public function scopePendingApproval($query)
    {
        return $query->where('status', 'pending_approval');
    }

    /**
     * Scope for pending (alias for pendingApproval).
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope for approved.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Check if refund is pending approval.
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if refund is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Check if refund is processed.
     */
    public function isProcessed(): bool
    {
        return $this->status === self::STATUS_PROCESSED;
    }

    /**
     * Check if refund is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Get status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending_approval' => 'Menunggu Kelulusan',
            'approved' => 'Diluluskan',
            'rejected' => 'Ditolak',
            'processed' => 'Diproses',
            default => $this->status,
        };
    }

    /**
     * Get status color.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending_approval' => 'warning',
            'approved' => 'info',
            'rejected' => 'danger',
            'processed' => 'success',
            default => 'secondary',
        };
    }

    /**
     * Check if refund requires approval.
     */
    public static function requiresApproval(float $amount): bool
    {
        $threshold = (float) BillingSetting::getValue('refund_approval_threshold', '100.00');

        return $amount >= $threshold;
    }

    /**
     * Generate refund number.
     */
    public static function generateRefundNumber(): string
    {
        $prefix = 'REF';
        $date = now()->format('Ymd');

        $lastRefund = self::where('refund_number', 'like', "{$prefix}-{$date}-%")
            ->orderBy('refund_number', 'desc')
            ->first();

        if ($lastRefund) {
            $lastNumber = (int) substr($lastRefund->refund_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return sprintf('%s-%s-%04d', $prefix, $date, $newNumber);
    }

    /**
     * Approve the refund.
     */
    public function approve(int $approverId): void
    {
        $this->update([
            'status' => 'approved',
            'approved_by' => $approverId,
            'approved_at' => now(),
        ]);
    }

    /**
     * Reject the refund.
     */
    public function reject(int $approverId, string $reason): void
    {
        $this->update([
            'status' => 'rejected',
            'approved_by' => $approverId,
            'approved_at' => now(),
            'rejection_reason' => $reason,
        ]);
    }

    /**
     * Mark as processed.
     */
    public function markAsProcessed(int $processedBy, ?string $referenceNumber = null): void
    {
        $this->update([
            'status' => 'processed',
            'processed_by' => $processedBy,
            'processed_at' => now(),
            'reference_number' => $referenceNumber,
        ]);
    }

    /**
     * Get method label.
     */
    public static function getMethodLabel(string $method): string
    {
        return match ($method) {
            'cash' => 'Tunai',
            'card' => 'Kad',
            'bank_transfer' => 'Pindahan Bank',
            'cheque' => 'Cek',
            default => ucfirst($method),
        };
    }
}
