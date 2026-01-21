<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OutstandingReminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'reminder_type',
        'sent_at',
        'sent_via',
        'sent_by',
        'response',
        'response_at',
        'next_reminder_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'response_at' => 'datetime',
        'next_reminder_at' => 'datetime',
    ];

    const TYPE_FIRST = 'first';

    const TYPE_SECOND = 'second';

    const TYPE_FINAL = 'final';

    const TYPE_LEGAL = 'legal';

    const VIA_SMS = 'sms';

    const VIA_EMAIL = 'email';

    const VIA_WHATSAPP = 'whatsapp';

    const VIA_CALL = 'call';

    const VIA_LETTER = 'letter';

    /**
     * Get the invoice.
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Get the staff who sent.
     */
    public function sentBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    /**
     * Scope for a specific invoice.
     */
    public function scopeForInvoice($query, int $invoiceId)
    {
        return $query->where('invoice_id', $invoiceId);
    }

    /**
     * Scope for pending reminders.
     */
    public function scopePending($query)
    {
        return $query->whereNull('sent_at');
    }

    /**
     * Scope for sent reminders.
     */
    public function scopeSent($query)
    {
        return $query->whereNotNull('sent_at');
    }

    /**
     * Scope for overdue reminders.
     */
    public function scopeOverdue($query)
    {
        return $query->whereNotNull('next_reminder_at')
            ->where('next_reminder_at', '<', now());
    }

    /**
     * Mark as sent.
     */
    public function markAsSent(string $via, int $sentBy): bool
    {
        $this->sent_at = now();
        $this->sent_via = $via;
        $this->sent_by = $sentBy;

        return $this->save();
    }

    /**
     * Record response.
     */
    public function recordResponse(string $response): bool
    {
        $this->response = $response;
        $this->response_at = now();

        return $this->save();
    }

    /**
     * Schedule next reminder.
     */
    public function scheduleNext(int $daysFromNow = 7): bool
    {
        $this->next_reminder_at = now()->addDays($daysFromNow);

        return $this->save();
    }

    /**
     * Get reminder type label.
     */
    public function getReminderTypeLabelAttribute(): string
    {
        return match ($this->reminder_type) {
            self::TYPE_FIRST => 'Peringatan Pertama',
            self::TYPE_SECOND => 'Peringatan Kedua',
            self::TYPE_FINAL => 'Peringatan Terakhir',
            self::TYPE_LEGAL => 'Notis Guaman',
            default => $this->reminder_type,
        };
    }

    /**
     * Get sent via label.
     */
    public function getSentViaLabelAttribute(): string
    {
        return match ($this->sent_via) {
            self::VIA_SMS => 'SMS',
            self::VIA_EMAIL => 'E-mel',
            self::VIA_WHATSAPP => 'WhatsApp',
            self::VIA_CALL => 'Panggilan',
            self::VIA_LETTER => 'Surat',
            default => $this->sent_via ?? '-',
        };
    }

    /**
     * Get days since sent.
     */
    public function getDaysSinceSentAttribute(): ?int
    {
        if (! $this->sent_at) {
            return null;
        }

        return $this->sent_at->diffInDays(now());
    }

    /**
     * Check if has response.
     */
    public function hasResponse(): bool
    {
        return ! empty($this->response);
    }

    /**
     * Get next reminder type based on current.
     */
    public static function getNextType(string $currentType): ?string
    {
        return match ($currentType) {
            self::TYPE_FIRST => self::TYPE_SECOND,
            self::TYPE_SECOND => self::TYPE_FINAL,
            self::TYPE_FINAL => self::TYPE_LEGAL,
            self::TYPE_LEGAL => null,
            default => self::TYPE_FIRST,
        };
    }

    /**
     * Create first reminder for invoice.
     */
    public static function createFirstReminder(int $invoiceId): self
    {
        return self::create([
            'invoice_id' => $invoiceId,
            'reminder_type' => self::TYPE_FIRST,
        ]);
    }
}
