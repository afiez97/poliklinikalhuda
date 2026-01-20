<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientVisit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'visit_no',
        'patient_id',
        'appointment_id',
        'visit_date',
        'check_in_time',
        'check_out_time',
        'visit_type',
        'priority',
        'queue_number',
        'queue_prefix',
        'status',
        'doctor_id',
        'nurse_id',
        'chief_complaint',
        'consultation_start',
        'consultation_end',
        'is_billable',
        'is_panel',
        'panel_company_id',
        'registered_by',
        'notes',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'check_in_time' => 'datetime:H:i',
        'check_out_time' => 'datetime:H:i',
        'consultation_start' => 'datetime',
        'consultation_end' => 'datetime',
        'is_billable' => 'boolean',
        'is_panel' => 'boolean',
    ];

    /**
     * Visit types.
     */
    public const VISIT_TYPES = [
        'walk_in' => 'Walk-in',
        'appointment' => 'Temujanji',
        'emergency' => 'Kecemasan',
        'follow_up' => 'Susulan',
        'referral' => 'Rujukan',
    ];

    /**
     * Priorities.
     */
    public const PRIORITIES = [
        'normal' => 'Biasa',
        'urgent' => 'Segera',
        'emergency' => 'Kecemasan',
    ];

    /**
     * Statuses.
     */
    public const STATUSES = [
        'registered' => 'Baru Daftar',
        'waiting' => 'Menunggu',
        'in_consultation' => 'Dalam Rawatan',
        'pending_lab' => 'Menunggu Lab',
        'pending_pharmacy' => 'Menunggu Ubat',
        'pending_payment' => 'Menunggu Bayaran',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
        'no_show' => 'Tidak Hadir',
    ];

    /**
     * Generate visit number.
     */
    public static function generateVisitNo(): string
    {
        $prefix = 'V';
        $date = date('ymd');

        $lastVisit = self::withTrashed()
            ->where('visit_no', 'like', "{$prefix}{$date}%")
            ->orderBy('visit_no', 'desc')
            ->first();

        if ($lastVisit) {
            $lastNumber = (int) substr($lastVisit->visit_no, 7);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return sprintf('%s%s%04d', $prefix, $date, $newNumber);
    }

    /**
     * Boot method.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($visit) {
            if (empty($visit->visit_no)) {
                $visit->visit_no = self::generateVisitNo();
            }
            if (empty($visit->visit_date)) {
                $visit->visit_date = now();
            }
        });
    }

    /**
     * Get visit type label.
     */
    public function getVisitTypeLabelAttribute(): string
    {
        return self::VISIT_TYPES[$this->visit_type] ?? $this->visit_type;
    }

    /**
     * Get priority label.
     */
    public function getPriorityLabelAttribute(): string
    {
        return self::PRIORITIES[$this->priority] ?? $this->priority;
    }

    /**
     * Get status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    /**
     * Get full queue number.
     */
    public function getFullQueueNumberAttribute(): string
    {
        if (! $this->queue_number) {
            return '-';
        }

        return ($this->queue_prefix ?? '').$this->queue_number;
    }

    /**
     * Get consultation duration in minutes.
     */
    public function getConsultationDurationAttribute(): ?int
    {
        if (! $this->consultation_start || ! $this->consultation_end) {
            return null;
        }

        return $this->consultation_start->diffInMinutes($this->consultation_end);
    }

    /**
     * Get the patient.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the doctor.
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'doctor_id');
    }

    /**
     * Get the nurse.
     */
    public function nurse(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'nurse_id');
    }

    /**
     * Get the registrar.
     */
    public function registrar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registered_by');
    }

    /**
     * Check in.
     */
    public function checkIn(): void
    {
        $this->check_in_time = now()->format('H:i:s');
        $this->status = 'waiting';
        $this->save();
    }

    /**
     * Check out.
     */
    public function checkOut(): void
    {
        $this->check_out_time = now()->format('H:i:s');
        $this->status = 'completed';
        $this->save();
    }

    /**
     * Start consultation.
     */
    public function startConsultation(): void
    {
        $this->consultation_start = now();
        $this->status = 'in_consultation';
        $this->save();
    }

    /**
     * End consultation.
     */
    public function endConsultation(): void
    {
        $this->consultation_end = now();
        $this->status = 'pending_pharmacy';
        $this->save();
    }

    /**
     * Scope for today.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('visit_date', today());
    }

    /**
     * Scope for waiting.
     */
    public function scopeWaiting($query)
    {
        return $query->whereIn('status', ['registered', 'waiting']);
    }

    /**
     * Scope for in progress.
     */
    public function scopeInProgress($query)
    {
        return $query->whereIn('status', ['in_consultation', 'pending_lab', 'pending_pharmacy', 'pending_payment']);
    }
}
