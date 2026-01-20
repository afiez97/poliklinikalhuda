<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PatientVisit;
use App\Models\QueueCounter;
use App\Models\QueueEntry;
use App\Services\AuditService;
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
        protected AuditService $auditService
    ) {}

    #[Get('/', name: 'admin.queue.index')]
    public function index(Request $request)
    {
        $counterId = $request->counter_id;

        $counters = QueueCounter::active()
            ->ordered()
            ->withCount([
                'entries as waiting_count' => fn ($q) => $q->today()->waiting(),
                'entries as serving_count' => fn ($q) => $q->today()->where('status', 'serving'),
            ])
            ->get();

        $currentCounter = $counterId ? $counters->find($counterId) : $counters->first();

        $queue = [];
        $currentServing = null;

        if ($currentCounter) {
            $queue = QueueEntry::with(['patientVisit.patient'])
                ->where('queue_counter_id', $currentCounter->id)
                ->today()
                ->waiting()
                ->orderByPriority()
                ->limit(20)
                ->get();

            $currentServing = QueueEntry::with(['patientVisit.patient', 'server'])
                ->where('queue_counter_id', $currentCounter->id)
                ->today()
                ->whereIn('status', ['calling', 'serving'])
                ->first();
        }

        $statistics = [
            'total_waiting' => QueueEntry::today()->waiting()->count(),
            'total_served' => QueueEntry::today()->where('status', 'completed')->count(),
            'avg_wait_time' => QueueEntry::today()
                ->where('status', 'completed')
                ->whereNotNull('wait_time_minutes')
                ->avg('wait_time_minutes') ?? 0,
        ];

        return view('admin.queue.index', compact('counters', 'currentCounter', 'queue', 'currentServing', 'statistics'));
    }

    #[Get('/display', name: 'admin.queue.display')]
    public function display(Request $request)
    {
        $counterIds = $request->counters ? explode(',', $request->counters) : null;

        $counters = QueueCounter::active()
            ->when($counterIds, fn ($q) => $q->whereIn('id', $counterIds))
            ->ordered()
            ->get();

        $currentlyServing = QueueEntry::with(['patientVisit.patient', 'queueCounter'])
            ->today()
            ->whereIn('status', ['calling', 'serving'])
            ->when($counterIds, fn ($q) => $q->whereIn('queue_counter_id', $counterIds))
            ->orderBy('called_at', 'desc')
            ->limit(5)
            ->get();

        $waiting = QueueEntry::with(['patientVisit.patient', 'queueCounter'])
            ->today()
            ->waiting()
            ->when($counterIds, fn ($q) => $q->whereIn('queue_counter_id', $counterIds))
            ->orderByPriority()
            ->limit(10)
            ->get();

        return view('admin.queue.display', compact('counters', 'currentlyServing', 'waiting'));
    }

    #[Post('/add', name: 'admin.queue.add')]
    public function add(Request $request)
    {
        $validated = $request->validate([
            'patient_visit_id' => 'required|exists:patient_visits,id',
            'queue_counter_id' => 'required|exists:queue_counters,id',
            'priority' => 'required|in:normal,elderly,disabled,pregnant,urgent,emergency',
        ]);

        try {
            $counter = QueueCounter::findOrFail($validated['queue_counter_id']);
            $visit = PatientVisit::findOrFail($validated['patient_visit_id']);

            // Check if already in queue for this counter
            $existingQueue = QueueEntry::where('patient_visit_id', $visit->id)
                ->where('queue_counter_id', $counter->id)
                ->today()
                ->whereIn('status', ['waiting', 'calling', 'serving'])
                ->first();

            if ($existingQueue) {
                return $this->errorRedirect('Pesakit sudah dalam giliran.');
            }

            $entry = QueueEntry::create([
                'queue_counter_id' => $counter->id,
                'patient_visit_id' => $visit->id,
                'queue_number' => $counter->getNextQueueNumber(),
                'priority' => $validated['priority'],
            ]);

            $this->auditService->log(
                'create',
                "Queue added: {$entry->queue_number}",
                $entry
            );

            return $this->successRedirect(
                'admin.queue.index',
                __('Berjaya masuk giliran. No: :no', ['no' => $entry->queue_number]),
                ['counter_id' => $counter->id]
            );
        } catch (\Exception $e) {
            Log::error('Failed to add to queue', ['error' => $e->getMessage()]);

            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Patch('/{entry}/call', name: 'admin.queue.call')]
    public function call(Request $request, QueueEntry $entry)
    {
        $validated = $request->validate([
            'counter_number' => 'required|string|max:10',
        ]);

        try {
            $entry->call($validated['counter_number']);

            $this->auditService->log('update', "Queue called: {$entry->queue_number}", $entry);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'entry' => $entry->load('patientVisit.patient'),
                ]);
            }

            return $this->successRedirect(
                'admin.queue.index',
                __('Memanggil :no', ['no' => $entry->queue_number]),
                ['counter_id' => $entry->queue_counter_id]
            );
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }

            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Patch('/{entry}/serve', name: 'admin.queue.serve')]
    public function serve(QueueEntry $entry)
    {
        try {
            $entry->startServing(auth()->id());

            $this->auditService->log('update', "Queue serving: {$entry->queue_number}", $entry);

            return $this->successRedirect(
                'admin.queue.index',
                __('Mula melayan :no', ['no' => $entry->queue_number]),
                ['counter_id' => $entry->queue_counter_id]
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Patch('/{entry}/complete', name: 'admin.queue.complete')]
    public function complete(QueueEntry $entry)
    {
        try {
            $entry->complete();

            $this->auditService->log('update', "Queue completed: {$entry->queue_number}", $entry);

            return $this->successRedirect(
                'admin.queue.index',
                __('Selesai melayan :no', ['no' => $entry->queue_number]),
                ['counter_id' => $entry->queue_counter_id]
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Patch('/{entry}/skip', name: 'admin.queue.skip')]
    public function skip(Request $request, QueueEntry $entry)
    {
        try {
            $entry->skip($request->input('reason'));

            $this->auditService->log('update', "Queue skipped: {$entry->queue_number}", $entry);

            return $this->successRedirect(
                'admin.queue.index',
                __('Giliran :no dilangkau', ['no' => $entry->queue_number]),
                ['counter_id' => $entry->queue_counter_id]
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Delete('/{entry}', name: 'admin.queue.cancel')]
    public function cancel(QueueEntry $entry)
    {
        try {
            $entry->cancel();

            $this->auditService->log('update', "Queue cancelled: {$entry->queue_number}", $entry);

            return $this->successRedirect(
                'admin.queue.index',
                __('Giliran :no dibatalkan', ['no' => $entry->queue_number]),
                ['counter_id' => $entry->queue_counter_id]
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Get('/next/{counter}', name: 'admin.queue.next')]
    public function getNext(QueueCounter $counter)
    {
        $next = $counter->getNextInQueue();

        return response()->json([
            'next' => $next ? $next->load('patientVisit.patient') : null,
            'waiting_count' => $counter->entries()->today()->waiting()->count(),
        ]);
    }

    #[Get('/status', name: 'admin.queue.status')]
    public function status()
    {
        $counters = QueueCounter::active()
            ->ordered()
            ->withCount([
                'entries as waiting_count' => fn ($q) => $q->today()->waiting(),
            ])
            ->get();

        $serving = QueueEntry::with(['patientVisit.patient', 'queueCounter'])
            ->today()
            ->whereIn('status', ['calling', 'serving'])
            ->get();

        return response()->json([
            'counters' => $counters,
            'serving' => $serving,
            'timestamp' => now()->toISOString(),
        ]);
    }

    #[Post('/call-next/{counter}', name: 'admin.queue.callNext')]
    public function callNext(Request $request, QueueCounter $counter)
    {
        $validated = $request->validate([
            'counter_number' => 'required|string|max:10',
        ]);

        try {
            // Complete current if any
            $current = QueueEntry::where('queue_counter_id', $counter->id)
                ->today()
                ->whereIn('status', ['calling', 'serving'])
                ->first();

            if ($current) {
                $current->complete();
            }

            // Get next
            $next = $counter->getNextInQueue();

            if (! $next) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tiada giliran dalam barisan.',
                ], 404);
            }

            $next->call($validated['counter_number']);
            $next->startServing(auth()->id());

            $this->auditService->log('update', "Queue auto-called: {$next->queue_number}", $next);

            return response()->json([
                'success' => true,
                'entry' => $next->load('patientVisit.patient'),
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }
}
