<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TriageAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'queue_id',
        'assessed_by',
        'chief_complaint',
        'symptoms_data',
        'vital_signs',
        'pain_score',
        'pain_location',
        'additional_notes',
        'severity_level',
        'severity_score',
        'ai_reasoning',
        'red_flags_detected',
        'differential_diagnoses',
        'recommended_actions',
        'ai_confidence',
        'override_level',
        'override_reason',
        'reviewed_by',
        'reviewed_at',
        'status',
    ];

    protected $casts = [
        'symptoms_data' => 'array',
        'vital_signs' => 'array',
        'ai_reasoning' => 'array',
        'red_flags_detected' => 'array',
        'differential_diagnoses' => 'array',
        'recommended_actions' => 'array',
        'reviewed_at' => 'datetime',
    ];

    // Relationships
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function queue(): BelongsTo
    {
        return $this->belongsTo(Queue::class);
    }

    public function assessedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assessed_by');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeEmergency($query)
    {
        return $query->where('severity_level', 'emergency');
    }

    public function scopeUrgent($query)
    {
        return $query->whereIn('severity_level', ['emergency', 'urgent']);
    }

    // Severity levels with colors and actions
    public static function severityLevels(): array
    {
        return [
            'emergency' => [
                'label' => 'Kecemasan',
                'label_en' => 'Emergency',
                'color' => 'danger',
                'bg_color' => '#dc3545',
                'max_wait' => 0,
                'description' => 'Perlu rawatan segera',
                'action' => 'Bawa terus ke bilik rawatan',
            ],
            'urgent' => [
                'label' => 'Segera',
                'label_en' => 'Urgent',
                'color' => 'warning',
                'bg_color' => '#fd7e14',
                'max_wait' => 10,
                'description' => 'Perlu dilihat dalam 10 minit',
                'action' => 'Prioriti tinggi dalam giliran',
            ],
            'semi_urgent' => [
                'label' => 'Separa Segera',
                'label_en' => 'Semi-Urgent',
                'color' => 'info',
                'bg_color' => '#ffc107',
                'max_wait' => 30,
                'description' => 'Perlu dilihat dalam 30 minit',
                'action' => 'Giliran biasa dengan pemantauan',
            ],
            'standard' => [
                'label' => 'Standard',
                'label_en' => 'Standard',
                'color' => 'success',
                'bg_color' => '#28a745',
                'max_wait' => 60,
                'description' => 'Perlu dilihat dalam 60 minit',
                'action' => 'Giliran biasa',
            ],
            'non_urgent' => [
                'label' => 'Tidak Segera',
                'label_en' => 'Non-Urgent',
                'color' => 'secondary',
                'bg_color' => '#6c757d',
                'max_wait' => 120,
                'description' => 'Boleh menunggu sehingga 2 jam',
                'action' => 'Giliran biasa',
            ],
        ];
    }

    // Get severity info
    public function getSeverityInfoAttribute(): array
    {
        return self::severityLevels()[$this->severity_level] ?? self::severityLevels()['standard'];
    }

    // Get final severity (override or AI)
    public function getFinalSeverityAttribute(): string
    {
        return $this->override_level ?? $this->severity_level;
    }

    // Check if has red flags
    public function hasRedFlags(): bool
    {
        return ! empty($this->red_flags_detected);
    }

    // Get vital signs summary
    public function getVitalsSummaryAttribute(): string
    {
        if (empty($this->vital_signs)) {
            return '-';
        }

        $vitals = $this->vital_signs;
        $parts = [];

        if (isset($vitals['bp_systolic'], $vitals['bp_diastolic'])) {
            $parts[] = "BP: {$vitals['bp_systolic']}/{$vitals['bp_diastolic']}";
        }
        if (isset($vitals['heart_rate'])) {
            $parts[] = "HR: {$vitals['heart_rate']}";
        }
        if (isset($vitals['temperature'])) {
            $parts[] = "T: {$vitals['temperature']}°C";
        }
        if (isset($vitals['spo2'])) {
            $parts[] = "SpO2: {$vitals['spo2']}%";
        }

        return implode(' | ', $parts);
    }
}
