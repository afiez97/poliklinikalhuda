<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prescription extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'prescription_no',
        'encounter_id',
        'patient_id',
        'prescribed_by',
        'prescription_date',
        'status',
        'valid_until',
        'refill_count',
        'max_refills',
        'clinical_notes',
        'pharmacist_notes',
        'dispensed_by',
        'dispensed_at',
    ];

    protected $casts = [
        'prescription_date' => 'datetime',
        'valid_until' => 'date',
        'dispensed_at' => 'datetime',
    ];

    public const STATUSES = [
        'draft' => 'Draf',
        'pending' => 'Menunggu',
        'dispensed' => 'Telah Dikeluarkan',
        'partially_dispensed' => 'Separa Dikeluarkan',
        'cancelled' => 'Dibatalkan',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($prescription) {
            if (empty($prescription->prescription_no)) {
                $prescription->prescription_no = self::generatePrescriptionNo();
            }
            if (empty($prescription->prescription_date)) {
                $prescription->prescription_date = now();
            }
        });
    }

    public static function generatePrescriptionNo(): string
    {
        $prefix = 'RX';
        $date = date('ymd');

        $lastPrescription = self::withTrashed()
            ->where('prescription_no', 'like', "{$prefix}{$date}%")
            ->orderBy('prescription_no', 'desc')
            ->first();

        if ($lastPrescription) {
            $lastNumber = (int) substr($lastPrescription->prescription_no, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "{$prefix}{$date}{$newNumber}";
    }

    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function prescribedBy(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'prescribed_by');
    }

    public function dispensedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dispensed_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PrescriptionItem::class)->orderBy('sort_order');
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getTotalItemsAttribute(): int
    {
        return $this->items()->count();
    }

    public function getTotalAmountAttribute(): float
    {
        return $this->items()->sum('total_price') ?? 0;
    }

    public function isValid(): bool
    {
        if (! $this->valid_until) {
            return true;
        }

        return $this->valid_until->isFuture();
    }

    public function canRefill(): bool
    {
        return $this->max_refills > 0 && $this->refill_count < $this->max_refills;
    }

    public function dispense(int $userId): void
    {
        $this->update([
            'status' => 'dispensed',
            'dispensed_by' => $userId,
            'dispensed_at' => now(),
        ]);

        $this->items()->update(['status' => 'dispensed', 'dispensed_at' => now()]);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeDispensed($query)
    {
        return $query->where('status', 'dispensed');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('prescription_date', today());
    }

    public function scopeByPatient($query, int $patientId)
    {
        return $query->where('patient_id', $patientId);
    }
}
