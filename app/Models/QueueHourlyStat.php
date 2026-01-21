<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QueueHourlyStat extends Model
{
    use HasFactory;

    protected $fillable = [
        'queue_type_id',
        'stat_date',
        'stat_hour',
        'tickets_issued',
        'tickets_served',
        'avg_wait_time',
        'active_counters',
    ];

    protected $casts = [
        'stat_date' => 'date',
        'stat_hour' => 'integer',
        'tickets_issued' => 'integer',
        'tickets_served' => 'integer',
        'avg_wait_time' => 'integer',
        'active_counters' => 'integer',
    ];

    /**
     * Get the queue type.
     */
    public function queueType(): BelongsTo
    {
        return $this->belongsTo(QueueType::class);
    }

    /**
     * Scope for a specific date.
     */
    public function scopeForDate($query, $date)
    {
        return $query->whereDate('stat_date', $date);
    }

    /**
     * Scope for today.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('stat_date', today());
    }

    /**
     * Scope for a specific hour.
     */
    public function scopeForHour($query, int $hour)
    {
        return $query->where('stat_hour', $hour);
    }

    /**
     * Scope for date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('stat_date', [$startDate, $endDate]);
    }

    /**
     * Scope for a specific queue type.
     */
    public function scopeForQueueType($query, int $queueTypeId)
    {
        return $query->where('queue_type_id', $queueTypeId);
    }

    /**
     * Get hour range display.
     */
    public function getHourRangeAttribute(): string
    {
        return sprintf('%02d:00 - %02d:00', $this->stat_hour, $this->stat_hour + 1);
    }

    /**
     * Get throughput (tickets per counter per hour).
     */
    public function getThroughputAttribute(): float
    {
        if ($this->active_counters === 0) {
            return 0;
        }

        return round($this->tickets_served / $this->active_counters, 1);
    }

    /**
     * Format wait time.
     */
    public function getFormattedWaitTimeAttribute(): string
    {
        return ($this->avg_wait_time ?? 0).' min';
    }

    /**
     * Update stats for current hour.
     */
    public static function updateCurrentHourStats(int $queueTypeId): self
    {
        $date = today();
        $hour = now()->hour;

        $tickets = QueueTicket::where('queue_type_id', $queueTypeId)
            ->whereDate('queue_date', $date)
            ->whereRaw('HOUR(issued_at) = ?', [$hour])
            ->get();

        $servedTickets = QueueTicket::where('queue_type_id', $queueTypeId)
            ->whereDate('queue_date', $date)
            ->whereRaw('HOUR(completed_at) = ?', [$hour])
            ->where('status', 'completed')
            ->get();

        $waitTimes = $servedTickets->pluck('actual_wait_time')->filter();

        $activeCounters = QueueStaffAssignment::whereHas('counter', function ($q) use ($queueTypeId) {
            $q->where('queue_type_id', $queueTypeId);
        })
            ->where('assignment_date', $date)
            ->where('is_active', true)
            ->count();

        return self::updateOrCreate(
            [
                'queue_type_id' => $queueTypeId,
                'stat_date' => $date,
                'stat_hour' => $hour,
            ],
            [
                'tickets_issued' => $tickets->count(),
                'tickets_served' => $servedTickets->count(),
                'avg_wait_time' => $waitTimes->avg() ? (int) round($waitTimes->avg()) : null,
                'active_counters' => $activeCounters,
            ]
        );
    }
}
