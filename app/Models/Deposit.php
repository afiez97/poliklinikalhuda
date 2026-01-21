<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deposit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'deposit_number',
        'patient_id',
        'amount',
        'balance',
        'payment_method',
        'reference_number',
        'notes',
        'received_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance' => 'decimal:2',
    ];

    /**
     * Get the patient.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the staff who received the deposit.
     */
    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    /**
     * Scope for deposits with balance.
     */
    public function scopeWithBalance($query)
    {
        return $query->where('balance', '>', 0);
    }

    /**
     * Scope for a specific patient.
     */
    public function scopeForPatient($query, int $patientId)
    {
        return $query->where('patient_id', $patientId);
    }

    /**
     * Check if deposit has balance.
     */
    public function hasBalance(): bool
    {
        return $this->balance > 0;
    }

    /**
     * Use deposit amount.
     */
    public function useAmount(float $amount): float
    {
        $amountToUse = min($amount, $this->balance);
        $this->balance -= $amountToUse;
        $this->save();

        return $amountToUse;
    }

    /**
     * Get used amount.
     */
    public function getUsedAmountAttribute(): float
    {
        return $this->amount - $this->balance;
    }

    /**
     * Get usage percentage.
     */
    public function getUsagePercentageAttribute(): float
    {
        if ($this->amount == 0) {
            return 0;
        }

        return round((($this->amount - $this->balance) / $this->amount) * 100, 1);
    }

    /**
     * Generate deposit number.
     */
    public static function generateDepositNumber(): string
    {
        $prefix = 'DEP';
        $date = now()->format('Ymd');
        $lastDeposit = self::whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastDeposit
            ? (int) substr($lastDeposit->deposit_number, -4) + 1
            : 1;

        return sprintf('%s%s%04d', $prefix, $date, $sequence);
    }

    /**
     * Boot method.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($deposit) {
            if (empty($deposit->deposit_number)) {
                $deposit->deposit_number = self::generateDepositNumber();
            }
            if (is_null($deposit->balance)) {
                $deposit->balance = $deposit->amount;
            }
        });
    }
}
