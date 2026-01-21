<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class BillingSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'description',
    ];

    /**
     * Get a setting value by key.
     */
    public static function getValue(string $key, $default = null)
    {
        return Cache::remember("billing_setting_{$key}", 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();

            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set a setting value.
     */
    public static function setValue(string $key, $value, ?string $description = null): self
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'description' => $description]
        );

        Cache::forget("billing_setting_{$key}");

        return $setting;
    }

    /**
     * Get SST rate.
     */
    public static function getSstRate(): float
    {
        return (float) self::getValue('sst_rate', 6);
    }

    /**
     * Check if SST is enabled.
     */
    public static function isSstEnabled(): bool
    {
        return (bool) self::getValue('sst_enabled', true);
    }

    /**
     * Check if rounding is enabled.
     */
    public static function isRoundingEnabled(): bool
    {
        return (bool) self::getValue('rounding_enabled', true);
    }

    /**
     * Get rounding precision (in sen).
     */
    public static function getRoundingPrecision(): int
    {
        return (int) self::getValue('rounding_precision', 5);
    }

    /**
     * Get discount approval threshold.
     */
    public static function getDiscountApprovalThreshold(): float
    {
        return (float) self::getValue('discount_approval_threshold', 10);
    }

    /**
     * Get max discount percentage.
     */
    public static function getMaxDiscountPercentage(): float
    {
        return (float) self::getValue('max_discount_percentage', 50);
    }

    /**
     * Get invoice prefix.
     */
    public static function getInvoicePrefix(): string
    {
        return (string) self::getValue('invoice_prefix', 'INV');
    }

    /**
     * Get receipt prefix.
     */
    public static function getReceiptPrefix(): string
    {
        return (string) self::getValue('receipt_prefix', 'RCP');
    }

    /**
     * Get payment terms (days).
     */
    public static function getPaymentTermsDays(): int
    {
        return (int) self::getValue('payment_terms_days', 30);
    }

    /**
     * Get default opening balance.
     */
    public static function getDefaultOpeningBalance(): float
    {
        return (float) self::getValue('default_opening_balance', 500);
    }

    /**
     * Calculate rounding adjustment.
     * Follows Bank Negara Malaysia guideline for rounding to nearest 5 sen.
     */
    public static function calculateRounding(float $amount): float
    {
        if (! self::isRoundingEnabled()) {
            return 0;
        }

        $precision = self::getRoundingPrecision();
        $cents = round(fmod($amount, 1) * 100);
        $remainder = $cents % $precision;

        if ($remainder == 0) {
            return 0;
        }

        // Round to nearest
        if ($remainder < ($precision / 2)) {
            return -$remainder / 100;
        } else {
            return ($precision - $remainder) / 100;
        }
    }

    /**
     * Apply rounding to amount.
     */
    public static function applyRounding(float $amount): float
    {
        return $amount + self::calculateRounding($amount);
    }

    /**
     * Calculate SST amount.
     */
    public static function calculateSst(float $amount): float
    {
        if (! self::isSstEnabled()) {
            return 0;
        }

        return round($amount * (self::getSstRate() / 100), 2);
    }

    /**
     * Get all settings as array.
     */
    public static function getAllSettings(): array
    {
        return self::all()->pluck('value', 'key')->toArray();
    }

    /**
     * Clear all cached settings.
     */
    public static function clearCache(): void
    {
        $settings = self::all();
        foreach ($settings as $setting) {
            Cache::forget("billing_setting_{$setting->key}");
        }
    }
}
