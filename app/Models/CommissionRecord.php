<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommissionRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'payroll_period_id',
        'commission_date',
        'reference_type',
        'reference_id',
        'description',
        'base_amount',
        'commission_rate',
        'rate_type',
        'commission_amount',
        'status',
        'approved_by',
        'approved_at',
        'notes',
    ];

    protected $casts = [
        'commission_date' => 'date',
        'approved_at' => 'datetime',
        'base_amount' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'commission_amount' => 'decimal:2',
    ];

    /**
     * Status options.
     */
    public const STATUSES = [
        'pending' => 'Menunggu',
        'approved' => 'Diluluskan',
        'paid' => 'Dibayar',
        'cancelled' => 'Dibatalkan',
    ];

    /**
     * Get the staff/doctor.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Get the payroll period.
     */
    public function payrollPeriod(): BelongsTo
    {
        return $this->belongsTo(PayrollPeriod::class);
    }

    /**
     * Get the approver.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    /**
     * Get rate display.
     */
    public function getRateDisplayAttribute(): string
    {
        if ($this->rate_type === 'percentage') {
            return $this->commission_rate.'%';
        }

        return 'RM '.number_format($this->commission_rate, 2);
    }

    /**
     * Approve commission.
     */
    public function approve(int $approverId): void
    {
        $this->status = 'approved';
        $this->approved_by = $approverId;
        $this->approved_at = now();
        $this->save();
    }

    /**
     * Cancel commission.
     */
    public function cancel(): void
    {
        $this->status = 'cancelled';
        $this->save();
    }

    /**
     * Scope for status.
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for pending.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('commission_date', [$startDate, $endDate]);
    }
}
