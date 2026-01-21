<?php

namespace App\Services;

use App\Models\QueueCall;
use App\Models\QueueCounter;
use App\Models\QueueDailyStat;
use App\Models\QueueHourlyStat;
use App\Models\QueueStaffAssignment;
use App\Models\QueueTicket;
use App\Models\QueueTransfer;
use App\Models\QueueType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class QueueService
{
    /**
     * Get dashboard stats for a queue type.
     */
    public function getDashboardStats(?int $queueTypeId = null): array
    {
        $query = QueueTicket::today();

        if ($queueTypeId) {
            $query->where('queue_type_id', $queueTypeId);
        }

        $tickets = $query->get();

        return [
            'total_tickets' => $tickets->count(),
            'waiting' => $tickets->where('status', 'waiting')->count(),
            'called' => $tickets->where('status', 'called')->count(),
            'serving' => $tickets->where('status', 'serving')->count(),
            'completed' => $tickets->where('status', 'completed')->count(),
            'no_show' => $tickets->where('status', 'no_show')->count(),
            'cancelled' => $tickets->where('status', 'cancelled')->count(),
            'avg_wait_time' => $this->calculateAverageWaitTime($queueTypeId),
        ];
    }

    /**
     * Get all active queue types with stats.
     */
    public function getActiveQueueTypes(): Collection
    {
        return QueueType::active()
            ->ordered()
            ->withCount([
                'tickets as waiting_count' => function ($query) {
                    $query->today()->where('status', 'waiting');
                },
                'tickets as serving_count' => function ($query) {
                    $query->today()->where('status', 'serving');
                },
                'counters as active_counters' => function ($query) {
                    $query->active();
                },
            ])
            ->get();
    }

    /**
     * Get counters with current status for a queue type.
     */
    public function getCountersWithStatus(int $queueTypeId): Collection
    {
        return QueueCounter::where('queue_type_id', $queueTypeId)
            ->active()
            ->with([
                'staffAssignments' => function ($query) {
                    $query->today()->active()->with('user');
                },
            ])
            ->withCount([
                'calls as today_served' => function ($query) {
                    $query->today()->responded();
                },
            ])
            ->get()
            ->map(function ($counter) {
                $counter->current_ticket = $counter->getCurrentServingTicket();
                $counter->assigned_user = $counter->staffAssignments->first()?->user;

                return $counter;
            });
    }

    /**
     * Get next ticket in queue for a specific queue type.
     */
    public function getNextTicket(int $queueTypeId): ?QueueTicket
    {
        return QueueTicket::where('queue_type_id', $queueTypeId)
            ->today()
            ->waiting()
            ->orderedByPriority()
            ->with(['patient', 'queueType'])
            ->first();
    }

    /**
     * Get waiting list for a queue type.
     */
    public function getWaitingList(int $queueTypeId, int $limit = 50): Collection
    {
        return QueueTicket::where('queue_type_id', $queueTypeId)
            ->today()
            ->whereIn('status', ['waiting', 'called'])
            ->orderedByPriority()
            ->with(['patient', 'currentCounter'])
            ->limit($limit)
            ->get();
    }

    /**
     * Get currently serving tickets for display.
     */
    public function getCurrentlyServing(?int $queueTypeId = null): Collection
    {
        $query = QueueTicket::today()
            ->whereIn('status', ['called', 'serving'])
            ->with(['currentCounter', 'queueType']);

        if ($queueTypeId) {
            $query->where('queue_type_id', $queueTypeId);
        }

        return $query->orderBy('called_at', 'desc')->get();
    }

    /**
     * Assign staff to counter.
     */
    public function assignStaffToCounter(int $userId, int $counterId): QueueStaffAssignment
    {
        // Deactivate any existing assignment for the user today
        QueueStaffAssignment::where('user_id', $userId)
            ->today()
            ->active()
            ->update(['is_active' => false, 'end_time' => now()->format('H:i')]);

        return QueueStaffAssignment::create([
            'user_id' => $userId,
            'counter_id' => $counterId,
            'assignment_date' => today(),
            'start_time' => now()->format('H:i'),
            'is_active' => true,
        ]);
    }

    /**
     * Get staff assignment for today.
     */
    public function getStaffAssignment(int $userId): ?QueueStaffAssignment
    {
        return QueueStaffAssignment::where('user_id', $userId)
            ->today()
            ->active()
            ->with('counter.queueType')
            ->first();
    }

    /**
     * Transfer ticket to another queue type.
     */
    public function transferTicket(
        QueueTicket $ticket,
        int $toQueueTypeId,
        int $transferredBy,
        string $reason = '',
        bool $isAuto = false
    ): QueueTicket {
        return DB::transaction(function () use ($ticket, $toQueueTypeId, $transferredBy, $reason, $isAuto) {
            $toQueueType = QueueType::findOrFail($toQueueTypeId);

            // Mark original ticket as transferred
            $ticket->update(['status' => 'transferred']);

            // Create new ticket in target queue
            $sequence = $toQueueType->getNextSequence();
            $newTicket = QueueTicket::create([
                'ticket_number' => $toQueueType->generateTicketNumber($sequence),
                'sequence' => $sequence,
                'queue_type_id' => $toQueueTypeId,
                'queue_date' => today(),
                'patient_id' => $ticket->patient_id,
                'patient_visit_id' => $ticket->patient_visit_id,
                'priority_level' => $ticket->priority_level,
                'priority_reason' => $ticket->priority_reason,
                'status' => 'waiting',
                'issued_at' => now(),
                'source' => 'auto',
                'parent_ticket_id' => $ticket->id,
            ]);

            // Record the transfer
            QueueTransfer::create([
                'from_ticket_id' => $ticket->id,
                'to_ticket_id' => $newTicket->id,
                'from_queue_type_id' => $ticket->queue_type_id,
                'to_queue_type_id' => $toQueueTypeId,
                'transfer_type' => $isAuto ? 'auto' : 'manual',
                'reason' => $reason,
                'transferred_by' => $transferredBy,
                'transferred_at' => now(),
            ]);

            return $newTicket;
        });
    }

    /**
     * Calculate average wait time for today.
     */
    public function calculateAverageWaitTime(?int $queueTypeId = null): ?int
    {
        $query = QueueTicket::today()
            ->where('status', 'completed')
            ->whereNotNull('actual_wait_time');

        if ($queueTypeId) {
            $query->where('queue_type_id', $queueTypeId);
        }

        $avg = $query->avg('actual_wait_time');

        return $avg ? (int) round($avg) : null;
    }

    /**
     * Get data for queue display screen.
     */
    public function getDisplayData(array $queueTypeIds = []): array
    {
        $query = QueueTicket::today()
            ->whereIn('status', ['called', 'serving'])
            ->with(['currentCounter', 'queueType'])
            ->orderBy('called_at', 'desc');

        if (! empty($queueTypeIds)) {
            $query->whereIn('queue_type_id', $queueTypeIds);
        }

        $currentlyServing = $query->limit(10)->get();

        // Get last completed tickets
        $lastCompleted = QueueTicket::today()
            ->where('status', 'completed')
            ->with(['currentCounter', 'queueType'])
            ->orderBy('completed_at', 'desc')
            ->limit(5)
            ->get();

        return [
            'currently_serving' => $currentlyServing,
            'last_completed' => $lastCompleted,
            'timestamp' => now()->toIso8601String(),
        ];
    }

    /**
     * Update statistics for a queue type.
     */
    public function updateStats(int $queueTypeId): void
    {
        QueueDailyStat::updateStatsForDate($queueTypeId);
        QueueHourlyStat::updateCurrentHourStats($queueTypeId);
    }

    /**
     * Get call history for a ticket.
     */
    public function getCallHistory(int $ticketId): Collection
    {
        return QueueCall::where('ticket_id', $ticketId)
            ->with(['counter', 'calledByUser'])
            ->orderBy('called_at', 'desc')
            ->get();
    }
}
