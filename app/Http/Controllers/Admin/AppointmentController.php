<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
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

#[Prefix('admin/appointments')]
#[Middleware(['web', 'auth'])]
class AppointmentController extends Controller
{
    use HandlesApiResponses;

    public function __construct(
        protected AuditService $auditService
    ) {}

    #[Get('/', name: 'admin.appointments.index')]
    public function index(Request $request)
    {
        $date = $request->date ? Carbon::parse($request->date) : Carbon::today();

        $query = Appointment::with(['patient', 'doctor.user'])
            ->when($request->status, fn ($q, $status) => $q->where('status', $status))
            ->when($request->doctor_id, fn ($q, $doctorId) => $q->where('doctor_id', $doctorId))
            ->when($request->view === 'all', fn ($q) => $q, fn ($q) => $q->forDate($date));

        $appointments = $query->orderBy('appointment_date')->orderBy('start_time')->paginate(25)->withQueryString();

        $doctors = Staff::whereHas('position', fn ($q) => $q->where('name', 'like', '%Doktor%'))
            ->orWhereHas('user.roles', fn ($q) => $q->where('name', 'doktor'))
            ->with('user')
            ->get();

        $statistics = [
            'total' => Appointment::forDate($date)->count(),
            'scheduled' => Appointment::forDate($date)->where('status', 'scheduled')->count(),
            'confirmed' => Appointment::forDate($date)->where('status', 'confirmed')->count(),
            'completed' => Appointment::forDate($date)->where('status', 'completed')->count(),
            'cancelled' => Appointment::forDate($date)->where('status', 'cancelled')->count(),
        ];

        return view('admin.appointments.index', compact('appointments', 'doctors', 'date', 'statistics'));
    }

    #[Get('/calendar', name: 'admin.appointments.calendar')]
    public function calendar(Request $request)
    {
        $month = $request->month ? Carbon::parse($request->month.'-01') : Carbon::now()->startOfMonth();
        $doctorId = $request->doctor_id;

        $appointments = Appointment::with(['patient', 'doctor.user'])
            ->whereBetween('appointment_date', [$month, $month->copy()->endOfMonth()])
            ->when($doctorId, fn ($q) => $q->where('doctor_id', $doctorId))
            ->orderBy('start_time')
            ->get()
            ->groupBy(fn ($a) => $a->appointment_date->format('Y-m-d'));

        $doctors = Staff::whereHas('position', fn ($q) => $q->where('name', 'like', '%Doktor%'))
            ->with('user')
            ->get();

        return view('admin.appointments.calendar', compact('appointments', 'month', 'doctors', 'doctorId'));
    }

    #[Get('/create', name: 'admin.appointments.create')]
    public function create(Request $request)
    {
        $patient = null;
        if ($request->patient_id) {
            $patient = Patient::find($request->patient_id);
        }

        $doctors = Staff::whereHas('position', fn ($q) => $q->where('name', 'like', '%Doktor%'))
            ->with('user')
            ->where('status', 'active')
            ->get();

        return view('admin.appointments.create', compact('patient', 'doctors'));
    }

    #[Post('/', name: 'admin.appointments.store')]
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:staff,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'duration_minutes' => 'required|integer|min:5|max:120',
            'appointment_type' => 'required|in:consultation,follow_up,procedure,medical_checkup,vaccination,other',
            'priority' => 'required|in:normal,urgent',
            'reason' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
            'is_panel' => 'boolean',
            'booking_source' => 'required|in:counter,phone,online,mobile_app',
        ]);

        // Check for conflicts
        $conflictExists = Appointment::where('doctor_id', $validated['doctor_id'])
            ->where('appointment_date', $validated['appointment_date'])
            ->where('start_time', $validated['start_time'])
            ->whereNotIn('status', ['cancelled', 'rescheduled'])
            ->exists();

        if ($conflictExists) {
            return $this->errorRedirect('Slot ini sudah ditempah. Sila pilih masa lain.');
        }

        try {
            DB::beginTransaction();

            $validated['created_by'] = auth()->id();
            $appointment = Appointment::create($validated);

            $this->auditService->log(
                'create',
                "Appointment created: {$appointment->appointment_no}",
                $appointment
            );

            DB::commit();

            return $this->successRedirect(
                'admin.appointments.show',
                __('Temujanji berjaya ditempah. No: :no', ['no' => $appointment->appointment_no]),
                ['appointment' => $appointment->id]
            );
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create appointment', ['error' => $e->getMessage()]);

            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Get('/{appointment}', name: 'admin.appointments.show')]
    public function show(Appointment $appointment)
    {
        $appointment->load(['patient', 'doctor.user', 'creator', 'canceller', 'originalAppointment', 'visit']);

        return view('admin.appointments.show', compact('appointment'));
    }

    #[Get('/{appointment}/edit', name: 'admin.appointments.edit')]
    public function edit(Appointment $appointment)
    {
        if (! $appointment->canBeRescheduled()) {
            return $this->errorRedirect('Temujanji ini tidak boleh diedit.');
        }

        $doctors = Staff::whereHas('position', fn ($q) => $q->where('name', 'like', '%Doktor%'))
            ->with('user')
            ->where('status', 'active')
            ->get();

        return view('admin.appointments.edit', compact('appointment', 'doctors'));
    }

    #[Patch('/{appointment}', name: 'admin.appointments.update')]
    public function update(Request $request, Appointment $appointment)
    {
        if (! $appointment->canBeRescheduled()) {
            return $this->errorRedirect('Temujanji ini tidak boleh diedit.');
        }

        $validated = $request->validate([
            'doctor_id' => 'required|exists:staff,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'duration_minutes' => 'required|integer|min:5|max:120',
            'appointment_type' => 'required|in:consultation,follow_up,procedure,medical_checkup,vaccination,other',
            'reason' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            $oldData = $appointment->toArray();

            // Check if date/time changed
            $dateChanged = $appointment->appointment_date->format('Y-m-d') !== $validated['appointment_date']
                || Carbon::parse($appointment->start_time)->format('H:i') !== $validated['start_time'];

            if ($dateChanged) {
                // Check for conflicts
                $conflictExists = Appointment::where('doctor_id', $validated['doctor_id'])
                    ->where('appointment_date', $validated['appointment_date'])
                    ->where('start_time', $validated['start_time'])
                    ->where('id', '!=', $appointment->id)
                    ->whereNotIn('status', ['cancelled', 'rescheduled'])
                    ->exists();

                if ($conflictExists) {
                    return $this->errorRedirect('Slot ini sudah ditempah. Sila pilih masa lain.');
                }
            }

            $appointment->update($validated);

            $this->auditService->log(
                'update',
                "Appointment updated: {$appointment->appointment_no}",
                $appointment,
                $oldData,
                $appointment->toArray()
            );

            return $this->successRedirect(
                'admin.appointments.show',
                __('Temujanji berjaya dikemaskini.'),
                ['appointment' => $appointment->id]
            );
        } catch (\Exception $e) {
            Log::error('Failed to update appointment', ['error' => $e->getMessage()]);

            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Patch('/{appointment}/confirm', name: 'admin.appointments.confirm')]
    public function confirm(Appointment $appointment)
    {
        try {
            $appointment->confirm();

            $this->auditService->log('update', "Appointment confirmed: {$appointment->appointment_no}", $appointment);

            return $this->successRedirect(
                'admin.appointments.index',
                __('Temujanji berjaya disahkan.')
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Patch('/{appointment}/cancel', name: 'admin.appointments.cancel')]
    public function cancel(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'cancellation_reason' => 'required|string|max:500',
        ]);

        if (! $appointment->canBeCancelled()) {
            return $this->errorRedirect('Temujanji ini tidak boleh dibatalkan.');
        }

        try {
            $appointment->cancel($validated['cancellation_reason'], auth()->id());

            $this->auditService->log('update', "Appointment cancelled: {$appointment->appointment_no}", $appointment);

            return $this->successRedirect(
                'admin.appointments.index',
                __('Temujanji berjaya dibatalkan.')
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Patch('/{appointment}/arrived', name: 'admin.appointments.arrived')]
    public function markArrived(Appointment $appointment)
    {
        try {
            $appointment->markAsArrived();

            $this->auditService->log('update', "Patient arrived: {$appointment->appointment_no}", $appointment);

            return $this->successRedirect(
                'admin.appointments.index',
                __('Pesakit telah tiba.')
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Patch('/{appointment}/no-show', name: 'admin.appointments.noShow')]
    public function markNoShow(Appointment $appointment)
    {
        try {
            $appointment->markAsNoShow();

            $this->auditService->log('update', "Marked as no-show: {$appointment->appointment_no}", $appointment);

            return $this->successRedirect(
                'admin.appointments.index',
                __('Temujanji ditanda sebagai tidak hadir.')
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    #[Get('/slots/available', name: 'admin.appointments.availableSlots')]
    public function availableSlots(Request $request)
    {
        $doctorId = $request->doctor_id;
        $date = $request->date ? Carbon::parse($request->date) : Carbon::today();

        if (! $doctorId) {
            return response()->json([]);
        }

        // Get existing appointments for this doctor on this date
        $bookedSlots = Appointment::where('doctor_id', $doctorId)
            ->where('appointment_date', $date)
            ->whereNotIn('status', ['cancelled', 'rescheduled'])
            ->pluck('start_time')
            ->map(fn ($t) => Carbon::parse($t)->format('H:i'))
            ->toArray();

        // Generate slots (9am-5pm, 15min intervals, excluding 1pm-2pm lunch)
        $slots = [];
        $current = Carbon::parse($date)->setTime(9, 0);
        $end = Carbon::parse($date)->setTime(17, 0);

        while ($current < $end) {
            $timeStr = $current->format('H:i');

            // Skip lunch hour
            if ($current->hour >= 13 && $current->hour < 14) {
                $current->addMinutes(15);

                continue;
            }

            $slots[] = [
                'time' => $timeStr,
                'available' => ! in_array($timeStr, $bookedSlots),
            ];

            $current->addMinutes(15);
        }

        return response()->json($slots);
    }
}
