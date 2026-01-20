<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shift extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'start_time',
        'end_time',
        'break_start',
        'break_end',
        'break_duration_minutes',
        'working_hours',
        'is_overnight',
        'color',
        'is_active',
    ];

    protected $casts = [
        'working_hours' => 'decimal:2',
        'is_overnight' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get rosters using this shift.
     */
    public function rosters(): HasMany
    {
        return $this->hasMany(Roster::class);
    }

    /**
     * Get attendances using this shift.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Scope for active shifts.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get time range display.
     */
    public function getTimeRangeAttribute(): string
    {
        $start = Carbon::parse($this->start_time)->format('h:i A');
        $end = Carbon::parse($this->end_time)->format('h:i A');

        return "{$start} - {$end}";
    }

    /**
     * Calculate late minutes for a clock in time.
     */
    public function calculateLateMinutes(Carbon $clockIn): int
    {
        $shiftStart = Carbon::parse($clockIn->format('Y-m-d').' '.$this->start_time);

        if ($clockIn->gt($shiftStart)) {
            return $clockIn->diffInMinutes($shiftStart);
        }

        return 0;
    }

    /**
     * Calculate early out minutes for a clock out time.
     */
    public function calculateEarlyOutMinutes(Carbon $clockOut): int
    {
        $shiftEnd = Carbon::parse($clockOut->format('Y-m-d').' '.$this->end_time);

        if ($this->is_overnight) {
            $shiftEnd->addDay();
        }

        if ($clockOut->lt($shiftEnd)) {
            return $shiftEnd->diffInMinutes($clockOut);
        }

        return 0;
    }

    /**
     * Calculate worked hours between clock in and clock out.
     */
    public function calculateWorkedHours(Carbon $clockIn, Carbon $clockOut): float
    {
        $minutes = $clockIn->diffInMinutes($clockOut);
        $minutes -= $this->break_duration_minutes;

        return max(0, $minutes / 60);
    }
}
