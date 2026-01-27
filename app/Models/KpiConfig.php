<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KpiConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'name_en',
        'category',
        'metric_type',
        'formula',
        'unit',
        'target_value',
        'warning_threshold',
        'critical_threshold',
        'comparison_operator',
        'frequency',
        'is_active',
        'sort_order',
        'config',
    ];

    protected $casts = [
        'target_value' => 'decimal:2',
        'warning_threshold' => 'decimal:2',
        'critical_threshold' => 'decimal:2',
        'is_active' => 'boolean',
        'config' => 'array',
    ];

    public const CATEGORY_FINANCIAL = 'financial';
    public const CATEGORY_CLINICAL = 'clinical';
    public const CATEGORY_OPERATIONAL = 'operational';
    public const CATEGORY_CUSTOMER = 'customer';
    public const CATEGORY_COMPLIANCE = 'compliance';

    public const CATEGORIES = [
        self::CATEGORY_FINANCIAL => 'Kewangan',
        self::CATEGORY_CLINICAL => 'Klinikal',
        self::CATEGORY_OPERATIONAL => 'Operasi',
        self::CATEGORY_CUSTOMER => 'Pelanggan',
        self::CATEGORY_COMPLIANCE => 'Pematuhan',
    ];

    public function snapshots(): HasMany
    {
        return $this->hasMany(KpiSnapshot::class);
    }

    public function latestSnapshot()
    {
        return $this->hasOne(KpiSnapshot::class)->latest('snapshot_date');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function getStatus(float $value): string
    {
        if ($this->comparison_operator === '>=') {
            if ($value >= $this->target_value) return 'good';
            if ($this->warning_threshold && $value >= $this->warning_threshold) return 'warning';
            return 'critical';
        } elseif ($this->comparison_operator === '<=') {
            if ($value <= $this->target_value) return 'good';
            if ($this->warning_threshold && $value <= $this->warning_threshold) return 'warning';
            return 'critical';
        }
        return $value == $this->target_value ? 'good' : 'warning';
    }

    public function getFormattedValue(float $value): string
    {
        return match($this->unit) {
            'RM' => 'RM ' . number_format($value, 2),
            '%' => number_format($value, 1) . '%',
            'minutes' => number_format($value, 0) . ' min',
            default => number_format($value, 0),
        };
    }
}
