<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PanelFeeSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'panel_id',
        'panel_package_id',
        'service_type',
        'service_code',
        'service_name',
        'panel_rate',
        'standard_rate',
        'markup_percentage',
        'is_active',
    ];

    protected $casts = [
        'panel_rate' => 'decimal:2',
        'standard_rate' => 'decimal:2',
        'markup_percentage' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public const TYPE_CONSULTATION = 'consultation';
    public const TYPE_PROCEDURE = 'procedure';
    public const TYPE_MEDICATION = 'medication';
    public const TYPE_LAB = 'lab';

    public const TYPES = [
        self::TYPE_CONSULTATION => 'Konsultasi',
        self::TYPE_PROCEDURE => 'Prosedur',
        self::TYPE_MEDICATION => 'Ubat',
        self::TYPE_LAB => 'Makmal',
    ];

    public function panel(): BelongsTo
    {
        return $this->belongsTo(Panel::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(PanelPackage::class, 'panel_package_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('service_type', $type);
    }

    public function getTypeNameAttribute(): string
    {
        return self::TYPES[$this->service_type] ?? $this->service_type;
    }
}
