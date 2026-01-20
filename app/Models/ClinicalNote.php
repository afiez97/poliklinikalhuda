<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClinicalNote extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'encounter_id',
        'patient_id',
        'note_type',
        'title',
        'content',
        'created_by',
    ];

    public const NOTE_TYPES = [
        'progress' => 'Nota Kemajuan',
        'procedure' => 'Nota Prosedur',
        'nursing' => 'Nota Kejururawatan',
        'consultation' => 'Nota Konsultasi',
        'discharge' => 'Nota Discaj',
        'other' => 'Lain-lain',
    ];

    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getNoteTypeLabelAttribute(): string
    {
        return self::NOTE_TYPES[$this->note_type] ?? $this->note_type;
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('note_type', $type);
    }

    public function scopeByPatient($query, int $patientId)
    {
        return $query->where('patient_id', $patientId);
    }
}
