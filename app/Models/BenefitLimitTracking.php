<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BenefitLimitTracking extends Model
{
    use HasFactory;

    protected $table = 'benefit_limit_trackings';

    protected $fillable = [
        'panel_id',
        'panel_employee_id',
        'panel_dependent_id',
        'patient_id',
        'panel_package_id',
        'benefit_year',
        'annual_limit',
        'annual_used',
        'annual_balance',
        'consultation_used',
        'medication_used',
        'procedure_used',
        'lab_used',
        'visit_count',
        'last_visit_date',
    ];

    protected $casts = [
        'benefit_year' => 'integer',
        'annual_limit' => 'decimal:2',
        'annual_used' => 'decimal:2',
        'annual_balance' => 'decimal:2',
        'consultation_used' => 'decimal:2',
        'medication_used' => 'decimal:2',
        'procedure_used' => 'decimal:2',
        'lab_used' => 'decimal:2',
        'visit_count' => 'integer',
        'last_visit_date' => 'date',
    ];

    public function panel(): BelongsTo
    {
        return $this->belongsTo(Panel::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(PanelEmployee::class, 'panel_employee_id');
    }

    public function dependent(): BelongsTo
    {
        return $this->belongsTo(PanelDependent::class, 'panel_dependent_id');
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(PanelPackage::class, 'panel_package_id');
    }

    public function scopeCurrentYear($query)
    {
        return $query->where('benefit_year', now()->year);
    }

    public function scopeByPatient($query, int $patientId)
    {
        return $query->where('patient_id', $patientId);
    }

    public function getUtilizationPercentageAttribute(): float
    {
        if ($this->annual_limit <= 0) {
            return 0;
        }

        return round(($this->annual_used / $this->annual_limit) * 100, 2);
    }

    public function getUtilizationLevelAttribute(): string
    {
        $percentage = $this->utilization_percentage;

        return match (true) {
            $percentage >= 100 => 'exceeded',
            $percentage >= 90 => 'critical',
            $percentage >= 80 => 'warning',
            default => 'normal',
        };
    }

    public function hasAvailableBalance(float $amount = 0): bool
    {
        return $this->annual_balance >= $amount;
    }

    public function recordUtilization(float $amount, string $category = 'consultation'): void
    {
        $this->annual_used += $amount;
        $this->annual_balance = $this->annual_limit - $this->annual_used;
        $this->visit_count++;
        $this->last_visit_date = now();

        match ($category) {
            'consultation' => $this->consultation_used += $amount,
            'medication' => $this->medication_used += $amount,
            'procedure' => $this->procedure_used += $amount,
            'lab' => $this->lab_used += $amount,
            default => null,
        };

        $this->save();
    }
}
