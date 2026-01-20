<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Diagnosis extends Model
{
    use HasFactory;

    protected $fillable = [
        'encounter_id',
        'patient_id',
        'icd10_id',
        'icd10_code',
        'diagnosis_text',
        'type',
        'status',
        'notes',
        'sort_order',
    ];

    public const TYPES = [
        'primary' => 'Utama',
        'secondary' => 'Sekunder',
        'provisional' => 'Sementara',
    ];

    public const STATUS_OPTIONS = [
        'active' => 'Aktif',
        'resolved' => 'Selesai',
        'chronic' => 'Kronik',
    ];

    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function icd10(): BelongsTo
    {
        return $this->belongsTo(Icd10Code::class, 'icd10_id');
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_OPTIONS[$this->status] ?? $this->status;
    }

    public function getFullDiagnosisAttribute(): string
    {
        if ($this->icd10_code) {
            return "[{$this->icd10_code}] {$this->diagnosis_text}";
        }

        return $this->diagnosis_text;
    }

    public function scopePrimary($query)
    {
        return $query->where('type', 'primary');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeChronic($query)
    {
        return $query->where('status', 'chronic');
    }

    public function scopeByPatient($query, int $patientId)
    {
        return $query->where('patient_id', $patientId);
    }
}
