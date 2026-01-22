<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class GuaranteeLetter extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'gl_number',
        'panel_id',
        'patient_id',
        'panel_employee_id',
        'panel_dependent_id',
        'document_path',
        'coverage_limit',
        'amount_used',
        'amount_balance',
        'effective_date',
        'expiry_date',
        'diagnoses_covered',
        'special_remarks',
        'verification_status',
        'verification_method',
        'verification_person',
        'verified_by',
        'verified_at',
        'verification_notes',
        'status',
        'created_by',
    ];

    protected $casts = [
        'coverage_limit' => 'decimal:2',
        'amount_used' => 'decimal:2',
        'amount_balance' => 'decimal:2',
        'effective_date' => 'date',
        'expiry_date' => 'date',
        'verified_at' => 'datetime',
    ];

    public const VERIFICATION_PENDING = 'pending';

    public const VERIFICATION_VERIFIED = 'verified';

    public const VERIFICATION_REJECTED = 'rejected';

    public const VERIFICATION_EXPIRED = 'expired';

    public const VERIFICATION_STATUSES = [
        self::VERIFICATION_PENDING => 'Menunggu Pengesahan',
        self::VERIFICATION_VERIFIED => 'Disahkan',
        self::VERIFICATION_REJECTED => 'Ditolak',
        self::VERIFICATION_EXPIRED => 'Tamat Tempoh',
    ];

    public const STATUS_ACTIVE = 'active';

    public const STATUS_UTILIZED = 'utilized';

    public const STATUS_EXPIRED = 'expired';

    public const STATUS_CANCELLED = 'cancelled';

    public const STATUSES = [
        self::STATUS_ACTIVE => 'Aktif',
        self::STATUS_UTILIZED => 'Telah Digunakan',
        self::STATUS_EXPIRED => 'Tamat Tempoh',
        self::STATUS_CANCELLED => 'Dibatalkan',
    ];

    public const METHOD_SYSTEM = 'system';

    public const METHOD_PHONE = 'phone';

    public const METHOD_EMAIL = 'email';

    public const METHOD_PORTAL = 'portal';

    public const METHODS = [
        self::METHOD_SYSTEM => 'Sistem',
        self::METHOD_PHONE => 'Telefon',
        self::METHOD_EMAIL => 'E-mel',
        self::METHOD_PORTAL => 'Portal',
    ];

    // Relationships
    public function panel(): BelongsTo
    {
        return $this->belongsTo(Panel::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(PanelEmployee::class, 'panel_employee_id');
    }

    public function dependent(): BelongsTo
    {
        return $this->belongsTo(PanelDependent::class, 'panel_dependent_id');
    }

    public function verifiedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function utilizations(): HasMany
    {
        return $this->hasMany(GLUtilization::class, 'guarantee_letter_id');
    }

    public function claims(): HasMany
    {
        return $this->hasMany(PanelClaim::class, 'guarantee_letter_id');
    }

    public function preAuthorizations(): HasMany
    {
        return $this->hasMany(PreAuthorization::class, 'guarantee_letter_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeVerified($query)
    {
        return $query->where('verification_status', self::VERIFICATION_VERIFIED);
    }

    public function scopeValid($query)
    {
        return $query->where('effective_date', '<=', now())
            ->where('expiry_date', '>=', now());
    }

    public function scopeExpiringSoon($query, int $days = 7)
    {
        return $query->where('expiry_date', '<=', now()->addDays($days))
            ->where('expiry_date', '>=', now());
    }

    public function scopeByPanel($query, int $panelId)
    {
        return $query->where('panel_id', $panelId);
    }

    // Accessors
    public function getVerificationStatusNameAttribute(): string
    {
        return self::VERIFICATION_STATUSES[$this->verification_status] ?? $this->verification_status;
    }

    public function getStatusNameAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getMethodNameAttribute(): string
    {
        return self::METHODS[$this->verification_method] ?? $this->verification_method;
    }

    public function getUtilizationPercentageAttribute(): float
    {
        if ($this->coverage_limit <= 0) {
            return 0;
        }

        return round(($this->amount_used / $this->coverage_limit) * 100, 2);
    }

    public function getUtilizationLevelAttribute(): string
    {
        $percentage = $this->utilization_percentage;

        return match (true) {
            $percentage >= 100 => 'exceeded',
            $percentage >= 90 => 'critical',
            $percentage >= 80 => 'warning',
            default => 'normal',
        };
    }

    // Methods
    public static function generateGLNumber(): string
    {
        $prefix = 'GL';
        $date = now()->format('Ymd');
        $lastGL = self::whereDate('created_at', now())->orderByDesc('id')->first();
        $sequence = $lastGL ? ((int) substr($lastGL->gl_number, -4)) + 1 : 1;

        return $prefix.$date.str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function isValid(): bool
    {
        return $this->verification_status === self::VERIFICATION_VERIFIED
            && $this->status === self::STATUS_ACTIVE
            && $this->effective_date <= now()
            && $this->expiry_date >= now();
    }

    public function isExpired(): bool
    {
        return $this->expiry_date < now();
    }

    public function isExpiringSoon(int $days = 7): bool
    {
        return $this->expiry_date->diffInDays(now()) <= $days
            && ! $this->isExpired();
    }

    public function hasAvailableBalance(float $amount = 0): bool
    {
        return $this->amount_balance >= $amount;
    }

    public function updateUtilization(float $amount): void
    {
        $this->amount_used += $amount;
        $this->amount_balance = $this->coverage_limit - $this->amount_used;

        if ($this->amount_balance <= 0) {
            $this->status = self::STATUS_UTILIZED;
        }

        $this->save();
    }

    public function verify(int $userId, string $method = 'system', ?string $notes = null): void
    {
        $this->verification_status = self::VERIFICATION_VERIFIED;
        $this->verification_method = $method;
        $this->verified_by = $userId;
        $this->verified_at = now();
        $this->verification_notes = $notes;
        $this->save();
    }
}
