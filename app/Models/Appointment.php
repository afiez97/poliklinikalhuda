<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'appointment_no',
        'patient_id',
        'doctor_id',
        'appointment_date',
        'start_time',
        'end_time',
        'duration_minutes',
        'appointment_type',
        'priority',
        'status',
        'reason',
        'notes',
        'reminder_sent',
        'reminder_sent_at',
        'reminder_type',
        'cancellation_reason',
        'cancelled_at',
        'cancelled_by',
        'rescheduled_from',
        'is_panel',
        'booking_source',
        'created_by',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'reminder_sent_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'reminder_sent' => 'boolean',
        'is_panel' => 'boolean',
    ];

    /**
     * Appointment types.
     */
    public const TYPES = [
        'consultation' => 'Konsultasi',
        'follow_up' => 'Susulan',
        'procedure' => 'Prosedur',
        'medical_checkup' => 'Pemeriksaan Kesihatan',
        'vaccination' => 'Vaksinasi',
        'other' => 'Lain-lain',
    ];

    /**
     * Statuses.
     */
    public const STATUSES = [
        'scheduled' => 'Dijadualkan',
        'confirmed' => 'Disahkan',
        'arrived' => 'Telah Tiba',
        'in_progress' => 'Sedang Berlangsung',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
        'no_show' => 'Tidak Hadir',
        'rescheduled' => 'Dijadualkan Semula',
    ];

    /**
     * Booking sources.
     */
    public const BOOKING_SOURCES = [
        'counter' => 'Kaunter',
        'phone' => 'Telefon',
        'online' => 'Dalam Talian',
        'mobile_app' => 'Aplikasi Mudah Alih',
    ];

    /**
     * Generate appointment number.
     */
    public static function generateAppointmentNo(): string
    {
        $prefix = 'APT';
        $date = date('ymd');

        $last = self::withTrashed()
            ->where('appointment_no', 'like', "{$prefix}{$date}%")
            ->orderBy('appointment_no', 'desc')
            ->first();

        if ($last) {
            $lastNumber = (int) substr($last->appointment_no, 9);
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

        static::creating(function ($appointment) {
            if (empty($appointment->appointment_no)) {
                $appointment->appointment_no = self::generateAppointmentNo();
            }
            if (empty($appointment->end_time)) {
                $startTime = \Carbon\Carbon::parse($appointment->start_time);
                $appointment->end_time = $startTime->addMinutes($appointment->duration_minutes)->format('H:i');
            }
        });
    }

    /**
     * Get type label.
     */
    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->appointment_type] ?? $this->appointment_type;
    }

    /**
     * Get status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    /**
     * Get booking source label.
     */
    public function getBookingSourceLabelAttribute(): string
    {
        return self::BOOKING_SOURCES[$this->booking_source] ?? $this->booking_source;
    }

    /**
     * Get formatted time.
     */
    public function getFormattedTimeAttribute(): string
    {
        $start = \Carbon\Carbon::parse($this->start_time)->format('H:i');
        $end = $this->end_time ? \Carbon\Carbon::parse($this->end_time)->format('H:i') : '';

        return $end ? "{$start} - {$end}" : $start;
    }

    /**
     * Check if appointment is upcoming.
     */
    public function isUpcoming(): bool
    {
        return $this->appointment_date->isFuture() ||
            ($this->appointment_date->isToday() && \Carbon\Carbon::parse($this->start_time)->isFuture());
    }

    /**
     * Check if can be cancelled.
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['scheduled', 'confirmed']);
    }

    /**
     * Check if can be rescheduled.
     */
    public function canBeRescheduled(): bool
    {
        return in_array($this->status, ['scheduled', 'confirmed']);
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
     * Get the creator.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the canceller.
     */
    public function canceller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    /**
     * Get the original appointment (if rescheduled).
     */
    public function originalAppointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class, 'rescheduled_from');
    }

    /**
     * Get the visit.
     */
    public function visit(): HasOne
    {
        return $this->hasOne(PatientVisit::class);
    }

    /**
     * Cancel appointment.
     */
    public function cancel(string $reason, int $userId): void
    {
        $this->status = 'cancelled';
        $this->cancellation_reason = $reason;
        $this->cancelled_at = now();
        $this->cancelled_by = $userId;
        $this->save();
    }

    /**
     * Mark as no show.
     */
    public function markAsNoShow(): void
    {
        $this->status = 'no_show';
        $this->save();
    }

    /**
     * Confirm appointment.
     */
    public function confirm(): void
    {
        $this->status = 'confirmed';
        $this->save();
    }

    /**
     * Mark as arrived.
     */
    public function markAsArrived(): void
    {
        $this->status = 'arrived';
        $this->save();
    }

    /**
     * Scope for date.
     */
    public function scopeForDate($query, $date)
    {
        return $query->whereDate('appointment_date', $date);
    }

    /**
     * Scope for doctor.
     */
    public function scopeForDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }

    /**
     * Scope for upcoming.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('appointment_date', '>=', today())
            ->whereIn('status', ['scheduled', 'confirmed']);
    }

    /**
     * Scope for today.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('appointment_date', today());
    }
}
