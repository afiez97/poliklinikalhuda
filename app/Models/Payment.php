<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Payment extends Model
{
    use HasFactory;

    // Status constants
    public const STATUS_PENDING = 'pending';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_FAILED = 'failed';

    public const STATUS_VOIDED = 'voided';

    public const STATUS_REFUNDED = 'refunded';

    protected $fillable = [
        'payment_number',
        'invoice_id',
        'patient_id',
        'amount',
        'payment_method',
        'reference_number',
        'card_type',
        'card_last4',
        'ewallet_provider',
        'panel_id',
        'change_amount',
        'payment_date',
        'status',
        'received_by',
        'voided_by',
        'voided_at',
        'void_reason',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'change_amount' => 'decimal:2',
        'payment_date' => 'datetime',
        'voided_at' => 'datetime',
    ];

    /**
     * Payment method labels.
     */
    public const METHOD_LABELS = [
        'cash' => 'Tunai',
        'card' => 'Kad Kredit/Debit',
        'qr_pay' => 'QR Pay (DuitNow)',
        'ewallet_tng' => 'Touch \'n Go eWallet',
        'ewallet_grabpay' => 'GrabPay',
        'ewallet_boost' => 'Boost',
        'bank_transfer' => 'Pindahan Bank',
        'panel' => 'Panel/Insurans',
        'deposit' => 'Deposit',
    ];

    /**
     * Get the invoice.
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Get the patient.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the panel.
     */
    public function panel(): BelongsTo
    {
        return $this->belongsTo(Panel::class);
    }

    /**
     * Get the receiver.
     */
    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    /**
     * Get the voider.
     */
    public function voidedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'voided_by');
    }

    /**
     * Get the receipt.
     */
    public function receipt(): HasOne
    {
        return $this->hasOne(Receipt::class);
    }

    /**
     * Get the refund.
     */
    public function refund(): HasOne
    {
        return $this->hasOne(Refund::class);
    }

    /**
     * Scope for today's payments.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('payment_date', today());
    }

    /**
     * Scope for completed payments.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('payment_date', [$startDate, $endDate]);
    }

    /**
     * Scope by payment method.
     */
    public function scopeByMethod($query, string $method)
    {
        return $query->where('payment_method', $method);
    }

    /**
     * Scope by user (cashier).
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('received_by', $userId);
    }

    /**
     * Get payment method label.
     */
    public function getPaymentMethodLabelAttribute(): string
    {
        return self::METHOD_LABELS[$this->payment_method] ?? $this->payment_method;
    }

    /**
     * Get status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu',
            'completed' => 'Selesai',
            'failed' => 'Gagal',
            'voided' => 'Dibatalkan',
            'refunded' => 'Dipulangkan',
            default => $this->status,
        };
    }

    /**
     * Get status color.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'warning',
            'completed' => 'success',
            'failed' => 'danger',
            'voided' => 'dark',
            'refunded' => 'purple',
            default => 'secondary',
        };
    }

    /**
     * Check if payment can be voided.
     */
    public function canBeVoided(): bool
    {
        return $this->status === 'completed'
            && $this->payment_date->isToday();
    }

    /**
     * Generate payment number.
     */
    public static function generatePaymentNumber(): string
    {
        $prefix = 'PAY';
        $date = now()->format('Ymd');

        $lastPayment = self::where('payment_number', 'like', "{$prefix}-{$date}-%")
            ->orderBy('payment_number', 'desc')
            ->first();

        if ($lastPayment) {
            $lastNumber = (int) substr($lastPayment->payment_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return sprintf('%s-%s-%04d', $prefix, $date, $newNumber);
    }

    /**
     * Get payment method icon.
     */
    public function getMethodIconAttribute(): string
    {
        return match ($this->payment_method) {
            'cash' => 'mdi-cash',
            'card' => 'mdi-credit-card',
            'qr_pay' => 'mdi-qrcode',
            'ewallet_tng', 'ewallet_grabpay', 'ewallet_boost' => 'mdi-wallet',
            'bank_transfer' => 'mdi-bank-transfer',
            'panel' => 'mdi-shield-account',
            'deposit' => 'mdi-piggy-bank',
            default => 'mdi-cash',
        };
    }

    /**
     * Get static method label.
     */
    public static function getMethodLabel(string $method): string
    {
        return self::METHOD_LABELS[$method] ?? ucfirst($method);
    }
}
