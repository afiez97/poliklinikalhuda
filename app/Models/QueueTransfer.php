<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QueueTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_ticket_id',
        'to_ticket_id',
        'from_queue_type_id',
        'to_queue_type_id',
        'transfer_type',
        'reason',
        'transferred_by',
        'transferred_at',
    ];

    protected $casts = [
        'transferred_at' => 'datetime',
    ];

    /**
     * Get the source ticket.
     */
    public function fromTicket(): BelongsTo
    {
        return $this->belongsTo(QueueTicket::class, 'from_ticket_id');
    }

    /**
     * Get the destination ticket.
     */
    public function toTicket(): BelongsTo
    {
        return $this->belongsTo(QueueTicket::class, 'to_ticket_id');
    }

    /**
     * Get the source queue type.
     */
    public function fromQueueType(): BelongsTo
    {
        return $this->belongsTo(QueueType::class, 'from_queue_type_id');
    }

    /**
     * Get the destination queue type.
     */
    public function toQueueType(): BelongsTo
    {
        return $this->belongsTo(QueueType::class, 'to_queue_type_id');
    }

    /**
     * Get the user who initiated the transfer.
     */
    public function transferredByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'transferred_by');
    }

    /**
     * Scope for auto transfers.
     */
    public function scopeAuto($query)
    {
        return $query->where('transfer_type', 'auto');
    }

    /**
     * Scope for manual transfers.
     */
    public function scopeManual($query)
    {
        return $query->where('transfer_type', 'manual');
    }

    /**
     * Scope for today's transfers.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('transferred_at', today());
    }

    /**
     * Check if this is an auto transfer.
     */
    public function isAutoTransfer(): bool
    {
        return $this->transfer_type === 'auto';
    }
}
