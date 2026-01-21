<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PoisonRegister extends Model
{
    use HasFactory;

    protected $fillable = [
        'register_no',
        'medicine_id',
        'patient_id',
        'dispensing_item_id',
        'transaction_type',
        'quantity',
        'balance_before',
        'balance_after',
        'batch_no',
        'patient_name',
        'patient_ic',
        'patient_address',
        'prescriber_name',
        'prescriber_mmc',
        'purpose',
        'remarks',
        'recorded_by',
        'witnessed_by',
    ];

    public const TRANSACTION_TYPES = [
        'received' => 'Diterima',
        'dispensed' => 'Dispens',
        'returned' => 'Dipulangkan',
        'destroyed' => 'Dimusnahkan',
    ];

    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function dispensingItem(): BelongsTo
    {
        return $this->belongsTo(DispensingItem::class);
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function witnessedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'witnessed_by');
    }

    public function getTransactionTypeLabelAttribute(): string
    {
        return self::TRANSACTION_TYPES[$this->transaction_type] ?? $this->transaction_type;
    }

    public function scopeForMedicine($query, int $medicineId)
    {
        return $query->where('medicine_id', $medicineId);
    }

    public static function generateRegisterNo(): string
    {
        $prefix = 'PR';
        $date = now()->format('Ymd');
        $lastRegister = self::where('register_no', 'like', "{$prefix}{$date}%")
            ->orderBy('register_no', 'desc')
            ->first();

        if ($lastRegister) {
            $lastNumber = (int) substr($lastRegister->register_no, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return sprintf('%s%s%04d', $prefix, $date, $newNumber);
    }
}
