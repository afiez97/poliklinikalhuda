@extends('layouts.admin')

@section('title', 'Mohon Cuti')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between">
        <div>
            <h1>Mohon Cuti</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.leave.index') }}">Cuti</a></li>
                    <li class="breadcrumb-item active">Mohon Cuti</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card card-default">
                <div class="card-header">
                    <h2>Borang Permohonan Cuti</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.leave.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Kakitangan <span class="text-danger">*</span></label>
                                <select name="staff_id" class="form-select @error('staff_id') is-invalid @enderror" required>
                                    <option value="">Pilih Kakitangan</option>
                                    @foreach($staff as $s)
                                    <option value="{{ $s->id }}" {{ old('staff_id') == $s->id ? 'selected' : '' }}>
                                        {{ $s->user->name }} ({{ $s->staff_no }})
                                    </option>
                                    @endforeach
                                </select>
                                @error('staff_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Jenis Cuti <span class="text-danger">*</span></label>
                                <select name="leave_type_id" class="form-select @error('leave_type_id') is-invalid @enderror" required>
                                    <option value="">Pilih Jenis Cuti</option>
                                    @foreach($leaveTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('leave_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                        @if($type->requires_attachment) (Perlu Lampiran) @endif
                                    </option>
                                    @endforeach
                                </select>
                                @error('leave_type_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Tarikh Mula <span class="text-danger">*</span></label>
                                <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror"
                                       value="{{ old('start_date') }}" min="{{ date('Y-m-d') }}" required>
                                @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Separuh Hari</label>
                                <select name="start_half" class="form-select">
                                    <option value="">Sehari Penuh</option>
                                    <option value="am" {{ old('start_half') == 'am' ? 'selected' : '' }}>Pagi sahaja</option>
                                    <option value="pm" {{ old('start_half') == 'pm' ? 'selected' : '' }}>Petang sahaja</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Tarikh Tamat <span class="text-danger">*</span></label>
                                <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror"
                                       value="{{ old('end_date') }}" min="{{ date('Y-m-d') }}" required>
                                @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Separuh Hari</label>
                                <select name="end_half" class="form-select">
                                    <option value="">Sehari Penuh</option>
                                    <option value="am" {{ old('end_half') == 'am' ? 'selected' : '' }}>Pagi sahaja</option>
                                    <option value="pm" {{ old('end_half') == 'pm' ? 'selected' : '' }}>Petang sahaja</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Sebab Cuti <span class="text-danger">*</span></label>
                            <textarea name="reason" class="form-control @error('reason') is-invalid @enderror" rows="4" required>{{ old('reason') }}</textarea>
                            @error('reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Lampiran (MC/Dokumen Sokongan)</label>
                            <input type="file" name="attachment" class="form-control @error('attachment') is-invalid @enderror"
                                   accept=".pdf,.jpg,.jpeg,.png">
                            <small class="text-muted">Format: PDF, JPG, PNG. Maksimum 5MB</small>
                            @error('attachment')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.leave.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send me-1"></i> Hantar Permohonan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card card-default">
                <div class="card-header">
                    <h2><i class="bi bi-info-circle me-2"></i>Panduan</h2>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Pilih kakitangan dan jenis cuti</li>
                        <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Masukkan tarikh mula dan tamat</li>
                        <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Nyatakan sebab permohonan cuti</li>
                        <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Lampirkan MC jika cuti sakit</li>
                        <li><i class="bi bi-info-circle text-info me-2"></i>Permohonan perlu kelulusan</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
