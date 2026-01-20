<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    protected $fillable = [
        'holiday_date',
        'name',
        'description',
        'type',
        'state',
        'is_recurring',
    ];

    protected $casts = [
        'holiday_date' => 'date',
        'is_recurring' => 'boolean',
    ];

    /**
     * Holiday types.
     */
    public const TYPES = [
        'national' => 'Kebangsaan',
        'state' => 'Negeri',
        'company' => 'Syarikat',
        'replacement' => 'Ganti',
    ];

    /**
     * Malaysian states.
     */
    public const STATES = [
        'JHR' => 'Johor',
        'KDH' => 'Kedah',
        'KTN' => 'Kelantan',
        'MLK' => 'Melaka',
        'NSN' => 'Negeri Sembilan',
        'PHG' => 'Pahang',
        'PNG' => 'Pulau Pinang',
        'PRK' => 'Perak',
        'PLS' => 'Perlis',
        'SBH' => 'Sabah',
        'SWK' => 'Sarawak',
        'SGR' => 'Selangor',
        'TRG' => 'Terengganu',
        'KUL' => 'Kuala Lumpur',
        'LBN' => 'Labuan',
        'PJY' => 'Putrajaya',
    ];

    /**
     * Get type label.
     */
    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    /**
     * Get state label.
     */
    public function getStateLabelAttribute(): string
    {
        if (! $this->state) {
            return 'Semua Negeri';
        }

        return self::STATES[$this->state] ?? $this->state;
    }

    /**
     * Check if date is a holiday.
     */
    public static function isHoliday($date, ?string $state = null): bool
    {
        $query = self::where('holiday_date', $date);

        if ($state) {
            $query->where(function ($q) use ($state) {
                $q->whereNull('state')->orWhere('state', $state);
            });
        }

        return $query->exists();
    }

    /**
     * Get holidays for date range.
     */
    public static function getForDateRange($startDate, $endDate, ?string $state = null)
    {
        $query = self::whereBetween('holiday_date', [$startDate, $endDate]);

        if ($state) {
            $query->where(function ($q) use ($state) {
                $q->whereNull('state')->orWhere('state', $state);
            });
        }

        return $query->orderBy('holiday_date')->get();
    }

    /**
     * Scope for year.
     */
    public function scopeForYear($query, int $year)
    {
        return $query->whereYear('holiday_date', $year);
    }

    /**
     * Scope for state.
     */
    public function scopeForState($query, ?string $state)
    {
        if ($state) {
            return $query->where(function ($q) use ($state) {
                $q->whereNull('state')->orWhere('state', $state);
            });
        }

        return $query->whereNull('state');
    }
}
