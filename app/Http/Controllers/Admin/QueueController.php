<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PatientVisit;
use App\Models\QueueCounter;
use App\Models\QueueTicket;
use App\Models\QueueType;
use App\Services\AuditService;
use App\Services\QueueService;
use App\Services\QueueTicketService;
use App\Traits\HandlesApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Patch;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('admin/queue')]
#[Middleware(['web', 'auth'])]
class QueueController extends Controller
{
    use HandlesApiResponses;

    public function __construct(
        protected AuditService $auditService,
        protected QueueService $queueService,
        protected QueueTicketService $ticketService
    ) {}

    /**
     * Staff dashboard - main queue management view.
     */
    #[Get('/', name: 'admin.queue.index')]
    public function index(Request $request)
    {
        $queueTypeId = $request->queue_type_id;

        $queueTypes = $this->queueService->getActiveQueueTypes();
        $currentQueueType = $queueTypeId ? $queueTypes->find($queueTypeId) : $queueTypes->first();

        $waitingList = [];
        $counters = collect();
        $currentServing = collect();

        if ($currentQueueType) {
            $waitingList = $this->queueService->getWaitingList($currentQueueType->id);
            $counters = $this->queueService->getCountersWithStatus($currentQueueType->id);
            $currentServing = $this->queueService->getCurrentlyServing($currentQueueType->id);
        }

        $statistics = $this->queueService->getDashboardStats($currentQueueType?->id);

        // Get current user's assignment
        $myAssignment = $this->queueService->getStaffAssignment(auth()->id());

        return view('admin.queue.index', compact(
            'queueTypes',
            'currentQueueType',
            'waitingList',
            'counters',
            'currentServing',
            'statistics',
            'myAssignment'
        ));
    }

    /**
     * Public display screen for queue.
     */
    #[Get('/display', name: 'admin.queue.display')]
    public function display(Request $request)
    {
        $queueTypeIds = $request->types ? explode(',', $request->types) : [];

        $queueTypes = QueueType::active()
            ->when(! empty($queueTypeIds), fn ($q) => $q->whereIn('id', $queueTypeIds))
            ->ordered()
            ->get();

        $displayData = $this->queueService->getDisplayData($queueTypeIds);

        return view('admin.queue.display', [
            'queueTypes' => $queueTypes,
            'currentlyServing' => $displayData['currently_serving'],
            'lastCompleted' => $displayData['last_completed'],
        ]);
    }

    /**
     * Kiosk interface for patients to get tickets.
     */
    #[Get('/kiosk', name: 'admin.queue.kiosk')]
    public function kiosk()
    {
        $queueTypes = QueueType::active()
            ->ordered()
            ->get()
            ->filter(fn ($type) => $type->isWithinOperatingHours() && ! $type->hasReachedMaxSize());

        return view('admin.queue.kiosk', compact('queueTypes'));
    }

    /**
     * Issue a new ticket (from kiosk or staff).
     */
    #[Post('/ticket', name: 'admin.queue.issueTicket')]
    public function issueTicket(Request $request)
    {
        $validated = $request->validate([
            'queue_type_id' => 'required|exists:queue_types,id',
            'patient_id' => 'nullable|exists:patients,id',
            'patient_visit_id' => 'nullable|exists:patient_visits,id',
            'priority_level' => 'nullable|integer|min:1|max:6',
            'priority_reason' => 'nullable|string|max:100',
            'source' => 'nullable|in:counter,kiosk,mobile',
        ]);

        try {
            $ticket = $this->ticketService->issueTicket(
                queueTypeId: $validated['queue_type_id'],
                patientId: $validated['patient_id'] ?? null,
                patientVisitId: $validated['patient_visit_id'] ?? null,
                priorityLevel: $validated['priority_level'] ?? 6,
                priorityReason: $validated['priority_reason'] ?? null,
                source: $validated['source'] ?? 'counter'
            );

            $this->auditService->log('create', "Queue ticket issued: {$ticket->ticket_number}", $ticket);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'ticket' => $ticket->load('queueType'),
                    'message' => __('Tiket dikeluarkan: :number', ['number' => $ticket->ticket_number]),
                ]);
            }

            return $this->successRedirect(
                'admin.queue.index',
                __('Tiket dikeluarkan: :number', ['number' => $ticket->ticket_number]),
                ['queue_type_id' => $ticket->queue_type_id]
            );
        } catch (\Exception $e) {
            Log::error('Failed to issue ticket', ['error' => $e->getMessage()]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }

            return $this->errorRedirect($e->getMessage());
        }
    }

    /**
     * Issue ticket from patient visit.
     */
    #[Post('/ticket/from-visit', name: 'admin.queue.issueFromVisit')]
    public function issueFromVisit(Request $request)
    {
        $validated = $request->validate([
            'patient_visit_id' => 'required|exists:patient_visits,id',
            'queue_type_id' => 'required|exists:queue_types,id',
        ]);

        try {
            $visit = PatientVisit::findOrFail($validated['patient_visit_id']);
            $ticket = $this->ticketService->issueFromVisit($visit, $validated['queue_type_id']);

            $this->auditService->log('create', "Queue ticket from visit: {$ticket->ticket_number}", $ticket);

            return $this->successRedirect(
                'admin.queue.index',
                __('Tiket dikeluarkan: :number', ['number' => $ticket->ticket_number]),
                ['queue_type_id' => $ticket->queue_type_id]
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    /**
     * Call a ticket.
     */
    #[Patch('/ticket/{ticket}/call', name: 'admin.queue.call')]
    public function call(Request $request, QueueTicket $ticket)
    {
        $validated = $request->validate([
            'counter_id' => 'required|exists:queue_counters,id',
        ]);

        try {
            $ticket = $this->ticketService->callTicket(
                $ticket,
                $validated['counter_id'],
                auth()->id()
            );

            $this->auditService->log('update', "Queue called: {$ticket->ticket_number}", $ticket);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'ticket' => $ticket->load(['patient', 'currentCounter']),
                ]);
            }

            return $this->successRedirect(
                'admin.queue.index',
                __('Memanggil :number', ['number' => $ticket->ticket_number]),
                ['queue_type_id' => $ticket->queue_type_id]
            );
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }

            return $this->errorRedirect($e->getMessage());
        }
    }

    /**
     * Recall a ticket.
     */
    #[Patch('/ticket/{ticket}/recall', name: 'admin.queue.recall')]
    public function recall(Request $request, QueueTicket $ticket)
    {
        $validated = $request->validate([
            'counter_id' => 'required|exists:queue_counters,id',
        ]);

        try {
            $ticket = $this->ticketService->recallTicket(
                $ticket,
                $validated['counter_id'],
                auth()->id()
            );

            $this->auditService->log('update', "Queue recalled: {$ticket->ticket_number}", $ticket);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'ticket' => $ticket->load(['patient', 'currentCounter']),
                ]);
            }

            return $this->successRedirect(
                'admin.queue.index',
                __('Panggilan ulang :number', ['number' => $ticket->ticket_number])
            );
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }

            return $this->errorRedirect($e->getMessage());
        }
    }

    /**
     * Start serving a ticket.
     */
    #[Patch('/ticket/{ticket}/serve', name: 'admin.queue.serve')]
    public function serve(QueueTicket $ticket)
    {
        try {
            $ticket = $this->ticketService->startServing($ticket, auth()->id());

            $this->auditService->log('update', "Queue serving: {$ticket->ticket_number}", $ticket);

            return $this->successRedirect(
                'admin.queue.index',
                __('Mula melayan :number', ['number' => $ticket->ticket_number]),
                ['queue_type_id' => $ticket->queue_type_id]
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    /**
     * Complete serving a ticket.
     */
    #[Patch('/ticket/{ticket}/complete', name: 'admin.queue.complete')]
    public function complete(Request $request, QueueTicket $ticket)
    {
        try {
            $ticket = $this->ticketService->completeTicket(
                $ticket,
                $request->input('transfer_to_queue_id'),
                auth()->id()
            );

            $this->auditService->log('update', "Queue completed: {$ticket->ticket_number}", $ticket);

            return $this->successRedirect(
                'admin.queue.index',
                __('Selesai melayan :number', ['number' => $ticket->ticket_number]),
                ['queue_type_id' => $ticket->queue_type_id]
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    /**
     * Mark ticket as no show.
     */
    #[Patch('/ticket/{ticket}/no-show', name: 'admin.queue.noShow')]
    public function noShow(QueueTicket $ticket)
    {
        try {
            $ticket = $this->ticketService->markNoShow($ticket);

            $this->auditService->log('update', "Queue no-show: {$ticket->ticket_number}", $ticket);

            return $this->successRedirect(
                'admin.queue.index',
                __(':number ditandakan tidak hadir', ['number' => $ticket->ticket_number]),
                ['queue_type_id' => $ticket->queue_type_id]
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    /**
     * Cancel a ticket.
     */
    #[Delete('/ticket/{ticket}', name: 'admin.queue.cancel')]
    public function cancel(QueueTicket $ticket)
    {
        try {
            $queueTypeId = $ticket->queue_type_id;
            $ticket = $this->ticketService->cancelTicket($ticket);

            $this->auditService->log('update', "Queue cancelled: {$ticket->ticket_number}", $ticket);

            return $this->successRedirect(
                'admin.queue.index',
                __(':number dibatalkan', ['number' => $ticket->ticket_number]),
                ['queue_type_id' => $queueTypeId]
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    /**
     * Transfer ticket to another queue.
     */
    #[Post('/ticket/{ticket}/transfer', name: 'admin.queue.transfer')]
    public function transfer(Request $request, QueueTicket $ticket)
    {
        $validated = $request->validate([
            'to_queue_type_id' => 'required|exists:queue_types,id',
            'reason' => 'nullable|string|max:255',
        ]);

        try {
            $newTicket = $this->queueService->transferTicket(
                $ticket,
                $validated['to_queue_type_id'],
                auth()->id(),
                $validated['reason'] ?? ''
            );

            $this->auditService->log('update', "Queue transferred: {$ticket->ticket_number} -> {$newTicket->ticket_number}", $newTicket);

            return $this->successRedirect(
                'admin.queue.index',
                __('Dipindahkan ke :number', ['number' => $newTicket->ticket_number]),
                ['queue_type_id' => $newTicket->queue_type_id]
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    /**
     * Assign current user to a counter.
     */
    #[Post('/counter/{counter}/assign', name: 'admin.queue.assignCounter')]
    public function assignToCounter(QueueCounter $counter)
    {
        try {
            $assignment = $this->queueService->assignStaffToCounter(auth()->id(), $counter->id);

            $this->auditService->log('create', "Staff assigned to counter: {$counter->code}", $assignment);

            return $this->successRedirect(
                'admin.queue.index',
                __('Berjaya ditugaskan ke :counter', ['counter' => $counter->display_name]),
                ['queue_type_id' => $counter->queue_type_id]
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    /**
     * Leave current counter assignment.
     */
    #[Post('/counter/leave', name: 'admin.queue.leaveCounter')]
    public function leaveCounter()
    {
        try {
            $assignment = $this->queueService->getStaffAssignment(auth()->id());

            if ($assignment) {
                $assignment->deactivate();
                $this->auditService->log('update', 'Staff left counter', $assignment);
            }

            return $this->successRedirect('admin.queue.index', __('Berjaya keluar dari kaunter'));
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    /**
     * Call next ticket for assigned counter.
     */
    #[Post('/call-next', name: 'admin.queue.callNext')]
    public function callNext(Request $request)
    {
        try {
            $assignment = $this->queueService->getStaffAssignment(auth()->id());

            if (! $assignment) {
                throw new \Exception('Anda tidak ditugaskan ke mana-mana kaunter.');
            }

            // Complete current ticket if serving
            $currentTicket = $assignment->counter->getCurrentServingTicket();
            if ($currentTicket) {
                $this->ticketService->completeTicket($currentTicket, null, auth()->id());
            }

            // Get and call next ticket
            $nextTicket = $this->queueService->getNextTicket($assignment->counter->queue_type_id);

            if (! $nextTicket) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tiada giliran dalam barisan.',
                ], 404);
            }

            $nextTicket = $this->ticketService->callTicket($nextTicket, $assignment->counter->id, auth()->id());
            $nextTicket = $this->ticketService->startServing($nextTicket, auth()->id());

            $this->auditService->log('update', "Queue auto-called: {$nextTicket->ticket_number}", $nextTicket);

            return response()->json([
                'success' => true,
                'ticket' => $nextTicket->load(['patient', 'currentCounter', 'queueType']),
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    /**
     * Get queue status (for AJAX/WebSocket).
     */
    #[Get('/status', name: 'admin.queue.status')]
    public function status(Request $request)
    {
        $queueTypeId = $request->queue_type_id;

        return response()->json([
            'stats' => $this->queueService->getDashboardStats($queueTypeId),
            'currently_serving' => $this->queueService->getCurrentlyServing($queueTypeId),
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Get display data for queue screen (for AJAX polling).
     */
    #[Get('/display-data', name: 'admin.queue.displayData')]
    public function displayData(Request $request)
    {
        $queueTypeIds = $request->types ? explode(',', $request->types) : [];

        return response()->json($this->queueService->getDisplayData($queueTypeIds));
    }

    /**
     * Queue types management.
     */
    #[Get('/types', name: 'admin.queue.types')]
    public function types()
    {
        $queueTypes = QueueType::ordered()
            ->withCount(['counters', 'tickets as today_tickets' => fn ($q) => $q->today()])
            ->get();

        return view('admin.queue.types.index', compact('queueTypes'));
    }

    /**
     * Counters management.
     */
    #[Get('/counters', name: 'admin.queue.counters')]
    public function counters()
    {
        $counters = QueueCounter::with('queueType')
            ->orderBy('queue_type_id')
            ->get();

        $queueTypes = QueueType::ordered()->get();

        return view('admin.queue.counters.index', compact('counters', 'queueTypes'));
    }

    /**
     * Statistics and reports.
     */
    #[Get('/reports', name: 'admin.queue.reports')]
    public function reports(Request $request)
    {
        $date = $request->date ? \Carbon\Carbon::parse($request->date) : today();
        $queueTypeId = $request->queue_type_id;

        $queueTypes = QueueType::ordered()->get();

        $dailyStats = \App\Models\QueueDailyStat::forDate($date)
            ->when($queueTypeId, fn ($q) => $q->where('queue_type_id', $queueTypeId))
            ->with('queueType')
            ->get();

        $hourlyStats = \App\Models\QueueHourlyStat::forDate($date)
            ->when($queueTypeId, fn ($q) => $q->where('queue_type_id', $queueTypeId))
            ->orderBy('stat_hour')
            ->get();

        return view('admin.queue.reports', compact('queueTypes', 'dailyStats', 'hourlyStats', 'date'));
    }
}
