<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiscountApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'requested_by',
        'approved_by',
        'discount_type',
        'discount_value',
        'discount_amount',
        'reason',
        'status',
        'rejection_reason',
        'approved_at',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    const STATUS_PENDING = 'pending';

    const STATUS_APPROVED = 'approved';

    const STATUS_REJECTED = 'rejected';

    const TYPE_PERCENTAGE = 'percentage';

    const TYPE_FIXED = 'fixed';

    /**
     * Get the invoice.
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Get the staff who requested.
     */
    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    /**
     * Get the staff who approved/rejected.
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Scope for pending approvals.
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
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Scope for rejected.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    /**
     * Check if pending.
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if approved.
     */
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Check if rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Approve the discount.
     */
    public function approve(int $approvedBy): bool
    {
        $this->status = self::STATUS_APPROVED;
        $this->approved_by = $approvedBy;
        $this->approved_at = now();

        if ($this->save()) {
            // Apply discount to invoice
            $this->invoice->applyDiscount(
                $this->discount_type,
                $this->discount_value
            );

            return true;
        }

        return false;
    }

    /**
     * Reject the discount.
     */
    public function reject(int $rejectedBy, string $reason): bool
    {
        $this->status = self::STATUS_REJECTED;
        $this->approved_by = $rejectedBy;
        $this->rejection_reason = $reason;
        $this->approved_at = now();

        return $this->save();
    }

    /**
     * Get discount display.
     */
    public function getDiscountDisplayAttribute(): string
    {
        if ($this->discount_type === self::TYPE_PERCENTAGE) {
            return number_format($this->discount_value, 0).'%';
        }

        return 'RM '.number_format($this->discount_value, 2);
    }

    /**
     * Get status badge class.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'bg-warning',
            self::STATUS_APPROVED => 'bg-success',
            self::STATUS_REJECTED => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    /**
     * Get status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'Menunggu Kelulusan',
            self::STATUS_APPROVED => 'Diluluskan',
            self::STATUS_REJECTED => 'Ditolak',
            default => $this->status,
        };
    }
}
