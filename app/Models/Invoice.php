<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Invoice extends Model
{
    use HasFactory;

    // Status constants
    public const STATUS_DRAFT = 'draft';

    public const STATUS_ISSUED = 'pending_payment';

    public const STATUS_PARTIAL = 'partially_paid';

    public const STATUS_PAID = 'fully_paid';

    public const STATUS_OVERDUE = 'overdue';

    public const STATUS_VOID = 'voided';

    public const STATUS_REFUNDED = 'refunded';

    protected $fillable = [
        'invoice_number',
        'patient_id',
        'patient_visit_id',
        'encounter_id',
        'invoice_date',
        'due_date',
        'subtotal',
        'discount_type',
        'discount_value',
        'discount_amount',
        'promo_code_id',
        'taxable_amount',
        'sst_rate',
        'sst_amount',
        'rounding_adjustment',
        'total_amount',
        'paid_amount',
        'balance_owed',
        'status',
        'notes',
        'created_by',
        'voided_by',
        'voided_at',
        'void_reason',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'discount_value' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'taxable_amount' => 'decimal:2',
        'sst_rate' => 'decimal:2',
        'sst_amount' => 'decimal:2',
        'rounding_adjustment' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'balance_owed' => 'decimal:2',
        'voided_at' => 'datetime',
    ];

    /**
     * Status labels.
     */
    public const STATUS_LABELS = [
        'draft' => 'Draf',
        'pending_payment' => 'Belum Bayar',
        'partially_paid' => 'Sebahagian',
        'fully_paid' => 'Selesai',
        'overdue' => 'Tertunggak',
        'voided' => 'Dibatalkan',
        'refunded' => 'Dipulangkan',
    ];

    /**
     * Get the patient.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the patient visit.
     */
    public function patientVisit(): BelongsTo
    {
        return $this->belongsTo(PatientVisit::class);
    }

    /**
     * Get the encounter.
     */
    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class);
    }

    /**
     * Get the promo code.
     */
    public function promoCode(): BelongsTo
    {
        return $this->belongsTo(PromoCode::class);
    }

    /**
     * Get the creator.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the voider.
     */
    public function voidedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'voided_by');
    }

    /**
     * Get the invoice items.
     */
    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    /**
     * Get the payments.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the receipts.
     */
    public function receipts(): HasMany
    {
        return $this->hasMany(Receipt::class);
    }

    /**
     * Get the refunds.
     */
    public function refunds(): HasMany
    {
        return $this->hasMany(Refund::class);
    }

    /**
     * Get the discount approval.
     */
    public function discountApproval(): HasOne
    {
        return $this->hasOne(DiscountApproval::class);
    }

    /**
     * Scope for today's invoices.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('invoice_date', today());
    }

    /**
     * Scope for pending payment.
     */
    public function scopePendingPayment($query)
    {
        return $query->where('status', 'pending_payment');
    }

    /**
     * Scope for overdue.
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue')
            ->orWhere(function ($q) {
                $q->whereIn('status', ['pending_payment', 'partially_paid'])
                    ->whereNotNull('due_date')
                    ->where('due_date', '<', today());
            });
    }

    /**
     * Scope for with outstanding balance.
     */
    public function scopeWithOutstanding($query)
    {
        return $query->where('balance_owed', '>', 0)
            ->whereNotIn('status', ['voided', 'refunded']);
    }

    /**
     * Get status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    /**
     * Get status color.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'secondary',
            'pending_payment' => 'warning',
            'partially_paid' => 'info',
            'fully_paid' => 'success',
            'overdue' => 'danger',
            'voided' => 'dark',
            'refunded' => 'purple',
            default => 'secondary',
        };
    }

    /**
     * Check if invoice can be paid.
     */
    public function canBePaid(): bool
    {
        return in_array($this->status, ['pending_payment', 'partially_paid', 'overdue'])
            && $this->balance_owed > 0;
    }

    /**
     * Check if invoice can be voided.
     */
    public function canBeVoided(): bool
    {
        return in_array($this->status, ['draft', 'pending_payment'])
            && $this->paid_amount == 0;
    }

    /**
     * Check if invoice is overdue.
     */
    public function isOverdue(): bool
    {
        return $this->due_date
            && $this->due_date->lt(today())
            && $this->balance_owed > 0;
    }

    /**
     * Get days overdue.
     */
    public function getDaysOverdueAttribute(): int
    {
        if (! $this->due_date || $this->balance_owed <= 0) {
            return 0;
        }

        return max(0, today()->diffInDays($this->due_date, false));
    }

    /**
     * Get aging bracket.
     */
    public function getAgingBracketAttribute(): string
    {
        $days = $this->days_overdue;

        if ($days <= 0) {
            return 'current';
        }
        if ($days <= 30) {
            return '1-30';
        }
        if ($days <= 60) {
            return '31-60';
        }
        if ($days <= 90) {
            return '61-90';
        }

        return '90+';
    }

    /**
     * Generate invoice number.
     */
    public static function generateInvoiceNumber(): string
    {
        $prefix = BillingSetting::getValue('invoice_prefix', 'INV');
        $date = now()->format('Ymd');

        $lastInvoice = self::where('invoice_number', 'like', "{$prefix}-{$date}-%")
            ->orderBy('invoice_number', 'desc')
            ->first();

        if ($lastInvoice) {
            $lastNumber = (int) substr($lastInvoice->invoice_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return sprintf('%s-%s-%04d', $prefix, $date, $newNumber);
    }

    /**
     * Calculate totals.
     */
    public function calculateTotals(): void
    {
        $subtotal = $this->items->sum('line_total');
        $taxableAmount = $this->items->where('is_taxable', true)->sum('line_total');

        // Apply discount
        $discountAmount = $this->calculateDiscountAmount($subtotal);
        $afterDiscount = $subtotal - $discountAmount;

        // Calculate SST
        $sstEnabled = BillingSetting::getValue('sst_enabled', 'true') === 'true';
        $sstRate = (float) BillingSetting::getValue('sst_rate', '6.00');
        $sstAmount = $sstEnabled ? round($taxableAmount * ($sstRate / 100), 2) : 0;

        // Calculate total before rounding
        $totalBeforeRounding = $afterDiscount + $sstAmount;

        // Apply rounding
        $roundingEnabled = BillingSetting::getValue('rounding_enabled', 'true') === 'true';
        $roundingAdjustment = $roundingEnabled ? $this->calculateRounding($totalBeforeRounding) : 0;

        $totalAmount = $totalBeforeRounding + $roundingAdjustment;

        $this->update([
            'subtotal' => $subtotal,
            'discount_amount' => $discountAmount,
            'taxable_amount' => $taxableAmount,
            'sst_rate' => $sstRate,
            'sst_amount' => $sstAmount,
            'rounding_adjustment' => $roundingAdjustment,
            'total_amount' => $totalAmount,
            'balance_owed' => $totalAmount - $this->paid_amount,
        ]);
    }

    /**
     * Calculate discount amount.
     */
    protected function calculateDiscountAmount(float $subtotal): float
    {
        if ($this->discount_type === 'none' || $this->discount_value <= 0) {
            return 0;
        }

        if ($this->discount_type === 'percentage' || $this->discount_type === 'senior' || $this->discount_type === 'staff') {
            return round($subtotal * ($this->discount_value / 100), 2);
        }

        // Fixed amount
        return min($this->discount_value, $subtotal);
    }

    /**
     * Calculate rounding to nearest 5 sen.
     */
    protected function calculateRounding(float $amount): float
    {
        $cents = round(($amount - floor($amount)) * 100);
        $lastDigit = $cents % 10;

        if ($lastDigit === 0 || $lastDigit === 5) {
            return 0;
        }

        if ($lastDigit < 3) {
            return -$lastDigit / 100;
        }
        if ($lastDigit < 5) {
            return (5 - $lastDigit) / 100;
        }
        if ($lastDigit < 8) {
            return (5 - $lastDigit) / 100;
        }

        return (10 - $lastDigit) / 100;
    }

    /**
     * Record payment.
     */
    public function recordPayment(float $amount, ?string $method = null, ?string $reference = null): void
    {
        $newPaidAmount = $this->paid_amount + $amount;
        $newBalance = $this->total_amount - $newPaidAmount;

        $status = 'partially_paid';
        if ($newBalance <= 0) {
            $status = 'fully_paid';
            $newBalance = 0;
        }

        $this->update([
            'paid_amount' => $newPaidAmount,
            'balance_owed' => $newBalance,
            'status' => $status,
        ]);
    }

    /**
     * Apply discount.
     */
    public function applyDiscount(string $type, float $value): void
    {
        $this->discount_type = $type;
        $this->discount_value = $value;
        $this->save();
        $this->calculateTotals();
    }

    /**
     * Get grand_total attribute (alias for total_amount).
     */
    public function getGrandTotalAttribute(): float
    {
        return (float) $this->total_amount;
    }

    /**
     * Get balance attribute (alias for balance_owed).
     */
    public function getBalanceAttribute(): float
    {
        return (float) $this->balance_owed;
    }

    /**
     * Get tax_amount attribute (alias for sst_amount).
     */
    public function getTaxAmountAttribute(): float
    {
        return (float) $this->sst_amount;
    }

    /**
     * Get tax_rate attribute (alias for sst_rate).
     */
    public function getTaxRateAttribute(): float
    {
        return (float) $this->sst_rate;
    }

    /**
     * Get rounding_amount attribute (alias for rounding_adjustment).
     */
    public function getRoundingAmountAttribute(): float
    {
        return (float) $this->rounding_adjustment;
    }

    /**
     * Get is_overdue attribute.
     */
    public function getIsOverdueAttribute(): bool
    {
        return $this->isOverdue();
    }

    /**
     * Get status badge class.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return 'bg-'.$this->status_color;
    }
}
