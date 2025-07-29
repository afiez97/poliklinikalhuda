<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MedicineController extends Controller
{
    /**
     * Display a listing of medicines.
     */
    public function index(Request $request)
    {
        // Untuk DataTable, kita ambil semua data tanpa pagination
        $medicines = Medicine::orderBy('name')->get();

        // Data untuk dropdown filter (jika diperlukan)
        $categories = Medicine::distinct()->pluck('category', 'category');
        $statuses = ['active' => 'Aktif', 'inactive' => 'Tidak Aktif', 'expired' => 'Luput'];

        return view('admin.medicine.index', compact('medicines', 'categories', 'statuses'));
    }

    /**
     * Show the form for creating a new medicine.
     */
    public function create()
    {
        return view('admin.medicine.create');
    }

    /**
     * Store a newly created medicine in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'medicine_code' => 'nullable|string|unique:medicines,medicine_code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:tablet,capsule,syrup,injection,cream,drops,spray,patch',
            'manufacturer' => 'nullable|string|max:255',
            'strength' => 'nullable|string|max:100',
            'unit_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'expiry_date' => 'nullable|date|after:today',
            'batch_number' => 'nullable|string|max:100',
        ]);

        Medicine::create($request->all());

        return redirect()->route('admin.medicine.index')
            ->with('success', 'Ubat berjaya ditambah ke inventori.');
    }

    /**
     * Display the specified medicine.
     */
    public function show(Medicine $medicine)
    {
        return view('admin.medicine.show', compact('medicine'));
    }

    /**
     * Show the form for editing the specified medicine.
     */
    public function edit(Medicine $medicine)
    {
        return view('admin.medicine.edit', compact('medicine'));
    }

    /**
     * Update the specified medicine in storage.
     */
    public function update(Request $request, Medicine $medicine)
    {
        $request->validate([
            'medicine_code' => 'required|string|unique:medicines,medicine_code,' . $medicine->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:tablet,capsule,syrup,injection,cream,drops,spray,patch',
            'manufacturer' => 'nullable|string|max:255',
            'strength' => 'nullable|string|max:100',
            'unit_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'expiry_date' => 'nullable|date|after:today',
            'batch_number' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive,expired',
        ]);

        $medicine->update($request->all());

        return redirect()->route('admin.medicine.index')
            ->with('success', 'Maklumat ubat berjaya dikemaskini.');
    }

    /**
     * Remove the specified medicine from storage.
     */
    public function destroy(Medicine $medicine)
    {
        $medicine->delete();

        return redirect()->route('admin.medicine.index')
            ->with('success', 'Ubat berjaya dipadam dari inventori.');
    }

    /**
     * Display medicines with low stock.
     */
    public function lowStock()
    {
        $medicines = Medicine::lowStock()->orderBy('stock_quantity')->get();

        return view('admin.medicine.low-stock', compact('medicines'));
    }

    /**
     * Display medicines expiring soon.
     */
    public function expiringSoon()
    {
        $medicines = Medicine::expiringSoon()->orderBy('expiry_date')->get();

        return view('admin.medicine.expiring', compact('medicines'));
    }

    /**
     * Update stock for a medicine.
     */
    public function updateStock(Request $request, Medicine $medicine)
    {
        $request->validate([
            'action' => 'required|in:add,subtract',
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            if ($request->action === 'add') {
                $medicine->increment('stock_quantity', $request->quantity);
                $message = "Stok ditambah: {$request->quantity} unit. {$request->reason}";
            } else {
                if ($medicine->stock_quantity < $request->quantity) {
                    return back()->with('error', 'Stok tidak mencukupi untuk dikurangkan.');
                }
                $medicine->decrement('stock_quantity', $request->quantity);
                $message = "Stok dikurangkan: {$request->quantity} unit. {$request->reason}";
            }

            DB::commit();

            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Ralat semasa mengemas kini stok: ' . $e->getMessage());
        }
    }

    /**
     * Display stock report.
     */
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
        return back()->with('success', "{$count} ubat telah dikemas kini statusnya kepada {$request->status}.");
    }
}
