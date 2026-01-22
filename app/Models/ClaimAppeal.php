<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClaimAppeal extends Model
{
    use HasFactory;

    protected $fillable = [
        'panel_claim_id',
        'claim_rejection_id',
        'appeal_number',
        'appeal_date',
        'appeal_reason',
        'additional_documents',
        'supporting_notes',
        'status',
        'original_amount',
        'appealed_amount',
        'approved_amount',
        'panel_response',
        'responded_at',
        'submitted_by',
    ];

    protected $casts = [
        'appeal_date' => 'date',
        'additional_documents' => 'array',
        'original_amount' => 'decimal:2',
        'appealed_amount' => 'decimal:2',
        'approved_amount' => 'decimal:2',
        'responded_at' => 'datetime',
    ];

    public const STATUS_SUBMITTED = 'submitted';

    public const STATUS_UNDER_REVIEW = 'under_review';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_REJECTED = 'rejected';

    public const STATUS_WITHDRAWN = 'withdrawn';

    public const STATUSES = [
        self::STATUS_SUBMITTED => 'Dihantar',
        self::STATUS_UNDER_REVIEW => 'Dalam Semakan',
        self::STATUS_APPROVED => 'Diluluskan',
        self::STATUS_REJECTED => 'Ditolak',
        self::STATUS_WITHDRAWN => 'Ditarik Balik',
    ];

    public function claim(): BelongsTo
    {
        return $this->belongsTo(PanelClaim::class, 'panel_claim_id');
    }

    public function rejection(): BelongsTo
    {
        return $this->belongsTo(ClaimRejection::class, 'claim_rejection_id');
    }

    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', [self::STATUS_SUBMITTED, self::STATUS_UNDER_REVIEW]);
    }

    public function getStatusNameAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public static function generateAppealNumber(): string
    {
        $prefix = 'APL';
        $date = now()->format('Ymd');
        $lastAppeal = self::whereDate('created_at', now())->orderByDesc('id')->first();
        $sequence = $lastAppeal ? ((int) substr($lastAppeal->appeal_number, -4)) + 1 : 1;

        return $prefix.$date.str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isPending(): bool
    {
        return in_array($this->status, [self::STATUS_SUBMITTED, self::STATUS_UNDER_REVIEW]);
    }
}
