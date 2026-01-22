<?php

namespace App\Services;

use App\Models\Patient;
use App\Repositories\PatientRepository;

class PatientService
{
    protected PatientRepository $repository;

    public function __construct(PatientRepository $repository)
    {
        $this->repository = $repository;
    }

    public function deletePatient(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
