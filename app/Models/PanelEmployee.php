<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PanelEmployee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'panel_id',
        'patient_id',
        'employee_id',
        'name',
        'ic_number',
        'passport_number',
        'department',
        'position',
        'email',
        'phone',
        'package_id',
        'join_date',
        'termination_date',
        'status',
    ];

    protected $casts = [
        'join_date' => 'date',
        'termination_date' => 'date',
    ];

    public const STATUS_ACTIVE = 'active';

    public const STATUS_INACTIVE = 'inactive';

    public const STATUS_TERMINATED = 'terminated';

    public const STATUSES = [
        self::STATUS_ACTIVE => 'Aktif',
        self::STATUS_INACTIVE => 'Tidak Aktif',
        self::STATUS_TERMINATED => 'Ditamatkan',
    ];

    public function panel(): BelongsTo
    {
        return $this->belongsTo(Panel::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(PanelPackage::class, 'package_id');
    }

    public function dependents(): HasMany
    {
        return $this->hasMany(PanelDependent::class);
    }

    public function guaranteeLetters(): HasMany
    {
        return $this->hasMany(GuaranteeLetter::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeByPanel($query, int $panelId)
    {
        return $query->where('panel_id', $panelId);
    }

    public function getStatusNameAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getIdentifierAttribute(): string
    {
        return $this->ic_number ?? $this->passport_number ?? $this->employee_id;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }
}
