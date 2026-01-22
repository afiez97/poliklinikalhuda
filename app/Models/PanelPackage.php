<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PanelPackage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'panel_id',
        'package_code',
        'package_name',
        'description',
        'annual_limit',
        'per_visit_limit',
        'consultation_limit',
        'medication_limit',
        'procedure_limit',
        'lab_limit',
        'co_payment_percentage',
        'deductible_amount',
        'deductible_type',
        'is_default',
        'is_active',
    ];

    protected $casts = [
        'annual_limit' => 'decimal:2',
        'per_visit_limit' => 'decimal:2',
        'consultation_limit' => 'decimal:2',
        'medication_limit' => 'decimal:2',
        'procedure_limit' => 'decimal:2',
        'lab_limit' => 'decimal:2',
        'co_payment_percentage' => 'decimal:2',
        'deductible_amount' => 'decimal:2',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    public const DEDUCTIBLE_PER_VISIT = 'per_visit';

    public const DEDUCTIBLE_PER_YEAR = 'per_year';

    public function panel(): BelongsTo
    {
        return $this->belongsTo(Panel::class);
    }

    public function feeSchedules(): HasMany
    {
        return $this->hasMany(PanelFeeSchedule::class);
    }

    public function exclusions(): HasMany
    {
        return $this->hasMany(PanelExclusion::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(PanelEmployee::class, 'package_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    public function calculateCoPayment(float $amount): float
    {
        return round($amount * ($this->co_payment_percentage / 100), 2);
    }

    public function calculatePanelPortion(float $amount): float
    {
        $coPayment = $this->calculateCoPayment($amount);

        return round($amount - $coPayment, 2);
    }

    public function hasAnnualLimit(): bool
    {
        return $this->annual_limit !== null && $this->annual_limit > 0;
    }

    public function hasPerVisitLimit(): bool
    {
        return $this->per_visit_limit !== null && $this->per_visit_limit > 0;
    }
}
