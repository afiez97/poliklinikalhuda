<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Panel;
use App\Models\PanelExclusion;
use App\Models\PanelPackage;
use App\Services\PanelService;
use App\Traits\HandlesApiResponses;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Patch;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('admin/panel/panels')]
#[Middleware(['web', 'auth'])]
class PanelController extends Controller
{
    use HandlesApiResponses;

    public function __construct(
        protected PanelService $panelService
    ) {}

    #[Get('/', name: 'admin.panel.panels.index')]
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->input('search'),
            'type' => $request->input('type'),
            'status' => $request->input('status'),
        ];

        $panels = $this->panelService->getPanels($filters);
        $statistics = $this->panelService->getStatistics();

        return view('admin.panel.panels.index', compact('panels', 'filters', 'statistics'));
    }

    #[Get('/create', name: 'admin.panel.panels.create')]
    public function create()
    {
        return view('admin.panel.panels.create');
    }

    #[Post('/', name: 'admin.panel.panels.store')]
    public function store(Request $request)
    {
        $validated = $request->validate([
            'panel_name' => ['required', 'string', 'max:255'],
            'panel_type' => ['required', 'in:corporate,insurance,government'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'postcode' => ['nullable', 'string', 'max:10'],
            'payment_terms_days' => ['nullable', 'integer', 'min:0'],
            'sla_approval_days' => ['nullable', 'integer', 'min:1'],
            'sla_payment_days' => ['nullable', 'integer', 'min:1'],
            'notes' => ['nullable', 'string'],
            'status' => ['nullable', 'in:active,inactive,suspended'],
        ]);

        try {
            $panel = $this->panelService->createPanel($validated);

            return $this->successRedirect(
                'admin.panel.panels.show',
                __('Panel berjaya dicipta.'),
                ['panel' => $panel->id]
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Get('/{panel}', name: 'admin.panel.panels.show')]
    public function show(Panel $panel)
    {
        $panel->load(['packages', 'exclusions', 'activeContract', 'employees' => fn ($q) => $q->active()->limit(10)]);

        $revenue = $this->panelService->getPanelRevenue($panel->id);

        return view('admin.panel.panels.show', compact('panel', 'revenue'));
    }

    #[Get('/{panel}/edit', name: 'admin.panel.panels.edit')]
    public function edit(Panel $panel)
    {
        return view('admin.panel.panels.edit', compact('panel'));
    }

    #[Patch('/{panel}', name: 'admin.panel.panels.update')]
    public function update(Request $request, Panel $panel)
    {
        $validated = $request->validate([
            'panel_name' => ['required', 'string', 'max:255'],
            'panel_type' => ['required', 'in:corporate,insurance,government'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'postcode' => ['nullable', 'string', 'max:10'],
            'payment_terms_days' => ['nullable', 'integer', 'min:0'],
            'sla_approval_days' => ['nullable', 'integer', 'min:1'],
            'sla_payment_days' => ['nullable', 'integer', 'min:1'],
            'notes' => ['nullable', 'string'],
            'status' => ['nullable', 'in:active,inactive,suspended'],
        ]);

        try {
            $this->panelService->updatePanel($panel, $validated);

            return $this->successRedirect(
                'admin.panel.panels.show',
                __('Panel berjaya dikemaskini.'),
                ['panel' => $panel->id]
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Delete('/{panel}', name: 'admin.panel.panels.destroy')]
    public function destroy(Panel $panel)
    {
        try {
            $this->panelService->deletePanel($panel);

            return $this->successRedirect(
                'admin.panel.panels.index',
                __('Panel berjaya dipadam.')
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    // Packages
    #[Get('/{panel}/packages/create', name: 'admin.panel.packages.create')]
    public function createPackage(Panel $panel)
    {
        return view('admin.panel.packages.create', compact('panel'));
    }

    #[Post('/{panel}/packages', name: 'admin.panel.packages.store')]
    public function storePackage(Request $request, Panel $panel)
    {
        $validated = $request->validate([
            'package_code' => ['required', 'string', 'max:50'],
            'package_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'annual_limit' => ['nullable', 'numeric', 'min:0'],
            'per_visit_limit' => ['nullable', 'numeric', 'min:0'],
            'consultation_limit' => ['nullable', 'numeric', 'min:0'],
            'medication_limit' => ['nullable', 'numeric', 'min:0'],
            'procedure_limit' => ['nullable', 'numeric', 'min:0'],
            'lab_limit' => ['nullable', 'numeric', 'min:0'],
            'co_payment_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'deductible_amount' => ['nullable', 'numeric', 'min:0'],
            'deductible_type' => ['nullable', 'in:per_visit,per_year'],
            'is_default' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        try {
            $this->panelService->createPackage($panel, $validated);

            return $this->successRedirect(
                'admin.panel.panels.show',
                __('Pakej berjaya dicipta.'),
                ['panel' => $panel->id]
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Get('/{panel}/packages/{package}/edit', name: 'admin.panel.packages.edit')]
    public function editPackage(Panel $panel, PanelPackage $package)
    {
        return view('admin.panel.packages.edit', compact('panel', 'package'));
    }

    #[Patch('/{panel}/packages/{package}', name: 'admin.panel.packages.update')]
    public function updatePackage(Request $request, Panel $panel, PanelPackage $package)
    {
        $validated = $request->validate([
            'package_code' => ['required', 'string', 'max:50'],
            'package_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'annual_limit' => ['nullable', 'numeric', 'min:0'],
            'per_visit_limit' => ['nullable', 'numeric', 'min:0'],
            'consultation_limit' => ['nullable', 'numeric', 'min:0'],
            'medication_limit' => ['nullable', 'numeric', 'min:0'],
            'procedure_limit' => ['nullable', 'numeric', 'min:0'],
            'lab_limit' => ['nullable', 'numeric', 'min:0'],
            'co_payment_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'deductible_amount' => ['nullable', 'numeric', 'min:0'],
            'deductible_type' => ['nullable', 'in:per_visit,per_year'],
            'is_default' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        try {
            $this->panelService->updatePackage($package, $validated);

            return $this->successRedirect(
                'admin.panel.panels.show',
                __('Pakej berjaya dikemaskini.'),
                ['panel' => $panel->id]
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    // Contracts
    #[Get('/{panel}/contracts/create', name: 'admin.panel.contracts.create')]
    public function createContract(Panel $panel)
    {
        return view('admin.panel.contracts.create', compact('panel'));
    }

    #[Post('/{panel}/contracts', name: 'admin.panel.contracts.store')]
    public function storeContract(Request $request, Panel $panel)
    {
        $validated = $request->validate([
            'contract_number' => ['nullable', 'string', 'max:100'],
            'effective_date' => ['required', 'date'],
            'expiry_date' => ['required', 'date', 'after:effective_date'],
            'renewal_date' => ['nullable', 'date'],
            'annual_cap' => ['nullable', 'numeric', 'min:0'],
            'terms_conditions' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'status' => ['nullable', 'in:draft,active'],
        ]);

        $validated['created_by'] = auth()->id();

        try {
            $this->panelService->createContract($panel, $validated);

            return $this->successRedirect(
                'admin.panel.panels.show',
                __('Kontrak berjaya dicipta.'),
                ['panel' => $panel->id]
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    // Exclusions
    #[Post('/{panel}/exclusions', name: 'admin.panel.exclusions.store')]
    public function storeExclusion(Request $request, Panel $panel)
    {
        $validated = $request->validate([
            'exclusion_type' => ['required', 'in:procedure,medication,diagnosis,category'],
            'exclusion_code' => ['nullable', 'string', 'max:50'],
            'exclusion_name' => ['required', 'string', 'max:255'],
            'reason' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        try {
            $this->panelService->createExclusion($panel, $validated);

            return $this->successRedirect(
                'admin.panel.panels.show',
                __('Pengecualian berjaya ditambah.'),
                ['panel' => $panel->id]
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Delete('/{panel}/exclusions/{exclusion}', name: 'admin.panel.exclusions.destroy')]
    public function destroyExclusion(Panel $panel, PanelExclusion $exclusion)
    {
        $exclusion->delete();

        return $this->successRedirect(
            'admin.panel.panels.show',
            __('Pengecualian berjaya dipadam.'),
            ['panel' => $panel->id]
        );
    }
}
