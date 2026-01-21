<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Services\PharmacyService;
use App\Traits\HandlesApiResponses;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Patch;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('admin/pharmacy/suppliers')]
#[Middleware(['web', 'auth'])]
class SupplierController extends Controller
{
    use HandlesApiResponses;

    public function __construct(
        protected PharmacyService $pharmacyService
    ) {}

    #[Get('/', name: 'admin.pharmacy.suppliers.index')]
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->input('search'),
            'is_active' => $request->input('is_active'),
        ];

        $suppliers = $this->pharmacyService->getSuppliers(
            array_filter($filters, fn ($v) => $v !== null && $v !== ''),
            $request->input('per_page', 25)
        );

        return view('admin.pharmacy.suppliers.index', compact('suppliers', 'filters'));
    }

    #[Get('/create', name: 'admin.pharmacy.suppliers.create')]
    public function create()
    {
        return view('admin.pharmacy.suppliers.create');
    }

    #[Post('/', name: 'admin.pharmacy.suppliers.store')]
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'postcode' => ['nullable', 'string', 'max:10'],
            'registration_no' => ['nullable', 'string', 'max:50'],
            'tax_id' => ['nullable', 'string', 'max:50'],
            'bank_name' => ['nullable', 'string', 'max:100'],
            'bank_account' => ['nullable', 'string', 'max:50'],
            'payment_terms' => ['nullable', 'integer', 'min:0'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        try {
            $supplier = $this->pharmacyService->createSupplier($validated, auth()->id());

            return $this->successRedirect(
                'admin.pharmacy.suppliers.show',
                __('Pembekal berjaya ditambah: :name', ['name' => $supplier->name]),
                ['supplier' => $supplier->id]
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Get('/{supplier}', name: 'admin.pharmacy.suppliers.show')]
    public function show(Supplier $supplier)
    {
        $supplier->load(['purchaseOrders' => fn ($q) => $q->latest()->limit(10)]);

        return view('admin.pharmacy.suppliers.show', compact('supplier'));
    }

    #[Get('/{supplier}/edit', name: 'admin.pharmacy.suppliers.edit')]
    public function edit(Supplier $supplier)
    {
        return view('admin.pharmacy.suppliers.edit', compact('supplier'));
    }

    #[Patch('/{supplier}', name: 'admin.pharmacy.suppliers.update')]
    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'postcode' => ['nullable', 'string', 'max:10'],
            'registration_no' => ['nullable', 'string', 'max:50'],
            'tax_id' => ['nullable', 'string', 'max:50'],
            'bank_name' => ['nullable', 'string', 'max:100'],
            'bank_account' => ['nullable', 'string', 'max:50'],
            'payment_terms' => ['nullable', 'integer', 'min:0'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        $supplier->update($validated);

        return $this->successRedirect(
            'admin.pharmacy.suppliers.show',
            __('Pembekal berjaya dikemaskini.'),
            ['supplier' => $supplier->id]
        );
    }

    #[Delete('/{supplier}', name: 'admin.pharmacy.suppliers.destroy')]
    public function destroy(Supplier $supplier)
    {
        $name = $supplier->name;
        $supplier->delete();

        return $this->successRedirect(
            'admin.pharmacy.suppliers.index',
            __('Pembekal :name berjaya dipadam.', ['name' => $name])
        );
    }
}
