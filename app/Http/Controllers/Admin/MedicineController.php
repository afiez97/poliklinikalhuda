<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use App\Http\Requests\StoreMedicineRequest;
use App\Http\Requests\UpdateMedicineRequest;
use App\Http\Requests\UpdateStockRequest;
use App\Services\MedicineService;
use App\Exceptions\MedicineException;
use App\Traits\HandlesApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Patch;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\Middleware;

#[Prefix('admin/medicine')]
#[Middleware(['web', 'auth'])]
class MedicineController extends Controller
{
    use HandlesApiResponses;

    protected MedicineService $medicineService;

    public function __construct(MedicineService $medicineService)
    {
        $this->medicineService = $medicineService;
    }
    /**
     * Display a listing of medicines.
     */
    #[Get('/', name: 'admin.medicine.index')]
    public function index(Request $request)
    {
        $medicines = Medicine::orderBy('name')->get();

        $stats = [
            'total_medicines' => $medicines->count(),
            'low_stock_count' => $medicines->filter(fn($m) => $m->isLowStock())->count(),
            'expiring_soon_count' => $medicines->filter(fn($m) => $m->isExpiringSoon())->count(),
            'total_value' => $medicines->sum('total_value'),
        ];

        $categories = config('medicine.category_labels', []);
        $statuses = config('medicine.status_labels', []);

        return view('admin.medicine.index', compact('medicines', 'categories', 'statuses', 'stats'));
    }

    /**
     * Show the form for creating a new medicine.
     */
    #[Get('/create', name: 'admin.medicine.create')]
    public function create()
    {
        return view('admin.medicine.create');
    }

    /**
     * Store a newly created medicine in storage.
     */
    #[Post('/', name: 'admin.medicine.store')]
    public function store(StoreMedicineRequest $request)
    {
        try {
            Medicine::create($request->validated());
            return $this->successRedirect('admin.medicine.index', __('medicine.success_created'));
        } catch (\Exception $e) {
            Log::error('Medicine creation failed', ['error' => $e->getMessage(), 'data' => $request->validated()]);
            return $this->errorRedirect(__('medicine.messages.create_failed'));
        }
    }

    /**
     * Display the specified medicine.
     */
    #[Get('/{medicine}', name: 'admin.medicine.show')]
    public function show(Medicine $medicine)
    {
        return view('admin.medicine.show', compact('medicine'));
    }

    /**
     * Show the form for editing the specified medicine.
     */
    #[Get('/{medicine}/edit', name: 'admin.medicine.edit')]
    public function edit(Medicine $medicine)
    {
        return view('admin.medicine.edit', compact('medicine'));
    }

    /**
     * Update the specified medicine in storage.
     */
    #[Patch('/{medicine}', name: 'admin.medicine.update')]
    public function update(UpdateMedicineRequest $request, Medicine $medicine)
    {
        try {
            $medicine->update($request->validated());
            return $this->successRedirect('admin.medicine.index', __('medicine.success_updated'));
        } catch (\Exception $e) {
            Log::error('Medicine update failed', ['id' => $medicine->id, 'error' => $e->getMessage()]);
            return $this->errorRedirect(__('medicine.messages.update_failed'));
        }
    }

    /**
     * Remove the specified medicine from storage.
     */
    #[Delete('/{medicine}', name: 'admin.medicine.destroy')]
    public function destroy(Medicine $medicine)
    {
        try {
            $medicine->delete();
            return $this->successRedirect('admin.medicine.index', __('medicine.messages.deleted_successfully'));
        } catch (\Exception $e) {
            Log::error('Medicine deletion failed', ['id' => $medicine->id, 'error' => $e->getMessage()]);
            return $this->errorRedirect(__('medicine.messages.delete_failed'));
        }
    }

    /**
     * Display medicines with low stock.
     */
    #[Get('/low-stock', name: 'admin.medicine.low-stock')]
    public function lowStock()
    {
        $medicines = Medicine::lowStock()->orderBy('stock_quantity')->get();

        return view('admin.medicine.low-stock', compact('medicines'));
    }

    /**
     * Display medicines expiring soon.
     */
    #[Get('/expiring', name: 'admin.medicine.expiring')]
    public function expiringSoon()
    {
        $medicines = Medicine::expiringSoon()->orderBy('expiry_date')->get();

        return view('admin.medicine.expiring', compact('medicines'));
    }

    /**
     * Update stock for a medicine.
     */
    #[Patch('/{medicine}/update-stock', name: 'admin.medicine.update-stock')]
    public function updateStock(UpdateStockRequest $request, Medicine $medicine)
    {
        try {
            DB::beginTransaction();

            if ($request->action === 'add') {
                $medicine->increment('stock_quantity', $request->quantity);
                $message = __('medicine.messages.stock_added', [
                    'quantity' => $request->quantity,
                    'reason' => $request->reason ?? ''
                ]);
            } else {
                if ($medicine->stock_quantity < $request->quantity) {
                    DB::rollBack();
                    throw MedicineException::insufficientStock(
                        $medicine->name,
                        $medicine->stock_quantity,
                        $request->quantity
                    );
                }
                $medicine->decrement('stock_quantity', $request->quantity);
                $message = __('medicine.messages.stock_reduced', [
                    'quantity' => $request->quantity,
                    'reason' => $request->reason ?? ''
                ]);
            }

            DB::commit();
            return back()->with('success', $message);

        } catch (MedicineException $e) {
            return $this->errorRedirect($e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Stock update failed', [
                'medicine_id' => $medicine->id,
                'action' => $request->action,
                'error' => $e->getMessage()
            ]);
            return $this->errorRedirect(__('medicine.messages.stock_update_error'));
        }
    }

    /**
     * Display stock report.
     */
    #[Get('/stock-report', name: 'admin.medicine.stock-report')]
    public function stockReport()
    {
        // Statistik keseluruhan
        $totalMedicines = Medicine::count();
        $activeMedicines = Medicine::active()->count();
        $lowStockMedicines = Medicine::lowStock()->count();
        $expiringSoonMedicines = Medicine::expiringSoon()->count();
        $totalStockValue = Medicine::active()->sum(DB::raw('stock_quantity * unit_price'));

        // Statistik mengikut kategori
        $medicinesByCategory = Medicine::active()
            ->selectRaw('category, COUNT(*) as count, SUM(stock_quantity) as total_stock, SUM(stock_quantity * unit_price) as total_value')
            ->groupBy('category')
            ->get();

        // Ubat dengan stok tertinggi
        $topStockMedicines = Medicine::active()
            ->orderBy('stock_quantity', 'desc')
            ->limit(10)
            ->get();

        // Ubat dengan nilai tertinggi
        $topValueMedicines = Medicine::active()
            ->selectRaw('*, (stock_quantity * unit_price) as total_value')
            ->orderByRaw('(stock_quantity * unit_price) desc')
            ->limit(10)
            ->get();

        return view('admin.medicine.stock-report', compact(
            'totalMedicines',
            'activeMedicines',
            'lowStockMedicines',
            'expiringSoonMedicines',
            'totalStockValue',
            'medicinesByCategory',
            'topStockMedicines',
            'topValueMedicines'
        ));
    }

    /**
     * Bulk update status for medicines.
     */
    #[Patch('/bulk-status', name: 'admin.medicine.bulk-status')]
    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'medicine_ids' => 'required|array',
            'medicine_ids.*' => 'exists:medicines,id',
            'status' => 'required|in:active,inactive,expired',
        ]);

        Medicine::whereIn('id', $request->medicine_ids)
                ->update(['status' => $request->status]);

        $count = count($request->medicine_ids);
        return back()->with('success', __('medicine.messages.bulk_status_updated', [
            'count' => $count,
            'status' => __('medicine.status.' . $request->status)
        ]));
    }
}
