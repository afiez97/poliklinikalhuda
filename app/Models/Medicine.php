<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medicine extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'barcode',
        'name',
        'name_generic',
        'category_id',
        'dosage_form',
        'strength',
        'unit',
        'manufacturer',
        'cost_price',
        'selling_price',
        'stock_quantity',
        'reorder_level',
        'max_stock_level',
        'expiry_date',
        'storage_conditions',
        'requires_prescription',
        'is_controlled',
        'poison_schedule',
        'contraindications',
        'side_effects',
        'dosage_instructions',
        'notes',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'cost_price' => 'decimal:2',
            'selling_price' => 'decimal:2',
            'expiry_date' => 'date',
            'requires_prescription' => 'boolean',
            'is_controlled' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public const DOSAGE_FORMS = [
        'tablet' => 'Tablet',
        'capsule' => 'Kapsul',
        'syrup' => 'Sirap',
        'suspension' => 'Suspensi',
        'injection' => 'Suntikan',
        'cream' => 'Krim',
        'ointment' => 'Salap',
        'gel' => 'Gel',
        'drops' => 'Titisan',
        'inhaler' => 'Inhaler',
        'suppository' => 'Supositoria',
        'patch' => 'Tampalan',
        'powder' => 'Serbuk',
        'solution' => 'Larutan',
        'lotion' => 'Losyen',
        'spray' => 'Semburan',
        'other' => 'Lain-lain',
    ];

    public const POISON_SCHEDULES = [
        'A' => 'Kumpulan A - Racun berbahaya',
        'B' => 'Kumpulan B - Racun terkawal',
        'C' => 'Kumpulan C - Ubat preskripsi',
        'D' => 'Kumpulan D - Ubat preskripsi khas',
    ];

    public const STORAGE_CONDITIONS = [
        'room_temp' => 'Suhu Bilik (15-25°C)',
        'cool' => 'Sejuk (8-15°C)',
        'refrigerate' => 'Peti Sejuk (2-8°C)',
        'freeze' => 'Beku (<-10°C)',
        'protect_light' => 'Lindung Cahaya',
        'dry' => 'Tempat Kering',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(MedicineCategory::class, 'category_id');
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(MedicineStockMovement::class);
    }

    public function batches(): HasMany
    {
        return $this->hasMany(MedicineBatch::class);
    }

    public function dispensingItems(): HasMany
    {
        return $this->hasMany(DispensingItem::class);
    }

    public function poisonRegisters(): HasMany
    {
        return $this->hasMany(PoisonRegister::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock_quantity', '<=', 'reorder_level');
    }

    public function scopeExpiringSoon($query, int $days = 90)
    {
        return $query->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', now()->addDays($days))
            ->where('expiry_date', '>', now());
    }

    public function scopeExpired($query)
    {
        return $query->whereNotNull('expiry_date')
            ->where('expiry_date', '<', now());
    }

    public function scopeControlled($query)
    {
        return $query->where('is_controlled', true);
    }

    public function isLowStock(): bool
    {
        return $this->stock_quantity <= $this->reorder_level;
    }

    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    public function isExpiringSoon(int $days = 90): bool
    {
        return $this->expiry_date
            && $this->expiry_date->isFuture()
            && $this->expiry_date->diffInDays(now()) <= $days;
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->stock_quantity <= 0) {
            return 'out_of_stock';
        }
        if ($this->isLowStock()) {
            return 'low';
        }

        return 'available';
    }

    public function getStockStatusLabelAttribute(): string
    {
        return match ($this->stock_status) {
            'out_of_stock' => 'Habis Stok',
            'low' => 'Stok Rendah',
            'available' => 'Tersedia',
            default => 'Tidak Diketahui',
        };
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'RM '.number_format($this->selling_price, 2);
    }

    public function getDosageFormLabelAttribute(): string
    {
        return self::DOSAGE_FORMS[$this->dosage_form] ?? $this->dosage_form;
    }

    public static function generateCode(): string
    {
        $prefix = 'MED';
        $lastMedicine = self::where('code', 'like', "{$prefix}%")
            ->orderBy('code', 'desc')
            ->first();

        if ($lastMedicine) {
            $lastNumber = (int) substr($lastMedicine->code, 3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return sprintf('%s%05d', $prefix, $newNumber);
    }
}
