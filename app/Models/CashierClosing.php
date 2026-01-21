<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashierClosing extends Model
{
    use HasFactory;

    protected $fillable = [
        'closing_date',
        'cashier_id',
        'opening_balance',
        'cash_sales',
        'card_sales',
        'qr_sales',
        'ewallet_sales',
        'transfer_sales',
        'panel_sales',
        'total_sales',
        'total_refunds',
        'net_sales',
        'expected_cash',
        'actual_cash',
        'cash_difference',
        'notes',
        'status',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'closing_date' => 'date',
        'opening_balance' => 'decimal:2',
        'cash_sales' => 'decimal:2',
        'card_sales' => 'decimal:2',
        'qr_sales' => 'decimal:2',
        'ewallet_sales' => 'decimal:2',
        'transfer_sales' => 'decimal:2',
        'panel_sales' => 'decimal:2',
        'total_sales' => 'decimal:2',
        'total_refunds' => 'decimal:2',
        'net_sales' => 'decimal:2',
        'expected_cash' => 'decimal:2',
        'actual_cash' => 'decimal:2',
        'cash_difference' => 'decimal:2',
        'verified_at' => 'datetime',
    ];

    const STATUS_DRAFT = 'draft';

    const STATUS_SUBMITTED = 'submitted';

    const STATUS_VERIFIED = 'verified';

    /**
     * Get the cashier.
     */
    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    /**
     * Get the verifier.
     */
    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Scope for a specific date.
     */
    public function scopeForDate($query, $date)
    {
        return $query->whereDate('closing_date', $date);
    }

    /**
     * Scope for today.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('closing_date', today());
    }

    /**
     * Scope for a specific cashier.
     */
    public function scopeForCashier($query, int $cashierId)
    {
        return $query->where('cashier_id', $cashierId);
    }

    /**
     * Scope for submitted status.
     */
    public function scopeSubmitted($query)
    {
        return $query->where('status', self::STATUS_SUBMITTED);
    }

    /**
     * Scope for verified status.
     */
    public function scopeVerified($query)
    {
        return $query->where('status', self::STATUS_VERIFIED);
    }

    /**
     * Check if has discrepancy.
     */
    public function hasDiscrepancy(): bool
    {
        return abs($this->cash_difference) > 0;
    }

    /**
     * Check if is over.
     */
    public function isOver(): bool
    {
        return $this->cash_difference > 0;
    }

    /**
     * Check if is short.
     */
    public function isShort(): bool
    {
        return $this->cash_difference < 0;
    }

    /**
     * Calculate totals from payments.
     */
    public function calculateFromPayments(): void
    {
        $payments = Payment::whereDate('created_at', $this->closing_date)
            ->where('status', 'completed')
            ->get();

        $this->cash_sales = $payments->where('payment_method', 'cash')->sum('amount');
        $this->card_sales = $payments->where('payment_method', 'card')->sum('amount');
        $this->qr_sales = $payments->where('payment_method', 'qr')->sum('amount');
        $this->ewallet_sales = $payments->where('payment_method', 'ewallet')->sum('amount');
        $this->transfer_sales = $payments->where('payment_method', 'transfer')->sum('amount');
        $this->panel_sales = $payments->where('payment_method', 'panel')->sum('amount');

        $this->total_sales = $this->cash_sales + $this->card_sales + $this->qr_sales
            + $this->ewallet_sales + $this->transfer_sales + $this->panel_sales;

        $this->total_refunds = Refund::whereDate('processed_at', $this->closing_date)
            ->where('status', 'processed')
            ->sum('amount');

        $this->net_sales = $this->total_sales - $this->total_refunds;
        $this->expected_cash = $this->opening_balance + $this->cash_sales;
    }

    /**
     * Set actual cash and calculate difference.
     */
    public function setActualCash(float $amount): void
    {
        $this->actual_cash = $amount;
        $this->cash_difference = $this->actual_cash - $this->expected_cash;
    }

    /**
     * Submit for verification.
     */
    public function submit(): bool
    {
        $this->status = self::STATUS_SUBMITTED;

        return $this->save();
    }

    /**
     * Verify the closing.
     */
    public function verify(int $verifiedBy): bool
    {
        $this->status = self::STATUS_VERIFIED;
        $this->verified_by = $verifiedBy;
        $this->verified_at = now();

        return $this->save();
    }

    /**
     * Get status badge class.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_DRAFT => 'bg-secondary',
            self::STATUS_SUBMITTED => 'bg-warning',
            self::STATUS_VERIFIED => 'bg-success',
            default => 'bg-secondary',
        };
    }

    /**
     * Get status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_DRAFT => 'Draf',
            self::STATUS_SUBMITTED => 'Dihantar',
            self::STATUS_VERIFIED => 'Disahkan',
            default => $this->status,
        };
    }

    /**
     * Get difference display with sign.
     */
    public function getDifferenceDisplayAttribute(): string
    {
        if ($this->cash_difference == 0) {
            return 'RM 0.00 (Seimbang)';
        }

        $sign = $this->cash_difference > 0 ? '+' : '';

        return $sign.'RM '.number_format($this->cash_difference, 2);
    }
}
