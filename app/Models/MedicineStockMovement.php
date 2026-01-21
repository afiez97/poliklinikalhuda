<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicineStockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_no',
        'medicine_id',
        'movement_type',
        'quantity',
        'stock_before',
        'stock_after',
        'unit_cost',
        'total_cost',
        'batch_no',
        'expiry_date',
        'supplier_id',
        'source_type',
        'source_id',
        'reason',
        'notes',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'unit_cost' => 'decimal:2',
            'total_cost' => 'decimal:2',
            'expiry_date' => 'date',
        ];
    }

    public const MOVEMENT_TYPES = [
        'in' => 'Masuk',
        'out' => 'Keluar',
        'adjustment' => 'Pelarasan',
        'return' => 'Pulangan',
        'expired' => 'Luput',
        'damaged' => 'Rosak',
    ];

    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getMovementTypeLabelAttribute(): string
    {
        return self::MOVEMENT_TYPES[$this->movement_type] ?? $this->movement_type;
    }

    public function scopeType($query, string $type)
    {
        return $query->where('movement_type', $type);
    }

    public function scopeForMedicine($query, int $medicineId)
    {
        return $query->where('medicine_id', $medicineId);
    }

    public static function generateReferenceNo(): string
    {
        $prefix = 'STK';
        $date = now()->format('Ymd');
        $lastMovement = self::where('reference_no', 'like', "{$prefix}{$date}%")
            ->orderBy('reference_no', 'desc')
            ->first();

        if ($lastMovement) {
            $lastNumber = (int) substr($lastMovement->reference_no, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return sprintf('%s%s%04d', $prefix, $date, $newNumber);
    }
}
