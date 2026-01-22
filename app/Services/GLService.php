<?php

namespace App\Services;

use App\Models\GLUtilization;
use App\Models\GuaranteeLetter;
use App\Models\Panel;
use App\Models\PanelDependent;
use App\Models\PanelEligibilityCheck;
use App\Models\PanelEmployee;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GLService
{
    public function getGuaranteeLetters(array $filters = []): LengthAwarePaginator
    {
        $query = GuaranteeLetter::with(['panel', 'patient', 'employee', 'dependent'])
            ->when($filters['search'] ?? null, function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    $q->where('gl_number', 'like', "%{$search}%")
                        ->orWhereHas('patient', fn ($q) => $q->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($filters['panel_id'] ?? null, fn ($q, $panelId) => $q->where('panel_id', $panelId))
            ->when($filters['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
            ->when($filters['verification_status'] ?? null, fn ($q, $status) => $q->where('verification_status', $status))
            ->when($filters['expiring_soon'] ?? false, fn ($q) => $q->expiringSoon(7))
            ->latest();

        return $query->paginate(25)->withQueryString();
    }

    public function createGuaranteeLetter(array $data, ?int $userId = null): GuaranteeLetter
    {
        $data['gl_number'] = $data['gl_number'] ?? GuaranteeLetter::generateGLNumber();
        $data['amount_balance'] = $data['coverage_limit'];
        $data['created_by'] = $userId;

        // Handle file upload
        if (isset($data['document']) && $data['document']) {
            $data['document_path'] = $data['document']->store('guarantee-letters', 'public');
            unset($data['document']);
        }

        return GuaranteeLetter::create($data);
    }

    public function updateGuaranteeLetter(GuaranteeLetter $gl, array $data): GuaranteeLetter
    {
        // Handle file upload
        if (isset($data['document']) && $data['document']) {
            // Delete old document
            if ($gl->document_path) {
                Storage::disk('public')->delete($gl->document_path);
            }

            $data['document_path'] = $data['document']->store('guarantee-letters', 'public');
            unset($data['document']);
        }

        // Recalculate balance if limit changed
        if (isset($data['coverage_limit']) && $data['coverage_limit'] != $gl->coverage_limit) {
            $data['amount_balance'] = $data['coverage_limit'] - $gl->amount_used;
        }

        $gl->update($data);

        return $gl->fresh();
    }

    public function verifyGL(GuaranteeLetter $gl, int $userId, string $method = 'system', ?string $notes = null): GuaranteeLetter
    {
        $gl->verify($userId, $method, $notes);

        return $gl->fresh();
    }

    public function recordUtilization(
        GuaranteeLetter $gl,
        float $amount,
        ?int $invoiceId = null,
        ?int $encounterId = null,
        string $type = 'invoice',
        ?string $notes = null,
        ?int $userId = null
    ): GLUtilization {
        return DB::transaction(function () use ($gl, $amount, $invoiceId, $encounterId, $type, $notes, $userId) {
            // Update GL balance
            $gl->updateUtilization($amount);

            // Record utilization
            return GLUtilization::create([
                'guarantee_letter_id' => $gl->id,
                'invoice_id' => $invoiceId,
                'encounter_id' => $encounterId,
                'utilization_date' => now(),
                'amount' => $amount,
                'running_balance' => $gl->amount_balance,
                'reference_type' => $type,
                'notes' => $notes,
                'created_by' => $userId,
            ]);
        });
    }

    public function checkEligibility(
        int $panelId,
        int $patientId,
        ?string $employeeId = null,
        ?string $icNumber = null,
        ?int $userId = null
    ): array {
        $panel = Panel::with('packages')->find($panelId);

        if (! $panel || $panel->status !== Panel::STATUS_ACTIVE) {
            return $this->createEligibilityResult(false, 'Panel tidak aktif atau tidak dijumpai.');
        }

        // Find employee by employee ID or IC number
        $employee = null;
        if ($employeeId) {
            $employee = PanelEmployee::where('panel_id', $panelId)
                ->where('employee_id', $employeeId)
                ->active()
                ->first();
        } elseif ($icNumber) {
            $employee = PanelEmployee::where('panel_id', $panelId)
                ->where('ic_number', $icNumber)
                ->active()
                ->first();

            // Check dependents if not found as employee
            if (! $employee) {
                $dependent = PanelDependent::whereHas('employee', fn ($q) => $q->where('panel_id', $panelId))
                    ->where('ic_number', $icNumber)
                    ->active()
                    ->first();

                if ($dependent) {
                    $employee = $dependent->employee;
                }
            }
        }

        if (! $employee) {
            return $this->createEligibilityResult(false, 'Pekerja/Tanggungan tidak dijumpai dalam panel ini.');
        }

        // Get active GL
        $gl = GuaranteeLetter::where('panel_id', $panelId)
            ->where('patient_id', $patientId)
            ->verified()
            ->valid()
            ->active()
            ->first();

        // Get package
        $package = $employee->package ?? $panel->getDefaultPackage();

        // Record eligibility check
        $check = PanelEligibilityCheck::create([
            'panel_id' => $panelId,
            'patient_id' => $patientId,
            'panel_employee_id' => $employee->id,
            'guarantee_letter_id' => $gl?->id,
            'check_date' => now(),
            'check_method' => 'system',
            'is_eligible' => true,
            'available_limit' => $gl?->amount_balance ?? $package?->annual_limit,
            'coverage_info' => [
                'package_name' => $package?->package_name,
                'annual_limit' => $package?->annual_limit,
                'per_visit_limit' => $package?->per_visit_limit,
                'co_payment' => $package?->co_payment_percentage,
            ],
            'checked_by' => $userId,
        ]);

        return $this->createEligibilityResult(true, 'Pesakit layak untuk panel ini.', [
            'panel' => $panel,
            'employee' => $employee,
            'package' => $package,
            'guarantee_letter' => $gl,
            'available_limit' => $gl?->amount_balance ?? $package?->annual_limit,
            'check' => $check,
        ]);
    }

    protected function createEligibilityResult(bool $eligible, string $message, array $data = []): array
    {
        return array_merge([
            'is_eligible' => $eligible,
            'message' => $message,
        ], $data);
    }

    public function getActiveGLForPatient(int $patientId, ?int $panelId = null): ?GuaranteeLetter
    {
        $query = GuaranteeLetter::where('patient_id', $patientId)
            ->verified()
            ->valid()
            ->active();

        if ($panelId) {
            $query->where('panel_id', $panelId);
        }

        return $query->first();
    }

    public function getExpiringSoon(int $days = 7): Collection
    {
        return GuaranteeLetter::with(['panel', 'patient'])
            ->verified()
            ->active()
            ->expiringSoon($days)
            ->orderBy('expiry_date')
            ->get();
    }

    public function getGLStatistics(): array
    {
        return [
            'total' => GuaranteeLetter::count(),
            'active' => GuaranteeLetter::active()->count(),
            'pending_verification' => GuaranteeLetter::where('verification_status', GuaranteeLetter::VERIFICATION_PENDING)->count(),
            'expiring_7_days' => GuaranteeLetter::verified()->active()->expiringSoon(7)->count(),
            'utilized' => GuaranteeLetter::where('status', GuaranteeLetter::STATUS_UTILIZED)->count(),
        ];
    }
}
