<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QueueDailyStat extends Model
{
    use HasFactory;

    protected $fillable = [
        'queue_type_id',
        'stat_date',
        'total_tickets',
        'served_tickets',
        'no_show_tickets',
        'cancelled_tickets',
        'avg_wait_time',
        'max_wait_time',
        'min_wait_time',
        'avg_service_time',
        'peak_hour_start',
        'peak_hour_end',
        'peak_hour_tickets',
    ];

    protected $casts = [
        'stat_date' => 'date',
        'total_tickets' => 'integer',
        'served_tickets' => 'integer',
        'no_show_tickets' => 'integer',
        'cancelled_tickets' => 'integer',
        'avg_wait_time' => 'integer',
        'max_wait_time' => 'integer',
        'min_wait_time' => 'integer',
        'avg_service_time' => 'integer',
        'peak_hour_start' => 'datetime:H:i',
        'peak_hour_end' => 'datetime:H:i',
        'peak_hour_tickets' => 'integer',
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
     * Get completion rate.
     */
    public function getCompletionRateAttribute(): float
    {
        if ($this->total_tickets === 0) {
            return 0;
        }

        return round(($this->served_tickets / $this->total_tickets) * 100, 1);
    }

    /**
     * Get no-show rate.
     */
    public function getNoShowRateAttribute(): float
    {
        if ($this->total_tickets === 0) {
            return 0;
        }

        return round(($this->no_show_tickets / $this->total_tickets) * 100, 1);
    }

    /**
     * Get cancellation rate.
     */
    public function getCancellationRateAttribute(): float
    {
        if ($this->total_tickets === 0) {
            return 0;
        }

        return round(($this->cancelled_tickets / $this->total_tickets) * 100, 1);
    }

    /**
     * Format avg wait time.
     */
    public function getFormattedAvgWaitTimeAttribute(): string
    {
        $minutes = $this->avg_wait_time ?? 0;

        if ($minutes < 60) {
            return "{$minutes} min";
        }

        $hours = floor($minutes / 60);
        $mins = $minutes % 60;

        return "{$hours}j {$mins}m";
    }

    /**
     * Format peak hour range.
     */
    public function getPeakHourRangeAttribute(): string
    {
        if (! $this->peak_hour_start || ! $this->peak_hour_end) {
            return '-';
        }

        return $this->peak_hour_start->format('H:i').' - '.$this->peak_hour_end->format('H:i');
    }

    /**
     * Create or update stats for a date.
     */
    public static function updateStatsForDate(int $queueTypeId, $date = null): self
    {
        $date = $date ?? today();

        $tickets = QueueTicket::where('queue_type_id', $queueTypeId)
            ->whereDate('queue_date', $date)
            ->get();

        $servedTickets = $tickets->where('status', 'completed');
        $noShowTickets = $tickets->where('status', 'no_show');
        $cancelledTickets = $tickets->where('status', 'cancelled');

        $waitTimes = $servedTickets->pluck('actual_wait_time')->filter();
        $serviceTimes = $servedTickets->pluck('service_time')->filter();

        return self::updateOrCreate(
            ['queue_type_id' => $queueTypeId, 'stat_date' => $date],
            [
                'total_tickets' => $tickets->count(),
                'served_tickets' => $servedTickets->count(),
                'no_show_tickets' => $noShowTickets->count(),
                'cancelled_tickets' => $cancelledTickets->count(),
                'avg_wait_time' => $waitTimes->avg() ? (int) round($waitTimes->avg()) : null,
                'max_wait_time' => $waitTimes->max(),
                'min_wait_time' => $waitTimes->min(),
                'avg_service_time' => $serviceTimes->avg() ? (int) round($serviceTimes->avg()) : null,
            ]
        );
    }
}
