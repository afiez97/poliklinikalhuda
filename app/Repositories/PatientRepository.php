<?php

namespace App\Repositories;

use App\Models\Patient;
use Illuminate\Support\Collection;

class PatientRepository
{
    protected Patient $model;

    public function __construct(Patient $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->newQuery()->whereNull('deleted_at')->get();
    }

    public function findById(int $id): ?Patient
    {
        return $this->model->find($id);
    }

    public function delete(int $id): bool
    {
        $patient = $this->findById($id);
        if (!$patient) {
            return false;
        }
        return $patient->delete();
    }
}
