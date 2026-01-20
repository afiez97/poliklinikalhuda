<?php

namespace App\Services;

use App\Models\Diagnosis;
use App\Models\Encounter;
use App\Models\Patient;
use App\Models\PatientVisit;
use App\Models\VitalSign;
use App\Repositories\EncounterRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class EmrService
{
    public function __construct(
        protected EncounterRepository $repository,
        protected AuditService $auditService
    ) {}

    /**
     * Get paginated encounters with filters.
     */
    public function getEncounters(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($filters, $perPage);
    }

    /**
     * Create new encounter.
     */
    public function createEncounter(array $data, ?int $createdBy = null): Encounter
    {
        return DB::transaction(function () use ($data, $createdBy) {
            // Set default values
            $data['status'] = $data['status'] ?? 'draft';

            $encounter = $this->repository->create($data);

            // If patient visit exists, update its status
            if ($encounter->patient_visit_id) {
                $encounter->patientVisit->startConsultation();
            }

            $this->auditService->logCreate($encounter, 'Encounter baharu dicipta: '.$encounter->encounter_no);

            return $encounter;
        });
    }

    /**
     * Update encounter.
     */
    public function updateEncounter(Encounter $encounter, array $data, ?int $updatedBy = null): Encounter
    {
        return DB::transaction(function () use ($encounter, $data) {
            $oldValues = $encounter->toArray();

            $encounter = $this->repository->update($encounter, $data);

            $this->auditService->logUpdate($encounter, $oldValues, 'Encounter dikemaskini: '.$encounter->encounter_no);

            return $encounter;
        });
    }

    /**
     * Delete encounter (soft delete).
     */
    public function deleteEncounter(Encounter $encounter): bool
    {
        return DB::transaction(function () use ($encounter) {
            $this->auditService->logDelete($encounter, 'Encounter dipadam: '.$encounter->encounter_no);

            return $this->repository->delete($encounter);
        });
    }

    /**
     * Start encounter (change status to in_progress).
     */
    public function startEncounter(Encounter $encounter): Encounter
    {
        return DB::transaction(function () use ($encounter) {
            $oldValues = $encounter->toArray();

            $encounter->start();

            $this->auditService->logUpdate($encounter, $oldValues, 'Encounter dimulakan: '.$encounter->encounter_no);

            return $encounter;
        });
    }

    /**
     * Complete encounter.
     */
    public function completeEncounter(Encounter $encounter, ?int $userId = null): Encounter
    {
        return DB::transaction(function () use ($encounter, $userId) {
            $oldValues = $encounter->toArray();

            $encounter->complete($userId);

            // Update patient visit status
            if ($encounter->patientVisit) {
                $encounter->patientVisit->endConsultation();
            }

            $this->auditService->logUpdate($encounter, $oldValues, 'Encounter diselesaikan: '.$encounter->encounter_no);

            return $encounter;
        });
    }

    /**
     * Record vital signs for encounter.
     */
    public function recordVitalSigns(Encounter $encounter, array $data, ?int $recordedBy = null): VitalSign
    {
        return DB::transaction(function () use ($encounter, $data, $recordedBy) {
            $data['encounter_id'] = $encounter->id;
            $data['patient_id'] = $encounter->patient_id;
            $data['recorded_by'] = $recordedBy ?? auth()->id();
            $data['recorded_at'] = $data['recorded_at'] ?? now();

            $vitalSign = VitalSign::create($data);

            $this->auditService->logCreate($vitalSign, 'Tanda vital direkod untuk encounter: '.$encounter->encounter_no);

            return $vitalSign;
        });
    }

    /**
     * Update vital signs.
     */
    public function updateVitalSigns(VitalSign $vitalSign, array $data): VitalSign
    {
        return DB::transaction(function () use ($vitalSign, $data) {
            $oldValues = $vitalSign->toArray();

            $vitalSign->update($data);

            $this->auditService->logUpdate($vitalSign, $oldValues, 'Tanda vital dikemaskini');

            return $vitalSign->fresh();
        });
    }

    /**
     * Add diagnosis to encounter.
     */
    public function addDiagnosis(Encounter $encounter, array $data): Diagnosis
    {
        return DB::transaction(function () use ($encounter, $data) {
            $data['encounter_id'] = $encounter->id;
            $data['patient_id'] = $encounter->patient_id;

            // Set sort order
            if (! isset($data['sort_order'])) {
                $maxSort = Diagnosis::where('encounter_id', $encounter->id)->max('sort_order');
                $data['sort_order'] = ($maxSort ?? 0) + 1;
            }

            $diagnosis = Diagnosis::create($data);

            $this->auditService->logCreate($diagnosis, 'Diagnosis ditambah: '.$diagnosis->diagnosis_text);

            return $diagnosis;
        });
    }

    /**
     * Update diagnosis.
     */
    public function updateDiagnosis(Diagnosis $diagnosis, array $data): Diagnosis
    {
        return DB::transaction(function () use ($diagnosis, $data) {
            $oldValues = $diagnosis->toArray();

            $diagnosis->update($data);

            $this->auditService->logUpdate($diagnosis, $oldValues, 'Diagnosis dikemaskini');

            return $diagnosis->fresh();
        });
    }

    /**
     * Remove diagnosis.
     */
    public function removeDiagnosis(Diagnosis $diagnosis): bool
    {
        return DB::transaction(function () use ($diagnosis) {
            $this->auditService->logDelete($diagnosis, 'Diagnosis dipadam: '.$diagnosis->diagnosis_text);

            return $diagnosis->delete();
        });
    }

    /**
     * Get patient's encounter history.
     */
    public function getPatientHistory(int $patientId, int $limit = 10)
    {
        return Encounter::where('patient_id', $patientId)
            ->with(['doctor', 'diagnoses', 'prescriptions'])
            ->byStatus('completed')
            ->latest('encounter_date')
            ->limit($limit)
            ->get();
    }

    /**
     * Get patient's vital signs history.
     */
    public function getPatientVitalHistory(int $patientId, int $limit = 20)
    {
        return VitalSign::where('patient_id', $patientId)
            ->with('recordedBy')
            ->latest('recorded_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Get patient's diagnosis history.
     */
    public function getPatientDiagnosisHistory(int $patientId)
    {
        return Diagnosis::where('patient_id', $patientId)
            ->with('encounter')
            ->latest('created_at')
            ->get();
    }

    /**
     * Get patient's chronic conditions.
     */
    public function getPatientChronicConditions(int $patientId)
    {
        return Diagnosis::where('patient_id', $patientId)
            ->chronic()
            ->with('encounter')
            ->get();
    }

    /**
     * Get today's encounters for doctor.
     */
    public function getTodayEncountersForDoctor(int $doctorId)
    {
        return Encounter::byDoctor($doctorId)
            ->today()
            ->with(['patient', 'patientVisit'])
            ->latest('encounter_date')
            ->get();
    }

    /**
     * Get encounter statistics.
     */
    public function getStatistics(int $days = 7): array
    {
        $startDate = now()->subDays($days);

        return [
            'total_encounters' => Encounter::where('created_at', '>=', $startDate)->count(),
            'completed' => Encounter::byStatus('completed')->where('created_at', '>=', $startDate)->count(),
            'in_progress' => Encounter::byStatus('in_progress')->where('created_at', '>=', $startDate)->count(),
            'draft' => Encounter::byStatus('draft')->where('created_at', '>=', $startDate)->count(),
            'today' => Encounter::today()->count(),
            'avg_duration' => $this->getAverageConsultationDuration($startDate),
        ];
    }

    /**
     * Get average consultation duration in minutes.
     */
    protected function getAverageConsultationDuration($startDate): ?float
    {
        $encounters = Encounter::where('created_at', '>=', $startDate)
            ->whereNotNull('started_at')
            ->whereNotNull('completed_at')
            ->get();

        if ($encounters->isEmpty()) {
            return null;
        }

        $totalMinutes = $encounters->sum(fn ($e) => $e->duration_minutes ?? 0);

        return round($totalMinutes / $encounters->count(), 1);
    }

    /**
     * Get pending encounters (draft or in_progress).
     */
    public function getPendingEncounters(?int $doctorId = null)
    {
        $query = Encounter::whereIn('status', ['draft', 'in_progress'])
            ->with(['patient', 'patientVisit', 'doctor'])
            ->latest('encounter_date');

        if ($doctorId) {
            $query->byDoctor($doctorId);
        }

        return $query->get();
    }

    /**
     * Search ICD-10 codes.
     */
    public function searchIcd10(string $term, int $limit = 20)
    {
        return DB::table('icd10_codes')
            ->where(function ($query) use ($term) {
                $query->where('code', 'like', "%{$term}%")
                    ->orWhere('description_en', 'like', "%{$term}%")
                    ->orWhere('description_my', 'like', "%{$term}%");
            })
            ->where('is_active', true)
            ->limit($limit)
            ->get();
    }
}
