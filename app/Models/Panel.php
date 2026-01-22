<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Panel extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'panel_code',
        'panel_name',
        'panel_type',
        'contact_person',
        'phone',
        'email',
        'address',
        'city',
        'state',
        'postcode',
        'payment_terms_days',
        'sla_approval_days',
        'sla_payment_days',
        'logo_path',
        'notes',
        'status',
    ];

    protected $casts = [
        'payment_terms_days' => 'integer',
        'sla_approval_days' => 'integer',
        'sla_payment_days' => 'integer',
    ];

    // Constants
    public const TYPE_CORPORATE = 'corporate';

    public const TYPE_INSURANCE = 'insurance';

    public const TYPE_GOVERNMENT = 'government';

    public const STATUS_ACTIVE = 'active';

    public const STATUS_INACTIVE = 'inactive';

    public const STATUS_SUSPENDED = 'suspended';

    public const TYPES = [
        self::TYPE_CORPORATE => 'Korporat',
        self::TYPE_INSURANCE => 'Insurans',
        self::TYPE_GOVERNMENT => 'Kerajaan',
    ];

    public const STATUSES = [
        self::STATUS_ACTIVE => 'Aktif',
        self::STATUS_INACTIVE => 'Tidak Aktif',
        self::STATUS_SUSPENDED => 'Digantung',
    ];

    // Relationships
    public function contracts(): HasMany
    {
        return $this->hasMany(PanelContract::class);
    }

    public function activeContract()
    {
        return $this->hasOne(PanelContract::class)->where('status', 'active')->latest();
    }

    public function packages(): HasMany
    {
        return $this->hasMany(PanelPackage::class);
    }

    public function feeSchedules(): HasMany
    {
        return $this->hasMany(PanelFeeSchedule::class);
    }

    public function exclusions(): HasMany
    {
        return $this->hasMany(PanelExclusion::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(PanelEmployee::class);
    }

    public function guaranteeLetters(): HasMany
    {
        return $this->hasMany(GuaranteeLetter::class);
    }

    public function claims(): HasMany
    {
        return $this->hasMany(PanelClaim::class);
    }

    public function preAuthorizations(): HasMany
    {
        return $this->hasMany(PreAuthorization::class);
    }

    public function paymentAdvices(): HasMany
    {
        return $this->hasMany(PaymentAdvice::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeCorporate($query)
    {
        return $query->where('panel_type', self::TYPE_CORPORATE);
    }

    public function scopeInsurance($query)
    {
        return $query->where('panel_type', self::TYPE_INSURANCE);
    }

    public function scopeGovernment($query)
    {
        return $query->where('panel_type', self::TYPE_GOVERNMENT);
    }

    // Accessors
    public function getTypeNameAttribute(): string
    {
        return self::TYPES[$this->panel_type] ?? $this->panel_type;
    }

    public function getStatusNameAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->postcode,
            $this->state,
        ]);

        return implode(', ', $parts);
    }

    // Methods
    public static function generateCode(): string
    {
        $lastPanel = self::withTrashed()->orderByDesc('id')->first();
        $nextNumber = $lastPanel ? ((int) substr($lastPanel->panel_code, -4)) + 1 : 1;

        return 'PAN-'.str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    public function getDefaultPackage(): ?PanelPackage
    {
        return $this->packages()->where('is_default', true)->first()
            ?? $this->packages()->first();
    }

    public function isContractExpiringSoon(int $days = 30): bool
    {
        $contract = $this->activeContract;
        if (! $contract) {
            return false;
        }

        return $contract->expiry_date->diffInDays(now()) <= $days;
    }
}
