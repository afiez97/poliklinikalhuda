<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QueueNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'notification_type',
        'channel',
        'recipient',
        'message',
        'status',
        'sent_at',
        'error_message',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    /**
     * Notification types.
     */
    public const TYPES = [
        'issued' => 'Tiket Dikeluarkan',
        'approaching' => 'Giliran Hampir',
        'called' => 'Dipanggil',
        'no_show_warning' => 'Amaran Tidak Hadir',
        'transferred' => 'Dipindahkan',
    ];

    /**
     * Channels.
     */
    public const CHANNELS = [
        'sms' => 'SMS',
        'whatsapp' => 'WhatsApp',
    ];

    /**
     * Get the ticket.
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(QueueTicket::class, 'ticket_id');
    }

    /**
     * Scope for pending notifications.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for sent notifications.
     */
    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    /**
     * Scope for failed notifications.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope by channel.
     */
    public function scopeByChannel($query, string $channel)
    {
        return $query->where('channel', $channel);
    }

    /**
     * Scope by type.
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('notification_type', $type);
    }

    /**
     * Mark as sent.
     */
    public function markAsSent(): void
    {
        $this->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);
    }

    /**
     * Mark as failed.
     */
    public function markAsFailed(string $errorMessage): void
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $errorMessage,
        ]);
    }

    /**
     * Get type label.
     */
    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->notification_type] ?? $this->notification_type;
    }

    /**
     * Get channel label.
     */
    public function getChannelLabelAttribute(): string
    {
        return self::CHANNELS[$this->channel] ?? $this->channel;
    }

    /**
     * Get status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu',
            'sent' => 'Dihantar',
            'failed' => 'Gagal',
            default => $this->status,
        };
    }
}
