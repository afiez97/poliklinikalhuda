<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DispensingRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'dispensing_no',
        'encounter_id',
        'prescription_id',
        'patient_id',
        'dispensed_by',
        'verified_by',
        'dispensed_at',
        'verified_at',
        'total_amount',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'dispensed_at' => 'datetime',
            'verified_at' => 'datetime',
            'total_amount' => 'decimal:2',
        ];
    }

    public const STATUS_PENDING = 'pending';

    public const STATUS_DISPENSED = 'dispensed';

    public const STATUS_PARTIALLY_DISPENSED = 'partially_dispensed';

    public const STATUS_CANCELLED = 'cancelled';

    public const STATUSES = [
        'pending' => 'Menunggu',
        'dispensed' => 'Selesai Dispens',
        'partially_dispensed' => 'Separa Dispens',
        'cancelled' => 'Dibatalkan',
    ];

    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class);
    }

    public function prescription(): BelongsTo
    {
        return $this->belongsTo(Prescription::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function dispensedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dispensed_by');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(DispensingItem::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('dispensed_at', today());
    }

    public function calculateTotal(): void
    {
        $this->total_amount = $this->items->sum('total_price');
    }

    public static function generateDispensingNo(): string
    {
        $prefix = 'DIS';
        $date = now()->format('Ymd');
        $lastRecord = self::where('dispensing_no', 'like', "{$prefix}{$date}%")
            ->orderBy('dispensing_no', 'desc')
            ->first();

        if ($lastRecord) {
            $lastNumber = (int) substr($lastRecord->dispensing_no, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return sprintf('%s%s%04d', $prefix, $date, $newNumber);
    }
}
