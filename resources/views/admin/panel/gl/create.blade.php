@extends('layouts.admin')

@section('title', 'Tambah Guarantee Letter')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between">
        <div>
            <h1>Tambah Guarantee Letter</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.panel.gl.index') }}">Guarantee Letter</a></li>
                    <li class="breadcrumb-item active">Tambah</li>
                </ol>
            </nav>
        </div>
    </div>

    <form action="{{ route('admin.panel.gl.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-lg-8">
                <!-- Maklumat GL -->
                <div class="card card-default">
                    <div class="card-header">
                        <h2><i class="bi bi-file-earmark-text me-2"></i>Maklumat GL</h2>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">No. GL</label>
                                <input type="text" name="gl_number" class="form-control @error('gl_number') is-invalid @enderror"
                                       value="{{ old('gl_number') }}" placeholder="Auto-generate jika kosong">
                                @error('gl_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Panel <span class="text-danger">*</span></label>
                                <select name="panel_id" class="form-select @error('panel_id') is-invalid @enderror" required>
                                    <option value="">Pilih Panel</option>
                                    @foreach($panels as $panel)
                                    <option value="{{ $panel->id }}" {{ old('panel_id') == $panel->id ? 'selected' : '' }}>
                                        {{ $panel->panel_name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('panel_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Pesakit <span class="text-danger">*</span></label>
                                <select name="patient_id" id="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                                    <option value="">Cari pesakit...</option>
                                    @if($patient ?? null)
                                    <option value="{{ $patient->id }}" selected>{{ $patient->mrn }} - {{ $patient->name }}</option>
                                    @endif
                                </select>
                                @error('patient_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tarikh Mula <span class="text-danger">*</span></label>
                                <input type="date" name="effective_date" class="form-control @error('effective_date') is-invalid @enderror"
                                       value="{{ old('effective_date', date('Y-m-d')) }}" required>
                                @error('effective_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tarikh Tamat <span class="text-danger">*</span></label>
                                <input type="date" name="expiry_date" class="form-control @error('expiry_date') is-invalid @enderror"
                                       value="{{ old('expiry_date') }}" required>
                                @error('expiry_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Had Liputan (RM) <span class="text-danger">*</span></label>
                                <input type="number" name="coverage_limit" class="form-control @error('coverage_limit') is-invalid @enderror"
                                       value="{{ old('coverage_limit') }}" step="0.01" min="0" required>
                                @error('coverage_limit')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Dokumen GL</label>
                                <input type="file" name="document" class="form-control @error('document') is-invalid @enderror"
                                       accept=".pdf,.jpg,.jpeg,.png">
                                <small class="text-muted">PDF, JPG, PNG (Maks 5MB)</small>
                                @error('document')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Maklumat Tambahan -->
                <div class="card card-default mt-4">
                    <div class="card-header">
                        <h2><i class="bi bi-info-circle me-2"></i>Maklumat Tambahan</h2>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Diagnosis Dilindungi</label>
                                <textarea name="diagnoses_covered" class="form-control @error('diagnoses_covered') is-invalid @enderror"
                                          rows="2" placeholder="Senarai diagnosis yang dilindungi (jika spesifik)">{{ old('diagnoses_covered') }}</textarea>
                                @error('diagnoses_covered')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Catatan Khas</label>
                                <textarea name="special_remarks" class="form-control @error('special_remarks') is-invalid @enderror"
                                          rows="2" placeholder="Sebarang catatan atau syarat khas">{{ old('special_remarks') }}</textarea>
                                @error('special_remarks')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="card card-default">
                    <div class="card-header">
                        <h2><i class="bi bi-check-circle me-2"></i>Status Pengesahan</h2>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="verification_status" value="pending"
                                   id="status_pending" {{ old('verification_status', 'pending') == 'pending' ? 'checked' : '' }}>
                            <label class="form-check-label" for="status_pending">Menunggu Pengesahan</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="verification_status" value="verified"
                                   id="status_verified" {{ old('verification_status') == 'verified' ? 'checked' : '' }}>
                            <label class="form-check-label" for="status_verified">Telah Disahkan</label>
                        </div>
                    </div>
                </div>

                <div class="card card-default mt-4">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-lg me-1"></i> Simpan GL
                            </button>
                            <a href="{{ route('admin.panel.gl.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-lg me-1"></i> Batal
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('#patient_id').select2({
        theme: 'bootstrap-5',
        placeholder: 'Cari pesakit...',
        allowClear: true,
        ajax: {
            url: '{{ route("admin.patients.search") }}',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return { q: params.term };
            },
            processResults: function(data) {
                return {
                    results: data.map(function(patient) {
                        return {
                            id: patient.id,
                            text: patient.mrn + ' - ' + patient.name
                        };
                    })
                };
            }
        }
    });
});
</script>
@endpush
@endsection
