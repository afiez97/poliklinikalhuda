<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEncounterRequest;
use App\Http\Requests\StoreDiagnosisRequest;
use App\Http\Requests\StoreVitalSignRequest;
use App\Http\Requests\UpdateEncounterRequest;
use App\Models\ClinicalTemplate;
use App\Models\Diagnosis;
use App\Models\Encounter;
use App\Models\Patient;
use App\Models\PatientVisit;
use App\Models\Staff;
use App\Models\VitalSign;
use App\Services\EmrService;
use App\Traits\HandlesApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Patch;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('admin/emr/encounters')]
#[Middleware(['web', 'auth'])]
class EncounterController extends Controller
{
    use HandlesApiResponses;

    public function __construct(
        protected EmrService $emrService
    ) {}

    /**
     * Display a listing of encounters.
     */
    #[Get('/', name: 'admin.emr.encounters.index')]
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->input('search'),
            'patient_id' => $request->input('patient_id'),
            'doctor_id' => $request->input('doctor_id'),
            'status' => $request->input('status'),
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
        ];

        $encounters = $this->emrService->getEncounters(
            filters: array_filter($filters),
            perPage: $request->input('per_page', 25)
        );

        $statistics = $this->emrService->getStatistics();
        $doctors = Staff::active()->doctors()->get();
        $statuses = Encounter::STATUSES;

        return view('admin.emr.encounters.index', compact(
            'encounters',
            'statistics',
            'doctors',
            'statuses',
            'filters'
        ));
    }

    /**
     * Show the form for creating a new encounter.
     */
    #[Get('/create', name: 'admin.emr.encounters.create')]
    public function create(Request $request)
    {
        $patient = null;
        $patientVisit = null;

        if ($request->has('patient_id')) {
            $patient = Patient::findOrFail($request->patient_id);
        }

        if ($request->has('visit_id')) {
            $patientVisit = PatientVisit::with('patient')->findOrFail($request->visit_id);
            $patient = $patientVisit->patient;
        }

        $doctors = Staff::active()->doctors()->get();
        $templates = ClinicalTemplate::where('is_active', true)->orderBy('name')->get();

        // Get patient's recent history if patient is selected
        $patientHistory = [];
        if ($patient) {
            $patientHistory = [
                'encounters' => $this->emrService->getPatientHistory($patient->id, 5),
                'chronic_conditions' => $this->emrService->getPatientChronicConditions($patient->id),
                'last_vital_signs' => VitalSign::where('patient_id', $patient->id)
                    ->latest('recorded_at')
                    ->first(),
            ];
        }

        return view('admin.emr.encounters.create', compact(
            'patient',
            'patientVisit',
            'doctors',
            'templates',
            'patientHistory'
        ));
    }

    /**
     * Store a newly created encounter.
     */
    #[Post('/', name: 'admin.emr.encounters.store')]
    public function store(StoreEncounterRequest $request)
    {
        try {
            $encounter = $this->emrService->createEncounter(
                data: $request->validated(),
                createdBy: auth()->id()
            );

            // If vital signs are provided, record them
            if ($request->has('vital_signs') && ! empty(array_filter($request->vital_signs))) {
                $this->emrService->recordVitalSigns(
                    $encounter,
                    $request->vital_signs,
                    auth()->id()
                );
            }

            return $this->successRedirect(
                'admin.emr.encounters.show',
                __('Encounter berjaya dicipta: :no', ['no' => $encounter->encounter_no]),
                ['encounter' => $encounter->id]
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    /**
     * Display the specified encounter.
     */
    #[Get('/{encounter}', name: 'admin.emr.encounters.show')]
    public function show(Encounter $encounter)
    {
        $encounter->load([
            'patient',
            'doctor',
            'patientVisit',
            'vitalSigns' => fn ($q) => $q->latest('recorded_at'),
            'diagnoses' => fn ($q) => $q->orderBy('sort_order'),
            'prescriptions',
            'clinicalNotes',
            'procedures',
            'attachments',
            'referrals',
            'completedBy',
        ]);

        // Get patient history
        $patientHistory = [
            'recent_encounters' => $this->emrService->getPatientHistory($encounter->patient_id, 5),
            'chronic_conditions' => $this->emrService->getPatientChronicConditions($encounter->patient_id),
            'vital_history' => $this->emrService->getPatientVitalHistory($encounter->patient_id, 10),
        ];

        return view('admin.emr.encounters.show', compact('encounter', 'patientHistory'));
    }

    /**
     * Show the form for editing the encounter.
     */
    #[Get('/{encounter}/edit', name: 'admin.emr.encounters.edit')]
    public function edit(Encounter $encounter)
    {
        // Only allow editing if not completed
        if ($encounter->status === 'completed') {
            return $this->errorRedirect('Encounter yang telah selesai tidak boleh diubah.');
        }

        $encounter->load(['patient', 'doctor', 'patientVisit', 'vitalSigns', 'diagnoses']);

        $doctors = Staff::active()->doctors()->get();
        $templates = ClinicalTemplate::where('is_active', true)->orderBy('name')->get();

        $patientHistory = [
            'encounters' => $this->emrService->getPatientHistory($encounter->patient_id, 5),
            'chronic_conditions' => $this->emrService->getPatientChronicConditions($encounter->patient_id),
        ];

        return view('admin.emr.encounters.edit', compact(
            'encounter',
            'doctors',
            'templates',
            'patientHistory'
        ));
    }

    /**
     * Update the specified encounter.
     */
    #[Patch('/{encounter}', name: 'admin.emr.encounters.update')]
    public function update(UpdateEncounterRequest $request, Encounter $encounter)
    {
        // Only allow editing if not completed
        if ($encounter->status === 'completed') {
            return $this->errorRedirect('Encounter yang telah selesai tidak boleh diubah.');
        }

        try {
            $this->emrService->updateEncounter(
                $encounter,
                $request->validated(),
                auth()->id()
            );

            return $this->successRedirect(
                'admin.emr.encounters.show',
                __('Encounter berjaya dikemaskini.'),
                ['encounter' => $encounter->id]
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    /**
     * Delete the encounter.
     */
    #[Delete('/{encounter}', name: 'admin.emr.encounters.destroy')]
    public function destroy(Encounter $encounter)
    {
        // Only allow deleting draft encounters
        if ($encounter->status !== 'draft') {
            return $this->errorRedirect('Hanya encounter dengan status draf boleh dipadam.');
        }

        try {
            $encounterNo = $encounter->encounter_no;
            $this->emrService->deleteEncounter($encounter);

            return $this->successRedirect(
                'admin.emr.encounters.index',
                __('Encounter :no berjaya dipadam.', ['no' => $encounterNo])
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    /**
     * Start the encounter.
     */
    #[Post('/{encounter}/start', name: 'admin.emr.encounters.start')]
    public function start(Encounter $encounter)
    {
        if (! in_array($encounter->status, ['draft'])) {
            return $this->errorRedirect('Encounter ini tidak boleh dimulakan.');
        }

        try {
            $this->emrService->startEncounter($encounter);

            return $this->successRedirect(
                'admin.emr.encounters.edit',
                __('Encounter dimulakan.'),
                ['encounter' => $encounter->id]
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    /**
     * Complete the encounter.
     */
    #[Post('/{encounter}/complete', name: 'admin.emr.encounters.complete')]
    public function complete(Encounter $encounter)
    {
        if ($encounter->status === 'completed') {
            return $this->errorRedirect('Encounter ini telah selesai.');
        }

        // Validate required fields before completing
        if (empty($encounter->chief_complaint)) {
            return $this->errorRedirect('Sila isi aduan utama sebelum menyelesaikan encounter.');
        }

        try {
            $this->emrService->completeEncounter($encounter, auth()->id());

            return $this->successRedirect(
                'admin.emr.encounters.show',
                __('Encounter berjaya diselesaikan.'),
                ['encounter' => $encounter->id]
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    /**
     * Record vital signs for encounter.
     */
    #[Post('/{encounter}/vital-signs', name: 'admin.emr.encounters.vitalSigns.store')]
    public function storeVitalSigns(StoreVitalSignRequest $request, Encounter $encounter)
    {
        try {
            $vitalSign = $this->emrService->recordVitalSigns(
                $encounter,
                $request->validated(),
                auth()->id()
            );

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tanda vital berjaya direkod.',
                    'data' => $vitalSign,
                ]);
            }

            return $this->successRedirect(
                'admin.emr.encounters.edit',
                __('Tanda vital berjaya direkod.'),
                ['encounter' => $encounter->id]
            );
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }

            return $this->errorRedirect($e->getMessage());
        }
    }

    /**
     * Add diagnosis to encounter.
     */
    #[Post('/{encounter}/diagnoses', name: 'admin.emr.encounters.diagnoses.store')]
    public function storeDiagnosis(StoreDiagnosisRequest $request, Encounter $encounter)
    {
        try {
            $diagnosis = $this->emrService->addDiagnosis(
                $encounter,
                $request->validated()
            );

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Diagnosis berjaya ditambah.',
                    'data' => $diagnosis,
                ]);
            }

            return $this->successRedirect(
                'admin.emr.encounters.edit',
                __('Diagnosis berjaya ditambah.'),
                ['encounter' => $encounter->id]
            );
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }

            return $this->errorRedirect($e->getMessage());
        }
    }

    /**
     * Remove diagnosis from encounter.
     */
    #[Delete('/{encounter}/diagnoses/{diagnosis}', name: 'admin.emr.encounters.diagnoses.destroy')]
    public function destroyDiagnosis(Encounter $encounter, Diagnosis $diagnosis)
    {
        if ($diagnosis->encounter_id !== $encounter->id) {
            return $this->errorRedirect('Diagnosis tidak sah.');
        }

        try {
            $this->emrService->removeDiagnosis($diagnosis);

            return $this->successRedirect(
                'admin.emr.encounters.edit',
                __('Diagnosis berjaya dipadam.'),
                ['encounter' => $encounter->id]
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    /**
     * Search ICD-10 codes.
     */
    #[Get('/icd10/search', name: 'admin.emr.icd10.search')]
    public function searchIcd10(Request $request)
    {
        $term = $request->input('q', '');

        if (strlen($term) < 2) {
            return response()->json([]);
        }

        $results = $this->emrService->searchIcd10($term);

        return response()->json($results);
    }

    /**
     * Get doctor's pending encounters.
     */
    #[Get('/pending', name: 'admin.emr.encounters.pending')]
    public function pending(Request $request)
    {
        $doctorId = $request->input('doctor_id');

        // If user is a doctor, only show their encounters
        $user = auth()->user();
        if ($user->hasRole('doctor') && $user->staff) {
            $doctorId = $user->staff->id;
        }

        $encounters = $this->emrService->getPendingEncounters($doctorId);

        return view('admin.emr.encounters.pending', compact('encounters'));
    }

    /**
     * Get today's encounters for the logged-in doctor.
     */
    #[Get('/today', name: 'admin.emr.encounters.today')]
    public function today(Request $request)
    {
        $doctorId = $request->input('doctor_id');

        $user = auth()->user();
        if ($user->hasRole('doctor') && $user->staff) {
            $doctorId = $user->staff->id;
        }

        $encounters = $doctorId
            ? $this->emrService->getTodayEncountersForDoctor($doctorId)
            : Encounter::today()->with(['patient', 'doctor'])->get();

        return view('admin.emr.encounters.today', compact('encounters'));
    }
}
