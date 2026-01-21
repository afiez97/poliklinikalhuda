<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QueueTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'sequence',
        'queue_type_id',
        'queue_date',
        'patient_id',
        'patient_visit_id',
        'priority_level',
        'priority_reason',
        'status',
        'current_counter_id',
        'served_by',
        'issued_at',
        'called_at',
        'serving_at',
        'completed_at',
        'call_count',
        'estimated_wait_time',
        'actual_wait_time',
        'service_time',
        'source',
        'parent_ticket_id',
        'notes',
    ];

    protected $casts = [
        'sequence' => 'integer',
        'queue_date' => 'date',
        'priority_level' => 'integer',
        'issued_at' => 'datetime',
        'called_at' => 'datetime',
        'serving_at' => 'datetime',
        'completed_at' => 'datetime',
        'call_count' => 'integer',
        'estimated_wait_time' => 'integer',
        'actual_wait_time' => 'integer',
        'service_time' => 'integer',
    ];

    /**
     * Priority levels (lower = higher priority).
     */
    public const PRIORITY_LEVELS = [
        1 => 'Kecemasan',
        2 => 'VIP',
        3 => 'OKU',
        4 => 'Warga Emas',
        5 => 'Wanita Mengandung',
        6 => 'Normal',
    ];

    /**
     * Get the queue type.
     */
    public function queueType(): BelongsTo
    {
        return $this->belongsTo(QueueType::class);
    }

    /**
     * Get the patient.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the patient visit.
     */
    public function patientVisit(): BelongsTo
    {
        return $this->belongsTo(PatientVisit::class);
    }

    /**
     * Get the current counter.
     */
    public function currentCounter(): BelongsTo
    {
        return $this->belongsTo(QueueCounter::class, 'current_counter_id');
    }

    /**
     * Get the serving staff.
     */
    public function servedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'served_by');
    }

    /**
     * Get the parent ticket (if transferred).
     */
    public function parentTicket(): BelongsTo
    {
        return $this->belongsTo(QueueTicket::class, 'parent_ticket_id');
    }

    /**
     * Get child tickets (transferred to).
     */
    public function childTickets(): HasMany
    {
        return $this->hasMany(QueueTicket::class, 'parent_ticket_id');
    }

    /**
     * Get calls for this ticket.
     */
    public function calls(): HasMany
    {
        return $this->hasMany(QueueCall::class, 'ticket_id');
    }

    /**
     * Get transfer records (as source).
     */
    public function transfersOut(): HasMany
    {
        return $this->hasMany(QueueTransfer::class, 'from_ticket_id');
    }

    /**
     * Get transfer records (as destination).
     */
    public function transfersIn(): HasMany
    {
        return $this->hasMany(QueueTransfer::class, 'to_ticket_id');
    }

    /**
     * Get notifications.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(QueueNotification::class, 'ticket_id');
    }

    /**
     * Scope for today's tickets.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('queue_date', today());
    }

    /**
     * Scope for waiting tickets.
     */
    public function scopeWaiting($query)
    {
        return $query->where('status', 'waiting');
    }

    /**
     * Scope for called tickets.
     */
    public function scopeCalled($query)
    {
        return $query->where('status', 'called');
    }

    /**
     * Scope for serving tickets.
     */
    public function scopeServing($query)
    {
        return $query->where('status', 'serving');
    }

    /**
     * Scope for completed tickets.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope ordered by priority and sequence.
     */
    public function scopeOrderedByPriority($query)
    {
        return $query->orderBy('priority_level')
            ->orderBy('sequence');
    }

    /**
     * Get priority label.
     */
    public function getPriorityLabelAttribute(): string
    {
        return self::PRIORITY_LEVELS[$this->priority_level] ?? 'Normal';
    }

    /**
     * Get status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'waiting' => 'Menunggu',
            'called' => 'Dipanggil',
            'serving' => 'Sedang Dilayan',
            'completed' => 'Selesai',
            'no_show' => 'Tidak Hadir',
            'cancelled' => 'Dibatalkan',
            'on_hold' => 'Ditangguh',
            'transferred' => 'Dipindahkan',
            default => $this->status,
        };
    }

    /**
     * Get status color for UI.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'waiting' => 'yellow',
            'called' => 'blue',
            'serving' => 'green',
            'completed' => 'gray',
            'no_show' => 'red',
            'cancelled' => 'red',
            'on_hold' => 'orange',
            'transferred' => 'purple',
            default => 'gray',
        };
    }

    /**
     * Get priority color for UI.
     */
    public function getPriorityColorAttribute(): string
    {
        return match ($this->priority_level) {
            1 => 'red',
            2 => 'purple',
            3 => 'blue',
            4 => 'cyan',
            5 => 'pink',
            default => 'gray',
        };
    }

    /**
     * Check if ticket can be called.
     */
    public function canBeCalled(): bool
    {
        return in_array($this->status, ['waiting', 'called']);
    }

    /**
     * Check if ticket can be completed.
     */
    public function canBeCompleted(): bool
    {
        return $this->status === 'serving';
    }

    /**
     * Get position in queue.
     */
    public function getQueuePositionAttribute(): int
    {
        if (! in_array($this->status, ['waiting', 'called'])) {
            return 0;
        }

        return self::where('queue_type_id', $this->queue_type_id)
            ->whereDate('queue_date', $this->queue_date)
            ->whereIn('status', ['waiting', 'called'])
            ->where(function ($query) {
                $query->where('priority_level', '<', $this->priority_level)
                    ->orWhere(function ($q) {
                        $q->where('priority_level', $this->priority_level)
                            ->where('sequence', '<', $this->sequence);
                    });
            })
            ->count() + 1;
    }

    /**
     * Calculate and get estimated wait time.
     */
    public function calculateEstimatedWaitTime(): int
    {
        $position = $this->queue_position;

        if ($position <= 1) {
            return 0;
        }

        $avgServiceTime = $this->queueType->avg_service_time ?? 5;
        $activeCounters = $this->queueType->counters()
            ->active()
            ->whereHas('staffAssignments', function ($q) {
                $q->where('assignment_date', today())
                    ->where('is_active', true);
            })
            ->count();

        $activeCounters = max(1, $activeCounters);

        return (int) ceil(($position - 1) * $avgServiceTime / $activeCounters);
    }

    /**
     * Call the ticket.
     */
    public function markAsCalled(int $counterId, int $calledBy): void
    {
        $this->update([
            'status' => 'called',
            'current_counter_id' => $counterId,
            'called_at' => now(),
            'call_count' => $this->call_count + 1,
        ]);

        QueueCall::create([
            'ticket_id' => $this->id,
            'counter_id' => $counterId,
            'called_by' => $calledBy,
            'call_type' => $this->call_count > 1 ? 'recall' : 'initial',
            'called_at' => now(),
        ]);
    }

    /**
     * Start serving.
     */
    public function startServing(int $userId): void
    {
        $waitTime = $this->issued_at ? now()->diffInMinutes($this->issued_at) : null;

        $this->update([
            'status' => 'serving',
            'served_by' => $userId,
            'serving_at' => now(),
            'actual_wait_time' => $waitTime,
        ]);

        // Mark last call as responded
        $this->calls()->latest()->first()?->update(['responded' => true]);
    }

    /**
     * Complete serving.
     */
    public function complete(): void
    {
        $serviceTime = $this->serving_at ? now()->diffInMinutes($this->serving_at) : null;

        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'service_time' => $serviceTime,
        ]);
    }

    /**
     * Mark as no show.
     */
    public function markNoShow(): void
    {
        $this->update([
            'status' => 'no_show',
            'completed_at' => now(),
        ]);
    }

    /**
     * Cancel ticket.
     */
    public function cancel(): void
    {
        $this->update([
            'status' => 'cancelled',
            'completed_at' => now(),
        ]);
    }

    /**
     * Put on hold.
     */
    public function putOnHold(): void
    {
        $this->update([
            'status' => 'on_hold',
        ]);
    }
}
