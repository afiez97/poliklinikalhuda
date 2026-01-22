<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Models\Patient;
use App\Models\PatientVisit;
use App\Services\AuditService;
use App\Services\PatientService;
use App\Traits\HandlesApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Patch;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('admin/patients')]
#[Middleware(['web', 'auth'])]
class PatientController extends Controller
{
    use HandlesApiResponses;

    public function __construct(
        protected AuditService $auditService,
        protected PatientService $service
    ) {}

    #[Get('/', name: 'admin.patients.index')]
    public function index(Request $request)
    {
        $query = Patient::query()
            ->when($request->search, fn ($q, $search) => $q->search($search))
            ->when($request->status, fn ($q, $status) => $q->where('status', $status))
            ->when($request->has_panel !== null, fn ($q) => $q->where('has_panel', $request->has_panel === '1'))
            ->when($request->gender, fn ($q, $gender) => $q->where('gender', $gender));

        $patients = $query->orderBy('created_at', 'desc')->paginate(25)->withQueryString();

        $statistics = [
            'total' => Patient::count(),
            'active' => Patient::active()->count(),
            'panel' => Patient::panel()->count(),
            'today' => Patient::whereDate('created_at', today())->count(),
        ];

        return view('admin.patients.index', compact('patients', 'statistics'));
    }

    #[Get('/create', name: 'admin.patients.create')]
    public function create()
    {
        $newMrn = Patient::generateMrn();

        return view('admin.patients.create', compact('newMrn'));
    }

    #[Post('/', name: 'admin.patients.store')]
    public function store(StorePatientRequest $request)
    {
        $validated = $request->validated();

        try {
            DB::beginTransaction();

            $validated['registered_by'] = auth()->id();

            if ($validated['pdpa_consent']) {
                $validated['pdpa_consent_at'] = now();
                $validated['pdpa_consent_by'] = auth()->user()->name;
            }

            $patient = Patient::create($validated);

            $this->auditService->log(
                'create',
                "Patient registered: {$patient->mrn} - {$patient->name}",
                $patient,
                metadata: ['patient_id' => $patient->id]
            );

            DB::commit();

            return $this->successRedirect(
                'admin.patients.index',
                __('Pesakit berjaya didaftarkan. MRN: :mrn', ['mrn' => $patient->mrn])
            );
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to register patient', ['error' => $e->getMessage()]);

            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Get('/{patient}', name: 'admin.patients.show')]
    public function show(Patient $patient)
    {
        $patient->load(['visits' => fn ($q) => $q->latest('visit_date')->limit(10), 'documents', 'registrar']);

        $visitStats = [
            'total' => $patient->visits()->count(),
            'this_year' => $patient->visits()->whereYear('visit_date', now()->year)->count(),
            'last_visit' => $patient->visits()->latest('visit_date')->first()?->visit_date,
        ];

        return view('admin.patients.show', compact('patient', 'visitStats'));
    }

    #[Get('/{patient}/edit', name: 'admin.patients.edit')]
    public function edit(Patient $patient)
    {
        return view('admin.patients.edit', compact('patient'));
    }

    #[Patch('/{patient}', name: 'admin.patients.update')]
    public function update(UpdatePatientRequest $request, Patient $patient)
    {
        $validated = $request->validated();

        try {
            $oldData = $patient->toArray();
            $patient->update($validated);

            $this->auditService->log(
                'update',
                "Patient updated: {$patient->mrn}",
                $patient,
                $oldData,
                $patient->toArray()
            );

            return $this->successRedirect(
                'admin.patients.index',
                __('Maklumat pesakit berjaya dikemaskini.')
            );
        } catch (\Exception $e) {
            Log::error('Failed to update patient', ['error' => $e->getMessage()]);

            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Delete('/{patient}', name: 'admin.patients.destroy')]
    public function destroy(Patient $patient)
    {
        try {
            $mrn = $patient->mrn;
            $this->service->deletePatient($patient->id);

            Log::info('Patient deleted successfully', ['id' => $patient->id, 'mrn' => $mrn]);

            return $this->successRedirect(
                'admin.patients.index',
                __('Pesakit berjaya dipadam.')
            );
        } catch (\Exception $e) {
            Log::error('Failed to delete patient', [
                'id' => $patient->id,
                'error' => $e->getMessage()
            ]);
            return $this->errorRedirect(__('Gagal memadam pesakit.'));
        }
    }

    #[Get('/search', name: 'admin.patients.search')]
    public function search(Request $request)
    {
        $term = $request->input('q');

        if (strlen($term) < 2) {
            return response()->json([]);
        }

        $patients = Patient::search($term)
            ->active()
            ->select('id', 'mrn', 'name', 'ic_number', 'phone', 'date_of_birth', 'gender')
            ->limit(15)
            ->get()
            ->map(fn ($p) => [
                'id' => $p->id,
                'mrn' => $p->mrn,
                'name' => $p->name,
                'ic_number' => $p->ic_number,
                'phone' => $p->phone,
                'age' => $p->formatted_age,
                'gender' => $p->gender_label,
            ]);

        return response()->json($patients);
    }

    #[Get('/search/quick', name: 'admin.patients.quickSearch')]
    public function quickSearch(Request $request)
    {
        $term = $request->input('q');

        if (strlen($term) < 2) {
            return response()->json([]);
        }

        $patients = Patient::search($term)
            ->active()
            ->select('id', 'mrn', 'name', 'ic_number', 'phone', 'date_of_birth', 'gender')
            ->limit(10)
            ->get()
            ->map(fn ($p) => [
                'id' => $p->id,
                'mrn' => $p->mrn,
                'name' => $p->name,
                'ic_number' => $p->ic_number,
                'phone' => $p->phone,
                'age' => $p->formatted_age,
                'gender' => $p->gender_label,
            ]);

        return response()->json($patients);
    }

    #[Post('/{patient}/register-visit', name: 'admin.patients.registerVisit')]
    public function registerVisit(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'visit_type' => 'required|in:walk_in,appointment,emergency,follow_up,referral',
            'priority' => 'required|in:normal,urgent,emergency',
            'chief_complaint' => 'nullable|string|max:1000',
            'doctor_id' => 'nullable|exists:staff,id',
            'is_panel' => 'boolean',
        ]);

        try {
            DB::beginTransaction();

            // Generate queue number
            $queuePrefix = match ($validated['priority']) {
                'emergency' => 'E',
                'urgent' => 'U',
                default => 'A',
            };

            $lastQueue = PatientVisit::whereDate('visit_date', today())
                ->where('queue_prefix', $queuePrefix)
                ->max('queue_number');

            $visit = PatientVisit::create([
                'patient_id' => $patient->id,
                'visit_date' => today(),
                'check_in_time' => now()->format('H:i:s'),
                'visit_type' => $validated['visit_type'],
                'priority' => $validated['priority'],
                'queue_prefix' => $queuePrefix,
                'queue_number' => ($lastQueue ?? 0) + 1,
                'status' => 'waiting',
                'chief_complaint' => $validated['chief_complaint'],
                'doctor_id' => $validated['doctor_id'],
                'is_panel' => $validated['is_panel'] ?? $patient->has_panel,
                'registered_by' => auth()->id(),
            ]);

            $this->auditService->log(
                'create',
                "Visit registered: {$visit->visit_no} for {$patient->mrn}",
                $visit
            );

            DB::commit();

            return $this->successRedirect(
                'admin.patients.show',
                __('Lawatan berjaya didaftarkan. No. Giliran: :queue', ['queue' => $visit->full_queue_number]),
                ['patient' => $patient->id]
            );
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to register visit', ['error' => $e->getMessage()]);

            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Get('/{patient}/visits', name: 'admin.patients.visits')]
    public function visits(Patient $patient)
    {
        $visits = $patient->visits()
            ->with(['doctor.user', 'registrar'])
            ->orderBy('visit_date', 'desc')
            ->paginate(25);

        return view('admin.patients.visits', compact('patient', 'visits'));
    }
}
