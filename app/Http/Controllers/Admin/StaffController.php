<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Position;
use App\Models\Staff;
use App\Models\User;
use App\Services\AuditService;
use App\Traits\HandlesApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Patch;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('admin/staff')]
#[Middleware(['web', 'auth'])]
class StaffController extends Controller
{
    use HandlesApiResponses;

    public function __construct(
        protected AuditService $auditService
    ) {}

    #[Get('/', name: 'admin.staff.index')]
    public function index(Request $request)
    {
        $query = Staff::with(['user', 'department', 'position'])
            ->when($request->search, fn ($q, $search) => $q->where(function ($q) use ($search) {
                $q->where('staff_no', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($q) => $q->where('name', 'like', "%{$search}%"));
            }))
            ->when($request->department_id, fn ($q, $dept) => $q->where('department_id', $dept))
            ->when($request->position_id, fn ($q, $pos) => $q->where('position_id', $pos))
            ->when($request->employment_type, fn ($q, $type) => $q->where('employment_type', $type))
            ->when($request->status, fn ($q, $status) => $q->where('status', $status));

        $staff = $query->orderBy('staff_no')->paginate(25)->withQueryString();

        $departments = Department::where('is_active', true)->orderBy('name')->get();
        $positions = Position::where('is_active', true)->orderBy('name')->get();

        return view('admin.staff.index', compact('staff', 'departments', 'positions'));
    }

    #[Get('/create', name: 'admin.staff.create')]
    public function create()
    {
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        $positions = Position::where('is_active', true)->orderBy('name')->get();
        $users = User::whereDoesntHave('staff')->orderBy('name')->get();

        return view('admin.staff.create', compact('departments', 'positions', 'users'));
    }

    #[Post('/', name: 'admin.staff.store')]
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:staff,user_id',
            'staff_no' => 'required|string|max:20|unique:staff,staff_no',
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'employment_type' => 'required|in:permanent,contract,part_time,locum',
            'join_date' => 'required|date',
            'probation_end_date' => 'nullable|date|after:join_date',
            'ic_number' => 'required|string|max:20',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female',
            'marital_status' => 'nullable|in:single,married,divorced,widowed',
            'nationality' => 'nullable|string|max:50',
            'religion' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'emergency_contact_name' => 'nullable|string|max:100',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relationship' => 'nullable|string|max:50',
            'bank_name' => 'nullable|string|max:100',
            'bank_account_no' => 'nullable|string|max:30',
            'epf_no' => 'nullable|string|max:20',
            'socso_no' => 'nullable|string|max:20',
            'tax_no' => 'nullable|string|max:20',
            'basic_salary' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $staff = Staff::create($validated);

            $this->auditService->log(
                'create',
                "Staff created: {$staff->staff_no}",
                $staff,
                metadata: ['staff_id' => $staff->id]
            );

            DB::commit();

            return $this->successRedirect(
                'admin.staff.index',
                __('Kakitangan berjaya ditambah.')
            );
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create staff', ['error' => $e->getMessage()]);

            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Get('/{staff}', name: 'admin.staff.show')]
    public function show(Staff $staff)
    {
        $staff->load([
            'user',
            'department',
            'position',
            'documents',
            'leaveBalances.leaveType',
            'attendances' => fn ($q) => $q->latest()->limit(10),
        ]);

        return view('admin.staff.show', compact('staff'));
    }

    #[Get('/{staff}/edit', name: 'admin.staff.edit')]
    public function edit(Staff $staff)
    {
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        $positions = Position::where('is_active', true)->orderBy('name')->get();

        return view('admin.staff.edit', compact('staff', 'departments', 'positions'));
    }

    #[Patch('/{staff}', name: 'admin.staff.update')]
    public function update(Request $request, Staff $staff)
    {
        $validated = $request->validate([
            'staff_no' => "required|string|max:20|unique:staff,staff_no,{$staff->id}",
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'employment_type' => 'required|in:permanent,contract,part_time,locum',
            'join_date' => 'required|date',
            'probation_end_date' => 'nullable|date|after:join_date',
            'contract_end_date' => 'nullable|date|after:join_date',
            'ic_number' => 'required|string|max:20',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female',
            'marital_status' => 'nullable|in:single,married,divorced,widowed',
            'nationality' => 'nullable|string|max:50',
            'religion' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'emergency_contact_name' => 'nullable|string|max:100',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relationship' => 'nullable|string|max:50',
            'bank_name' => 'nullable|string|max:100',
            'bank_account_no' => 'nullable|string|max:30',
            'epf_no' => 'nullable|string|max:20',
            'socso_no' => 'nullable|string|max:20',
            'tax_no' => 'nullable|string|max:20',
            'basic_salary' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive,resigned,terminated',
        ]);

        try {
            $oldData = $staff->toArray();
            $staff->update($validated);

            $this->auditService->log(
                'update',
                "Staff updated: {$staff->staff_no}",
                $staff,
                $oldData,
                $staff->toArray()
            );

            return $this->successRedirect(
                'admin.staff.show',
                __('Maklumat kakitangan berjaya dikemaskini.'),
                ['staff' => $staff->id]
            );
        } catch (\Exception $e) {
            Log::error('Failed to update staff', ['error' => $e->getMessage()]);

            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Delete('/{staff}', name: 'admin.staff.destroy')]
    public function destroy(Staff $staff)
    {
        try {
            $staffNo = $staff->staff_no;
            $staff->delete();

            $this->auditService->log(
                'delete',
                "Staff deleted: {$staffNo}",
                null,
                metadata: ['staff_no' => $staffNo]
            );

            return $this->successRedirect(
                'admin.staff.index',
                __('Kakitangan berjaya dipadam.')
            );
        } catch (\Exception $e) {
            Log::error('Failed to delete staff', ['error' => $e->getMessage()]);

            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Patch('/{staff}/resign', name: 'admin.staff.resign')]
    public function resign(Request $request, Staff $staff)
    {
        $validated = $request->validate([
            'resignation_date' => 'required|date',
            'resignation_reason' => 'nullable|string|max:500',
        ]);

        try {
            $staff->update([
                'status' => 'resigned',
                'resignation_date' => $validated['resignation_date'],
                'notes' => $validated['resignation_reason'] ?? null,
            ]);

            $this->auditService->log(
                'update',
                "Staff resigned: {$staff->staff_no}",
                $staff
            );

            return $this->successRedirect(
                'admin.staff.show',
                __('Status perletakan jawatan berjaya dikemaskini.'),
                ['staff' => $staff->id]
            );
        } catch (\Exception $e) {
            Log::error('Failed to process resignation', ['error' => $e->getMessage()]);

            return $this->errorRedirect($e->getMessage());
        }
    }
}
