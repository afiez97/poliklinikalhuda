<?php

namespace App\Services;

use App\Models\DispensingItem;
use App\Models\DispensingRecord;
use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\MedicineCategory;
use App\Models\MedicineStockMovement;
use App\Models\PoisonRegister;
use App\Models\Prescription;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class PharmacyService
{
    public function __construct(
        protected AuditService $auditService
    ) {}

    // ==================== MEDICINE METHODS ====================

    public function getMedicines(array $filters = [], int $perPage = 25): LengthAwarePaginator
    {
        $query = Medicine::with(['category'])
            ->when(isset($filters['search']), function ($q) use ($filters) {
                $search = $filters['search'];
                $q->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('name_generic', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('barcode', 'like', "%{$search}%");
                });
            })
            ->when(isset($filters['category_id']), fn ($q) => $q->where('category_id', $filters['category_id']))
            ->when(isset($filters['dosage_form']), fn ($q) => $q->where('dosage_form', $filters['dosage_form']))
            ->when(isset($filters['stock_status']), function ($q) use ($filters) {
                match ($filters['stock_status']) {
                    'low' => $q->lowStock(),
                    'out_of_stock' => $q->where('stock_quantity', '<=', 0),
                    'available' => $q->where('stock_quantity', '>', 0),
                    default => $q,
                };
            })
            ->when(isset($filters['is_controlled']), fn ($q) => $q->where('is_controlled', $filters['is_controlled']))
            ->when(isset($filters['is_active']), fn ($q) => $q->where('is_active', $filters['is_active']))
            ->latest();

        return $query->paginate($perPage)->withQueryString();
    }

    public function createMedicine(array $data, ?int $createdBy = null): Medicine
    {
        return DB::transaction(function () use ($data, $createdBy) {
            $data['code'] = $data['code'] ?? Medicine::generateCode();
            $data['created_by'] = $createdBy;

            $medicine = Medicine::create($data);

            $this->auditService->log(
                'create',
                "Ubat dicipta: {$medicine->name}",
                $medicine,
                metadata: ['code' => $medicine->code]
            );

            return $medicine;
        });
    }

    public function updateMedicine(Medicine $medicine, array $data, ?int $updatedBy = null): Medicine
    {
        return DB::transaction(function () use ($medicine, $data, $updatedBy) {
            $oldData = $medicine->toArray();
            $data['updated_by'] = $updatedBy;

            $medicine->update($data);

            $this->auditService->log(
                'update',
                "Ubat dikemaskini: {$medicine->name}",
                $medicine,
                metadata: ['old_data' => $oldData]
            );

            return $medicine->fresh();
        });
    }

    public function deleteMedicine(Medicine $medicine): void
    {
        DB::transaction(function () use ($medicine) {
            $this->auditService->log(
                'delete',
                "Ubat dipadam: {$medicine->name}",
                $medicine
            );

            $medicine->delete();
        });
    }

    // ==================== STOCK MANAGEMENT ====================

    public function adjustStock(
        Medicine $medicine,
        int $quantity,
        string $movementType,
        ?string $reason = null,
        ?int $createdBy = null,
        ?string $batchNo = null
    ): MedicineStockMovement {
        return DB::transaction(function () use ($medicine, $quantity, $movementType, $reason, $createdBy, $batchNo) {
            $stockBefore = $medicine->stock_quantity;

            // Calculate new stock based on movement type
            $stockAfter = match ($movementType) {
                'in', 'return' => $stockBefore + $quantity,
                'out', 'expired', 'damaged' => $stockBefore - $quantity,
                'adjustment' => $stockBefore + $quantity, // quantity can be negative
                default => $stockBefore,
            };

            // Update medicine stock
            $medicine->update(['stock_quantity' => max(0, $stockAfter)]);

            // Create stock movement record
            $movement = MedicineStockMovement::create([
                'reference_no' => MedicineStockMovement::generateReferenceNo(),
                'medicine_id' => $medicine->id,
                'movement_type' => $movementType,
                'quantity' => abs($quantity),
                'stock_before' => $stockBefore,
                'stock_after' => $medicine->stock_quantity,
                'batch_no' => $batchNo,
                'reason' => $reason,
                'created_by' => $createdBy,
            ]);

            $this->auditService->log(
                'stock_adjustment',
                "Stok ubat diselaraskan: {$medicine->name}",
                $medicine,
                metadata: [
                    'movement_type' => $movementType,
                    'quantity' => $quantity,
                    'stock_before' => $stockBefore,
                    'stock_after' => $medicine->stock_quantity,
                ]
            );

            return $movement;
        });
    }

    public function receiveStock(
        Medicine $medicine,
        int $quantity,
        array $data,
        ?int $createdBy = null
    ): MedicineStockMovement {
        return DB::transaction(function () use ($medicine, $quantity, $data, $createdBy) {
            $stockBefore = $medicine->stock_quantity;
            $stockAfter = $stockBefore + $quantity;

            $medicine->update(['stock_quantity' => $stockAfter]);

            // Create batch if batch info provided
            if (! empty($data['batch_no'])) {
                MedicineBatch::create([
                    'medicine_id' => $medicine->id,
                    'batch_no' => $data['batch_no'],
                    'expiry_date' => $data['expiry_date'] ?? null,
                    'initial_quantity' => $quantity,
                    'current_quantity' => $quantity,
                    'cost_price' => $data['unit_cost'] ?? $medicine->cost_price,
                    'supplier_id' => $data['supplier_id'] ?? null,
                    'purchase_order_id' => $data['purchase_order_id'] ?? null,
                    'created_by' => $createdBy,
                ]);
            }

            $movement = MedicineStockMovement::create([
                'reference_no' => MedicineStockMovement::generateReferenceNo(),
                'medicine_id' => $medicine->id,
                'movement_type' => 'in',
                'quantity' => $quantity,
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'unit_cost' => $data['unit_cost'] ?? null,
                'total_cost' => ($data['unit_cost'] ?? 0) * $quantity,
                'batch_no' => $data['batch_no'] ?? null,
                'expiry_date' => $data['expiry_date'] ?? null,
                'supplier_id' => $data['supplier_id'] ?? null,
                'source_type' => $data['source_type'] ?? 'manual',
                'source_id' => $data['source_id'] ?? null,
                'notes' => $data['notes'] ?? null,
                'created_by' => $createdBy,
            ]);

            return $movement;
        });
    }

    public function getLowStockMedicines(): Collection
    {
        return Medicine::active()->lowStock()->with('category')->get();
    }

    public function getExpiringSoonMedicines(int $days = 90): Collection
    {
        return Medicine::active()->expiringSoon($days)->with('category')->get();
    }

    public function getExpiredMedicines(): Collection
    {
        return Medicine::active()->expired()->with('category')->get();
    }

    // ==================== DISPENSING ====================

    public function createDispensingFromPrescription(
        Prescription $prescription,
        int $dispensedBy
    ): DispensingRecord {
        return DB::transaction(function () use ($prescription, $dispensedBy) {
            $record = DispensingRecord::create([
                'dispensing_no' => DispensingRecord::generateDispensingNo(),
                'encounter_id' => $prescription->encounter_id,
                'prescription_id' => $prescription->id,
                'patient_id' => $prescription->patient_id,
                'dispensed_by' => $dispensedBy,
                'dispensed_at' => now(),
                'status' => DispensingRecord::STATUS_PENDING,
            ]);

            // Create dispensing items from prescription items
            foreach ($prescription->items as $item) {
                $medicine = Medicine::find($item->medicine_id);
                if ($medicine) {
                    DispensingItem::create([
                        'dispensing_record_id' => $record->id,
                        'medicine_id' => $medicine->id,
                        'prescription_item_id' => $item->id,
                        'quantity_prescribed' => $item->quantity,
                        'quantity_dispensed' => 0,
                        'unit_price' => $medicine->selling_price,
                        'total_price' => 0,
                        'dosage_instructions' => $item->dosage_instructions,
                    ]);
                }
            }

            return $record->load('items.medicine');
        });
    }

    public function dispenseItem(
        DispensingItem $item,
        int $quantity,
        ?string $batchNo = null,
        ?int $dispensedBy = null
    ): DispensingItem {
        return DB::transaction(function () use ($item, $quantity, $batchNo, $dispensedBy) {
            $medicine = $item->medicine;

            // Check stock availability
            if ($medicine->stock_quantity < $quantity) {
                throw new \Exception("Stok tidak mencukupi untuk {$medicine->name}");
            }

            // Deduct stock
            $this->adjustStock(
                $medicine,
                $quantity,
                'out',
                "Dispens kepada pesakit - {$item->dispensingRecord->dispensing_no}",
                $dispensedBy,
                $batchNo
            );

            // Update batch quantity if batch specified
            if ($batchNo) {
                $batch = MedicineBatch::where('medicine_id', $medicine->id)
                    ->where('batch_no', $batchNo)
                    ->first();
                if ($batch) {
                    $batch->decrement('current_quantity', $quantity);
                    $batch->updateStatus();
                }
            }

            // Update dispensing item
            $item->update([
                'quantity_dispensed' => $item->quantity_dispensed + $quantity,
                'batch_no' => $batchNo,
                'total_price' => $item->unit_price * ($item->quantity_dispensed + $quantity),
            ]);

            // Record in poison register if controlled medicine
            if ($medicine->is_controlled) {
                $this->recordPoisonRegister($item, $quantity, $dispensedBy);
            }

            // Update dispensing record status
            $this->updateDispensingRecordStatus($item->dispensingRecord);

            return $item->fresh();
        });
    }

    protected function recordPoisonRegister(
        DispensingItem $item,
        int $quantity,
        ?int $recordedBy = null
    ): PoisonRegister {
        $medicine = $item->medicine;
        $patient = $item->dispensingRecord->patient;
        $prescription = $item->dispensingRecord->prescription;

        $balanceBefore = $medicine->stock_quantity + $quantity; // Before deduction
        $balanceAfter = $medicine->stock_quantity;

        return PoisonRegister::create([
            'register_no' => PoisonRegister::generateRegisterNo(),
            'medicine_id' => $medicine->id,
            'patient_id' => $patient->id,
            'dispensing_item_id' => $item->id,
            'transaction_type' => 'dispensed',
            'quantity' => $quantity,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'batch_no' => $item->batch_no,
            'patient_name' => $patient->name,
            'patient_ic' => $patient->ic_number,
            'patient_address' => $patient->full_address ?? '',
            'prescriber_name' => $prescription?->doctor?->user?->name,
            'prescriber_mmc' => $prescription?->doctor?->mmc_number,
            'purpose' => $item->dosage_instructions,
            'recorded_by' => $recordedBy,
        ]);
    }

    protected function updateDispensingRecordStatus(DispensingRecord $record): void
    {
        $record->load('items');

        $totalPrescribed = $record->items->sum('quantity_prescribed');
        $totalDispensed = $record->items->sum('quantity_dispensed');

        if ($totalDispensed >= $totalPrescribed) {
            $record->status = DispensingRecord::STATUS_DISPENSED;
        } elseif ($totalDispensed > 0) {
            $record->status = DispensingRecord::STATUS_PARTIALLY_DISPENSED;
        }

        $record->total_amount = $record->items->sum('total_price');
        $record->save();
    }

    public function getPendingDispensing(): Collection
    {
        return DispensingRecord::pending()
            ->with(['patient', 'prescription', 'items.medicine'])
            ->latest()
            ->get();
    }

    // ==================== CATEGORIES ====================

    public function getCategories(): Collection
    {
        return MedicineCategory::active()
            ->with('children')
            ->roots()
            ->orderBy('sort_order')
            ->get();
    }

    public function createCategory(array $data): MedicineCategory
    {
        $data['code'] = $data['code'] ?? MedicineCategory::generateCode();

        return MedicineCategory::create($data);
    }

    // ==================== SUPPLIERS ====================

    public function getSuppliers(array $filters = [], int $perPage = 25): LengthAwarePaginator
    {
        return Supplier::query()
            ->when(isset($filters['search']), function ($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['search']}%")
                    ->orWhere('code', 'like', "%{$filters['search']}%");
            })
            ->when(isset($filters['is_active']), fn ($q) => $q->where('is_active', $filters['is_active']))
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function createSupplier(array $data, ?int $createdBy = null): Supplier
    {
        $data['code'] = $data['code'] ?? Supplier::generateCode();
        $data['created_by'] = $createdBy;

        return Supplier::create($data);
    }

    // ==================== PURCHASE ORDERS ====================

    public function createPurchaseOrder(array $data, array $items, ?int $createdBy = null): PurchaseOrder
    {
        return DB::transaction(function () use ($data, $items, $createdBy) {
            $po = PurchaseOrder::create([
                'po_number' => PurchaseOrder::generatePoNumber(),
                'supplier_id' => $data['supplier_id'],
                'order_date' => $data['order_date'] ?? now(),
                'expected_date' => $data['expected_date'] ?? null,
                'notes' => $data['notes'] ?? null,
                'status' => PurchaseOrder::STATUS_DRAFT,
                'created_by' => $createdBy,
            ]);

            foreach ($items as $item) {
                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'medicine_id' => $item['medicine_id'],
                    'quantity_ordered' => $item['quantity'],
                    'unit_cost' => $item['unit_cost'],
                    'total_cost' => $item['quantity'] * $item['unit_cost'],
                ]);
            }

            $po->calculateTotals();
            $po->save();

            return $po->load('items.medicine', 'supplier');
        });
    }

    public function receivePurchaseOrder(
        PurchaseOrder $po,
        array $receivedItems,
        ?int $receivedBy = null
    ): PurchaseOrder {
        return DB::transaction(function () use ($po, $receivedItems, $receivedBy) {
            foreach ($receivedItems as $itemData) {
                $item = PurchaseOrderItem::find($itemData['item_id']);
                if (! $item) {
                    continue;
                }

                $quantityReceived = $itemData['quantity_received'] ?? 0;
                if ($quantityReceived <= 0) {
                    continue;
                }

                // Update PO item
                $item->increment('quantity_received', $quantityReceived);
                $item->batch_no = $itemData['batch_no'] ?? null;
                $item->expiry_date = $itemData['expiry_date'] ?? null;
                $item->save();

                // Receive stock
                $this->receiveStock(
                    $item->medicine,
                    $quantityReceived,
                    [
                        'batch_no' => $itemData['batch_no'] ?? null,
                        'expiry_date' => $itemData['expiry_date'] ?? null,
                        'unit_cost' => $item->unit_cost,
                        'supplier_id' => $po->supplier_id,
                        'purchase_order_id' => $po->id,
                        'source_type' => 'purchase_order',
                        'source_id' => $po->id,
                    ],
                    $receivedBy
                );
            }

            // Update PO status
            $po->load('items');
            $allReceived = $po->items->every(fn ($item) => $item->isFullyReceived());
            $anyReceived = $po->items->contains(fn ($item) => $item->quantity_received > 0);

            if ($allReceived) {
                $po->status = PurchaseOrder::STATUS_RECEIVED;
                $po->received_date = now();
            } elseif ($anyReceived) {
                $po->status = PurchaseOrder::STATUS_PARTIAL;
            }

            $po->save();

            return $po->fresh(['items.medicine', 'supplier']);
        });
    }

    // ==================== STATISTICS ====================

    public function getStatistics(): array
    {
        return [
            'total_medicines' => Medicine::active()->count(),
            'low_stock_count' => Medicine::active()->lowStock()->count(),
            'expiring_soon_count' => Medicine::active()->expiringSoon(90)->count(),
            'expired_count' => Medicine::active()->expired()->count(),
            'controlled_count' => Medicine::active()->controlled()->count(),
            'pending_dispensing' => DispensingRecord::pending()->count(),
            'today_dispensing' => DispensingRecord::today()->count(),
            'pending_po' => PurchaseOrder::pending()->count(),
            'total_stock_value' => Medicine::active()->sum(DB::raw('stock_quantity * cost_price')),
        ];
    }
}
