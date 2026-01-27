@extends('layouts.admin')

@section('title', 'Kehadiran Staf - ' . ($staff->user->name ?? $staff->name ?? 'Staf'))

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between">
        <div>
            <h1>Rekod Kehadiran Staf</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.attendance.index') }}">Kehadiran</a></li>
                    <li class="breadcrumb-item active">{{ $staff->user->name ?? $staff->name ?? 'Staf' }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.attendance.report') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Staff Info Card -->
    <div class="card card-default mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-auto">
                    @if($staff->user && $staff->user->avatar)
                    <img src="{{ asset('storage/'.$staff->user->avatar) }}" class="rounded-circle"
                         width="80" height="80" alt="">
                    @else
                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center"
                         style="width:80px;height:80px;">
                        <i class="bi bi-person fs-1 text-white"></i>
                    </div>
                    @endif
                </div>
                <div class="col">
                    <h4 class="mb-1">{{ $staff->user->name ?? $staff->name ?? '-' }}</h4>
                    <p class="text-muted mb-0">
                        <code>{{ $staff->staff_no }}</code> &bull;
                        {{ $staff->department->name ?? '-' }} &bull;
                        {{ $staff->position ?? '-' }}
                    </p>
                </div>
                <div class="col-auto">
                    <form method="GET" action="{{ route('admin.attendance.staff', $staff) }}" class="d-flex gap-2">
                        <input type="month" name="month" class="form-control" value="{{ $month->format('Y-m') }}">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card card-default h-100">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-success">{{ $summary['present'] }}</h3>
                    <small class="text-muted">Hadir</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card card-default h-100">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-warning">{{ $summary['late'] }}</h3>
                    <small class="text-muted">Lewat</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card card-default h-100">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-danger">{{ $summary['absent'] }}</h3>
                    <small class="text-muted">Tidak Hadir</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card card-default h-100">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-info">{{ $summary['leave'] }}</h3>
                    <small class="text-muted">Cuti</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card card-default h-100">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-primary">{{ number_format($summary['total_hours'], 1) }}</h3>
                    <small class="text-muted">Jumlah Jam</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card card-default h-100">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-secondary">{{ number_format($summary['overtime_hours'], 1) }}</h3>
                    <small class="text-muted">Overtime</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Calendar View -->
        <div class="col-lg-7">
            <div class="card card-default">
                <div class="card-header">
                    <h2 class="mb-0">
                        <i class="bi bi-calendar3 me-2"></i>
                        {{ $month->locale('ms')->translatedFormat('F Y') }}
                    </h2>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered mb-0 attendance-calendar">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">Ahd</th>
                                <th class="text-center">Isn</th>
                                <th class="text-center">Sel</th>
                                <th class="text-center">Rab</th>
                                <th class="text-center">Kha</th>
                                <th class="text-center">Jum</th>
                                <th class="text-center">Sab</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $firstDay = $month->copy()->startOfMonth();
                                $lastDay = $month->copy()->endOfMonth();
                                $currentDate = $firstDay->copy()->startOfWeek(Carbon\Carbon::SUNDAY);
                            @endphp

                            @while($currentDate->lte($lastDay) || $currentDate->dayOfWeek != Carbon\Carbon::SUNDAY)
                            @if($currentDate->dayOfWeek == Carbon\Carbon::SUNDAY)
                            <tr>
                            @endif

                            @php
                                $dateKey = $currentDate->format('Y-m-d');
                                $attendance = $calendar[$dateKey] ?? null;
                                $isCurrentMonth = $currentDate->month == $month->month;
                                $isToday = $currentDate->isToday();
                                $isWeekend = $currentDate->isWeekend();
                            @endphp

                            <td class="text-center p-2 {{ !$isCurrentMonth ? 'text-muted bg-light' : '' }} {{ $isToday ? 'border-primary border-2' : '' }} {{ $isWeekend && $isCurrentMonth ? 'bg-light' : '' }}"
                                style="min-height: 60px; vertical-align: top;">
                                <div class="fw-bold {{ $isToday ? 'text-primary' : '' }}">{{ $currentDate->day }}</div>
                                @if($isCurrentMonth && $attendance)
                                    @switch($attendance->status)
                                        @case('present')
                                            <span class="badge bg-success w-100 mt-1" title="Hadir">
                                                <i class="bi bi-check"></i>
                                            </span>
                                        @break
                                        @case('late')
                                            <span class="badge bg-warning w-100 mt-1" title="Lewat">
                                                <i class="bi bi-clock"></i>
                                            </span>
                                        @break
                                        @case('absent')
                                            <span class="badge bg-danger w-100 mt-1" title="Tidak Hadir">
                                                <i class="bi bi-x"></i>
                                            </span>
                                        @break
                                        @case('leave')
                                            <span class="badge bg-info w-100 mt-1" title="Cuti">
                                                <i class="bi bi-calendar-x"></i>
                                            </span>
                                        @break
                                        @case('half_day')
                                            <span class="badge bg-secondary w-100 mt-1" title="Separuh Hari">
                                                <i class="bi bi-hourglass-split"></i>
                                            </span>
                                        @break
                                    @endswitch
                                    @if($attendance->clock_in)
                                    <small class="d-block text-muted" style="font-size:0.65rem;">
                                        {{ $attendance->clock_in->format('H:i') }}
                                    </small>
                                    @endif
                                @endif
                            </td>

                            @if($currentDate->dayOfWeek == Carbon\Carbon::SATURDAY)
                            </tr>
                            @endif

                            @php $currentDate->addDay(); @endphp
                            @endwhile
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <span><span class="badge bg-success">&nbsp;</span> Hadir</span>
                        <span><span class="badge bg-warning">&nbsp;</span> Lewat</span>
                        <span><span class="badge bg-danger">&nbsp;</span> Tidak Hadir</span>
                        <span><span class="badge bg-info">&nbsp;</span> Cuti</span>
                        <span><span class="badge bg-secondary">&nbsp;</span> Separuh Hari</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance List -->
        <div class="col-lg-5">
            <div class="card card-default">
                <div class="card-header">
                    <h2 class="mb-0">
                        <i class="bi bi-list-ul me-2"></i>
                        Rekod Terperinci
                    </h2>
                </div>
                <div class="card-body p-0" style="max-height: 500px; overflow-y: auto;">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>Tarikh</th>
                                <th>Masuk</th>
                                <th>Keluar</th>
                                <th>Jam</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendances->sortByDesc('attendance_date') as $att)
                            <tr>
                                <td>
                                    <strong>{{ $att->attendance_date->format('d') }}</strong>
                                    <small class="text-muted">{{ $att->attendance_date->locale('ms')->translatedFormat('D') }}</small>
                                </td>
                                <td>
                                    @if($att->clock_in)
                                    {{ $att->clock_in->format('H:i') }}
                                    @else
                                    <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($att->clock_out)
                                    {{ $att->clock_out->format('H:i') }}
                                    @else
                                    <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($att->hours_worked)
                                    {{ number_format($att->hours_worked, 1) }}
                                    @else
                                    <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @switch($att->status)
                                        @case('present')
                                            <span class="badge bg-success">Hadir</span>
                                        @break
                                        @case('late')
                                            <span class="badge bg-warning">Lewat</span>
                                        @break
                                        @case('absent')
                                            <span class="badge bg-danger">Tidak Hadir</span>
                                        @break
                                        @case('leave')
                                            <span class="badge bg-info">Cuti</span>
                                        @break
                                        @case('half_day')
                                            <span class="badge bg-secondary">Separuh Hari</span>
                                        @break
                                    @endswitch
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    Tiada rekod kehadiran untuk bulan ini
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Manual Entry Form -->
            @if(auth()->user()->hasRole('admin'))
            <div class="card card-default mt-4">
                <div class="card-header">
                    <h2 class="mb-0">
                        <i class="bi bi-pencil-square me-2"></i>
                        Kemasukan Manual
                    </h2>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.attendance.manual', ['attendance' => 0]) }}" id="manualEntryForm">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label for="attendance_date" class="form-label">Tarikh</label>
                            <input type="date" class="form-control" id="attendance_date" name="attendance_date"
                                   max="{{ now()->format('Y-m-d') }}" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <label for="clock_in" class="form-label">Masuk</label>
                                <input type="time" class="form-control" id="clock_in" name="clock_in" required>
                            </div>
                            <div class="col-6">
                                <label for="clock_out" class="form-label">Keluar</label>
                                <input type="time" class="form-control" id="clock_out" name="clock_out">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="present">Hadir</option>
                                <option value="late">Lewat</option>
                                <option value="absent">Tidak Hadir</option>
                                <option value="half_day">Separuh Hari</option>
                                <option value="leave">Cuti</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Catatan</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-save me-1"></i> Simpan
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
.attendance-calendar td {
    min-width: 50px;
    height: 70px;
}
</style>
@endsection
