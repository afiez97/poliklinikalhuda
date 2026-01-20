<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientAllergy extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'patient_id',
        'allergen',
        'allergen_type',
        'severity',
        'reaction',
        'onset_date',
        'status',
        'notes',
        'recorded_by',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'onset_date' => 'date',
        'verified_at' => 'datetime',
    ];

    public const ALLERGEN_TYPES = [
        'drug' => 'Ubat',
        'food' => 'Makanan',
        'environmental' => 'Persekitaran',
        'other' => 'Lain-lain',
    ];

    public const SEVERITIES = [
        'mild' => 'Ringan',
        'moderate' => 'Sederhana',
        'severe' => 'Teruk',
        'life_threatening' => 'Mengancam Nyawa',
    ];

    public const STATUS_OPTIONS = [
        'active' => 'Aktif',
        'inactive' => 'Tidak Aktif',
        'resolved' => 'Selesai',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function getAllergenTypeLabelAttribute(): string
    {
        return self::ALLERGEN_TYPES[$this->allergen_type] ?? $this->allergen_type;
    }

    public function getSeverityLabelAttribute(): string
    {
        return self::SEVERITIES[$this->severity] ?? $this->severity;
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_OPTIONS[$this->status] ?? $this->status;
    }

    public function isVerified(): bool
    {
        return $this->verified_by !== null && $this->verified_at !== null;
    }

    public function verify(int $userId): void
    {
        $this->update([
            'verified_by' => $userId,
            'verified_at' => now(),
        ]);
    }

    public function isSevere(): bool
    {
        return in_array($this->severity, ['severe', 'life_threatening']);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeDrug($query)
    {
        return $query->where('allergen_type', 'drug');
    }

    public function scopeSevere($query)
    {
        return $query->whereIn('severity', ['severe', 'life_threatening']);
    }

    public function scopeByPatient($query, int $patientId)
    {
        return $query->where('patient_id', $patientId);
    }
}
