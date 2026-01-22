<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PreAuthorization extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'pa_number',
        'panel_id',
        'patient_id',
        'guarantee_letter_id',
        'encounter_id',
        'procedure_code',
        'procedure_name',
        'estimated_cost',
        'approved_amount',
        'icd10_primary',
        'icd10_secondary',
        'clinical_justification',
        'supporting_documents',
        'requested_date',
        'procedure_date',
        'approval_number',
        'approval_expiry',
        'status',
        'rejection_reason',
        'panel_remarks',
        'submitted_at',
        'responded_at',
        'requested_by',
        'approved_by',
    ];

    protected $casts = [
        'estimated_cost' => 'decimal:2',
        'approved_amount' => 'decimal:2',
        'icd10_secondary' => 'array',
        'supporting_documents' => 'array',
        'requested_date' => 'date',
        'procedure_date' => 'date',
        'approval_expiry' => 'date',
        'submitted_at' => 'datetime',
        'responded_at' => 'datetime',
    ];

    public const STATUS_DRAFT = 'draft';

    public const STATUS_SUBMITTED = 'submitted';

    public const STATUS_PENDING = 'pending';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_REJECTED = 'rejected';

    public const STATUS_EXPIRED = 'expired';

    public const STATUS_CANCELLED = 'cancelled';

    public const STATUSES = [
        self::STATUS_DRAFT => 'Draf',
        self::STATUS_SUBMITTED => 'Dihantar',
        self::STATUS_PENDING => 'Menunggu',
        self::STATUS_APPROVED => 'Diluluskan',
        self::STATUS_REJECTED => 'Ditolak',
        self::STATUS_EXPIRED => 'Tamat Tempoh',
        self::STATUS_CANCELLED => 'Dibatalkan',
    ];

    public function panel(): BelongsTo
    {
        return $this->belongsTo(Panel::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function guaranteeLetter(): BelongsTo
    {
        return $this->belongsTo(GuaranteeLetter::class);
    }

    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class);
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function claims(): HasMany
    {
        return $this->hasMany(PanelClaim::class, 'pre_authorization_id');
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', [self::STATUS_SUBMITTED, self::STATUS_PENDING]);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeByPanel($query, int $panelId)
    {
        return $query->where('panel_id', $panelId);
    }

    public function getStatusNameAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public static function generatePANumber(): string
    {
        $prefix = 'PA';
        $date = now()->format('Ymd');
        $lastPA = self::whereDate('created_at', now())->orderByDesc('id')->first();
        $sequence = $lastPA ? ((int) substr($lastPA->pa_number, -4)) + 1 : 1;

        return $prefix.$date.str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isExpired(): bool
    {
        return $this->approval_expiry && $this->approval_expiry < now();
    }

    public function isValid(): bool
    {
        return $this->isApproved() && ! $this->isExpired();
    }
}
