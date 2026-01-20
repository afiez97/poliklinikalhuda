<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class QueueEntry extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'queue_counter_id',
        'patient_visit_id',
        'queue_number',
        'priority',
        'priority_weight',
        'status',
        'served_by',
        'counter_number',
        'called_at',
        'served_at',
        'completed_at',
        'wait_time_minutes',
        'serve_time_minutes',
        'notes',
    ];

    protected $casts = [
        'called_at' => 'datetime',
        'served_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Priorities with weight.
     */
    public const PRIORITIES = [
        'emergency' => ['label' => 'Kecemasan', 'weight' => 100],
        'urgent' => ['label' => 'Segera', 'weight' => 80],
        'pregnant' => ['label' => 'Wanita Mengandung', 'weight' => 60],
        'disabled' => ['label' => 'OKU', 'weight' => 50],
        'elderly' => ['label' => 'Warga Emas', 'weight' => 40],
        'normal' => ['label' => 'Biasa', 'weight' => 0],
    ];

    /**
     * Statuses.
     */
    public const STATUSES = [
        'waiting' => 'Menunggu',
        'calling' => 'Sedang Dipanggil',
        'serving' => 'Sedang Dilayan',
        'completed' => 'Selesai',
        'skipped' => 'Dilangkau',
        'transferred' => 'Dipindahkan',
        'cancelled' => 'Dibatalkan',
    ];

    /**
     * Boot method.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($entry) {
            if (empty($entry->priority_weight)) {
                $entry->priority_weight = self::PRIORITIES[$entry->priority]['weight'] ?? 0;
            }
        });
    }

    /**
     * Get priority label.
     */
    public function getPriorityLabelAttribute(): string
    {
        return self::PRIORITIES[$this->priority]['label'] ?? $this->priority;
    }

    /**
     * Get status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    /**
     * Get formatted wait time.
     */
    public function getFormattedWaitTimeAttribute(): string
    {
        if (! $this->wait_time_minutes) {
            if ($this->status === 'waiting') {
                $minutes = $this->created_at->diffInMinutes(now());

                return $minutes.' min';
            }

            return '-';
        }

        if ($this->wait_time_minutes >= 60) {
            $hours = floor($this->wait_time_minutes / 60);
            $mins = $this->wait_time_minutes % 60;

            return "{$hours}j {$mins}m";
        }

        return $this->wait_time_minutes.' min';
    }

    /**
     * Get the queue counter.
     */
    public function queueCounter(): BelongsTo
    {
        return $this->belongsTo(QueueCounter::class);
    }

    /**
     * Get the patient visit.
     */
    public function patientVisit(): BelongsTo
    {
        return $this->belongsTo(PatientVisit::class);
    }

    /**
     * Get the server (user).
     */
    public function server(): BelongsTo
    {
        return $this->belongsTo(User::class, 'served_by');
    }

    /**
     * Call this queue.
     */
    public function call(string $counterNumber): void
    {
        $this->status = 'calling';
        $this->counter_number = $counterNumber;
        $this->called_at = now();

        if ($this->status === 'waiting') {
            $this->wait_time_minutes = $this->created_at->diffInMinutes(now());
        }

        $this->save();
    }

    /**
     * Start serving.
     */
    public function startServing(int $userId): void
    {
        $this->status = 'serving';
        $this->served_by = $userId;
        $this->served_at = now();
        $this->save();
    }

    /**
     * Complete serving.
     */
    public function complete(): void
    {
        $this->status = 'completed';
        $this->completed_at = now();

        if ($this->served_at) {
            $this->serve_time_minutes = $this->served_at->diffInMinutes(now());
        }

        $this->save();
    }

    /**
     * Skip this queue.
     */
    public function skip(?string $reason = null): void
    {
        $this->status = 'skipped';
        $this->notes = $reason;
        $this->save();
    }

    /**
     * Cancel this queue.
     */
    public function cancel(): void
    {
        $this->status = 'cancelled';
        $this->save();
    }

    /**
     * Scope for waiting.
     */
    public function scopeWaiting($query)
    {
        return $query->where('status', 'waiting');
    }

    /**
     * Scope for today.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope ordered by priority.
     */
    public function scopeOrderByPriority($query)
    {
        return $query->orderBy('priority_weight', 'desc')
            ->orderBy('created_at', 'asc');
    }
}
