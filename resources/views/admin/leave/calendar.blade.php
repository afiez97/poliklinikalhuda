@extends('layouts.admin')

@section('title', 'Kalendar Cuti')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between">
        <div>
            <h1>Kalendar Cuti</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.leave.index') }}">Cuti</a></li>
                    <li class="breadcrumb-item active">Kalendar</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.leave.create') }}" class="btn btn-primary">
                <i class="bi bi-plus me-1"></i> Mohon Cuti
            </a>
        </div>
    </div>

    <div class="card card-default mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="{{ route('admin.leave.calendar', ['month' => $month->copy()->subMonth()->format('Y-m')]) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-chevron-left"></i> Bulan Sebelum
                </a>
                <h3 class="mb-0">{{ $month->translatedFormat('F Y') }}</h3>
                <a href="{{ route('admin.leave.calendar', ['month' => $month->copy()->addMonth()->format('Y-m')]) }}" class="btn btn-outline-secondary">
                    Bulan Seterusnya <i class="bi bi-chevron-right"></i>
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 14.28%">Ahad</th>
                            <th class="text-center" style="width: 14.28%">Isnin</th>
                            <th class="text-center" style="width: 14.28%">Selasa</th>
                            <th class="text-center" style="width: 14.28%">Rabu</th>
                            <th class="text-center" style="width: 14.28%">Khamis</th>
                            <th class="text-center" style="width: 14.28%">Jumaat</th>
                            <th class="text-center" style="width: 14.28%">Sabtu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $startOfMonth = $month->copy()->startOfMonth();
                            $endOfMonth = $month->copy()->endOfMonth();
                            $startDay = $startOfMonth->dayOfWeek;
                            $daysInMonth = $endOfMonth->day;
                            $currentDay = 1;
                            $totalCells = ceil(($startDay + $daysInMonth) / 7) * 7;
                        @endphp

                        @for ($i = 0; $i < $totalCells; $i++)
                            @if ($i % 7 == 0)
                                <tr>
                            @endif

                            <td class="p-2" style="height: 100px; vertical-align: top;">
                                @if ($i >= $startDay && $currentDay <= $daysInMonth)
                                    @php
                                        $date = $month->copy()->setDay($currentDay);
                                        $dayLeaves = $leaves->filter(function($leave) use ($date) {
                                            return $date->between($leave->start_date, $leave->end_date);
                                        });
                                    @endphp
                                    <div class="fw-bold {{ $date->isToday() ? 'text-primary' : '' }}">
                                        {{ $currentDay }}
                                    </div>
                                    @foreach($dayLeaves->take(3) as $leave)
                                    <div class="small p-1 mb-1 rounded text-truncate"
                                         style="background-color: {{ $leave->leaveType->color ?? '#6c757d' }}20; border-left: 3px solid {{ $leave->leaveType->color ?? '#6c757d' }};"
                                         title="{{ $leave->staff->user->name ?? 'N/A' }} - {{ $leave->leaveType->name }}">
                                        <small>{{ $leave->staff->user->name ?? 'N/A' }}</small>
                                    </div>
                                    @endforeach
                                    @if($dayLeaves->count() > 3)
                                    <small class="text-muted">+{{ $dayLeaves->count() - 3 }} lagi</small>
                                    @endif
                                    @php $currentDay++; @endphp
                                @endif
                            </td>

                            @if ($i % 7 == 6)
                                </tr>
                            @endif
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Legend -->
    <div class="card card-default">
        <div class="card-header">
            <h2>Cuti Bulan Ini</h2>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Kakitangan</th>
                            <th>Jenis Cuti</th>
                            <th>Tarikh</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaves as $leave)
                        <tr>
                            <td>{{ $leave->staff->user->name ?? '-' }}</td>
                            <td>
                                <span class="badge" style="background-color: {{ $leave->leaveType->color ?? '#6c757d' }}">
                                    {{ $leave->leaveType->name }}
                                </span>
                            </td>
                            <td>
                                {{ $leave->start_date->format('d/m/Y') }}
                                @if($leave->start_date->ne($leave->end_date))
                                - {{ $leave->end_date->format('d/m/Y') }}
                                @endif
                                <small class="text-muted">({{ $leave->total_days }} hari)</small>
                            </td>
                            <td>
                                @if($leave->status === 'approved')
                                <span class="badge bg-success">Diluluskan</span>
                                @else
                                <span class="badge bg-warning">Menunggu</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">Tiada cuti bulan ini</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
