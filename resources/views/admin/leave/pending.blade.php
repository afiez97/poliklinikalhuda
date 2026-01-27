@extends('layouts.admin')

@section('title', 'Permohonan Cuti Menunggu Kelulusan')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between">
        <div>
            <h1>Permohonan Menunggu Kelulusan</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.leave.index') }}">Cuti</a></li>
                    <li class="breadcrumb-item active">Menunggu</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.leave.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    @if($leaveRequests->count() > 0)
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <strong>{{ $leaveRequests->total() }}</strong> permohonan cuti menunggu kelulusan anda.
    </div>
    @endif

    <div class="card card-default">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Kakitangan</th>
                            <th>Jenis Cuti</th>
                            <th>Tarikh</th>
                            <th>Hari</th>
                            <th>Sebab</th>
                            <th>Dimohon</th>
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
                                <br><small class="text-muted">- {{ $leave->end_date->format('d/m/Y') }}</small>
                                @endif
                            </td>
                            <td>{{ $leave->total_days }}</td>
                            <td>{{ Str::limit($leave->reason, 50) }}</td>
                            <td>
                                {{ $leave->created_at->diffForHumans() }}
                            </td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a href="{{ route('admin.leave.show', $leave) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.leave.approve', $leave) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Luluskan permohonan ini?')">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $leave->id }}">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Reject Modal -->
                        <div class="modal fade" id="rejectModal{{ $leave->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.leave.reject', $leave) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Tolak Permohonan - {{ $leave->staff->user->name ?? 'N/A' }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Sebab Penolakan <span class="text-danger">*</span></label>
                                                <textarea name="remarks" class="form-control" rows="3" required placeholder="Nyatakan sebab penolakan..."></textarea>
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
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-3 mb-0">Tiada permohonan yang menunggu kelulusan</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($leaveRequests->hasPages())
        <div class="card-footer">
            {{ $leaveRequests->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
