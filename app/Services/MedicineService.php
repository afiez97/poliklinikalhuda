<?php

namespace App\Services;

use App\Models\Medicine;
use App\Repositories\MedicineRepository;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class MedicineService
{
    protected MedicineRepository $repository;

    public function __construct(MedicineRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllMedicines(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage);
    }

    public function searchMedicines(string $search, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->search($search, $perPage);
    }

    public function getMedicineById(int $id): ?Medicine
    {
        return $this->repository->findById($id);
    }

    public function createMedicine(array $data): Medicine
    {
        return $this->repository->create($data);
    }

    public function updateMedicine(int $id, array $data): bool
    {
        return $this->repository->update($id, $data);
    }

    public function deleteMedicine(int $id): bool
    {
        return $this->repository->delete($id);
    }

    public function getLowStockMedicines(): Collection
    {
        return $this->repository->getLowStock();
    }

    public function getExpiringSoonMedicines(): Collection
    {
        return $this->repository->getExpiringSoon();
    }

    public function getExpiredMedicines(): Collection
    {
        return $this->repository->getExpired();
    }

    public function getMedicinesByCategory(): Collection
    {
        return $this->repository->getMedicinesByCategory();
    }

    public function addStock(int $id, int $quantity): bool
    {
        $medicine = $this->repository->findById($id);

        if (!$medicine) {
            return false;
        }

        return $this->repository->update($id, [
            'stock_quantity' => $medicine->stock_quantity + $quantity,
        ]);
    }

    public function reduceStock(int $id, int $quantity): bool
    {
        $medicine = $this->repository->findById($id);

        if (!$medicine || $medicine->stock_quantity < $quantity) {
            return false;
        }

        return $this->repository->update($id, [
            'stock_quantity' => $medicine->stock_quantity - $quantity,
        ]);
    }

    public function getInventoryStats(): array
    {
        return [
            'total_medicines' => $this->repository->count(),
            'low_stock_count' => $this->repository->getLowStock()->count(),
            'expiring_soon_count' => $this->repository->getExpiringSoon()->count(),
            'expired_count' => $this->repository->getExpired()->count(),
            'total_value' => $this->repository->getTotalInventoryValue(),
        ];
    }
}
