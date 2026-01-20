<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'leave_type_id',
        'year',
        'entitled_days',
        'carry_forward_days',
        'adjustment_days',
        'used_days',
        'pending_days',
        'notes',
    ];

    protected $casts = [
        'entitled_days' => 'decimal:2',
        'carry_forward_days' => 'decimal:2',
        'adjustment_days' => 'decimal:2',
        'used_days' => 'decimal:2',
        'pending_days' => 'decimal:2',
    ];

    /**
     * Get the staff.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Get the leave type.
     */
    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(LeaveType::class);
    }

    /**
     * Get total entitlement.
     */
    public function getTotalEntitledAttribute(): float
    {
        return $this->entitled_days + $this->carry_forward_days + $this->adjustment_days;
    }

    /**
     * Get available balance.
     */
    public function getAvailableBalanceAttribute(): float
    {
        return $this->total_entitled - $this->used_days - $this->pending_days;
    }

    /**
     * Check if can apply for days.
     */
    public function canApply(float $days): bool
    {
        return $this->available_balance >= $days;
    }

    /**
     * Add pending days.
     */
    public function addPendingDays(float $days): void
    {
        $this->pending_days += $days;
        $this->save();
    }

    /**
     * Remove pending days and add to used.
     */
    public function approveDays(float $days): void
    {
        $this->pending_days -= $days;
        $this->used_days += $days;
        $this->save();
    }

    /**
     * Cancel pending days.
     */
    public function cancelPendingDays(float $days): void
    {
        $this->pending_days -= $days;
        $this->save();
    }

    /**
     * Scope for year.
     */
    public function scopeForYear($query, int $year)
    {
        return $query->where('year', $year);
    }

    /**
     * Scope for current year.
     */
    public function scopeCurrentYear($query)
    {
        return $query->where('year', date('Y'));
    }
}
