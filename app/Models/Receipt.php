<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Receipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'receipt_number',
        'payment_id',
        'invoice_id',
        'patient_id',
        'amount',
        'receipt_date',
        'is_printed',
        'is_emailed',
        'email_sent_to',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'receipt_date' => 'datetime',
        'is_printed' => 'boolean',
        'is_emailed' => 'boolean',
    ];

    /**
     * Get the payment.
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

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
     * Get the creator.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Generate receipt number.
     */
    public static function generateReceiptNumber(): string
    {
        $prefix = BillingSetting::getValue('receipt_prefix', 'RCP');
        $date = now()->format('Ymd');

        $lastReceipt = self::where('receipt_number', 'like', "{$prefix}-{$date}-%")
            ->orderBy('receipt_number', 'desc')
            ->first();

        if ($lastReceipt) {
            $lastNumber = (int) substr($lastReceipt->receipt_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return sprintf('%s-%s-%04d', $prefix, $date, $newNumber);
    }

    /**
     * Mark as printed.
     */
    public function markAsPrinted(): void
    {
        $this->update(['is_printed' => true]);
    }

    /**
     * Mark as emailed.
     */
    public function markAsEmailed(string $email): void
    {
        $this->update([
            'is_emailed' => true,
            'email_sent_to' => $email,
        ]);
    }
}
