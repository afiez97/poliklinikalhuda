<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Encounter extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'encounter_no',
        'patient_id',
        'patient_visit_id',
        'doctor_id',
        'template_id',
        'encounter_date',
        'status',
        'chief_complaint',
        'history_present_illness',
        'subjective',
        'objective',
        'assessment',
        'plan',
        'clinical_notes',
        'private_notes',
        'follow_up_date',
        'follow_up_instructions',
        'needs_referral',
        'referral_specialty',
        'referral_notes',
        'started_at',
        'completed_at',
        'completed_by',
    ];

    protected $casts = [
        'encounter_date' => 'datetime',
        'follow_up_date' => 'date',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'needs_referral' => 'boolean',
    ];

    public const STATUSES = [
        'draft' => 'Draf',
        'in_progress' => 'Sedang Rawatan',
        'pending_review' => 'Menunggu Semakan',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($encounter) {
            if (empty($encounter->encounter_no)) {
                $encounter->encounter_no = self::generateEncounterNo();
            }
            if (empty($encounter->encounter_date)) {
                $encounter->encounter_date = now();
            }
        });
    }

    public static function generateEncounterNo(): string
    {
        $prefix = 'ENC';
        $date = date('ymd');

        $lastEncounter = self::withTrashed()
            ->where('encounter_no', 'like', "{$prefix}{$date}%")
            ->orderBy('encounter_no', 'desc')
            ->first();

        if ($lastEncounter) {
            $lastNumber = (int) substr($lastEncounter->encounter_no, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "{$prefix}{$date}{$newNumber}";
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function patientVisit(): BelongsTo
    {
        return $this->belongsTo(PatientVisit::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'doctor_id');
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(ClinicalTemplate::class, 'template_id');
    }

    public function completedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    public function vitalSigns(): HasMany
    {
        return $this->hasMany(VitalSign::class);
    }

    public function latestVitalSigns(): HasOne
    {
        return $this->hasOne(VitalSign::class)->latestOfMany('recorded_at');
    }

    public function diagnoses(): HasMany
    {
        return $this->hasMany(Diagnosis::class)->orderBy('sort_order');
    }

    public function primaryDiagnosis(): HasOne
    {
        return $this->hasOne(Diagnosis::class)->where('type', 'primary');
    }

    public function clinicalNotes(): HasMany
    {
        return $this->hasMany(ClinicalNote::class);
    }

    public function prescriptions(): HasMany
    {
        return $this->hasMany(Prescription::class);
    }

    public function procedures(): HasMany
    {
        return $this->hasMany(Procedure::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(ClinicalAttachment::class);
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(Referral::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getDurationMinutesAttribute(): ?int
    {
        if ($this->started_at && $this->completed_at) {
            return $this->started_at->diffInMinutes($this->completed_at);
        }

        return null;
    }

    public function start(): void
    {
        $this->update([
            'status' => 'in_progress',
            'started_at' => now(),
        ]);
    }

    public function complete(?int $userId = null): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'completed_by' => $userId ?? auth()->id(),
        ]);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('encounter_date', today());
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByDoctor($query, int $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }
}
