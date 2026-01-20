@extends('layouts.admin')
@section('title', 'Kehadiran Harian')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper breadcrumb-contacts">
        <div>
            <h1>Kehadiran Harian - {{ $date->format('d/m/Y') }}</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>Kehadiran</span>
            </p>
        </div>
        <div>
            <a href="{{ route('admin.attendance.report') }}" class="btn btn-outline-primary">
                <i class="mdi mdi-file-chart"></i> Laporan
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Date Navigation & Statistics -->
    <div class="row mb-4">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.attendance.index') }}" method="GET" class="d-flex gap-2">
                        <input type="date" name="date" class="form-control" value="{{ $date->format('Y-m-d') }}" onchange="this.form.submit()">
                        <a href="{{ route('admin.attendance.index', ['date' => $date->subDay()->format('Y-m-d')]) }}" class="btn btn-outline-secondary">
                            <i class="mdi mdi-chevron-left"></i>
                        </a>
                        <a href="{{ route('admin.attendance.index', ['date' => $date->addDays(2)->format('Y-m-d')]) }}" class="btn btn-outline-secondary">
                            <i class="mdi mdi-chevron-right"></i>
                        </a>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="row">
                <div class="col-3">
                    <div class="card card-mini">
                        <div class="card-body text-center">
                            <h3 class="mb-0 text-success">{{ $statistics['present'] }}</h3>
                            <small>Hadir</small>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card card-mini">
                        <div class="card-body text-center">
                            <h3 class="mb-0 text-warning">{{ $statistics['late'] }}</h3>
                            <small>Lewat</small>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card card-mini">
                        <div class="card-body text-center">
                            <h3 class="mb-0 text-danger">{{ $statistics['absent'] }}</h3>
                            <small>Tidak Hadir</small>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card card-mini">
                        <div class="card-body text-center">
                            <h3 class="mb-0 text-info">{{ $statistics['leave'] }}</h3>
                            <small>Cuti</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.attendance.index') }}" method="GET" class="row g-3">
                <input type="hidden" name="date" value="{{ $date->format('Y-m-d') }}">
                <div class="col-md-3">
                    <select name="department_id" class="form-select">
                        <option value="">Semua Jabatan</option>
                        @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Hadir</option>
                        <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>Lewat</option>
                        <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Tidak Hadir</option>
                        <option value="leave" {{ request('status') == 'leave' ? 'selected' : '' }}>Cuti</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-secondary">
                        <i class="mdi mdi-magnify"></i> Tapis
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Attendance Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Kakitangan</th>
                            <th>Jabatan</th>
                            <th>Syif</th>
                            <th>Masuk</th>
                            <th>Keluar</th>
                            <th>Jam Kerja</th>
                            <th>Status</th>
                            <th class="text-end">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $att)
                        <tr>
                            <td>
                                <strong>{{ $att->staff->user->name ?? '-' }}</strong>
                                <br><small class="text-muted">{{ $att->staff->staff_no }}</small>
                            </td>
                            <td>{{ $att->staff->department->name ?? '-' }}</td>
                            <td>{{ $att->shift->name ?? '-' }}</td>
                            <td>
                                @if($att->clock_in)
                                <span class="text-success">{{ $att->clock_in->format('H:i') }}</span>
                                @if($att->late_minutes > 0)
                                <br><small class="text-danger">+{{ $att->late_minutes }} min</small>
                                @endif
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($att->clock_out)
                                <span class="text-danger">{{ $att->clock_out->format('H:i') }}</span>
                                @if($att->early_out_minutes > 0)
                                <br><small class="text-warning">-{{ $att->early_out_minutes }} min</small>
                                @endif
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($att->hours_worked)
                                {{ number_format($att->hours_worked, 1) }} jam
                                @if($att->overtime_hours > 0)
                                <br><small class="text-info">+{{ number_format($att->overtime_hours, 1) }} OT</small>
                                @endif
                                @else
                                -
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
                                    @case('half_day')
                                        <span class="badge bg-info">Separuh Hari</span>
                                        @break
                                    @case('leave')
                                        <span class="badge bg-secondary">Cuti</span>
                                        @break
                                    @case('holiday')
                                        <span class="badge bg-primary">Cuti Umum</span>
                                        @break
                                @endswitch
                                @if($att->is_approved)
                                <i class="mdi mdi-check-circle text-success" title="Disahkan"></i>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.attendance.staff', $att->staff) }}" class="btn btn-sm btn-outline-primary" title="Lihat Rekod">
                                    <i class="mdi mdi-eye"></i>
                                </a>
                                @if(!$att->is_approved)
                                <form action="{{ route('admin.attendance.approve', $att) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-outline-success" title="Sahkan">
                                        <i class="mdi mdi-check"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="mdi mdi-clock-outline mdi-48px text-muted"></i>
                                <p class="text-muted mb-0">Tiada rekod kehadiran untuk tarikh ini</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $attendances->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
