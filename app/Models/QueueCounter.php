<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QueueCounter extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Counter types.
     */
    public const TYPES = [
        'registration' => 'Pendaftaran',
        'consultation' => 'Konsultasi',
        'pharmacy' => 'Farmasi',
        'payment' => 'Pembayaran',
        'lab' => 'Makmal',
        'other' => 'Lain-lain',
    ];

    /**
     * Get type label.
     */
    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    /**
     * Get entries.
     */
    public function entries(): HasMany
    {
        return $this->hasMany(QueueEntry::class);
    }

    /**
     * Get today's entries.
     */
    public function todayEntries(): HasMany
    {
        return $this->entries()->today();
    }

    /**
     * Get waiting count.
     */
    public function getWaitingCountAttribute(): int
    {
        return $this->entries()->today()->waiting()->count();
    }

    /**
     * Get next queue number.
     */
    public function getNextQueueNumber(): string
    {
        $lastEntry = $this->entries()
            ->whereDate('created_at', today())
            ->orderBy('queue_number', 'desc')
            ->first();

        if ($lastEntry) {
            // Extract number from queue_number (e.g., "A001" -> 1)
            $lastNumber = (int) preg_replace('/[^0-9]/', '', $lastEntry->queue_number);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return sprintf('%s%03d', $this->code, $newNumber);
    }

    /**
     * Get next in queue.
     */
    public function getNextInQueue(): ?QueueEntry
    {
        return $this->entries()
            ->today()
            ->waiting()
            ->orderByPriority()
            ->first();
    }

    /**
     * Scope for active.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope ordered.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderBy('name');
    }
}
