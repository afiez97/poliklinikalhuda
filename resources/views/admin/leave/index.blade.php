@extends('layouts.admin')
@section('title', 'Permohonan Cuti')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper breadcrumb-contacts">
        <div>
            <h1>Permohonan Cuti</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>Cuti</span>
            </p>
        </div>
        <div>
            @if($pendingCount > 0)
            <a href="{{ route('admin.leave.pending') }}" class="btn btn-warning me-2">
                <i class="mdi mdi-clock-outline"></i> {{ $pendingCount }} Menunggu
            </a>
            @endif
            <a href="{{ route('admin.leave.calendar') }}" class="btn btn-outline-primary me-2">
                <i class="mdi mdi-calendar"></i> Kalendar
            </a>
            <a href="{{ route('admin.leave.create') }}" class="btn btn-primary">
                <i class="mdi mdi-plus"></i> Mohon Cuti
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
            <form action="{{ route('admin.leave.index') }}" method="GET" class="row g-3">
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Diluluskan</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="leave_type_id" class="form-select">
                        <option value="">Semua Jenis</option>
                        @foreach($leaveTypes as $type)
                        <option value="{{ $type->id }}" {{ request('leave_type_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_from" class="form-control" placeholder="Dari" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_to" class="form-control" placeholder="Hingga" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-secondary">
                        <i class="mdi mdi-magnify"></i> Cari
                    </button>
                    <a href="{{ route('admin.leave.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Leave Requests Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Kakitangan</th>
                            <th>Jenis Cuti</th>
                            <th>Tarikh</th>
                            <th>Hari</th>
                            <th>Status</th>
                            <th>Diluluskan Oleh</th>
                            <th class="text-end">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaveRequests as $leave)
                        <tr>
                            <td>
                                <strong>{{ $leave->staff->user->name ?? '-' }}</strong>
                                <br><small class="text-muted">{{ $leave->staff->staff_no }}</small>
                            </td>
                            <td>
                                <span class="badge" style="background-color: {{ $leave->leaveType->color ?? '#6c757d' }}">
                                    {{ $leave->leaveType->name }}
                                </span>
                            </td>
                            <td>
                                {{ $leave->start_date->format('d/m/Y') }}
                                @if($leave->start_date->ne($leave->end_date))
                                <br><small>hingga {{ $leave->end_date->format('d/m/Y') }}</small>
                                @endif
                            </td>
                            <td>{{ $leave->total_days }} hari</td>
                            <td>
                                @switch($leave->status)
                                    @case('pending')
                                        <span class="badge bg-warning">Menunggu</span>
                                        @break
                                    @case('approved')
                                        <span class="badge bg-success">Diluluskan</span>
                                        @break
                                    @case('rejected')
                                        <span class="badge bg-danger">Ditolak</span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge bg-secondary">Dibatalkan</span>
                                        @break
                                @endswitch
                            </td>
                            <td>
                                @if($leave->approver)
                                {{ $leave->approver->name }}
                                <br><small class="text-muted">{{ $leave->approved_at?->format('d/m/Y H:i') }}</small>
                                @else
                                -
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.leave.show', $leave) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="mdi mdi-eye"></i>
                                </a>
                                @if($leave->status === 'pending')
                                <form action="{{ route('admin.leave.approve', $leave) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Luluskan permohonan ini?')">
                                        <i class="mdi mdi-check"></i>
                                    </button>
                                </form>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $leave->id }}">
                                    <i class="mdi mdi-close"></i>
                                </button>
                                @endif
                            </td>
                        </tr>

                        <!-- Reject Modal -->
                        @if($leave->status === 'pending')
                        <div class="modal fade" id="rejectModal{{ $leave->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.leave.reject', $leave) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Tolak Permohonan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Sebab Penolakan <span class="text-danger">*</span></label>
                                                <textarea name="remarks" class="form-control" rows="3" required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger">Tolak</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="mdi mdi-calendar-remove mdi-48px text-muted"></i>
                                <p class="text-muted mb-0">Tiada permohonan cuti dijumpai</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $leaveRequests->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
