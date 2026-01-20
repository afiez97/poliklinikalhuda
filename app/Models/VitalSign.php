<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VitalSign extends Model
{
    use HasFactory;

    protected $fillable = [
        'encounter_id',
        'patient_id',
        'recorded_at',
        'temperature',
        'pulse_rate',
        'respiratory_rate',
        'systolic_bp',
        'diastolic_bp',
        'spo2',
        'weight',
        'height',
        'bmi',
        'blood_glucose',
        'pain_score',
        'pain_location',
        'notes',
        'recorded_by',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
        'temperature' => 'decimal:1',
        'weight' => 'decimal:2',
        'height' => 'decimal:2',
        'bmi' => 'decimal:1',
        'blood_glucose' => 'decimal:1',
    ];

    public const NORMAL_RANGES = [
        'temperature' => ['min' => 36.1, 'max' => 37.2, 'unit' => '°C'],
        'pulse_rate' => ['min' => 60, 'max' => 100, 'unit' => 'bpm'],
        'respiratory_rate' => ['min' => 12, 'max' => 20, 'unit' => '/min'],
        'systolic_bp' => ['min' => 90, 'max' => 120, 'unit' => 'mmHg'],
        'diastolic_bp' => ['min' => 60, 'max' => 80, 'unit' => 'mmHg'],
        'spo2' => ['min' => 95, 'max' => 100, 'unit' => '%'],
        'blood_glucose' => ['min' => 4.0, 'max' => 7.8, 'unit' => 'mmol/L'],
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($vitalSign) {
            // Auto-calculate BMI if height and weight are provided
            if ($vitalSign->weight && $vitalSign->height) {
                $heightInMeters = $vitalSign->height / 100;
                $vitalSign->bmi = round($vitalSign->weight / ($heightInMeters * $heightInMeters), 1);
            }
        });
    }

    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function getBloodPressureAttribute(): ?string
    {
        if ($this->systolic_bp && $this->diastolic_bp) {
            return "{$this->systolic_bp}/{$this->diastolic_bp}";
        }

        return null;
    }

    public function getBmiCategoryAttribute(): ?string
    {
        if (! $this->bmi) {
            return null;
        }

        if ($this->bmi < 18.5) {
            return 'Kurang Berat';
        }
        if ($this->bmi < 25) {
            return 'Normal';
        }
        if ($this->bmi < 30) {
            return 'Berat Berlebihan';
        }

        return 'Obes';
    }

    public function getAbnormalVitalsAttribute(): array
    {
        $abnormal = [];

        foreach (self::NORMAL_RANGES as $vital => $range) {
            $value = $this->$vital;
            if ($value !== null) {
                if ($value < $range['min'] || $value > $range['max']) {
                    $abnormal[$vital] = [
                        'value' => $value,
                        'status' => $value < $range['min'] ? 'low' : 'high',
                        'normal_range' => "{$range['min']}-{$range['max']} {$range['unit']}",
                    ];
                }
            }
        }

        return $abnormal;
    }

    public function hasAbnormalVitals(): bool
    {
        return count($this->abnormal_vitals) > 0;
    }

    public function isTemperatureFever(): bool
    {
        return $this->temperature && $this->temperature >= 37.5;
    }

    public function isHypertensive(): bool
    {
        return ($this->systolic_bp && $this->systolic_bp >= 140)
            || ($this->diastolic_bp && $this->diastolic_bp >= 90);
    }

    public function isHypotensive(): bool
    {
        return ($this->systolic_bp && $this->systolic_bp < 90)
            || ($this->diastolic_bp && $this->diastolic_bp < 60);
    }

    public function scopeByPatient($query, int $patientId)
    {
        return $query->where('patient_id', $patientId);
    }

    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('recorded_at', '>=', now()->subDays($days));
    }
}
