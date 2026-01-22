<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PanelExclusion extends Model
{
    use HasFactory;

    protected $fillable = [
        'panel_id',
        'panel_package_id',
        'exclusion_type',
        'exclusion_code',
        'exclusion_name',
        'reason',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public const TYPE_PROCEDURE = 'procedure';

    public const TYPE_MEDICATION = 'medication';

    public const TYPE_DIAGNOSIS = 'diagnosis';

    public const TYPE_CATEGORY = 'category';

    public const TYPES = [
        self::TYPE_PROCEDURE => 'Prosedur',
        self::TYPE_MEDICATION => 'Ubat',
        self::TYPE_DIAGNOSIS => 'Diagnosis',
        self::TYPE_CATEGORY => 'Kategori',
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
        return $query->where('exclusion_type', $type);
    }

    public function getTypeNameAttribute(): string
    {
        return self::TYPES[$this->exclusion_type] ?? $this->exclusion_type;
    }
}
