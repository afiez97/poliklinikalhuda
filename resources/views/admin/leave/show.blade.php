@extends('layouts.admin')

@section('title', 'Butiran Permohonan Cuti')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between">
        <div>
            <h1>Butiran Permohonan Cuti</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.leave.index') }}">Cuti</a></li>
                    <li class="breadcrumb-item active">Butiran</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.leave.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card card-default">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2>Maklumat Permohonan</h2>
                    @switch($leaveRequest->status)
                        @case('pending')
                            <span class="badge bg-warning fs-6">Menunggu Kelulusan</span>
                            @break
                        @case('approved')
                            <span class="badge bg-success fs-6">Diluluskan</span>
                            @break
                        @case('rejected')
                            <span class="badge bg-danger fs-6">Ditolak</span>
                            @break
                        @case('cancelled')
                            <span class="badge bg-secondary fs-6">Dibatalkan</span>
                            @break
                    @endswitch
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Kakitangan</h6>
                            <p class="mb-0 fw-bold">{{ $leaveRequest->staff->user->name ?? '-' }}</p>
                            <small class="text-muted">{{ $leaveRequest->staff->staff_no ?? '-' }}</small>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Jabatan</h6>
                            <p class="mb-0">{{ $leaveRequest->staff->department->name ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Jenis Cuti</h6>
                            <span class="badge fs-6" style="background-color: {{ $leaveRequest->leaveType->color ?? '#6c757d' }}">
                                {{ $leaveRequest->leaveType->name }}
                            </span>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Jumlah Hari</h6>
                            <p class="mb-0 fs-4 fw-bold">{{ $leaveRequest->total_days }} hari</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Tarikh Mula</h6>
                            <p class="mb-0">{{ $leaveRequest->start_date->format('d F Y') }}</p>
                            @if($leaveRequest->start_half)
                            <small class="text-muted">{{ $leaveRequest->start_half == 'am' ? 'Pagi' : 'Petang' }} sahaja</small>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Tarikh Tamat</h6>
                            <p class="mb-0">{{ $leaveRequest->end_date->format('d F Y') }}</p>
                            @if($leaveRequest->end_half)
                            <small class="text-muted">{{ $leaveRequest->end_half == 'am' ? 'Pagi' : 'Petang' }} sahaja</small>
                            @endif
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Sebab Permohonan</h6>
                        <p class="mb-0">{{ $leaveRequest->reason }}</p>
                    </div>

                    @if($leaveRequest->attachment_path)
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Lampiran</h6>
                        <a href="{{ Storage::url($leaveRequest->attachment_path) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-paperclip me-1"></i> Lihat Lampiran
                        </a>
                    </div>
                    @endif

                    @if($leaveRequest->approver)
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Diproses Oleh</h6>
                            <p class="mb-0">{{ $leaveRequest->approver->name }}</p>
                            <small class="text-muted">{{ $leaveRequest->approved_at?->format('d/m/Y H:i') }}</small>
                        </div>
                        @if($leaveRequest->approver_remarks)
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Catatan</h6>
                            <p class="mb-0">{{ $leaveRequest->approver_remarks }}</p>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>

                @if($leaveRequest->status === 'pending')
                <div class="card-footer">
                    <div class="d-flex gap-2">
                        <form action="{{ route('admin.leave.approve', $leaveRequest) }}" method="POST" class="flex-fill">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success w-100" onclick="return confirm('Luluskan permohonan ini?')">
                                <i class="bi bi-check-lg me-1"></i> Luluskan
                            </button>
                        </form>
                        <button type="button" class="btn btn-danger flex-fill" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="bi bi-x-lg me-1"></i> Tolak
                        </button>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="col-lg-4">
            @if($balance)
            <div class="card card-default">
                <div class="card-header">
                    <h2><i class="bi bi-calendar-check me-2"></i>Baki Cuti</h2>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Kelayakan</span>
                        <strong>{{ $balance->entitled_days }} hari</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Dibawa</span>
                        <strong>{{ $balance->carried_forward ?? 0 }} hari</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Digunakan</span>
                        <strong class="text-danger">{{ $balance->used_days }} hari</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span>Baki</span>
                        <strong class="text-success fs-5">{{ $balance->remaining_days }} hari</strong>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Reject Modal -->
@if($leaveRequest->status === 'pending')
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.leave.reject', $leaveRequest) }}" method="POST">
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
                    <button type="submit" class="btn btn-danger">Tolak Permohonan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
