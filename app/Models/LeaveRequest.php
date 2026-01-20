<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'request_no',
        'staff_id',
        'leave_type_id',
        'start_date',
        'end_date',
        'total_days',
        'start_half',
        'end_half',
        'reason',
        'attachment',
        'emergency_contact',
        'status',
        'approved_by',
        'approved_at',
        'approval_remarks',
        'replacement_staff_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
        'total_days' => 'decimal:2',
    ];

    /**
     * Status options.
     */
    public const STATUSES = [
        'draft' => 'Draf',
        'pending' => 'Menunggu Kelulusan',
        'approved' => 'Diluluskan',
        'rejected' => 'Ditolak',
        'cancelled' => 'Dibatalkan',
    ];

    /**
     * Half day options.
     */
    public const HALF_OPTIONS = [
        'full' => 'Hari Penuh',
        'am' => 'Pagi Sahaja',
        'pm' => 'Petang Sahaja',
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
     * Get the approver.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the replacement staff.
     */
    public function replacementStaff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'replacement_staff_id');
    }

    /**
     * Get status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    /**
     * Get date range display.
     */
    public function getDateRangeAttribute(): string
    {
        if ($this->start_date->eq($this->end_date)) {
            return $this->start_date->format('d/m/Y');
        }

        return $this->start_date->format('d/m/Y').' - '.$this->end_date->format('d/m/Y');
    }

    /**
     * Calculate total days based on start/end date and half options.
     */
    public static function calculateTotalDays(
        Carbon $startDate,
        Carbon $endDate,
        string $startHalf = 'full',
        string $endHalf = 'full'
    ): float {
        $days = $startDate->diffInDays($endDate) + 1;

        // Adjust for half days
        if ($startHalf !== 'full') {
            $days -= 0.5;
        }
        if ($endHalf !== 'full' && ! $startDate->eq($endDate)) {
            $days -= 0.5;
        }

        return max(0.5, $days);
    }

    /**
     * Submit leave request.
     */
    public function submit(): void
    {
        $this->status = 'pending';
        $this->save();

        // Add to pending balance
        $balance = LeaveBalance::where('staff_id', $this->staff_id)
            ->where('leave_type_id', $this->leave_type_id)
            ->where('year', $this->start_date->year)
            ->first();

        if ($balance) {
            $balance->addPendingDays($this->total_days);
        }
    }

    /**
     * Approve leave request.
     */
    public function approve(int $approverId, ?string $remarks = null): void
    {
        $this->status = 'approved';
        $this->approved_by = $approverId;
        $this->approved_at = now();
        $this->approval_remarks = $remarks;
        $this->save();

        // Move from pending to used
        $balance = LeaveBalance::where('staff_id', $this->staff_id)
            ->where('leave_type_id', $this->leave_type_id)
            ->where('year', $this->start_date->year)
            ->first();

        if ($balance) {
            $balance->approveDays($this->total_days);
        }
    }

    /**
     * Reject leave request.
     */
    public function reject(int $approverId, ?string $remarks = null): void
    {
        $this->status = 'rejected';
        $this->approved_by = $approverId;
        $this->approved_at = now();
        $this->approval_remarks = $remarks;
        $this->save();

        // Remove from pending
        $balance = LeaveBalance::where('staff_id', $this->staff_id)
            ->where('leave_type_id', $this->leave_type_id)
            ->where('year', $this->start_date->year)
            ->first();

        if ($balance) {
            $balance->cancelPendingDays($this->total_days);
        }
    }

    /**
     * Cancel leave request.
     */
    public function cancel(): void
    {
        $previousStatus = $this->status;
        $this->status = 'cancelled';
        $this->save();

        $balance = LeaveBalance::where('staff_id', $this->staff_id)
            ->where('leave_type_id', $this->leave_type_id)
            ->where('year', $this->start_date->year)
            ->first();

        if ($balance) {
            if ($previousStatus === 'pending') {
                $balance->cancelPendingDays($this->total_days);
            } elseif ($previousStatus === 'approved') {
                $balance->used_days -= $this->total_days;
                $balance->save();
            }
        }
    }

    /**
     * Generate request number.
     */
    public static function generateRequestNo(): string
    {
        $year = date('Y');
        $last = self::withTrashed()
            ->where('request_no', 'like', "LV{$year}%")
            ->orderBy('request_no', 'desc')
            ->first();

        if ($last) {
            $lastNumber = (int) substr($last->request_no, -5);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return sprintf('LV%s%05d', $year, $nextNumber);
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
        return $query->where(function ($q) use ($startDate, $endDate) {
            $q->whereBetween('start_date', [$startDate, $endDate])
                ->orWhereBetween('end_date', [$startDate, $endDate]);
        });
    }
}
