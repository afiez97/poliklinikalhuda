<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreditNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'credit_note_number',
        'refund_id',
        'invoice_id',
        'patient_id',
        'amount',
        'reason',
        'issue_date',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'issue_date' => 'datetime',
    ];

    /**
     * Get the refund.
     */
    public function refund(): BelongsTo
    {
        return $this->belongsTo(Refund::class);
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
     * Generate credit note number.
     */
    public static function generateCreditNoteNumber(): string
    {
        $prefix = 'CN';
        $date = now()->format('Ymd');

        $last = self::where('credit_note_number', 'like', "{$prefix}-{$date}-%")
            ->orderBy('credit_note_number', 'desc')
            ->first();

        if ($last) {
            $lastNumber = (int) substr($last->credit_note_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return sprintf('%s-%s-%04d', $prefix, $date, $newNumber);
    }
}
