<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'attendance_date',
        'shift_id',
        'clock_in',
        'clock_in_ip',
        'clock_in_location',
        'clock_in_lat',
        'clock_in_lng',
        'clock_in_photo',
        'clock_out',
        'clock_out_ip',
        'clock_out_location',
        'clock_out_lat',
        'clock_out_lng',
        'clock_out_photo',
        'hours_worked',
        'overtime_hours',
        'late_minutes',
        'early_out_minutes',
        'status',
        'notes',
        'is_approved',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'clock_in' => 'datetime',
        'clock_out' => 'datetime',
        'approved_at' => 'datetime',
        'hours_worked' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
        'clock_in_lat' => 'decimal:8',
        'clock_in_lng' => 'decimal:8',
        'clock_out_lat' => 'decimal:8',
        'clock_out_lng' => 'decimal:8',
        'is_approved' => 'boolean',
    ];

    /**
     * Status options.
     */
    public const STATUSES = [
        'present' => 'Hadir',
        'absent' => 'Tidak Hadir',
        'late' => 'Lewat',
        'half_day' => 'Separuh Hari',
        'leave' => 'Cuti',
        'holiday' => 'Cuti Umum',
    ];

    /**
     * Get the staff.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Get the shift.
     */
    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
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
     * Clock in.
     */
    public function clockIn(?string $ip = null, ?string $location = null, ?float $lat = null, ?float $lng = null): void
    {
        $now = Carbon::now();

        $this->clock_in = $now;
        $this->clock_in_ip = $ip;
        $this->clock_in_location = $location;
        $this->clock_in_lat = $lat;
        $this->clock_in_lng = $lng;

        // Calculate late minutes if shift is set
        if ($this->shift) {
            $this->late_minutes = $this->shift->calculateLateMinutes($now);
            $this->status = $this->late_minutes > 0 ? 'late' : 'present';
        } else {
            $this->status = 'present';
        }

        $this->save();
    }

    /**
     * Clock out.
     */
    public function clockOut(?string $ip = null, ?string $location = null, ?float $lat = null, ?float $lng = null): void
    {
        $now = Carbon::now();

        $this->clock_out = $now;
        $this->clock_out_ip = $ip;
        $this->clock_out_location = $location;
        $this->clock_out_lat = $lat;
        $this->clock_out_lng = $lng;

        // Calculate worked hours
        if ($this->clock_in) {
            if ($this->shift) {
                $this->hours_worked = $this->shift->calculateWorkedHours($this->clock_in, $now);
                $this->early_out_minutes = $this->shift->calculateEarlyOutMinutes($now);

                // Calculate overtime
                if ($this->hours_worked > $this->shift->working_hours) {
                    $this->overtime_hours = $this->hours_worked - $this->shift->working_hours;
                }
            } else {
                $this->hours_worked = $this->clock_in->diffInMinutes($now) / 60;
            }
        }

        $this->save();
    }

    /**
     * Scope for date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('attendance_date', [$startDate, $endDate]);
    }

    /**
     * Scope for staff.
     */
    public function scopeForStaff($query, $staffId)
    {
        return $query->where('staff_id', $staffId);
    }

    /**
     * Scope for pending approval.
     */
    public function scopePendingApproval($query)
    {
        return $query->where('is_approved', false);
    }
}
