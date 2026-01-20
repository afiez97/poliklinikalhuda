<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\Staff;
use App\Services\AuditService;
use App\Traits\HandlesApiResponses;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Patch;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('admin/attendance')]
#[Middleware(['web', 'auth'])]
class AttendanceController extends Controller
{
    use HandlesApiResponses;

    public function __construct(
        protected AuditService $auditService
    ) {}

    #[Get('/', name: 'admin.attendance.index')]
    public function index(Request $request)
    {
        $date = $request->date ? Carbon::parse($request->date) : Carbon::today();

        $query = Attendance::with(['staff.user', 'staff.department', 'shift'])
            ->where('attendance_date', $date)
            ->when($request->department_id, fn ($q, $dept) => $q->whereHas('staff', fn ($q) => $q->where('department_id', $dept)))
            ->when($request->status, fn ($q, $status) => $q->where('status', $status));

        $attendances = $query->orderBy('clock_in', 'desc')->paginate(25)->withQueryString();

        $departments = Department::where('is_active', true)->orderBy('name')->get();

        $statistics = [
            'total' => Attendance::where('attendance_date', $date)->count(),
            'present' => Attendance::where('attendance_date', $date)->where('status', 'present')->count(),
            'late' => Attendance::where('attendance_date', $date)->where('status', 'late')->count(),
            'absent' => Attendance::where('attendance_date', $date)->where('status', 'absent')->count(),
            'leave' => Attendance::where('attendance_date', $date)->where('status', 'leave')->count(),
        ];

        return view('admin.attendance.index', compact('attendances', 'departments', 'date', 'statistics'));
    }

    #[Get('/report', name: 'admin.attendance.report')]
    public function report(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();

        $query = Staff::with(['user', 'department'])
            ->withCount([
                'attendances as present_days' => fn ($q) => $q->whereBetween('attendance_date', [$startDate, $endDate])
                    ->whereIn('status', ['present', 'late']),
                'attendances as late_days' => fn ($q) => $q->whereBetween('attendance_date', [$startDate, $endDate])
                    ->where('status', 'late'),
                'attendances as absent_days' => fn ($q) => $q->whereBetween('attendance_date', [$startDate, $endDate])
                    ->where('status', 'absent'),
                'attendances as leave_days' => fn ($q) => $q->whereBetween('attendance_date', [$startDate, $endDate])
                    ->where('status', 'leave'),
            ])
            ->withSum([
                'attendances as total_hours' => fn ($q) => $q->whereBetween('attendance_date', [$startDate, $endDate]),
            ], 'hours_worked')
            ->withSum([
                'attendances as overtime_hours' => fn ($q) => $q->whereBetween('attendance_date', [$startDate, $endDate]),
            ], 'overtime_hours')
            ->where('status', 'active');

        if ($request->department_id) {
            $query->where('department_id', $request->department_id);
        }

        $staffReport = $query->orderBy('staff_no')->paginate(25)->withQueryString();

        $departments = Department::where('is_active', true)->orderBy('name')->get();

        return view('admin.attendance.report', compact('staffReport', 'departments', 'startDate', 'endDate'));
    }

    #[Get('/staff/{staff}', name: 'admin.attendance.staff')]
    public function staffAttendance(Request $request, Staff $staff)
    {
        $month = $request->month ? Carbon::parse($request->month.'-01') : Carbon::now()->startOfMonth();

        $attendances = Attendance::with('shift')
            ->where('staff_id', $staff->id)
            ->whereBetween('attendance_date', [$month, $month->copy()->endOfMonth()])
            ->orderBy('attendance_date')
            ->get()
            ->keyBy(fn ($att) => $att->attendance_date->format('Y-m-d'));

        $calendar = [];
        $current = $month->copy();
        while ($current->lte($month->copy()->endOfMonth())) {
            $dateKey = $current->format('Y-m-d');
            $calendar[$dateKey] = $attendances->get($dateKey);
            $current->addDay();
        }

        $summary = [
            'present' => $attendances->whereIn('status', ['present', 'late'])->count(),
            'late' => $attendances->where('status', 'late')->count(),
            'absent' => $attendances->where('status', 'absent')->count(),
            'leave' => $attendances->where('status', 'leave')->count(),
            'total_hours' => $attendances->sum('hours_worked'),
            'overtime_hours' => $attendances->sum('overtime_hours'),
        ];

        return view('admin.attendance.staff', compact('staff', 'calendar', 'month', 'summary', 'attendances'));
    }

    #[Post('/clock-in', name: 'admin.attendance.clockIn')]
    public function clockIn(Request $request)
    {
        $validated = $request->validate([
            'staff_id' => 'required|exists:staff,id',
        ]);

        try {
            $staff = Staff::findOrFail($validated['staff_id']);

            // Check if already clocked in today
            $existing = Attendance::where('staff_id', $staff->id)
                ->where('attendance_date', Carbon::today())
                ->first();

            if ($existing && $existing->clock_in) {
                return $this->errorRedirect('Kakitangan sudah clock in hari ini.');
            }

            $attendance = $existing ?? Attendance::create([
                'staff_id' => $staff->id,
                'attendance_date' => Carbon::today(),
                'shift_id' => $staff->currentRoster?->shift_id,
            ]);

            $attendance->clockIn(
                $request->ip(),
                $request->input('location'),
                $request->input('lat'),
                $request->input('lng')
            );

            $this->auditService->log(
                'create',
                "Clock in: {$staff->staff_no}",
                $attendance
            );

            return $this->successRedirect(
                'admin.attendance.index',
                __('Clock in berjaya direkodkan.')
            );
        } catch (\Exception $e) {
            Log::error('Failed to clock in', ['error' => $e->getMessage()]);

            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Post('/clock-out', name: 'admin.attendance.clockOut')]
    public function clockOut(Request $request)
    {
        $validated = $request->validate([
            'staff_id' => 'required|exists:staff,id',
        ]);

        try {
            $staff = Staff::findOrFail($validated['staff_id']);

            $attendance = Attendance::where('staff_id', $staff->id)
                ->where('attendance_date', Carbon::today())
                ->whereNotNull('clock_in')
                ->whereNull('clock_out')
                ->first();

            if (! $attendance) {
                return $this->errorRedirect('Tiada rekod clock in untuk hari ini.');
            }

            $attendance->clockOut(
                $request->ip(),
                $request->input('location'),
                $request->input('lat'),
                $request->input('lng')
            );

            $this->auditService->log(
                'update',
                "Clock out: {$staff->staff_no}",
                $attendance
            );

            return $this->successRedirect(
                'admin.attendance.index',
                __('Clock out berjaya direkodkan.')
            );
        } catch (\Exception $e) {
            Log::error('Failed to clock out', ['error' => $e->getMessage()]);

            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Patch('/{attendance}/approve', name: 'admin.attendance.approve')]
    public function approve(Attendance $attendance)
    {
        try {
            $attendance->update([
                'is_approved' => true,
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            $this->auditService->log(
                'update',
                "Attendance approved: {$attendance->staff->staff_no} ({$attendance->attendance_date->format('d/m/Y')})",
                $attendance
            );

            return $this->successRedirect(
                'admin.attendance.index',
                __('Kehadiran berjaya diluluskan.')
            );
        } catch (\Exception $e) {
            Log::error('Failed to approve attendance', ['error' => $e->getMessage()]);

            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Patch('/{attendance}/manual', name: 'admin.attendance.manual')]
    public function manualEntry(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'clock_in' => 'required|date_format:H:i',
            'clock_out' => 'nullable|date_format:H:i|after:clock_in',
            'status' => 'required|in:present,late,absent,half_day,leave',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $date = $attendance->attendance_date;

            $attendance->update([
                'clock_in' => Carbon::parse($date->format('Y-m-d').' '.$validated['clock_in']),
                'clock_out' => $validated['clock_out']
                    ? Carbon::parse($date->format('Y-m-d').' '.$validated['clock_out'])
                    : null,
                'status' => $validated['status'],
                'notes' => $validated['notes'],
            ]);

            // Recalculate hours if both clock in and out
            if ($attendance->clock_in && $attendance->clock_out) {
                $attendance->hours_worked = $attendance->clock_in->diffInMinutes($attendance->clock_out) / 60;
                $attendance->save();
            }

            $this->auditService->log(
                'update',
                "Manual attendance entry: {$attendance->staff->staff_no}",
                $attendance
            );

            return $this->successRedirect(
                'admin.attendance.staff',
                __('Kehadiran berjaya dikemaskini.'),
                ['staff' => $attendance->staff_id]
            );
        } catch (\Exception $e) {
            Log::error('Failed to update attendance', ['error' => $e->getMessage()]);

            return $this->errorRedirect($e->getMessage());
        }
    }
}
