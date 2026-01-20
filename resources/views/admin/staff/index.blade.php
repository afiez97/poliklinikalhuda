@extends('layouts.admin')
@section('title', 'Senarai Kakitangan')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper breadcrumb-contacts">
        <div>
            <h1>Pengurusan Kakitangan</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>Kakitangan</span>
            </p>
        </div>
        <div>
            <a href="{{ route('admin.staff.create') }}" class="btn btn-primary">
                <i class="mdi mdi-plus"></i> Tambah Kakitangan
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.staff.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama/no. kakitangan..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="department_id" class="form-select">
                        <option value="">Semua Jabatan</option>
                        @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="position_id" class="form-select">
                        <option value="">Semua Jawatan</option>
                        @foreach($positions as $pos)
                        <option value="{{ $pos->id }}" {{ request('position_id') == $pos->id ? 'selected' : '' }}>
                            {{ $pos->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                        <option value="resigned" {{ request('status') == 'resigned' ? 'selected' : '' }}>Berhenti</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-secondary">
                        <i class="mdi mdi-magnify"></i> Cari
                    </button>
                    <a href="{{ route('admin.staff.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Staff Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No. Kakitangan</th>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th>Jawatan</th>
                            <th>Jenis</th>
                            <th>Status</th>
                            <th class="text-end">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($staff as $s)
                        <tr>
                            <td>
                                <a href="{{ route('admin.staff.show', $s) }}" class="fw-bold text-primary">
                                    {{ $s->staff_no }}
                                </a>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-initial rounded-circle bg-primary me-2" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                                        {{ strtoupper(substr($s->user->name ?? 'S', 0, 2)) }}
                                    </div>
                                    <div>
                                        <strong>{{ $s->user->name ?? '-' }}</strong>
                                        <br><small class="text-muted">{{ $s->user->email ?? '-' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $s->department->name ?? '-' }}</td>
                            <td>{{ $s->position->name ?? '-' }}</td>
                            <td>
                                @switch($s->employment_type)
                                    @case('permanent')
                                        <span class="badge bg-success">Tetap</span>
                                        @break
                                    @case('contract')
                                        <span class="badge bg-info">Kontrak</span>
                                        @break
                                    @case('part_time')
                                        <span class="badge bg-warning">Sambilan</span>
                                        @break
                                    @case('locum')
                                        <span class="badge bg-secondary">Locum</span>
                                        @break
                                @endswitch
                            </td>
                            <td>
                                @switch($s->status)
                                    @case('active')
                                        <span class="badge bg-success">Aktif</span>
                                        @break
                                    @case('inactive')
                                        <span class="badge bg-secondary">Tidak Aktif</span>
                                        @break
                                    @case('resigned')
                                        <span class="badge bg-danger">Berhenti</span>
                                        @break
                                    @case('terminated')
                                        <span class="badge bg-dark">Diberhentikan</span>
                                        @break
                                @endswitch
                            </td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        Tindakan
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.staff.show', $s) }}">
                                                <i class="mdi mdi-eye me-2"></i> Lihat
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.staff.edit', $s) }}">
                                                <i class="mdi mdi-pencil me-2"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.attendance.staff', $s) }}">
                                                <i class="mdi mdi-clock-outline me-2"></i> Kehadiran
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.leave.balance', $s) }}">
                                                <i class="mdi mdi-calendar-check me-2"></i> Baki Cuti
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('admin.staff.destroy', $s) }}" method="POST" onsubmit="return confirm('Adakah anda pasti?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="mdi mdi-delete me-2"></i> Padam
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="mdi mdi-account-group mdi-48px text-muted"></i>
                                <p class="text-muted mb-0">Tiada kakitangan dijumpai</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $staff->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
