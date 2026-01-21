<?php

namespace App\Services;

use App\Models\Patient;
use App\Models\PatientVisit;
use App\Models\QueueNotification;
use App\Models\QueueTicket;
use App\Models\QueueType;
use Illuminate\Support\Facades\DB;

class QueueTicketService
{
    public function __construct(
        protected QueueService $queueService
    ) {}

    /**
     * Issue a new ticket.
     */
    public function issueTicket(
        int $queueTypeId,
        ?int $patientId = null,
        ?int $patientVisitId = null,
        int $priorityLevel = 6,
        ?string $priorityReason = null,
        string $source = 'counter'
    ): QueueTicket {
        return DB::transaction(function () use (
            $queueTypeId,
            $patientId,
            $patientVisitId,
            $priorityLevel,
            $priorityReason,
            $source
        ) {
            $queueType = QueueType::findOrFail($queueTypeId);

            // Check if queue has reached max size
            if ($queueType->hasReachedMaxSize()) {
                throw new \Exception('Queue has reached maximum size for today.');
            }

            // Check if within operating hours
            if (! $queueType->isWithinOperatingHours()) {
                throw new \Exception('Queue is not within operating hours.');
            }

            $sequence = $queueType->getNextSequence();
            $ticketNumber = $queueType->generateTicketNumber($sequence);

            $ticket = QueueTicket::create([
                'ticket_number' => $ticketNumber,
                'sequence' => $sequence,
                'queue_type_id' => $queueTypeId,
                'queue_date' => today(),
                'patient_id' => $patientId,
                'patient_visit_id' => $patientVisitId,
                'priority_level' => $priorityLevel,
                'priority_reason' => $priorityReason,
                'status' => 'waiting',
                'issued_at' => now(),
                'estimated_wait_time' => $this->calculateEstimatedWaitTime($queueTypeId),
                'source' => $source,
            ]);

            // Send notification if patient has phone
            if ($patientId) {
                $this->sendTicketIssuedNotification($ticket);
            }

            return $ticket;
        });
    }

    /**
     * Issue ticket from patient visit.
     */
    public function issueFromVisit(PatientVisit $visit, int $queueTypeId): QueueTicket
    {
        $priorityLevel = $this->determinePriorityLevel($visit->patient);

        return $this->issueTicket(
            queueTypeId: $queueTypeId,
            patientId: $visit->patient_id,
            patientVisitId: $visit->id,
            priorityLevel: $priorityLevel,
            priorityReason: $this->getPriorityReason($visit->patient),
            source: 'counter'
        );
    }

    /**
     * Call a ticket.
     */
    public function callTicket(QueueTicket $ticket, int $counterId, int $calledBy): QueueTicket
    {
        if (! $ticket->canBeCalled()) {
            throw new \Exception('Ticket cannot be called in current status.');
        }

        $ticket->markAsCalled($counterId, $calledBy);

        // Send approaching notification to next few in queue
        $this->notifyApproachingTickets($ticket->queue_type_id);

        return $ticket->fresh();
    }

    /**
     * Recall a ticket (call again).
     */
    public function recallTicket(QueueTicket $ticket, int $counterId, int $calledBy): QueueTicket
    {
        if ($ticket->status !== 'called') {
            throw new \Exception('Can only recall a called ticket.');
        }

        $ticket->markAsCalled($counterId, $calledBy);

        return $ticket->fresh();
    }

    /**
     * Start serving a ticket.
     */
    public function startServing(QueueTicket $ticket, int $userId): QueueTicket
    {
        if (! in_array($ticket->status, ['called', 'waiting'])) {
            throw new \Exception('Ticket must be in called or waiting status to start serving.');
        }

        $ticket->startServing($userId);

        return $ticket->fresh();
    }

    /**
     * Complete serving a ticket.
     */
    public function completeTicket(QueueTicket $ticket, ?int $transferToQueueId = null, int $completedBy = null): QueueTicket
    {
        return DB::transaction(function () use ($ticket, $transferToQueueId, $completedBy) {
            if (! $ticket->canBeCompleted()) {
                throw new \Exception('Ticket cannot be completed in current status.');
            }

            $ticket->complete();

            // Auto-transfer if configured
            if ($transferToQueueId || $ticket->queueType->auto_transfer_to) {
                $targetQueueId = $transferToQueueId ?? $ticket->queueType->auto_transfer_to;
                $this->queueService->transferTicket(
                    $ticket,
                    $targetQueueId,
                    $completedBy ?? $ticket->served_by,
                    'Auto-transfer after service completion',
                    true
                );
            }

            // Update stats
            $this->queueService->updateStats($ticket->queue_type_id);

            return $ticket->fresh();
        });
    }

    /**
     * Mark ticket as no show.
     */
    public function markNoShow(QueueTicket $ticket): QueueTicket
    {
        if (! in_array($ticket->status, ['waiting', 'called'])) {
            throw new \Exception('Can only mark waiting or called ticket as no show.');
        }

        $ticket->markNoShow();

        return $ticket->fresh();
    }

    /**
     * Cancel a ticket.
     */
    public function cancelTicket(QueueTicket $ticket): QueueTicket
    {
        if (in_array($ticket->status, ['completed', 'no_show', 'cancelled'])) {
            throw new \Exception('Cannot cancel a ticket that is already completed, no show, or cancelled.');
        }

        $ticket->cancel();

        return $ticket->fresh();
    }

    /**
     * Put ticket on hold.
     */
    public function putOnHold(QueueTicket $ticket): QueueTicket
    {
        if ($ticket->status !== 'waiting') {
            throw new \Exception('Can only put waiting ticket on hold.');
        }

        $ticket->putOnHold();

        return $ticket->fresh();
    }

    /**
     * Resume ticket from hold.
     */
    public function resumeFromHold(QueueTicket $ticket): QueueTicket
    {
        if ($ticket->status !== 'on_hold') {
            throw new \Exception('Can only resume ticket that is on hold.');
        }

        $ticket->update(['status' => 'waiting']);

        return $ticket->fresh();
    }

    /**
     * Calculate estimated wait time.
     */
    protected function calculateEstimatedWaitTime(int $queueTypeId): int
    {
        $queueType = QueueType::find($queueTypeId);
        $waitingCount = QueueTicket::where('queue_type_id', $queueTypeId)
            ->today()
            ->whereIn('status', ['waiting', 'called'])
            ->count();

        $avgServiceTime = $queueType->avg_service_time ?? 5;

        // Count active counters
        $activeCounters = $queueType->counters()
            ->active()
            ->whereHas('staffAssignments', function ($q) {
                $q->today()->active();
            })
            ->count();

        $activeCounters = max(1, $activeCounters);

        return (int) ceil($waitingCount * $avgServiceTime / $activeCounters);
    }

    /**
     * Determine priority level based on patient info.
     */
    protected function determinePriorityLevel(Patient $patient): int
    {
        // Emergency - handled manually
        // VIP - handled manually

        // OKU
        if ($patient->is_oku) {
            return 3;
        }

        // Warga Emas (60+)
        if ($patient->date_of_birth && $patient->date_of_birth->age >= 60) {
            return 4;
        }

        // Normal
        return 6;
    }

    /**
     * Get priority reason text.
     */
    protected function getPriorityReason(Patient $patient): ?string
    {
        if ($patient->is_oku) {
            return 'OKU';
        }

        if ($patient->date_of_birth && $patient->date_of_birth->age >= 60) {
            return 'Warga Emas';
        }

        return null;
    }

    /**
     * Send notification when ticket is issued.
     */
    protected function sendTicketIssuedNotification(QueueTicket $ticket): void
    {
        if (! $ticket->patient?->phone) {
            return;
        }

        $message = sprintf(
            'Nombor giliran anda: %s. Anggaran masa menunggu: %d minit. Sila pantau paparan giliran.',
            $ticket->ticket_number,
            $ticket->estimated_wait_time ?? 0
        );

        QueueNotification::create([
            'ticket_id' => $ticket->id,
            'notification_type' => 'issued',
            'channel' => 'sms',
            'recipient' => $ticket->patient->phone,
            'message' => $message,
            'status' => 'pending',
        ]);
    }

    /**
     * Notify approaching tickets.
     */
    protected function notifyApproachingTickets(int $queueTypeId): void
    {
        // Get the next 3 waiting tickets
        $tickets = QueueTicket::where('queue_type_id', $queueTypeId)
            ->today()
            ->waiting()
            ->orderedByPriority()
            ->limit(3)
            ->get();

        foreach ($tickets as $index => $ticket) {
            if (! $ticket->patient?->phone) {
                continue;
            }

            // Check if we already sent approaching notification
            $existingNotification = QueueNotification::where('ticket_id', $ticket->id)
                ->where('notification_type', 'approaching')
                ->exists();

            if ($existingNotification) {
                continue;
            }

            $position = $index + 1;
            $message = sprintf(
                'Giliran anda (%s) hampir dipanggil. Anda nombor %d dalam barisan. Sila bersedia.',
                $ticket->ticket_number,
                $position
            );

            QueueNotification::create([
                'ticket_id' => $ticket->id,
                'notification_type' => 'approaching',
                'channel' => 'sms',
                'recipient' => $ticket->patient->phone,
                'message' => $message,
                'status' => 'pending',
            ]);
        }
    }

    /**
     * Get ticket by number for today.
     */
    public function findByTicketNumber(string $ticketNumber): ?QueueTicket
    {
        return QueueTicket::where('ticket_number', $ticketNumber)
            ->today()
            ->with(['patient', 'queueType', 'currentCounter'])
            ->first();
    }

    /**
     * Get patient's active tickets for today.
     */
    public function getPatientActiveTickets(int $patientId): \Illuminate\Database\Eloquent\Collection
    {
        return QueueTicket::where('patient_id', $patientId)
            ->today()
            ->whereNotIn('status', ['completed', 'cancelled', 'no_show'])
            ->with(['queueType', 'currentCounter'])
            ->get();
    }
}
