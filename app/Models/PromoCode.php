<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PromoCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'discount_type',
        'discount_value',
        'min_purchase',
        'max_discount',
        'usage_limit',
        'usage_count',
        'valid_from',
        'valid_until',
        'is_active',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'min_purchase' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'usage_limit' => 'integer',
        'usage_count' => 'integer',
        'valid_from' => 'date',
        'valid_until' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get invoices using this promo code.
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Scope for active promo codes.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for valid promo codes.
     */
    public function scopeValid($query)
    {
        return $query->active()
            ->where('valid_from', '<=', today())
            ->where('valid_until', '>=', today())
            ->where(function ($q) {
                $q->whereNull('usage_limit')
                    ->orWhereColumn('usage_count', '<', 'usage_limit');
            });
    }

    /**
     * Check if promo code is valid.
     */
    public function isValid(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->valid_from > today() || $this->valid_until < today()) {
            return false;
        }

        if ($this->usage_limit && $this->usage_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    /**
     * Calculate discount for amount.
     */
    public function calculateDiscount(float $amount): float
    {
        if (! $this->isValid()) {
            return 0;
        }

        if ($this->min_purchase && $amount < $this->min_purchase) {
            return 0;
        }

        if ($this->discount_type === 'percentage') {
            $discount = $amount * ($this->discount_value / 100);

            if ($this->max_discount) {
                $discount = min($discount, $this->max_discount);
            }

            return round($discount, 2);
        }

        // Fixed amount
        return min($this->discount_value, $amount);
    }

    /**
     * Increment usage count.
     */
    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }

    /**
     * Get remaining usage.
     */
    public function getRemainingUsageAttribute(): ?int
    {
        if (! $this->usage_limit) {
            return null;
        }

        return max(0, $this->usage_limit - $this->usage_count);
    }

    /**
     * Get discount description.
     */
    public function getDiscountDescriptionAttribute(): string
    {
        if ($this->discount_type === 'percentage') {
            return number_format($this->discount_value, 0).'%';
        }

        return 'RM '.number_format($this->discount_value, 2);
    }
}
