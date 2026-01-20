<?php

namespace App\Repositories;

use App\Models\Encounter;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EncounterRepository
{
    public function __construct(protected Encounter $model) {}

    /**
     * Get paginated encounters with filters.
     */
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->with(['patient', 'doctor', 'patientVisit', 'diagnoses']);

        if (! empty($filters['patient_id'])) {
            $query->where('patient_id', $filters['patient_id']);
        }

        if (! empty($filters['doctor_id'])) {
            $query->where('doctor_id', $filters['doctor_id']);
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['date_from'])) {
            $query->whereDate('encounter_date', '>=', $filters['date_from']);
        }

        if (! empty($filters['date_to'])) {
            $query->whereDate('encounter_date', '<=', $filters['date_to']);
        }

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('encounter_no', 'like', "%{$search}%")
                    ->orWhere('chief_complaint', 'like', "%{$search}%")
                    ->orWhereHas('patient', function ($pq) use ($search) {
                        $pq->where('name', 'like', "%{$search}%")
                            ->orWhere('mrn', 'like', "%{$search}%")
                            ->orWhere('ic_number', 'like', "%{$search}%");
                    });
            });
        }

        return $query->latest('encounter_date')->paginate($perPage);
    }

    /**
     * Find encounter by ID.
     */
    public function findById(int $id): ?Encounter
    {
        return $this->model->with([
            'patient',
            'doctor',
            'patientVisit',
            'vitalSigns',
            'diagnoses',
            'prescriptions',
            'clinicalNotes',
            'procedures',
            'attachments',
            'referrals',
        ])->find($id);
    }

    /**
     * Find encounter by encounter number.
     */
    public function findByEncounterNo(string $encounterNo): ?Encounter
    {
        return $this->model->where('encounter_no', $encounterNo)->first();
    }

    /**
     * Create new encounter.
     */
    public function create(array $data): Encounter
    {
        return $this->model->create($data);
    }

    /**
     * Update encounter.
     */
    public function update(Encounter $encounter, array $data): Encounter
    {
        $encounter->update($data);

        return $encounter->fresh();
    }

    /**
     * Delete encounter (soft delete).
     */
    public function delete(Encounter $encounter): bool
    {
        return $encounter->delete();
    }

    /**
     * Get encounters by patient.
     */
    public function getByPatient(int $patientId, int $limit = 50): Collection
    {
        return $this->model
            ->where('patient_id', $patientId)
            ->with(['doctor', 'diagnoses'])
            ->latest('encounter_date')
            ->limit($limit)
            ->get();
    }

    /**
     * Get encounters by doctor.
     */
    public function getByDoctor(int $doctorId, int $limit = 50): Collection
    {
        return $this->model
            ->where('doctor_id', $doctorId)
            ->with(['patient', 'diagnoses'])
            ->latest('encounter_date')
            ->limit($limit)
            ->get();
    }

    /**
     * Get encounters by status.
     */
    public function getByStatus(string $status, int $limit = 50): Collection
    {
        return $this->model
            ->where('status', $status)
            ->with(['patient', 'doctor'])
            ->latest('encounter_date')
            ->limit($limit)
            ->get();
    }

    /**
     * Get today's encounters.
     */
    public function getToday(?int $doctorId = null): Collection
    {
        $query = $this->model->today()
            ->with(['patient', 'doctor', 'patientVisit']);

        if ($doctorId) {
            $query->where('doctor_id', $doctorId);
        }

        return $query->latest('encounter_date')->get();
    }

    /**
     * Get pending encounters (draft or in_progress).
     */
    public function getPending(?int $doctorId = null): Collection
    {
        $query = $this->model
            ->whereIn('status', ['draft', 'in_progress'])
            ->with(['patient', 'doctor', 'patientVisit']);

        if ($doctorId) {
            $query->where('doctor_id', $doctorId);
        }

        return $query->latest('encounter_date')->get();
    }

    /**
     * Count encounters by status.
     */
    public function countByStatus(): array
    {
        return $this->model
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }

    /**
     * Check if patient has active encounter.
     */
    public function hasActiveEncounter(int $patientId): bool
    {
        return $this->model
            ->where('patient_id', $patientId)
            ->whereIn('status', ['draft', 'in_progress'])
            ->exists();
    }

    /**
     * Get patient's last encounter.
     */
    public function getLastForPatient(int $patientId): ?Encounter
    {
        return $this->model
            ->where('patient_id', $patientId)
            ->latest('encounter_date')
            ->first();
    }
}
