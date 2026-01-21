<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DispensingItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'dispensing_record_id',
        'medicine_id',
        'prescription_item_id',
        'quantity_prescribed',
        'quantity_dispensed',
        'batch_no',
        'expiry_date',
        'unit_price',
        'total_price',
        'dosage_instructions',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:2',
            'total_price' => 'decimal:2',
            'expiry_date' => 'date',
        ];
    }

    public function dispensingRecord(): BelongsTo
    {
        return $this->belongsTo(DispensingRecord::class);
    }

    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }

    public function prescriptionItem(): BelongsTo
    {
        return $this->belongsTo(PrescriptionItem::class);
    }

    public function poisonRegisters(): HasMany
    {
        return $this->hasMany(PoisonRegister::class);
    }

    public function calculateTotal(): void
    {
        $this->total_price = $this->unit_price * $this->quantity_dispensed;
    }
}
