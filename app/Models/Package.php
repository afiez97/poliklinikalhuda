<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'name_en',
        'description',
        'price',
        'original_price',
        'is_taxable',
        'is_active',
        'valid_from',
        'valid_until',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'is_taxable' => 'boolean',
        'is_active' => 'boolean',
        'valid_from' => 'date',
        'valid_until' => 'date',
    ];

    /**
     * Get package items.
     */
    public function items(): HasMany
    {
        return $this->hasMany(PackageItem::class);
    }

    /**
     * Scope for active packages.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for valid packages.
     */
    public function scopeValid($query)
    {
        return $query->active()
            ->where(function ($q) {
                $q->whereNull('valid_from')
                    ->orWhere('valid_from', '<=', today());
            })
            ->where(function ($q) {
                $q->whereNull('valid_until')
                    ->orWhere('valid_until', '>=', today());
            });
    }

    /**
     * Check if package is valid.
     */
    public function isValid(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->valid_from && $this->valid_from > today()) {
            return false;
        }

        if ($this->valid_until && $this->valid_until < today()) {
            return false;
        }

        return true;
    }

    /**
     * Get savings amount.
     */
    public function getSavingsAmountAttribute(): float
    {
        if (! $this->original_price) {
            return 0;
        }

        return $this->original_price - $this->price;
    }

    /**
     * Get savings percentage.
     */
    public function getSavingsPercentageAttribute(): float
    {
        if (! $this->original_price || $this->original_price <= 0) {
            return 0;
        }

        return round((($this->original_price - $this->price) / $this->original_price) * 100, 1);
    }

    /**
     * Calculate original price from items.
     */
    public function calculateOriginalPrice(): float
    {
        return $this->items->sum(function ($item) {
            return $item->quantity * $item->unit_price;
        });
    }

    /**
     * Update original price from items.
     */
    public function updateOriginalPrice(): void
    {
        $this->update([
            'original_price' => $this->calculateOriginalPrice(),
        ]);
    }
}
