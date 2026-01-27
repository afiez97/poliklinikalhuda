@extends('layouts.admin')

@section('title', 'Laporan Kehadiran')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between">
        <div>
            <h1>Laporan Kehadiran Bulanan</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.attendance.index') }}">Kehadiran</a></li>
                    <li class="breadcrumb-item active">Laporan</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.attendance.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card card-default mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.attendance.report') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Tarikh Mula</label>
                    <input type="date" name="start_date" id="start_date" class="form-control"
                           value="{{ $startDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">Tarikh Akhir</label>
                    <input type="date" name="end_date" id="end_date" class="form-control"
                           value="{{ $endDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-3">
                    <label for="department_id" class="form-label">Jabatan</label>
                    <select name="department_id" id="department_id" class="form-select">
                        <option value="">Semua Jabatan</option>
                        @foreach($departments as $department)
                        <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel me-1"></i> Tapis
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Report Period Info -->
    <div class="alert alert-info mb-4">
        <i class="bi bi-info-circle me-2"></i>
        Laporan untuk tempoh <strong>{{ $startDate->format('d/m/Y') }}</strong> hingga <strong>{{ $endDate->format('d/m/Y') }}</strong>
        ({{ $startDate->diffInDays($endDate) + 1 }} hari)
    </div>

    <!-- Staff Attendance Report -->
    <div class="card card-default">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Ringkasan Kehadiran Staf</h2>
            <span class="badge bg-secondary">{{ $staffReport->total() }} staf</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No. Staf</th>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th class="text-center">Hadir</th>
                            <th class="text-center">Lewat</th>
                            <th class="text-center">Tidak Hadir</th>
                            <th class="text-center">Cuti</th>
                            <th class="text-center">Jumlah Jam</th>
                            <th class="text-center">Overtime</th>
                            <th class="text-center">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($staffReport as $staff)
                        <tr>
                            <td><code>{{ $staff->staff_no }}</code></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($staff->user && $staff->user->avatar)
                                    <img src="{{ asset('storage/'.$staff->user->avatar) }}" class="rounded-circle me-2"
                                         width="32" height="32" alt="">
                                    @else
                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-2"
                                         style="width:32px;height:32px;">
                                        <i class="bi bi-person text-white"></i>
                                    </div>
                                    @endif
                                    <div>
                                        <strong>{{ $staff->user->name ?? $staff->name ?? '-' }}</strong>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $staff->department->name ?? '-' }}</td>
                            <td class="text-center">
                                <span class="badge bg-success">{{ $staff->present_days ?? 0 }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-warning">{{ $staff->late_days ?? 0 }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-danger">{{ $staff->absent_days ?? 0 }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info">{{ $staff->leave_days ?? 0 }}</span>
                            </td>
                            <td class="text-center">
                                <strong>{{ number_format($staff->total_hours ?? 0, 1) }}</strong>
                            </td>
                            <td class="text-center">
                                @if(($staff->overtime_hours ?? 0) > 0)
                                <span class="text-primary">+{{ number_format($staff->overtime_hours, 1) }}</span>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.attendance.staff', $staff) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-calendar3"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Tiada rekod kehadiran
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($staffReport->hasPages())
        <div class="card-footer">
            {{ $staffReport->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
