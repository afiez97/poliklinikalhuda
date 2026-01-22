<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class PanelClaim extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'claim_number',
        'invoice_id',
        'panel_id',
        'guarantee_letter_id',
        'patient_id',
        'encounter_id',
        'pre_authorization_id',
        'claim_date',
        'service_date',
        'icd10_primary',
        'icd10_secondary',
        'total_invoice_amount',
        'co_payment_amount',
        'deductible_amount',
        'excluded_amount',
        'claimable_amount',
        'approved_amount',
        'paid_amount',
        'adjustment_amount',
        'claim_status',
        'rejection_reason',
        'panel_remarks',
        'submitted_at',
        'acknowledged_at',
        'approved_at',
        'paid_at',
        'sla_due_date',
        'is_overdue',
        'batch_id',
        'payment_reference',
        'notes',
        'created_by',
        'submitted_by',
    ];

    protected $casts = [
        'claim_date' => 'date',
        'service_date' => 'date',
        'icd10_secondary' => 'array',
        'total_invoice_amount' => 'decimal:2',
        'co_payment_amount' => 'decimal:2',
        'deductible_amount' => 'decimal:2',
        'excluded_amount' => 'decimal:2',
        'claimable_amount' => 'decimal:2',
        'approved_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'adjustment_amount' => 'decimal:2',
        'submitted_at' => 'datetime',
        'acknowledged_at' => 'datetime',
        'approved_at' => 'datetime',
        'paid_at' => 'datetime',
        'sla_due_date' => 'date',
        'is_overdue' => 'boolean',
    ];

    public const STATUS_DRAFT = 'draft';

    public const STATUS_SUBMITTED = 'submitted';

    public const STATUS_ACKNOWLEDGED = 'acknowledged';

    public const STATUS_UNDER_REVIEW = 'under_review';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_PARTIALLY_APPROVED = 'partially_approved';

    public const STATUS_REJECTED = 'rejected';

    public const STATUS_PAID = 'paid';

    public const STATUS_CANCELLED = 'cancelled';

    public const STATUSES = [
        self::STATUS_DRAFT => 'Draf',
        self::STATUS_SUBMITTED => 'Dihantar',
        self::STATUS_ACKNOWLEDGED => 'Diterima',
        self::STATUS_UNDER_REVIEW => 'Dalam Semakan',
        self::STATUS_APPROVED => 'Diluluskan',
        self::STATUS_PARTIALLY_APPROVED => 'Diluluskan Sebahagian',
        self::STATUS_REJECTED => 'Ditolak',
        self::STATUS_PAID => 'Telah Dibayar',
        self::STATUS_CANCELLED => 'Dibatalkan',
    ];

    // Relationships
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function panel(): BelongsTo
    {
        return $this->belongsTo(Panel::class);
    }

    public function guaranteeLetter(): BelongsTo
    {
        return $this->belongsTo(GuaranteeLetter::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class);
    }

    public function preAuthorization(): BelongsTo
    {
        return $this->belongsTo(PreAuthorization::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(ClaimDocument::class, 'panel_claim_id');
    }

    public function rejections(): HasMany
    {
        return $this->hasMany(ClaimRejection::class, 'panel_claim_id');
    }

    public function latestRejection(): HasOne
    {
        return $this->hasOne(ClaimRejection::class, 'panel_claim_id')->latest();
    }

    public function appeals(): HasMany
    {
        return $this->hasMany(ClaimAppeal::class, 'panel_claim_id');
    }

    public function reconciliations(): HasMany
    {
        return $this->hasMany(PaymentReconciliation::class, 'panel_claim_id');
    }

    // Scopes
    public function scopeDraft($query)
    {
        return $query->where('claim_status', self::STATUS_DRAFT);
    }

    public function scopeSubmitted($query)
    {
        return $query->whereIn('claim_status', [
            self::STATUS_SUBMITTED,
            self::STATUS_ACKNOWLEDGED,
            self::STATUS_UNDER_REVIEW,
        ]);
    }

    public function scopeApproved($query)
    {
        return $query->whereIn('claim_status', [
            self::STATUS_APPROVED,
            self::STATUS_PARTIALLY_APPROVED,
        ]);
    }

    public function scopeRejected($query)
    {
        return $query->where('claim_status', self::STATUS_REJECTED);
    }

    public function scopePaid($query)
    {
        return $query->where('claim_status', self::STATUS_PAID);
    }

    public function scopeOutstanding($query)
    {
        return $query->whereNotIn('claim_status', [
            self::STATUS_PAID,
            self::STATUS_CANCELLED,
            self::STATUS_REJECTED,
        ]);
    }

    public function scopeOverdue($query)
    {
        return $query->where('is_overdue', true);
    }

    public function scopeByPanel($query, int $panelId)
    {
        return $query->where('panel_id', $panelId);
    }

    public function scopeByBatch($query, string $batchId)
    {
        return $query->where('batch_id', $batchId);
    }

    public function scopeAgingDays($query, int $minDays, ?int $maxDays = null)
    {
        $query->where('submitted_at', '<=', now()->subDays($minDays));

        if ($maxDays !== null) {
            $query->where('submitted_at', '>', now()->subDays($maxDays));
        }

        return $query;
    }

    // Accessors
    public function getStatusNameAttribute(): string
    {
        return self::STATUSES[$this->claim_status] ?? $this->claim_status;
    }

    public function getAgingDaysAttribute(): int
    {
        if (! $this->submitted_at) {
            return 0;
        }

        return $this->submitted_at->diffInDays(now());
    }

    public function getPatientPortionAttribute(): float
    {
        return $this->co_payment_amount + $this->deductible_amount + $this->excluded_amount;
    }

    public function getOutstandingAmountAttribute(): float
    {
        if ($this->claim_status === self::STATUS_PAID) {
            return 0;
        }

        return $this->claimable_amount - ($this->paid_amount ?? 0);
    }

    public function getClaimAmountAttribute(): float
    {
        return (float) ($this->claimable_amount ?? 0);
    }

    // Methods
    public static function generateClaimNumber(): string
    {
        $prefix = 'CLM';
        $date = now()->format('Ymd');
        $lastClaim = self::whereDate('created_at', now())->orderByDesc('id')->first();
        $sequence = $lastClaim ? ((int) substr($lastClaim->claim_number, -4)) + 1 : 1;

        return $prefix.'-'.$date.'-'.str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function submit(int $userId): void
    {
        $this->claim_status = self::STATUS_SUBMITTED;
        $this->submitted_at = now();
        $this->submitted_by = $userId;
        $this->sla_due_date = now()->addDays($this->panel->sla_payment_days ?? 14);
        $this->save();
    }

    public function approve(float $amount, ?string $remarks = null): void
    {
        $this->claim_status = $amount >= $this->claimable_amount
            ? self::STATUS_APPROVED
            : self::STATUS_PARTIALLY_APPROVED;
        $this->approved_amount = $amount;
        $this->approved_at = now();
        $this->panel_remarks = $remarks;
        $this->save();
    }

    public function reject(string $reason, ?string $remarks = null): void
    {
        $this->claim_status = self::STATUS_REJECTED;
        $this->rejection_reason = $reason;
        $this->panel_remarks = $remarks;
        $this->save();
    }

    public function markAsPaid(float $amount, ?string $paymentReference = null): void
    {
        $this->claim_status = self::STATUS_PAID;
        $this->paid_amount = $amount;
        $this->paid_at = now();
        $this->payment_reference = $paymentReference;
        $this->save();
    }

    public function checkSLAStatus(): void
    {
        if ($this->sla_due_date && $this->sla_due_date < now()) {
            $this->is_overdue = true;
            $this->save();
        }
    }
}
