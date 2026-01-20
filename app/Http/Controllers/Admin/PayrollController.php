<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PayrollPeriod;
use App\Models\PayrollRecord;
use App\Models\Staff;
use App\Services\AuditService;
use App\Traits\HandlesApiResponses;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Patch;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('admin/payroll')]
#[Middleware(['web', 'auth'])]
class PayrollController extends Controller
{
    use HandlesApiResponses;

    public function __construct(
        protected AuditService $auditService
    ) {}

    #[Get('/', name: 'admin.payroll.index')]
    public function index(Request $request)
    {
        $periods = PayrollPeriod::withCount('payrollRecords')
            ->withSum('payrollRecords', 'net_salary')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->paginate(12);

        return view('admin.payroll.index', compact('periods'));
    }

    #[Get('/period/create', name: 'admin.payroll.period.create')]
    public function createPeriod()
    {
        $nextMonth = Carbon::now()->addMonth();

        return view('admin.payroll.period-create', compact('nextMonth'));
    }

    #[Post('/period', name: 'admin.payroll.period.store')]
    public function storePeriod(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required|integer|min:2020|max:2100',
            'month' => 'required|integer|min:1|max:12',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'payment_date' => 'required|date',
        ]);

        // Check if period already exists
        $exists = PayrollPeriod::where('year', $validated['year'])
            ->where('month', $validated['month'])
            ->exists();

        if ($exists) {
            return $this->errorRedirect('Tempoh gaji untuk bulan ini sudah wujud.');
        }

        try {
            $period = PayrollPeriod::create([
                'year' => $validated['year'],
                'month' => $validated['month'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'payment_date' => $validated['payment_date'],
                'status' => 'draft',
            ]);

            $this->auditService->log(
                'create',
                "Payroll period created: {$period->period_name}",
                $period
            );

            return $this->successRedirect(
                'admin.payroll.period.show',
                __('Tempoh gaji berjaya dicipta.'),
                ['period' => $period->id]
            );
        } catch (\Exception $e) {
            Log::error('Failed to create payroll period', ['error' => $e->getMessage()]);

            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Get('/period/{period}', name: 'admin.payroll.period.show')]
    public function showPeriod(PayrollPeriod $period)
    {
        $period->load(['payrollRecords.staff.user', 'payrollRecords.staff.department']);

        $records = $period->payrollRecords()
            ->with(['staff.user', 'staff.department', 'staff.position'])
            ->paginate(25);

        $summary = [
            'total_staff' => $period->payrollRecords()->count(),
            'total_gross' => $period->payrollRecords()->sum('gross_salary'),
            'total_deductions' => $period->payrollRecords()->sum('total_deductions'),
            'total_net' => $period->payrollRecords()->sum('net_salary'),
            'total_employer_contribution' => $period->payrollRecords()->sum('employer_epf')
                + $period->payrollRecords()->sum('employer_socso')
                + $period->payrollRecords()->sum('employer_eis'),
        ];

        return view('admin.payroll.period-show', compact('period', 'records', 'summary'));
    }

    #[Post('/period/{period}/generate', name: 'admin.payroll.period.generate')]
    public function generatePayroll(PayrollPeriod $period)
    {
        if ($period->status !== 'draft') {
            return $this->errorRedirect('Hanya tempoh draft boleh dijana.');
        }

        try {
            DB::beginTransaction();

            // Get all active staff
            $staffList = Staff::where('status', 'active')
                ->whereNotNull('basic_salary')
                ->with(['department', 'position'])
                ->get();

            foreach ($staffList as $staff) {
                // Check if record already exists
                $existing = PayrollRecord::where('payroll_period_id', $period->id)
                    ->where('staff_id', $staff->id)
                    ->first();

                if ($existing) {
                    continue;
                }

                // Calculate salary components
                $basicSalary = $staff->basic_salary;
                $allowances = 0;
                $overtime = 0;
                $commission = 0;

                // Calculate deductions
                $employeeEpf = $basicSalary * 0.11; // 11% employee EPF
                $employerEpf = $basicSalary * 0.12; // 12% employer EPF
                $employeeSocso = min($basicSalary * 0.005, 39.50); // Approximate
                $employerSocso = min($basicSalary * 0.0175, 138.25); // Approximate
                $employeeEis = min($basicSalary * 0.002, 19.75);
                $employerEis = min($basicSalary * 0.002, 19.75);
                $pcb = 0; // Would need proper tax calculation

                $grossSalary = $basicSalary + $allowances + $overtime + $commission;
                $totalDeductions = $employeeEpf + $employeeSocso + $employeeEis + $pcb;
                $netSalary = $grossSalary - $totalDeductions;

                PayrollRecord::create([
                    'payroll_period_id' => $period->id,
                    'staff_id' => $staff->id,
                    'basic_salary' => $basicSalary,
                    'allowances' => $allowances,
                    'overtime_pay' => $overtime,
                    'commission' => $commission,
                    'gross_salary' => $grossSalary,
                    'employee_epf' => $employeeEpf,
                    'employer_epf' => $employerEpf,
                    'employee_socso' => $employeeSocso,
                    'employer_socso' => $employerSocso,
                    'employee_eis' => $employeeEis,
                    'employer_eis' => $employerEis,
                    'pcb' => $pcb,
                    'other_deductions' => 0,
                    'total_deductions' => $totalDeductions,
                    'net_salary' => $netSalary,
                    'status' => 'draft',
                ]);
            }

            $period->update(['status' => 'processing']);

            $this->auditService->log(
                'create',
                "Payroll generated: {$period->period_name}",
                $period
            );

            DB::commit();

            return $this->successRedirect(
                'admin.payroll.period.show',
                __('Gaji berjaya dijana untuk :count kakitangan.', ['count' => $staffList->count()]),
                ['period' => $period->id]
            );
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to generate payroll', ['error' => $e->getMessage()]);

            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Get('/record/{record}', name: 'admin.payroll.record.show')]
    public function showRecord(PayrollRecord $record)
    {
        $record->load(['staff.user', 'staff.department', 'staff.position', 'payrollPeriod', 'items']);

        return view('admin.payroll.record-show', compact('record'));
    }

    #[Get('/record/{record}/edit', name: 'admin.payroll.record.edit')]
    public function editRecord(PayrollRecord $record)
    {
        if ($record->status !== 'draft') {
            return $this->errorRedirect('Hanya rekod draft boleh diedit.');
        }

        $record->load(['staff.user', 'payrollPeriod', 'items']);

        return view('admin.payroll.record-edit', compact('record'));
    }

    #[Patch('/record/{record}', name: 'admin.payroll.record.update')]
    public function updateRecord(Request $request, PayrollRecord $record)
    {
        if ($record->status !== 'draft') {
            return $this->errorRedirect('Hanya rekod draft boleh diedit.');
        }

        $validated = $request->validate([
            'basic_salary' => 'required|numeric|min:0',
            'allowances' => 'required|numeric|min:0',
            'overtime_pay' => 'required|numeric|min:0',
            'commission' => 'required|numeric|min:0',
            'employee_epf' => 'required|numeric|min:0',
            'employee_socso' => 'required|numeric|min:0',
            'employee_eis' => 'required|numeric|min:0',
            'pcb' => 'required|numeric|min:0',
            'other_deductions' => 'required|numeric|min:0',
        ]);

        try {
            $grossSalary = $validated['basic_salary'] + $validated['allowances']
                + $validated['overtime_pay'] + $validated['commission'];

            $totalDeductions = $validated['employee_epf'] + $validated['employee_socso']
                + $validated['employee_eis'] + $validated['pcb'] + $validated['other_deductions'];

            $netSalary = $grossSalary - $totalDeductions;

            $record->update([
                'basic_salary' => $validated['basic_salary'],
                'allowances' => $validated['allowances'],
                'overtime_pay' => $validated['overtime_pay'],
                'commission' => $validated['commission'],
                'gross_salary' => $grossSalary,
                'employee_epf' => $validated['employee_epf'],
                'employee_socso' => $validated['employee_socso'],
                'employee_eis' => $validated['employee_eis'],
                'pcb' => $validated['pcb'],
                'other_deductions' => $validated['other_deductions'],
                'total_deductions' => $totalDeductions,
                'net_salary' => $netSalary,
            ]);

            $this->auditService->log(
                'update',
                "Payroll record updated: {$record->staff->staff_no}",
                $record
            );

            return $this->successRedirect(
                'admin.payroll.record.show',
                __('Rekod gaji berjaya dikemaskini.'),
                ['record' => $record->id]
            );
        } catch (\Exception $e) {
            Log::error('Failed to update payroll record', ['error' => $e->getMessage()]);

            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Post('/period/{period}/finalize', name: 'admin.payroll.period.finalize')]
    public function finalizePeriod(PayrollPeriod $period)
    {
        if ($period->status !== 'processing') {
            return $this->errorRedirect('Hanya tempoh dalam pemprosesan boleh dimuktamadkan.');
        }

        try {
            DB::beginTransaction();

            $period->update([
                'status' => 'finalized',
                'finalized_at' => now(),
                'finalized_by' => auth()->id(),
            ]);

            // Update all records to approved
            $period->payrollRecords()->update(['status' => 'approved']);

            $this->auditService->log(
                'update',
                "Payroll finalized: {$period->period_name}",
                $period
            );

            DB::commit();

            return $this->successRedirect(
                'admin.payroll.period.show',
                __('Gaji berjaya dimuktamadkan.'),
                ['period' => $period->id]
            );
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to finalize payroll', ['error' => $e->getMessage()]);

            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Post('/period/{period}/pay', name: 'admin.payroll.period.pay')]
    public function markAsPaid(PayrollPeriod $period)
    {
        if ($period->status !== 'finalized') {
            return $this->errorRedirect('Hanya tempoh yang dimuktamadkan boleh ditandakan sebagai dibayar.');
        }

        try {
            DB::beginTransaction();

            $period->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            // Update all records to paid
            $period->payrollRecords()->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            $this->auditService->log(
                'update',
                "Payroll marked as paid: {$period->period_name}",
                $period
            );

            DB::commit();

            return $this->successRedirect(
                'admin.payroll.period.show',
                __('Gaji berjaya ditandakan sebagai dibayar.'),
                ['period' => $period->id]
            );
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to mark payroll as paid', ['error' => $e->getMessage()]);

            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Get('/record/{record}/payslip', name: 'admin.payroll.record.payslip')]
    public function generatePayslip(PayrollRecord $record)
    {
        $record->load(['staff.user', 'staff.department', 'staff.position', 'payrollPeriod', 'items']);

        return view('admin.payroll.payslip', compact('record'));
    }
}
