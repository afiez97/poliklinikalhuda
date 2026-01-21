<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QueueType extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'name_en',
        'name_zh',
        'avg_service_time',
        'max_queue_size',
        'priority_ratio',
        'auto_transfer_to',
        'operating_start',
        'operating_end',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'avg_service_time' => 'integer',
        'max_queue_size' => 'integer',
        'priority_ratio' => 'integer',
        'operating_start' => 'datetime:H:i',
        'operating_end' => 'datetime:H:i',
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];

    /**
     * Get counters for this queue type.
     */
    public function counters(): HasMany
    {
        return $this->hasMany(QueueCounter::class);
    }

    /**
     * Get tickets for this queue type.
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(QueueTicket::class);
    }

    /**
     * Get the queue type to auto-transfer to.
     */
    public function autoTransferQueue(): BelongsTo
    {
        return $this->belongsTo(QueueType::class, 'auto_transfer_to');
    }

    /**
     * Get daily stats.
     */
    public function dailyStats(): HasMany
    {
        return $this->hasMany(QueueDailyStat::class);
    }

    /**
     * Get hourly stats.
     */
    public function hourlyStats(): HasMany
    {
        return $this->hasMany(QueueHourlyStat::class);
    }

    /**
     * Scope for active queue types.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope ordered by display order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }

    /**
     * Get localized name based on locale.
     */
    public function getLocalizedNameAttribute(): string
    {
        $locale = app()->getLocale();

        return match ($locale) {
            'en' => $this->name_en ?? $this->name,
            'zh' => $this->name_zh ?? $this->name,
            default => $this->name,
        };
    }

    /**
     * Check if queue is within operating hours.
     */
    public function isWithinOperatingHours(): bool
    {
        if (! $this->operating_start || ! $this->operating_end) {
            return true;
        }

        $now = now()->format('H:i:s');

        return $now >= $this->operating_start->format('H:i:s')
            && $now <= $this->operating_end->format('H:i:s');
    }

    /**
     * Get today's ticket count.
     */
    public function getTodayTicketCountAttribute(): int
    {
        return $this->tickets()
            ->whereDate('queue_date', today())
            ->count();
    }

    /**
     * Get today's waiting count.
     */
    public function getTodayWaitingCountAttribute(): int
    {
        return $this->tickets()
            ->whereDate('queue_date', today())
            ->where('status', 'waiting')
            ->count();
    }

    /**
     * Check if queue has reached max size.
     */
    public function hasReachedMaxSize(): bool
    {
        if (! $this->max_queue_size) {
            return false;
        }

        return $this->today_ticket_count >= $this->max_queue_size;
    }

    /**
     * Get next sequence number for today.
     */
    public function getNextSequence(): int
    {
        $lastSequence = $this->tickets()
            ->whereDate('queue_date', today())
            ->max('sequence');

        return ($lastSequence ?? 0) + 1;
    }

    /**
     * Generate ticket number.
     */
    public function generateTicketNumber(int $sequence): string
    {
        return sprintf('%s-%03d', $this->code, $sequence);
    }
}
