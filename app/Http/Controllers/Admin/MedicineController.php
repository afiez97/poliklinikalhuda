<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use App\Models\MedicineCategory;
use App\Services\PharmacyService;
use App\Traits\HandlesApiResponses;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Patch;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('admin/pharmacy/medicines')]
#[Middleware(['web', 'auth'])]
class MedicineController extends Controller
{
    use HandlesApiResponses;

    public function __construct(
        protected PharmacyService $pharmacyService
    ) {}

    #[Get('/', name: 'admin.pharmacy.medicines.index')]
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->input('search'),
            'category_id' => $request->input('category_id'),
            'dosage_form' => $request->input('dosage_form'),
            'stock_status' => $request->input('stock_status'),
            'is_controlled' => $request->input('is_controlled'),
            'is_active' => $request->input('is_active'),
        ];

        $medicines = $this->pharmacyService->getMedicines(
            filters: array_filter($filters, fn ($v) => $v !== null && $v !== ''),
            perPage: $request->input('per_page', 25)
        );

        $statistics = $this->pharmacyService->getStatistics();
        $categories = MedicineCategory::active()->get();
        $dosageForms = Medicine::DOSAGE_FORMS;

        return view('admin.pharmacy.medicines.index', compact(
            'medicines',
            'statistics',
            'categories',
            'dosageForms',
            'filters'
        ));
    }

    #[Get('/create', name: 'admin.pharmacy.medicines.create')]
    public function create()
    {
        $categories = MedicineCategory::active()->get();
        $dosageForms = Medicine::DOSAGE_FORMS;
        $poisonSchedules = Medicine::POISON_SCHEDULES;
        $storageConditions = Medicine::STORAGE_CONDITIONS;

        return view('admin.pharmacy.medicines.create', compact(
            'categories',
            'dosageForms',
            'poisonSchedules',
            'storageConditions'
        ));
    }

    #[Post('/', name: 'admin.pharmacy.medicines.store')]
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'name_generic' => ['nullable', 'string', 'max:255'],
            'barcode' => ['nullable', 'string', 'max:50', 'unique:medicines,barcode'],
            'category_id' => ['nullable', 'exists:medicine_categories,id'],
            'dosage_form' => ['nullable', 'string', 'max:50'],
            'strength' => ['nullable', 'string', 'max:100'],
            'unit' => ['required', 'string', 'max:30'],
            'manufacturer' => ['nullable', 'string', 'max:255'],
            'cost_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'reorder_level' => ['required', 'integer', 'min:0'],
            'max_stock_level' => ['nullable', 'integer', 'min:0'],
            'expiry_date' => ['nullable', 'date'],
            'storage_conditions' => ['nullable', 'string'],
            'requires_prescription' => ['boolean'],
            'is_controlled' => ['boolean'],
            'poison_schedule' => ['nullable', 'string'],
            'contraindications' => ['nullable', 'string'],
            'side_effects' => ['nullable', 'string'],
            'dosage_instructions' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        try {
            $medicine = $this->pharmacyService->createMedicine(
                $validated,
                auth()->id()
            );

            return $this->successRedirect(
                'admin.pharmacy.medicines.show',
                __('Ubat berjaya ditambah: :name', ['name' => $medicine->name]),
                ['medicine' => $medicine->id]
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Get('/{medicine}', name: 'admin.pharmacy.medicines.show')]
    public function show(Medicine $medicine)
    {
        $medicine->load(['category', 'batches' => fn ($q) => $q->latest(), 'stockMovements' => fn ($q) => $q->latest()->limit(20)]);

        return view('admin.pharmacy.medicines.show', compact('medicine'));
    }

    #[Get('/{medicine}/edit', name: 'admin.pharmacy.medicines.edit')]
    public function edit(Medicine $medicine)
    {
        $categories = MedicineCategory::active()->get();
        $dosageForms = Medicine::DOSAGE_FORMS;
        $poisonSchedules = Medicine::POISON_SCHEDULES;
        $storageConditions = Medicine::STORAGE_CONDITIONS;

        return view('admin.pharmacy.medicines.edit', compact(
            'medicine',
            'categories',
            'dosageForms',
            'poisonSchedules',
            'storageConditions'
        ));
    }

    #[Patch('/{medicine}', name: 'admin.pharmacy.medicines.update')]
    public function update(Request $request, Medicine $medicine)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'name_generic' => ['nullable', 'string', 'max:255'],
            'barcode' => ['nullable', 'string', 'max:50', 'unique:medicines,barcode,'.$medicine->id],
            'category_id' => ['nullable', 'exists:medicine_categories,id'],
            'dosage_form' => ['nullable', 'string', 'max:50'],
            'strength' => ['nullable', 'string', 'max:100'],
            'unit' => ['required', 'string', 'max:30'],
            'manufacturer' => ['nullable', 'string', 'max:255'],
            'cost_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'reorder_level' => ['required', 'integer', 'min:0'],
            'max_stock_level' => ['nullable', 'integer', 'min:0'],
            'expiry_date' => ['nullable', 'date'],
            'storage_conditions' => ['nullable', 'string'],
            'requires_prescription' => ['boolean'],
            'is_controlled' => ['boolean'],
            'poison_schedule' => ['nullable', 'string'],
            'contraindications' => ['nullable', 'string'],
            'side_effects' => ['nullable', 'string'],
            'dosage_instructions' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        try {
            $this->pharmacyService->updateMedicine(
                $medicine,
                $validated,
                auth()->id()
            );

            return $this->successRedirect(
                'admin.pharmacy.medicines.show',
                __('Ubat berjaya dikemaskini.'),
                ['medicine' => $medicine->id]
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Delete('/{medicine}', name: 'admin.pharmacy.medicines.destroy')]
    public function destroy(Medicine $medicine)
    {
        try {
            $name = $medicine->name;
            $this->pharmacyService->deleteMedicine($medicine);

            return $this->successRedirect(
                'admin.pharmacy.medicines.index',
                __('Ubat :name berjaya dipadam.', ['name' => $name])
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Post('/{medicine}/adjust-stock', name: 'admin.pharmacy.medicines.adjustStock')]
    public function adjustStock(Request $request, Medicine $medicine)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer'],
            'movement_type' => ['required', 'in:in,out,adjustment,return,expired,damaged'],
            'reason' => ['required', 'string', 'max:500'],
            'batch_no' => ['nullable', 'string', 'max:50'],
        ]);

        try {
            $this->pharmacyService->adjustStock(
                $medicine,
                $validated['quantity'],
                $validated['movement_type'],
                $validated['reason'],
                auth()->id(),
                $validated['batch_no'] ?? null
            );

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Stok berjaya diselaraskan.',
                    'new_stock' => $medicine->fresh()->stock_quantity,
                ]);
            }

            return $this->successRedirect(
                'admin.pharmacy.medicines.show',
                __('Stok berjaya diselaraskan.'),
                ['medicine' => $medicine->id]
            );
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }

            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Get('/low-stock', name: 'admin.pharmacy.medicines.lowStock')]
    public function lowStock()
    {
        $medicines = $this->pharmacyService->getLowStockMedicines();

        return view('admin.pharmacy.medicines.low-stock', compact('medicines'));
    }

    #[Get('/expiring', name: 'admin.pharmacy.medicines.expiring')]
    public function expiring(Request $request)
    {
        $days = $request->input('days', 90);
        $medicines = $this->pharmacyService->getExpiringSoonMedicines($days);
        $expired = $this->pharmacyService->getExpiredMedicines();

        return view('admin.pharmacy.medicines.expiring', compact('medicines', 'expired', 'days'));
    }

    #[Get('/search', name: 'admin.pharmacy.medicines.search')]
    public function search(Request $request)
    {
        $term = $request->input('q', '');

        if (strlen($term) < 2) {
            return response()->json([]);
        }

        $medicines = Medicine::active()
            ->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('name_generic', 'like', "%{$term}%")
                    ->orWhere('code', 'like', "%{$term}%")
                    ->orWhere('barcode', $term);
            })
            ->limit(20)
            ->get(['id', 'code', 'name', 'name_generic', 'strength', 'unit', 'selling_price', 'stock_quantity']);

        return response()->json($medicines);
    }
}
