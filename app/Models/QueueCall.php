<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QueueCall extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'counter_id',
        'called_by',
        'call_type',
        'called_at',
        'responded',
    ];

    protected $casts = [
        'called_at' => 'datetime',
        'responded' => 'boolean',
    ];

    /**
     * Get the ticket.
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(QueueTicket::class, 'ticket_id');
    }

    /**
     * Get the counter.
     */
    public function counter(): BelongsTo
    {
        return $this->belongsTo(QueueCounter::class, 'counter_id');
    }

    /**
     * Get the staff who made the call.
     */
    public function calledByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'called_by');
    }

    /**
     * Scope for today's calls.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('called_at', today());
    }

    /**
     * Scope for responded calls.
     */
    public function scopeResponded($query)
    {
        return $query->where('responded', true);
    }

    /**
     * Scope for initial calls.
     */
    public function scopeInitial($query)
    {
        return $query->where('call_type', 'initial');
    }

    /**
     * Scope for recall calls.
     */
    public function scopeRecall($query)
    {
        return $query->where('call_type', 'recall');
    }

    /**
     * Generate announcement text.
     */
    public function generateAnnouncementText(): string
    {
        $ticketNumber = $this->ticket->ticket_number;
        $counterName = $this->counter->localized_name;

        return "Nombor {$ticketNumber}, sila ke {$counterName}";
    }

    /**
     * Get announcement text in English.
     */
    public function generateAnnouncementTextEn(): string
    {
        $ticketNumber = $this->ticket->ticket_number;
        $counterName = $this->counter->name_en ?? $this->counter->name;

        return "Number {$ticketNumber}, please proceed to {$counterName}";
    }

    /**
     * Get announcement text in Chinese.
     */
    public function generateAnnouncementTextZh(): string
    {
        $ticketNumber = $this->ticket->ticket_number;
        $counterName = $this->counter->name_zh ?? $this->counter->name;

        return "号码 {$ticketNumber}，请到 {$counterName}";
    }
}
