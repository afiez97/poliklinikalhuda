<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Referral extends Model
{
    use HasFactory;

    protected $fillable = [
        'referral_no',
        'encounter_id',
        'patient_id',
        'referring_doctor_id',
        'referred_to',
        'department',
        'specialist_name',
        'urgency',
        'reason_for_referral',
        'clinical_summary',
        'relevant_investigations',
        'current_medications',
        'referral_date',
        'appointment_date',
        'status',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'referral_date' => 'date',
        'appointment_date' => 'date',
    ];

    public const URGENCY_ROUTINE = 'routine';
    public const URGENCY_URGENT = 'urgent';
    public const URGENCY_EMERGENCY = 'emergency';

    public const URGENCIES = [
        'routine' => 'Biasa',
        'urgent' => 'Segera',
        'emergency' => 'Kecemasan',
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_SENT = 'sent';
    public const STATUS_ACKNOWLEDGED = 'acknowledged';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    public const STATUSES = [
        'pending' => 'Menunggu',
        'sent' => 'Dihantar',
        'acknowledged' => 'Diterima',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
    ];

    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function referringDoctor(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'referring_doctor_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getUrgencyLabelAttribute(): string
    {
        return self::URGENCIES[$this->urgency] ?? $this->urgency;
    }

    public static function generateReferralNo(): string
    {
        $prefix = 'REF';
        $date = now()->format('Ymd');
        $lastReferral = self::where('referral_no', 'like', "{$prefix}-{$date}-%")
            ->orderBy('referral_no', 'desc')
            ->first();

        if ($lastReferral) {
            $lastNumber = (int) substr($lastReferral->referral_no, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return sprintf('%s-%s-%04d', $prefix, $date, $newNumber);
    }
}
