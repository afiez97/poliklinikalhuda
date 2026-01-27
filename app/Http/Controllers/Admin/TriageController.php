<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Queue;
use App\Models\TriageAssessment;
use App\Services\AuditService;
use App\Services\TriageService;
use App\Traits\HandlesApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Patch;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('admin/triage')]
#[Middleware(['web', 'auth'])]
class TriageController extends Controller
{
    use HandlesApiResponses;

    public function __construct(
        protected TriageService $triageService,
        protected AuditService $auditService
    ) {}

    #[Get('/', name: 'admin.triage.index')]
    public function index(Request $request)
    {
        $query = TriageAssessment::with(['patient', 'assessedBy', 'queue'])
            ->orderBy('created_at', 'desc');

        // Filter by severity
        if ($request->severity) {
            $query->where('severity_level', $request->severity);
        }

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter by date
        if ($request->date) {
            $query->whereDate('created_at', $request->date);
        } else {
            $query->whereDate('created_at', today());
        }

        $assessments = $query->paginate(20)->withQueryString();

        // Statistics
        $todayStats = [
            'total' => TriageAssessment::whereDate('created_at', today())->count(),
            'emergency' => TriageAssessment::whereDate('created_at', today())->where('severity_level', 'emergency')->count(),
            'urgent' => TriageAssessment::whereDate('created_at', today())->where('severity_level', 'urgent')->count(),
            'pending' => TriageAssessment::whereDate('created_at', today())->where('status', 'pending')->count(),
        ];

        $severityLevels = TriageAssessment::severityLevels();

        return view('admin.triage.index', compact('assessments', 'todayStats', 'severityLevels'));
    }

    #[Get('/create', name: 'admin.triage.create')]
    public function create(Request $request)
    {
        $patient = null;
        $queue = null;

        if ($request->patient_id) {
            $patient = Patient::findOrFail($request->patient_id);
        }

        if ($request->queue_id) {
            $queue = Queue::with('patient')->findOrFail($request->queue_id);
            $patient = $queue->patient;
        }

        $symptoms = $this->triageService->getCommonSymptoms();
        $bodyRegions = \App\Models\Symptom::bodyRegions();
        $categories = \App\Models\Symptom::categories();
        $severityLevels = TriageAssessment::severityLevels();

        return view('admin.triage.create', compact(
            'patient',
            'queue',
            'symptoms',
            'bodyRegions',
            'categories',
            'severityLevels'
        ));
    }

    #[Post('/', name: 'admin.triage.store')]
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'queue_id' => 'nullable|exists:queues,id',
            'chief_complaint' => 'required|string|max:500',
            'symptoms' => 'required|array|min:1',
            'symptoms.*.code' => 'required|string',
            'symptoms.*.name' => 'required|string',
            'symptoms.*.severity' => 'required|in:mild,moderate,severe',
            'symptoms.*.duration' => 'nullable|string',
            'vital_signs' => 'nullable|array',
            'vital_signs.bp_systolic' => 'nullable|numeric|min:50|max:300',
            'vital_signs.bp_diastolic' => 'nullable|numeric|min:30|max:200',
            'vital_signs.heart_rate' => 'nullable|numeric|min:30|max:250',
            'vital_signs.temperature' => 'nullable|numeric|min:30|max:45',
            'vital_signs.respiratory_rate' => 'nullable|numeric|min:5|max:60',
            'vital_signs.spo2' => 'nullable|numeric|min:50|max:100',
            'pain_score' => 'nullable|integer|min:0|max:10',
            'pain_location' => 'nullable|string|max:255',
            'additional_notes' => 'nullable|string|max:1000',
        ]);

        try {
            $patient = Patient::findOrFail($validated['patient_id']);

            // Perform AI triage assessment
            $aiAssessment = $this->triageService->assessTriage($validated, $patient);

            // Create triage record
            $triage = TriageAssessment::create([
                'patient_id' => $validated['patient_id'],
                'queue_id' => $validated['queue_id'] ?? null,
                'assessed_by' => auth()->id(),
                'chief_complaint' => $validated['chief_complaint'],
                'symptoms_data' => $validated['symptoms'],
                'vital_signs' => $validated['vital_signs'] ?? null,
                'pain_score' => $validated['pain_score'] ?? null,
                'pain_location' => $validated['pain_location'] ?? null,
                'additional_notes' => $validated['additional_notes'] ?? null,
                'severity_level' => $aiAssessment['severity_level'],
                'severity_score' => $aiAssessment['severity_score'],
                'ai_reasoning' => $aiAssessment['ai_reasoning'],
                'red_flags_detected' => $aiAssessment['red_flags_detected'],
                'differential_diagnoses' => $aiAssessment['differential_diagnoses'],
                'recommended_actions' => $aiAssessment['recommended_actions'],
                'ai_confidence' => $aiAssessment['ai_confidence'],
                'status' => 'pending',
            ]);

            // Update queue priority if exists
            if ($triage->queue_id && $triage->queue) {
                $priorityMap = [
                    'emergency' => 1,
                    'urgent' => 2,
                    'semi_urgent' => 3,
                    'standard' => 4,
                    'non_urgent' => 5,
                ];
                $triage->queue->update([
                    'priority' => $priorityMap[$triage->severity_level] ?? 4,
                ]);
            }

            $this->auditService->log(
                'create',
                "Triage assessment created for patient {$patient->name}",
                $triage
            );

            return $this->successRedirect(
                'admin.triage.show',
                __('Penilaian triage berjaya direkodkan.'),
                ['triage' => $triage->id]
            );
        } catch (\Exception $e) {
            Log::error('Failed to create triage assessment', ['error' => $e->getMessage()]);

            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Get('/{triage}', name: 'admin.triage.show')]
    public function show(TriageAssessment $triage)
    {
        $triage->load(['patient', 'assessedBy', 'reviewedBy', 'queue']);

        $severityLevels = TriageAssessment::severityLevels();

        // Get patient's recent medical history
        $recentHistory = $triage->patient->encounters()
            ->with('diagnoses')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.triage.show', compact('triage', 'severityLevels', 'recentHistory'));
    }

    #[Get('/{triage}/edit', name: 'admin.triage.edit')]
    public function edit(TriageAssessment $triage)
    {
        if ($triage->status === 'completed') {
            return $this->errorRedirect('Penilaian yang telah selesai tidak boleh diedit.');
        }

        $triage->load(['patient', 'queue']);

        $symptoms = $this->triageService->getCommonSymptoms();
        $bodyRegions = \App\Models\Symptom::bodyRegions();
        $categories = \App\Models\Symptom::categories();
        $severityLevels = TriageAssessment::severityLevels();

        return view('admin.triage.edit', compact(
            'triage',
            'symptoms',
            'bodyRegions',
            'categories',
            'severityLevels'
        ));
    }

    #[Patch('/{triage}/review', name: 'admin.triage.review')]
    public function review(Request $request, TriageAssessment $triage)
    {
        $validated = $request->validate([
            'action' => 'required|in:accept,override',
            'override_level' => 'required_if:action,override|in:emergency,urgent,semi_urgent,standard,non_urgent',
            'override_reason' => 'required_if:action,override|nullable|string|max:500',
        ]);

        try {
            $updateData = [
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
                'status' => 'reviewed',
            ];

            if ($validated['action'] === 'override') {
                $updateData['override_level'] = $validated['override_level'];
                $updateData['override_reason'] = $validated['override_reason'];

                // Update queue priority if exists
                if ($triage->queue_id && $triage->queue) {
                    $priorityMap = [
                        'emergency' => 1,
                        'urgent' => 2,
                        'semi_urgent' => 3,
                        'standard' => 4,
                        'non_urgent' => 5,
                    ];
                    $triage->queue->update([
                        'priority' => $priorityMap[$validated['override_level']] ?? 4,
                    ]);
                }
            }

            $triage->update($updateData);

            $this->auditService->log(
                'update',
                "Triage assessment reviewed: ".($validated['action'] === 'override' ? "Override to {$validated['override_level']}" : 'Accepted'),
                $triage
            );

            return $this->successRedirect(
                'admin.triage.show',
                __('Penilaian triage berjaya disemak.'),
                ['triage' => $triage->id]
            );
        } catch (\Exception $e) {
            Log::error('Failed to review triage', ['error' => $e->getMessage()]);

            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Patch('/{triage}/complete', name: 'admin.triage.complete')]
    public function complete(TriageAssessment $triage)
    {
        try {
            $triage->update(['status' => 'completed']);

            $this->auditService->log(
                'update',
                "Triage assessment completed for patient {$triage->patient->name}",
                $triage
            );

            return $this->successRedirect(
                'admin.triage.index',
                __('Penilaian triage berjaya ditandakan selesai.')
            );
        } catch (\Exception $e) {
            Log::error('Failed to complete triage', ['error' => $e->getMessage()]);

            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Get('/patient/{patient}', name: 'admin.triage.patient')]
    public function patientHistory(Patient $patient)
    {
        $assessments = TriageAssessment::with(['assessedBy', 'reviewedBy'])
            ->where('patient_id', $patient->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $severityLevels = TriageAssessment::severityLevels();

        return view('admin.triage.patient', compact('patient', 'assessments', 'severityLevels'));
    }
}
