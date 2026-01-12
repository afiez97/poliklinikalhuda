<?php

namespace App\Repositories;

use App\Models\Medicine;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class MedicineRepository
{
    protected Medicine $model;

    public function __construct(Medicine $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->latest()->paginate($perPage);
    }

    public function search(string $search, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->where(function ($query) use ($search) {
                $query->where('medicine_code', 'LIKE', "%{$search}%")
                    ->orWhere('name', 'LIKE', "%{$search}%")
                    ->orWhere('manufacturer', 'LIKE', "%{$search}%")
                    ->orWhere('batch_number', 'LIKE', "%{$search}%");
            })
            ->latest()
            ->paginate($perPage);
    }

    public function findById(int $id): ?Medicine
    {
        return $this->model->find($id);
    }

    public function create(array $data): Medicine
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $medicine = $this->findById($id);

        if (!$medicine) {
            return false;
        }

        return $medicine->update($data);
    }

    public function delete(int $id): bool
    {
        $medicine = $this->findById($id);

        if (!$medicine) {
            return false;
        }

        return $medicine->delete();
    }

    public function getLowStock(): Collection
    {
        return $this->model->lowStock()->get();
    }

    public function getExpiringSoon(): Collection
    {
        return $this->model->expiringSoon()->get();
    }

    public function getExpired(): Collection
    {
        return $this->model->expired()->get();
    }

    public function getActive(): Collection
    {
        return $this->model->active()->get();
    }

    public function getMedicinesByCategory(): Collection
    {
        return $this->model
            ->select('category', DB::raw('count(*) as total'), DB::raw('sum(stock_quantity) as total_stock'))
            ->where('status', 'active')
            ->groupBy('category')
            ->get();
    }

    public function count(): int
    {
        return $this->model->count();
    }

    public function getTotalInventoryValue(): float
    {
        return (float) $this->model
            ->where('status', 'active')
            ->get()
            ->sum(function ($medicine) {
                return $medicine->total_value;
            });
    }
}
