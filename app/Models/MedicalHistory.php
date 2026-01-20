<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicalHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'patient_id',
        'history_type',
        'condition',
        'onset_date',
        'resolved_date',
        'status',
        'details',
        'treatment',
        'recorded_by',
    ];

    protected $casts = [
        'onset_date' => 'date',
        'resolved_date' => 'date',
    ];

    public const HISTORY_TYPES = [
        'past_medical' => 'Sejarah Perubatan',
        'surgical' => 'Sejarah Pembedahan',
        'hospitalization' => 'Sejarah Kemasukan',
        'chronic_disease' => 'Penyakit Kronik',
        'immunization' => 'Imunisasi',
        'social' => 'Sejarah Sosial',
        'obstetric' => 'Sejarah Obstetrik',
    ];

    public const STATUS_OPTIONS = [
        'active' => 'Aktif',
        'resolved' => 'Selesai',
        'ongoing' => 'Berterusan',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function getHistoryTypeLabelAttribute(): string
    {
        return self::HISTORY_TYPES[$this->history_type] ?? $this->history_type;
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_OPTIONS[$this->status] ?? $this->status;
    }

    public function getDurationAttribute(): ?string
    {
        if (! $this->onset_date) {
            return null;
        }

        $endDate = $this->resolved_date ?? now();

        return $this->onset_date->diffForHumans($endDate, true);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['active', 'ongoing']);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('history_type', $type);
    }

    public function scopeChronic($query)
    {
        return $query->where('history_type', 'chronic_disease');
    }

    public function scopeSurgical($query)
    {
        return $query->where('history_type', 'surgical');
    }

    public function scopeByPatient($query, int $patientId)
    {
        return $query->where('patient_id', $patientId);
    }
}
