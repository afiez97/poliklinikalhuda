<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DispensingItem;
use App\Models\DispensingRecord;
use App\Models\Medicine;
use App\Models\Patient;
use App\Models\Prescription;
use App\Services\PharmacyService;
use App\Traits\HandlesApiResponses;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('admin/pharmacy/dispensing')]
#[Middleware(['web', 'auth'])]
class DispensingController extends Controller
{
    use HandlesApiResponses;

    public function __construct(
        protected PharmacyService $pharmacyService
    ) {}

    #[Get('/', name: 'admin.pharmacy.dispensing.index')]
    public function index(Request $request)
    {
        $filters = [
            'status' => $request->input('status'),
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
            'patient_id' => $request->input('patient_id'),
        ];

        $records = DispensingRecord::with(['patient', 'dispensedBy', 'items.medicine'])
            ->when($filters['status'], fn ($q) => $q->where('status', $filters['status']))
            ->when($filters['date_from'], fn ($q) => $q->whereDate('dispensed_at', '>=', $filters['date_from']))
            ->when($filters['date_to'], fn ($q) => $q->whereDate('dispensed_at', '<=', $filters['date_to']))
            ->when($filters['patient_id'], fn ($q) => $q->where('patient_id', $filters['patient_id']))
            ->latest('dispensed_at')
            ->paginate(25)
            ->withQueryString();

        $pendingCount = DispensingRecord::pending()->count();
        $todayCount = DispensingRecord::today()->count();

        return view('admin.pharmacy.dispensing.index', compact('records', 'filters', 'pendingCount', 'todayCount'));
    }

    #[Get('/pending', name: 'admin.pharmacy.dispensing.pending')]
    public function pending()
    {
        $records = $this->pharmacyService->getPendingDispensing();

        return view('admin.pharmacy.dispensing.pending', compact('records'));
    }

    #[Get('/prescription/{prescription}', name: 'admin.pharmacy.dispensing.fromPrescription')]
    public function fromPrescription(Prescription $prescription)
    {
        // Check if dispensing record already exists
        $existingRecord = DispensingRecord::where('prescription_id', $prescription->id)
            ->whereIn('status', [DispensingRecord::STATUS_PENDING, DispensingRecord::STATUS_PARTIALLY_DISPENSED])
            ->first();

        if ($existingRecord) {
            return redirect()->route('admin.pharmacy.dispensing.show', $existingRecord);
        }

        $prescription->load(['patient', 'doctor.user', 'items.medicine', 'encounter']);

        return view('admin.pharmacy.dispensing.from-prescription', compact('prescription'));
    }

    #[Post('/prescription/{prescription}', name: 'admin.pharmacy.dispensing.createFromPrescription')]
    public function createFromPrescription(Prescription $prescription)
    {
        try {
            $record = $this->pharmacyService->createDispensingFromPrescription(
                $prescription,
                auth()->id()
            );

            return $this->successRedirect(
                'admin.pharmacy.dispensing.show',
                __('Rekod dispens berjaya dicipta.'),
                ['record' => $record->id]
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Get('/create', name: 'admin.pharmacy.dispensing.create')]
    public function create(Request $request)
    {
        $patient = null;
        if ($request->has('patient_id')) {
            $patient = Patient::find($request->patient_id);
        }

        $medicines = Medicine::active()->where('stock_quantity', '>', 0)->get();

        return view('admin.pharmacy.dispensing.create', compact('patient', 'medicines'));
    }

    #[Post('/', name: 'admin.pharmacy.dispensing.store')]
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.medicine_id' => ['required', 'exists:medicines,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.batch_no' => ['nullable', 'string'],
            'items.*.dosage_instructions' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        try {
            $record = DispensingRecord::create([
                'dispensing_no' => DispensingRecord::generateDispensingNo(),
                'patient_id' => $validated['patient_id'],
                'dispensed_by' => auth()->id(),
                'dispensed_at' => now(),
                'status' => DispensingRecord::STATUS_PENDING,
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($validated['items'] as $itemData) {
                $medicine = Medicine::find($itemData['medicine_id']);

                DispensingItem::create([
                    'dispensing_record_id' => $record->id,
                    'medicine_id' => $medicine->id,
                    'quantity_prescribed' => $itemData['quantity'],
                    'quantity_dispensed' => 0,
                    'unit_price' => $medicine->selling_price,
                    'total_price' => 0,
                    'dosage_instructions' => $itemData['dosage_instructions'] ?? null,
                ]);
            }

            return $this->successRedirect(
                'admin.pharmacy.dispensing.show',
                __('Rekod dispens berjaya dicipta.'),
                ['record' => $record->id]
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Get('/{record}', name: 'admin.pharmacy.dispensing.show')]
    public function show(DispensingRecord $record)
    {
        $record->load([
            'patient',
            'prescription.doctor.user',
            'encounter',
            'dispensedBy',
            'verifiedBy',
            'items.medicine.batches' => fn ($q) => $q->available(),
        ]);

        return view('admin.pharmacy.dispensing.show', compact('record'));
    }

    #[Post('/{record}/dispense-item', name: 'admin.pharmacy.dispensing.dispenseItem')]
    public function dispenseItem(Request $request, DispensingRecord $record)
    {
        $validated = $request->validate([
            'item_id' => ['required', 'exists:dispensing_items,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'batch_no' => ['nullable', 'string'],
        ]);

        $item = DispensingItem::where('dispensing_record_id', $record->id)
            ->findOrFail($validated['item_id']);

        try {
            $this->pharmacyService->dispenseItem(
                $item,
                $validated['quantity'],
                $validated['batch_no'] ?? null,
                auth()->id()
            );

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Item berjaya didispens.',
                    'item' => $item->fresh(['medicine']),
                    'record' => $record->fresh(),
                ]);
            }

            return $this->successRedirect(
                'admin.pharmacy.dispensing.show',
                __('Item berjaya didispens.'),
                ['record' => $record->id]
            );
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }

            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Post('/{record}/complete', name: 'admin.pharmacy.dispensing.complete')]
    public function complete(DispensingRecord $record)
    {
        if ($record->status === DispensingRecord::STATUS_DISPENSED) {
            return $this->errorRedirect('Rekod dispens ini telah selesai.');
        }

        $record->load('items');

        // Check all items are dispensed
        $hasUndispensed = $record->items->contains(fn ($item) => $item->quantity_dispensed < $item->quantity_prescribed);

        if ($hasUndispensed) {
            return $this->errorRedirect('Masih ada item yang belum didispens sepenuhnya.');
        }

        $record->update([
            'status' => DispensingRecord::STATUS_DISPENSED,
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        return $this->successRedirect(
            'admin.pharmacy.dispensing.show',
            __('Rekod dispens berjaya diselesaikan.'),
            ['record' => $record->id]
        );
    }

    #[Post('/{record}/cancel', name: 'admin.pharmacy.dispensing.cancel')]
    public function cancel(Request $request, DispensingRecord $record)
    {
        if (! in_array($record->status, [DispensingRecord::STATUS_PENDING, DispensingRecord::STATUS_PARTIALLY_DISPENSED])) {
            return $this->errorRedirect('Rekod dispens ini tidak boleh dibatalkan.');
        }

        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        // Return dispensed items to stock
        foreach ($record->items as $item) {
            if ($item->quantity_dispensed > 0) {
                $this->pharmacyService->adjustStock(
                    $item->medicine,
                    $item->quantity_dispensed,
                    'return',
                    "Pembatalan dispens: {$validated['reason']}",
                    auth()->id(),
                    $item->batch_no
                );
            }
        }

        $record->update([
            'status' => DispensingRecord::STATUS_CANCELLED,
            'notes' => ($record->notes ? $record->notes."\n" : '')."Dibatalkan: {$validated['reason']}",
        ]);

        return $this->successRedirect(
            'admin.pharmacy.dispensing.index',
            __('Rekod dispens berjaya dibatalkan.')
        );
    }

    #[Get('/{record}/print', name: 'admin.pharmacy.dispensing.print')]
    public function print(DispensingRecord $record)
    {
        $record->load(['patient', 'prescription.doctor.user', 'items.medicine', 'dispensedBy']);

        return view('admin.pharmacy.dispensing.print', compact('record'));
    }
}
