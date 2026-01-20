<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveBalance;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\Staff;
use App\Services\AuditService;
use App\Traits\HandlesApiResponses;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Patch;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('admin/leave')]
#[Middleware(['web', 'auth'])]
class LeaveController extends Controller
{
    use HandlesApiResponses;

    public function __construct(
        protected AuditService $auditService
    ) {}

    #[Get('/', name: 'admin.leave.index')]
    public function index(Request $request)
    {
        $query = LeaveRequest::with(['staff.user', 'staff.department', 'leaveType', 'approver'])
            ->when($request->status, fn ($q, $status) => $q->where('status', $status))
            ->when($request->leave_type_id, fn ($q, $type) => $q->where('leave_type_id', $type))
            ->when($request->staff_id, fn ($q, $staff) => $q->where('staff_id', $staff));

        if ($request->date_from) {
            $query->where('start_date', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->where('end_date', '<=', $request->date_to);
        }

        $leaveRequests = $query->orderBy('created_at', 'desc')->paginate(25)->withQueryString();

        $leaveTypes = LeaveType::where('is_active', true)->orderBy('name')->get();
        $pendingCount = LeaveRequest::where('status', 'pending')->count();

        return view('admin.leave.index', compact('leaveRequests', 'leaveTypes', 'pendingCount'));
    }

    #[Get('/pending', name: 'admin.leave.pending')]
    public function pending()
    {
        $leaveRequests = LeaveRequest::with(['staff.user', 'staff.department', 'leaveType'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->paginate(25);

        return view('admin.leave.pending', compact('leaveRequests'));
    }

    #[Get('/calendar', name: 'admin.leave.calendar')]
    public function calendar(Request $request)
    {
        $month = $request->month ? Carbon::parse($request->month.'-01') : Carbon::now()->startOfMonth();

        $leaves = LeaveRequest::with(['staff.user', 'leaveType'])
            ->whereIn('status', ['approved', 'pending'])
            ->where(function ($q) use ($month) {
                $q->whereBetween('start_date', [$month, $month->copy()->endOfMonth()])
                    ->orWhereBetween('end_date', [$month, $month->copy()->endOfMonth()])
                    ->orWhere(function ($q) use ($month) {
                        $q->where('start_date', '<=', $month)
                            ->where('end_date', '>=', $month->copy()->endOfMonth());
                    });
            })
            ->get();

        return view('admin.leave.calendar', compact('leaves', 'month'));
    }

    #[Get('/create', name: 'admin.leave.create')]
    public function create()
    {
        $staff = Staff::with('user')->where('status', 'active')->orderBy('staff_no')->get();
        $leaveTypes = LeaveType::where('is_active', true)->orderBy('name')->get();

        return view('admin.leave.create', compact('staff', 'leaveTypes'));
    }

    #[Post('/', name: 'admin.leave.store')]
    public function store(Request $request)
    {
        $validated = $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_half' => 'nullable|in:am,pm',
            'end_half' => 'nullable|in:am,pm',
            'reason' => 'required|string|max:1000',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        try {
            DB::beginTransaction();

            // Calculate days
            $startDate = Carbon::parse($validated['start_date']);
            $endDate = Carbon::parse($validated['end_date']);
            $days = $startDate->diffInDays($endDate) + 1;

            // Adjust for half days
            if ($validated['start_half']) {
                $days -= 0.5;
            }
            if ($validated['end_half'] && $startDate->ne($endDate)) {
                $days -= 0.5;
            }

            // Check balance
            $balance = LeaveBalance::where('staff_id', $validated['staff_id'])
                ->where('leave_type_id', $validated['leave_type_id'])
                ->where('year', $startDate->year)
                ->first();

            if ($balance && $balance->remaining_days < $days) {
                return $this->errorRedirect('Baki cuti tidak mencukupi.');
            }

            // Handle attachment
            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $attachmentPath = $request->file('attachment')->store('leave-attachments', 'private');
            }

            $leaveRequest = LeaveRequest::create([
                'staff_id' => $validated['staff_id'],
                'leave_type_id' => $validated['leave_type_id'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'start_half' => $validated['start_half'],
                'end_half' => $validated['end_half'],
                'total_days' => $days,
                'reason' => $validated['reason'],
                'attachment_path' => $attachmentPath,
                'status' => 'pending',
            ]);

            $this->auditService->log(
                'create',
                "Leave request created: {$leaveRequest->staff->staff_no}",
                $leaveRequest
            );

            DB::commit();

            return $this->successRedirect(
                'admin.leave.index',
                __('Permohonan cuti berjaya dihantar.')
            );
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create leave request', ['error' => $e->getMessage()]);

            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Get('/{leaveRequest}', name: 'admin.leave.show')]
    public function show(LeaveRequest $leaveRequest)
    {
        $leaveRequest->load(['staff.user', 'staff.department', 'leaveType', 'approver']);

        $balance = LeaveBalance::where('staff_id', $leaveRequest->staff_id)
            ->where('leave_type_id', $leaveRequest->leave_type_id)
            ->where('year', $leaveRequest->start_date->year)
            ->first();

        return view('admin.leave.show', compact('leaveRequest', 'balance'));
    }

    #[Patch('/{leaveRequest}/approve', name: 'admin.leave.approve')]
    public function approve(Request $request, LeaveRequest $leaveRequest)
    {
        if ($leaveRequest->status !== 'pending') {
            return $this->errorRedirect('Permohonan ini sudah diproses.');
        }

        try {
            DB::beginTransaction();

            $leaveRequest->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'approver_remarks' => $request->input('remarks'),
            ]);

            // Deduct from balance
            $balance = LeaveBalance::where('staff_id', $leaveRequest->staff_id)
                ->where('leave_type_id', $leaveRequest->leave_type_id)
                ->where('year', $leaveRequest->start_date->year)
                ->first();

            if ($balance) {
                $balance->increment('used_days', $leaveRequest->total_days);
            }

            $this->auditService->log(
                'update',
                "Leave approved: {$leaveRequest->staff->staff_no}",
                $leaveRequest
            );

            DB::commit();

            return $this->successRedirect(
                'admin.leave.index',
                __('Permohonan cuti berjaya diluluskan.')
            );
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to approve leave', ['error' => $e->getMessage()]);

            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Patch('/{leaveRequest}/reject', name: 'admin.leave.reject')]
    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        $validated = $request->validate([
            'remarks' => 'required|string|max:500',
        ]);

        if ($leaveRequest->status !== 'pending') {
            return $this->errorRedirect('Permohonan ini sudah diproses.');
        }

        try {
            $leaveRequest->update([
                'status' => 'rejected',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'approver_remarks' => $validated['remarks'],
            ]);

            $this->auditService->log(
                'update',
                "Leave rejected: {$leaveRequest->staff->staff_no}",
                $leaveRequest
            );

            return $this->successRedirect(
                'admin.leave.index',
                __('Permohonan cuti telah ditolak.')
            );
        } catch (\Exception $e) {
            Log::error('Failed to reject leave', ['error' => $e->getMessage()]);

            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Delete('/{leaveRequest}', name: 'admin.leave.destroy')]
    public function destroy(LeaveRequest $leaveRequest)
    {
        if ($leaveRequest->status !== 'pending') {
            return $this->errorRedirect('Hanya permohonan pending boleh dibatalkan.');
        }

        try {
            $leaveRequest->delete();

            $this->auditService->log(
                'delete',
                'Leave request cancelled',
                null
            );

            return $this->successRedirect(
                'admin.leave.index',
                __('Permohonan cuti berjaya dibatalkan.')
            );
        } catch (\Exception $e) {
            Log::error('Failed to delete leave request', ['error' => $e->getMessage()]);

            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Get('/balance/{staff}', name: 'admin.leave.balance')]
    public function balance(Staff $staff)
    {
        $year = request('year', Carbon::now()->year);

        $balances = LeaveBalance::with('leaveType')
            ->where('staff_id', $staff->id)
            ->where('year', $year)
            ->get();

        $leaveTypes = LeaveType::where('is_active', true)->get();

        return view('admin.leave.balance', compact('staff', 'balances', 'leaveTypes', 'year'));
    }

    #[Patch('/balance/{staff}', name: 'admin.leave.balance.update')]
    public function updateBalance(Request $request, Staff $staff)
    {
        $validated = $request->validate([
            'balances' => 'required|array',
            'balances.*.leave_type_id' => 'required|exists:leave_types,id',
            'balances.*.entitled_days' => 'required|numeric|min:0',
            'balances.*.carried_forward' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $year = $request->input('year', Carbon::now()->year);

            foreach ($validated['balances'] as $data) {
                LeaveBalance::updateOrCreate(
                    [
                        'staff_id' => $staff->id,
                        'leave_type_id' => $data['leave_type_id'],
                        'year' => $year,
                    ],
                    [
                        'entitled_days' => $data['entitled_days'],
                        'carried_forward' => $data['carried_forward'] ?? 0,
                    ]
                );
            }

            $this->auditService->log(
                'update',
                "Leave balance updated: {$staff->staff_no}",
                $staff
            );

            DB::commit();

            return $this->successRedirect(
                'admin.leave.balance',
                __('Baki cuti berjaya dikemaskini.'),
                ['staff' => $staff->id]
            );
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update leave balance', ['error' => $e->getMessage()]);

            return $this->errorRedirect($e->getMessage());
        }
    }
}
