@extends('layouts.admin')

@section('title', 'Edit GL: ' . $gl->gl_number)

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between">
        <div>
            <h1>Edit Guarantee Letter</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.panel.gl.index') }}">Guarantee Letter</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.panel.gl.show', $gl) }}">{{ $gl->gl_number }}</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card card-default">
                <div class="card-header">
                    <h2>Maklumat Guarantee Letter</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.panel.gl.update', $gl) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">No. GL</label>
                                <input type="text" class="form-control" value="{{ $gl->gl_number }}" disabled>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Panel <span class="text-danger">*</span></label>
                                <select name="panel_id" class="form-select @error('panel_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Panel --</option>
                                    @foreach($panels as $panel)
                                    <option value="{{ $panel->id }}" {{ old('panel_id', $gl->panel_id) == $panel->id ? 'selected' : '' }}>
                                        {{ $panel->panel_name }} ({{ $panel->type_name }})
                                    </option>
                                    @endforeach
                                </select>
                                @error('panel_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Pesakit <span class="text-danger">*</span></label>
                                <select name="patient_id" id="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                                    @if($gl->patient)
                                    <option value="{{ $gl->patient_id }}" selected>{{ $gl->patient->mrn }} - {{ $gl->patient->name }}</option>
                                    @endif
                                </select>
                                @error('patient_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Pekerja Panel</label>
                                <select name="panel_employee_id" id="panel_employee_id" class="form-select @error('panel_employee_id') is-invalid @enderror">
                                    <option value="">-- Pilih Pekerja --</option>
                                    @if($gl->panelEmployee)
                                    <option value="{{ $gl->panel_employee_id }}" selected>{{ $gl->panelEmployee->employee_id }} - {{ $gl->panelEmployee->name }}</option>
                                    @endif
                                </select>
                                @error('panel_employee_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tarikh Mula <span class="text-danger">*</span></label>
                                <input type="date" name="effective_date" class="form-control @error('effective_date') is-invalid @enderror"
                                       value="{{ old('effective_date', $gl->effective_date->format('Y-m-d')) }}" required>
                                @error('effective_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tarikh Tamat <span class="text-danger">*</span></label>
                                <input type="date" name="expiry_date" class="form-control @error('expiry_date') is-invalid @enderror"
                                       value="{{ old('expiry_date', $gl->expiry_date->format('Y-m-d')) }}" required>
                                @error('expiry_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Had Liputan (RM) <span class="text-danger">*</span></label>
                                <input type="number" name="coverage_limit" class="form-control @error('coverage_limit') is-invalid @enderror"
                                       value="{{ old('coverage_limit', $gl->coverage_limit) }}" step="0.01" min="0" required>
                                @error('coverage_limit')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror">
                                    @foreach(\App\Models\GuaranteeLetter::STATUSES as $value => $label)
                                    <option value="{{ $value }}" {{ old('status', $gl->status) == $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Diagnosis Dilindungi</label>
                            <textarea name="diagnoses_covered" class="form-control @error('diagnoses_covered') is-invalid @enderror"
                                      rows="3" placeholder="Senaraikan diagnosis yang dilindungi...">{{ old('diagnoses_covered', $gl->diagnoses_covered) }}</textarea>
                            @error('diagnoses_covered')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Catatan Khas</label>
                            <textarea name="special_remarks" class="form-control @error('special_remarks') is-invalid @enderror"
                                      rows="3" placeholder="Sebarang catatan khas...">{{ old('special_remarks', $gl->special_remarks) }}</textarea>
                            @error('special_remarks')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Dokumen GL</label>
                            @if($gl->document_path)
                            <div class="mb-2">
                                <a href="{{ Storage::url($gl->document_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-file-earmark-pdf me-1"></i> Lihat Dokumen Semasa
                                </a>
                            </div>
                            @endif
                            <input type="file" name="document" class="form-control @error('document') is-invalid @enderror"
                                   accept=".pdf,.jpg,.jpeg,.png">
                            <small class="text-muted">Format: PDF, JPG, PNG. Maks: 5MB. Biarkan kosong jika tidak mahu tukar.</small>
                            @error('document')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.panel.gl.show', $gl) }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i> Kemaskini
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="col-lg-4">
            <div class="card card-default">
                <div class="card-header">
                    <h2><i class="bi bi-info-circle me-2"></i>Maklumat</h2>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td class="text-muted">Dicipta:</td>
                            <td>{{ $gl->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Dikemaskini:</td>
                            <td>{{ $gl->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status:</td>
                            <td>
                                @php
                                    $statusColors = [
                                        'active' => 'success',
                                        'utilized' => 'info',
                                        'expired' => 'secondary',
                                        'cancelled' => 'danger',
                                    ];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$gl->status] ?? 'secondary' }}">
                                    {{ $gl->status_name }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Pengesahan:</td>
                            <td>
                                @php
                                    $verificationColors = [
                                        'pending' => 'warning',
                                        'verified' => 'success',
                                        'rejected' => 'danger',
                                        'expired' => 'secondary',
                                    ];
                                @endphp
                                <span class="badge bg-{{ $verificationColors[$gl->verification_status] ?? 'secondary' }}">
                                    {{ $gl->verification_status_name }}
                                </span>
                            </td>
                        </tr>
                    </table>

                    @if($gl->amount_used > 0)
                    <div class="alert alert-info mb-0">
                        <i class="bi bi-exclamation-circle me-1"></i>
                        GL ini telah digunakan sebanyak <strong>RM {{ number_format($gl->amount_used, 2) }}</strong>.
                        Had liputan tidak boleh dikurangkan di bawah jumlah yang telah digunakan.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Patient search with Select2
    $('#patient_id').select2({
        theme: 'bootstrap-5',
        placeholder: 'Cari pesakit (nama/MRN/IC)...',
        allowClear: true,
        minimumInputLength: 2,
        ajax: {
            url: '{{ route("admin.patients.search") }}',
            dataType: 'json',
            delay: 300,
            data: function(params) {
                return { q: params.term };
            },
            processResults: function(data) {
                return {
                    results: data.map(function(patient) {
                        return {
                            id: patient.id,
                            text: patient.mrn + ' - ' + patient.name + ' (' + (patient.ic_number || 'No IC') + ')'
                        };
                    })
                };
            }
        }
    });

    // Load employees when panel changes
    $('select[name="panel_id"]').on('change', function() {
        var panelId = $(this).val();
        var $employeeSelect = $('#panel_employee_id');

        $employeeSelect.empty().append('<option value="">-- Pilih Pekerja --</option>');

        if (panelId) {
            $.get('/admin/panel/panels/' + panelId + '/employees', function(data) {
                data.forEach(function(emp) {
                    $employeeSelect.append(
                        '<option value="' + emp.id + '">' + emp.employee_id + ' - ' + emp.name + '</option>'
                    );
                });
            });
        }
    });
});
</script>
@endpush
