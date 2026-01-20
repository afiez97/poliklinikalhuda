<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Roster;
use App\Models\Shift;
use App\Models\Staff;
use App\Services\AuditService;
use App\Traits\HandlesApiResponses;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('admin/roster')]
#[Middleware(['web', 'auth'])]
class RosterController extends Controller
{
    use HandlesApiResponses;

    public function __construct(
        protected AuditService $auditService
    ) {}

    #[Get('/', name: 'admin.roster.index')]
    public function index(Request $request)
    {
        $weekStart = $request->week
            ? Carbon::parse($request->week)
            : Carbon::now()->startOfWeek();

        $weekEnd = $weekStart->copy()->endOfWeek();

        $staffQuery = Staff::with('user')
            ->where('status', 'active')
            ->when($request->department_id, fn ($q, $dept) => $q->where('department_id', $dept));

        $staffList = $staffQuery->orderBy('staff_no')->get();

        // Get rosters for the week
        $rosters = Roster::with('shift')
            ->whereBetween('roster_date', [$weekStart, $weekEnd])
            ->whereIn('staff_id', $staffList->pluck('id'))
            ->get()
            ->groupBy(fn ($r) => $r->staff_id.'-'.$r->roster_date->format('Y-m-d'));

        // Generate week days
        $weekDays = collect(CarbonPeriod::create($weekStart, $weekEnd))
            ->mapWithKeys(fn ($date) => [$date->format('Y-m-d') => $date]);

        $shifts = Shift::where('is_active', true)->orderBy('name')->get();
        $departments = Department::where('is_active', true)->orderBy('name')->get();

        return view('admin.roster.index', compact(
            'staffList',
            'rosters',
            'weekDays',
            'weekStart',
            'weekEnd',
            'shifts',
            'departments'
        ));
    }

    #[Get('/staff/{staff}', name: 'admin.roster.staff')]
    public function staffRoster(Request $request, Staff $staff)
    {
        $month = $request->month
            ? Carbon::parse($request->month.'-01')
            : Carbon::now()->startOfMonth();

        $rosters = Roster::with('shift')
            ->where('staff_id', $staff->id)
            ->whereBetween('roster_date', [$month, $month->copy()->endOfMonth()])
            ->get()
            ->keyBy(fn ($r) => $r->roster_date->format('Y-m-d'));

        $shifts = Shift::where('is_active', true)->orderBy('name')->get();

        $calendar = [];
        $current = $month->copy();
        while ($current->lte($month->copy()->endOfMonth())) {
            $dateKey = $current->format('Y-m-d');
            $calendar[$dateKey] = [
                'date' => $current->copy(),
                'roster' => $rosters->get($dateKey),
            ];
            $current->addDay();
        }

        return view('admin.roster.staff', compact('staff', 'calendar', 'month', 'shifts'));
    }

    #[Post('/', name: 'admin.roster.store')]
    public function store(Request $request)
    {
        $validated = $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'roster_date' => 'required|date',
            'shift_id' => 'required|exists:shifts,id',
            'notes' => 'nullable|string|max:255',
        ]);

        try {
            $roster = Roster::updateOrCreate(
                [
                    'staff_id' => $validated['staff_id'],
                    'roster_date' => $validated['roster_date'],
                ],
                [
                    'shift_id' => $validated['shift_id'],
                    'notes' => $validated['notes'],
                    'is_published' => false,
                ]
            );

            $this->auditService->log(
                'create',
                "Roster assigned: {$roster->staff->staff_no} on {$roster->roster_date->format('d/m/Y')}",
                $roster
            );

            if ($request->ajax()) {
                return response()->json(['success' => true, 'roster' => $roster->load('shift')]);
            }

            return $this->successRedirect(
                'admin.roster.index',
                __('Jadual kerja berjaya ditambah.')
            );
        } catch (\Exception $e) {
            Log::error('Failed to create roster', ['error' => $e->getMessage()]);

            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }

            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Post('/bulk', name: 'admin.roster.bulk')]
    public function bulkStore(Request $request)
    {
        $validated = $request->validate([
            'staff_ids' => 'required|array',
            'staff_ids.*' => 'exists:staff,id',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'shift_id' => 'required|exists:shifts,id',
            'days' => 'required|array',
            'days.*' => 'integer|min:0|max:6', // 0=Sunday, 6=Saturday
        ]);

        try {
            DB::beginTransaction();

            $count = 0;
            $period = CarbonPeriod::create($validated['date_from'], $validated['date_to']);

            foreach ($validated['staff_ids'] as $staffId) {
                foreach ($period as $date) {
                    // Check if this day of week is selected
                    if (! in_array($date->dayOfWeek, $validated['days'])) {
                        continue;
                    }

                    Roster::updateOrCreate(
                        [
                            'staff_id' => $staffId,
                            'roster_date' => $date->format('Y-m-d'),
                        ],
                        [
                            'shift_id' => $validated['shift_id'],
                            'is_published' => false,
                        ]
                    );
                    $count++;
                }
            }

            $this->auditService->log(
                'create',
                "Bulk roster created: {$count} entries",
                null,
                metadata: [
                    'count' => $count,
                    'date_range' => "{$validated['date_from']} - {$validated['date_to']}",
                ]
            );

            DB::commit();

            return $this->successRedirect(
                'admin.roster.index',
                __(':count jadual kerja berjaya ditambah.', ['count' => $count])
            );
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create bulk roster', ['error' => $e->getMessage()]);

            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Delete('/{roster}', name: 'admin.roster.destroy')]
    public function destroy(Request $request, Roster $roster)
    {
        try {
            $roster->delete();

            if ($request->ajax()) {
                return response()->json(['success' => true]);
            }

            return $this->successRedirect(
                'admin.roster.index',
                __('Jadual kerja berjaya dipadam.')
            );
        } catch (\Exception $e) {
            Log::error('Failed to delete roster', ['error' => $e->getMessage()]);

            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }

            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Post('/publish', name: 'admin.roster.publish')]
    public function publish(Request $request)
    {
        $validated = $request->validate([
            'week' => 'required|date',
        ]);

        try {
            $weekStart = Carbon::parse($validated['week'])->startOfWeek();
            $weekEnd = $weekStart->copy()->endOfWeek();

            $count = Roster::whereBetween('roster_date', [$weekStart, $weekEnd])
                ->where('is_published', false)
                ->update([
                    'is_published' => true,
                    'published_at' => now(),
                    'published_by' => auth()->id(),
                ]);

            $this->auditService->log(
                'update',
                "Roster published for week: {$weekStart->format('d/m/Y')} - {$weekEnd->format('d/m/Y')}",
                null,
                metadata: ['count' => $count]
            );

            return $this->successRedirect(
                'admin.roster.index',
                __(':count jadual kerja berjaya diterbitkan.', ['count' => $count])
            );
        } catch (\Exception $e) {
            Log::error('Failed to publish roster', ['error' => $e->getMessage()]);

            return $this->errorRedirect($e->getMessage());
        }
    }
}
