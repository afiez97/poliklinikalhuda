<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class QueueKiosk extends Model
{
    use HasFactory;

    protected $fillable = [
        'kiosk_id',
        'name',
        'location',
        'status',
        'is_active',
        'available_queue_types',
        'last_heartbeat',
        'notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'available_queue_types' => 'array',
        'last_heartbeat' => 'datetime',
    ];

    /**
     * Get tickets issued from this kiosk.
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(QueueTicket::class, 'source', 'kiosk_id')
            ->where('source', 'kiosk');
    }

    /**
     * Get available queue types.
     */
    public function availableQueueTypes()
    {
        return QueueType::whereIn('id', $this->available_queue_types ?? [])->get();
    }

    /**
     * Scope for active kiosks.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for online kiosks.
     */
    public function scopeOnline($query)
    {
        return $query->where('status', 'online')
            ->where('last_heartbeat', '>', now()->subMinutes(5));
    }

    /**
     * Scope for offline kiosks.
     */
    public function scopeOffline($query)
    {
        return $query->where(function ($q) {
            $q->where('status', 'offline')
                ->orWhere('last_heartbeat', '<', now()->subMinutes(5))
                ->orWhereNull('last_heartbeat');
        });
    }

    /**
     * Check if kiosk is online.
     */
    public function isOnline(): bool
    {
        if ($this->status !== 'online') {
            return false;
        }

        if (! $this->last_heartbeat) {
            return false;
        }

        return $this->last_heartbeat->gt(now()->subMinutes(5));
    }

    /**
     * Update heartbeat.
     */
    public function updateHeartbeat(): void
    {
        $this->update([
            'last_heartbeat' => now(),
            'status' => 'online',
        ]);
    }

    /**
     * Go offline.
     */
    public function goOffline(): void
    {
        $this->update(['status' => 'offline']);
    }

    /**
     * Set maintenance mode.
     */
    public function setMaintenance(): void
    {
        $this->update(['status' => 'maintenance']);
    }

    /**
     * Get today's ticket count.
     */
    public function getTodayTicketCountAttribute(): int
    {
        return QueueTicket::where('source', 'kiosk')
            ->whereDate('queue_date', today())
            ->count();
    }

    /**
     * Get status label.
     */
    public function getStatusLabelAttribute(): string
    {
        if (! $this->isOnline() && $this->status === 'online') {
            return 'Offline (Tiada Heartbeat)';
        }

        return match ($this->status) {
            'online' => 'Online',
            'offline' => 'Offline',
            'maintenance' => 'Penyelenggaraan',
            default => $this->status,
        };
    }

    /**
     * Get status color.
     */
    public function getStatusColorAttribute(): string
    {
        if (! $this->isOnline()) {
            return 'red';
        }

        return match ($this->status) {
            'online' => 'green',
            'offline' => 'red',
            'maintenance' => 'yellow',
            default => 'gray',
        };
    }

    /**
     * Generate unique kiosk ID.
     */
    public static function generateKioskId(): string
    {
        do {
            $id = 'KSK-'.strtoupper(Str::random(6));
        } while (self::where('kiosk_id', $id)->exists());

        return $id;
    }
}
