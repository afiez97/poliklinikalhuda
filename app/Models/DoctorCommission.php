<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DoctorCommission extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'staff_id',
        'commission_type',
        'calculation_type',
        'rate',
        'min_amount',
        'max_amount',
        'effective_from',
        'effective_to',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'effective_from' => 'date',
        'effective_to' => 'date',
        'rate' => 'decimal:2',
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Commission types.
     */
    public const COMMISSION_TYPES = [
        'consultation' => 'Konsultasi',
        'procedure' => 'Prosedur',
        'panel' => 'Panel',
        'lab' => 'Makmal',
        'medicine' => 'Ubat',
        'other' => 'Lain-lain',
    ];

    /**
     * Calculation types.
     */
    public const CALCULATION_TYPES = [
        'percentage' => 'Peratus',
        'fixed' => 'Tetap',
    ];

    /**
     * Get the staff/doctor.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Get commission type label.
     */
    public function getCommissionTypeLabelAttribute(): string
    {
        return self::COMMISSION_TYPES[$this->commission_type] ?? $this->commission_type;
    }

    /**
     * Get calculation type label.
     */
    public function getCalculationTypeLabelAttribute(): string
    {
        return self::CALCULATION_TYPES[$this->calculation_type] ?? $this->calculation_type;
    }

    /**
     * Get rate display.
     */
    public function getRateDisplayAttribute(): string
    {
        if ($this->calculation_type === 'percentage') {
            return $this->rate.'%';
        }

        return 'RM '.number_format($this->rate, 2);
    }

    /**
     * Calculate commission amount.
     */
    public function calculateCommission(float $baseAmount): float
    {
        if ($this->calculation_type === 'percentage') {
            $commission = $baseAmount * ($this->rate / 100);
        } else {
            $commission = $this->rate;
        }

        // Apply min/max limits
        if ($this->min_amount && $commission < $this->min_amount) {
            $commission = $this->min_amount;
        }
        if ($this->max_amount && $commission > $this->max_amount) {
            $commission = $this->max_amount;
        }

        return round($commission, 2);
    }

    /**
     * Scope for active commissions.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('effective_from', '<=', now())
            ->where(function ($q) {
                $q->whereNull('effective_to')->orWhere('effective_to', '>=', now());
            });
    }

    /**
     * Scope for commission type.
     */
    public function scopeForType($query, string $type)
    {
        return $query->where('commission_type', $type);
    }
}
