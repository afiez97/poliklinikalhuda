<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentReconciliation extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_advice_id',
        'panel_claim_id',
        'claimed_amount',
        'approved_amount',
        'paid_amount',
        'adjustment_amount',
        'discrepancy_amount',
        'match_status',
        'discrepancy_reason',
        'notes',
        'is_resolved',
        'reconciled_by',
    ];

    protected $casts = [
        'claimed_amount' => 'decimal:2',
        'approved_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'adjustment_amount' => 'decimal:2',
        'discrepancy_amount' => 'decimal:2',
        'is_resolved' => 'boolean',
    ];

    public const STATUS_MATCHED = 'matched';

    public const STATUS_SHORT_PAYMENT = 'short_payment';

    public const STATUS_OVER_PAYMENT = 'over_payment';

    public const STATUS_UNMATCHED = 'unmatched';

    public const STATUSES = [
        self::STATUS_MATCHED => 'Sepadan',
        self::STATUS_SHORT_PAYMENT => 'Kurang Bayar',
        self::STATUS_OVER_PAYMENT => 'Lebih Bayar',
        self::STATUS_UNMATCHED => 'Tidak Sepadan',
    ];

    public function paymentAdvice(): BelongsTo
    {
        return $this->belongsTo(PaymentAdvice::class);
    }

    public function claim(): BelongsTo
    {
        return $this->belongsTo(PanelClaim::class, 'panel_claim_id');
    }

    public function reconciledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reconciled_by');
    }

    public function scopeMatched($query)
    {
        return $query->where('match_status', self::STATUS_MATCHED);
    }

    public function scopeWithDiscrepancy($query)
    {
        return $query->whereIn('match_status', [self::STATUS_SHORT_PAYMENT, self::STATUS_OVER_PAYMENT]);
    }

    public function scopeUnresolved($query)
    {
        return $query->where('is_resolved', false);
    }

    public function getStatusNameAttribute(): string
    {
        return self::STATUSES[$this->match_status] ?? $this->match_status;
    }

    public function hasDiscrepancy(): bool
    {
        return in_array($this->match_status, [self::STATUS_SHORT_PAYMENT, self::STATUS_OVER_PAYMENT]);
    }

    public function resolve(int $userId, ?string $notes = null): void
    {
        $this->is_resolved = true;
        $this->reconciled_by = $userId;

        if ($notes) {
            $this->notes = $notes;
        }

        $this->save();
    }
}
