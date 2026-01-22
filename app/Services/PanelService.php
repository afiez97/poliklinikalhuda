<?php

namespace App\Services;

use App\Models\BenefitLimitTracking;
use App\Models\Panel;
use App\Models\PanelContract;
use App\Models\PanelEmployee;
use App\Models\PanelExclusion;
use App\Models\PanelPackage;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class PanelService
{
    public function getPanels(array $filters = []): LengthAwarePaginator
    {
        $query = Panel::with(['packages', 'activeContract'])
            ->when($filters['search'] ?? null, function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    $q->where('panel_code', 'like', "%{$search}%")
                        ->orWhere('panel_name', 'like', "%{$search}%");
                });
            })
            ->when($filters['type'] ?? null, fn ($q, $type) => $q->where('panel_type', $type))
            ->when($filters['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
            ->orderBy('panel_name');

        return $query->paginate(25)->withQueryString();
    }

    public function createPanel(array $data): Panel
    {
        $data['panel_code'] = Panel::generateCode();

        return Panel::create($data);
    }

    public function updatePanel(Panel $panel, array $data): Panel
    {
        $panel->update($data);

        return $panel->fresh();
    }

    public function deletePanel(Panel $panel): bool
    {
        return $panel->delete();
    }

    // Package Management
    public function createPackage(Panel $panel, array $data): PanelPackage
    {
        $data['panel_id'] = $panel->id;

        if ($data['is_default'] ?? false) {
            $panel->packages()->update(['is_default' => false]);
        }

        return PanelPackage::create($data);
    }

    public function updatePackage(PanelPackage $package, array $data): PanelPackage
    {
        if ($data['is_default'] ?? false) {
            $package->panel->packages()->where('id', '!=', $package->id)->update(['is_default' => false]);
        }

        $package->update($data);

        return $package->fresh();
    }

    // Contract Management
    public function createContract(Panel $panel, array $data): PanelContract
    {
        $data['panel_id'] = $panel->id;

        // Deactivate previous active contracts
        $panel->contracts()->where('status', PanelContract::STATUS_ACTIVE)->update([
            'status' => PanelContract::STATUS_EXPIRED,
        ]);

        return PanelContract::create($data);
    }

    public function getExpiringContracts(int $days = 30): Collection
    {
        return PanelContract::with('panel')
            ->active()
            ->expiringSoon($days)
            ->orderBy('expiry_date')
            ->get();
    }

    // Exclusion Management
    public function createExclusion(Panel $panel, array $data): PanelExclusion
    {
        $data['panel_id'] = $panel->id;

        return PanelExclusion::create($data);
    }

    public function checkExclusion(Panel $panel, string $type, string $code): ?PanelExclusion
    {
        return $panel->exclusions()
            ->active()
            ->where('exclusion_type', $type)
            ->where('exclusion_code', $code)
            ->first();
    }

    public function getExcludedItems(Panel $panel, array $items): array
    {
        $excluded = [];

        foreach ($items as $item) {
            $exclusion = $this->checkExclusion($panel, $item['type'], $item['code']);

            if ($exclusion) {
                $excluded[] = [
                    'item' => $item,
                    'exclusion' => $exclusion,
                    'reason' => $exclusion->reason,
                ];
            }
        }

        return $excluded;
    }

    // Employee Management
    public function createEmployee(Panel $panel, array $data): PanelEmployee
    {
        $data['panel_id'] = $panel->id;

        return PanelEmployee::create($data);
    }

    public function searchEmployee(int $panelId, string $search): Collection
    {
        return PanelEmployee::where('panel_id', $panelId)
            ->where(function ($q) use ($search) {
                $q->where('employee_id', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('ic_number', 'like', "%{$search}%");
            })
            ->active()
            ->limit(10)
            ->get();
    }

    // Benefit Limit
    public function getOrCreateBenefitTracking(
        int $panelId,
        int $patientId,
        ?int $employeeId = null,
        ?int $packageId = null
    ): BenefitLimitTracking {
        $year = now()->year;

        $tracking = BenefitLimitTracking::where('panel_id', $panelId)
            ->where('patient_id', $patientId)
            ->where('benefit_year', $year)
            ->first();

        if (! $tracking) {
            $package = $packageId
                ? PanelPackage::find($packageId)
                : Panel::find($panelId)?->getDefaultPackage();

            $tracking = BenefitLimitTracking::create([
                'panel_id' => $panelId,
                'patient_id' => $patientId,
                'panel_employee_id' => $employeeId,
                'panel_package_id' => $packageId,
                'benefit_year' => $year,
                'annual_limit' => $package?->annual_limit ?? 0,
                'annual_used' => 0,
                'annual_balance' => $package?->annual_limit ?? 0,
            ]);
        }

        return $tracking;
    }

    public function checkBenefitLimit(int $panelId, int $patientId, float $amount): array
    {
        $tracking = $this->getOrCreateBenefitTracking($panelId, $patientId);

        return [
            'annual_limit' => $tracking->annual_limit,
            'annual_used' => $tracking->annual_used,
            'annual_balance' => $tracking->annual_balance,
            'requested_amount' => $amount,
            'has_sufficient_balance' => $tracking->hasAvailableBalance($amount),
            'utilization_percentage' => $tracking->utilization_percentage,
            'utilization_level' => $tracking->utilization_level,
        ];
    }

    // Statistics
    public function getStatistics(): array
    {
        return [
            'total_panels' => Panel::count(),
            'active_panels' => Panel::active()->count(),
            'corporate_panels' => Panel::corporate()->active()->count(),
            'insurance_panels' => Panel::insurance()->active()->count(),
            'government_panels' => Panel::government()->active()->count(),
            'expiring_contracts' => PanelContract::active()->expiringSoon(30)->count(),
        ];
    }

    public function getPanelRevenue(int $panelId, ?string $startDate = null, ?string $endDate = null): array
    {
        $startDate = $startDate ?? now()->startOfMonth()->toDateString();
        $endDate = $endDate ?? now()->endOfMonth()->toDateString();

        $panel = Panel::with(['claims' => function ($q) use ($startDate, $endDate) {
            $q->whereBetween('service_date', [$startDate, $endDate]);
        }])->find($panelId);

        if (! $panel) {
            return [];
        }

        $claims = $panel->claims;

        return [
            'total_claims' => $claims->count(),
            'total_claimed' => $claims->sum('claimable_amount'),
            'total_approved' => $claims->sum('approved_amount'),
            'total_paid' => $claims->sum('paid_amount'),
            'total_outstanding' => $claims->whereNotIn('claim_status', ['paid', 'cancelled', 'rejected'])->sum('claimable_amount'),
            'rejection_count' => $claims->where('claim_status', 'rejected')->count(),
            'rejection_rate' => $claims->count() > 0
                ? round(($claims->where('claim_status', 'rejected')->count() / $claims->count()) * 100, 2)
                : 0,
        ];
    }
}
