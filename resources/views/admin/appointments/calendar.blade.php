@extends('layouts.admin')
@section('title', 'Kalendar Temujanji')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper breadcrumb-contacts">
        <div>
            <h1>Kalendar Temujanji</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span><a href="{{ route('admin.appointments') }}">Temujanji</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>Kalendar</span>
            </p>
        </div>
        <div>
            <a href="{{ route('admin.appointments') }}" class="btn btn-outline-secondary me-2">
                <i class="mdi mdi-format-list-bulleted"></i> Senarai
            </a>
            <a href="{{ route('admin.appointments.create') }}" class="btn btn-primary">
                <i class="mdi mdi-plus"></i> Buat Temujanji
            </a>
        </div>
    </div>

    <!-- Month Navigation & Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="d-flex align-items-center gap-3">
                        <a href="{{ route('admin.appointments.calendar', ['month' => $month->copy()->subMonth()->format('Y-m'), 'doctor_id' => $doctorId]) }}" class="btn btn-outline-secondary">
                            <i class="mdi mdi-chevron-left"></i>
                        </a>
                        <h4 class="mb-0">{{ $month->translatedFormat('F Y') }}</h4>
                        <a href="{{ route('admin.appointments.calendar', ['month' => $month->copy()->addMonth()->format('Y-m'), 'doctor_id' => $doctorId]) }}" class="btn btn-outline-secondary">
                            <i class="mdi mdi-chevron-right"></i>
                        </a>
                        <a href="{{ route('admin.appointments.calendar') }}" class="btn btn-outline-info btn-sm">
                            Hari Ini
                        </a>
                    </div>
                </div>
                <div class="col-md-6">
                    <form action="{{ route('admin.appointments.calendar') }}" method="GET" class="d-flex gap-2 justify-content-end">
                        <input type="hidden" name="month" value="{{ $month->format('Y-m') }}">
                        <select name="doctor_id" class="form-select" style="width: auto;" onchange="this.form.submit()">
                            <option value="">Semua Doktor</option>
                            @foreach($doctors as $doc)
                            <option value="{{ $doc->id }}" {{ $doctorId == $doc->id ? 'selected' : '' }}>
                                {{ $doc->user->name ?? $doc->staff_no }}
                            </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendar Grid -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0" style="table-layout: fixed;">
                    <thead>
                        <tr class="bg-light">
                            <th class="text-center py-3" style="width: 14.28%;">Ahad</th>
                            <th class="text-center py-3" style="width: 14.28%;">Isnin</th>
                            <th class="text-center py-3" style="width: 14.28%;">Selasa</th>
                            <th class="text-center py-3" style="width: 14.28%;">Rabu</th>
                            <th class="text-center py-3" style="width: 14.28%;">Khamis</th>
                            <th class="text-center py-3" style="width: 14.28%;">Jumaat</th>
                            <th class="text-center py-3" style="width: 14.28%;">Sabtu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $startOfMonth = $month->copy()->startOfMonth();
                            $endOfMonth = $month->copy()->endOfMonth();
                            $startOfCalendar = $startOfMonth->copy()->startOfWeek(\Carbon\Carbon::SUNDAY);
                            $endOfCalendar = $endOfMonth->copy()->endOfWeek(\Carbon\Carbon::SATURDAY);
                            $currentDate = $startOfCalendar->copy();
                            $today = \Carbon\Carbon::today();
                        @endphp

                        @while($currentDate <= $endOfCalendar)
                            <tr>
                                @for($i = 0; $i < 7; $i++)
                                    @php
                                        $dateKey = $currentDate->format('Y-m-d');
                                        $dayAppointments = $appointments->get($dateKey, collect());
                                        $isCurrentMonth = $currentDate->month === $month->month;
                                        $isToday = $currentDate->isSameDay($today);
                                    @endphp
                                    <td class="p-2 {{ !$isCurrentMonth ? 'bg-light text-muted' : '' }} {{ $isToday ? 'border-primary border-2' : '' }}" style="height: 120px; vertical-align: top;">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="fw-bold {{ $isToday ? 'text-primary' : '' }}">
                                                {{ $currentDate->day }}
                                            </span>
                                            @if($dayAppointments->count() > 0)
                                                <span class="badge bg-secondary">{{ $dayAppointments->count() }}</span>
                                            @endif
                                        </div>
                                        <div class="calendar-appointments" style="max-height: 80px; overflow-y: auto;">
                                            @foreach($dayAppointments->take(3) as $apt)
                                                <a href="{{ route('admin.appointments.show', $apt) }}"
                                                   class="d-block small text-truncate mb-1 p-1 rounded {{ $apt->status === 'cancelled' ? 'bg-danger-subtle text-danger' : ($apt->status === 'completed' ? 'bg-success-subtle text-success' : 'bg-info-subtle text-info') }}"
                                                   title="{{ $apt->patient->name }} - {{ $apt->formatted_time }}">
                                                    <i class="mdi mdi-clock-outline"></i>
                                                    {{ \Carbon\Carbon::parse($apt->start_time)->format('H:i') }}
                                                    {{ Str::limit($apt->patient->name, 10) }}
                                                </a>
                                            @endforeach
                                            @if($dayAppointments->count() > 3)
                                                <a href="{{ route('admin.appointments.index', ['date' => $dateKey]) }}" class="d-block small text-muted text-center">
                                                    +{{ $dayAppointments->count() - 3 }} lagi
                                                </a>
                                            @endif
                                        </div>
                                        @if($isCurrentMonth && $currentDate >= $today)
                                            <a href="{{ route('admin.appointments.create', ['date' => $dateKey]) }}"
                                               class="btn btn-sm btn-outline-primary w-100 mt-1" style="font-size: 10px;">
                                                <i class="mdi mdi-plus"></i>
                                            </a>
                                        @endif
                                    </td>
                                    @php $currentDate->addDay(); @endphp
                                @endfor
                            </tr>
                        @endwhile
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Legend -->
    <div class="card mt-3">
        <div class="card-body py-2">
            <div class="d-flex gap-4 justify-content-center">
                <span><span class="badge bg-info-subtle text-info">&#9679;</span> Dijadualkan/Disahkan</span>
                <span><span class="badge bg-success-subtle text-success">&#9679;</span> Selesai</span>
                <span><span class="badge bg-danger-subtle text-danger">&#9679;</span> Dibatalkan</span>
            </div>
        </div>
    </div>
</div>
@endsection
