@extends('layouts.admin')

@section('title', 'Pre-Authorization Baru')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between">
        <div>
            <h1>Pre-Authorization Baru</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.panel.pa.index') }}">Pre-Authorization</a></li>
                    <li class="breadcrumb-item active">Baru</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card card-default">
                <div class="card-header">
                    <h2>Maklumat Pre-Authorization</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.panel.pa.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Panel <span class="text-danger">*</span></label>
                                <select name="panel_id" id="panel_id" class="form-select @error('panel_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Panel --</option>
                                    @foreach($panels as $panel)
                                    <option value="{{ $panel->id }}" {{ old('panel_id') == $panel->id ? 'selected' : '' }}>
                                        {{ $panel->panel_name }} ({{ $panel->type_name }})
                                    </option>
                                    @endforeach
                                </select>
                                @error('panel_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tarikh Permintaan <span class="text-danger">*</span></label>
                                <input type="date" name="request_date" class="form-control @error('request_date') is-invalid @enderror"
                                       value="{{ old('request_date', date('Y-m-d')) }}" required>
                                @error('request_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Pesakit <span class="text-danger">*</span></label>
                                <select name="patient_id" id="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                                </select>
                                @error('patient_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Pekerja Panel</label>
                                <select name="panel_employee_id" id="panel_employee_id" class="form-select @error('panel_employee_id') is-invalid @enderror">
                                    <option value="">-- Pilih Pekerja --</option>
                                </select>
                                @error('panel_employee_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Guarantee Letter</label>
                            <select name="guarantee_letter_id" id="guarantee_letter_id" class="form-select @error('guarantee_letter_id') is-invalid @enderror">
                                <option value="">-- Tiada GL --</option>
                            </select>
                            @error('guarantee_letter_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jenis Permintaan <span class="text-danger">*</span></label>
                                <select name="request_type" class="form-select @error('request_type') is-invalid @enderror" required>
                                    <option value="">-- Pilih Jenis --</option>
                                    <option value="procedure" {{ old('request_type') == 'procedure' ? 'selected' : '' }}>Prosedur</option>
                                    <option value="medication" {{ old('request_type') == 'medication' ? 'selected' : '' }}>Ubatan</option>
                                    <option value="investigation" {{ old('request_type') == 'investigation' ? 'selected' : '' }}>Ujian/Siasatan</option>
                                    <option value="referral" {{ old('request_type') == 'referral' ? 'selected' : '' }}>Rujukan</option>
                                </select>
                                @error('request_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Anggaran Kos (RM) <span class="text-danger">*</span></label>
                                <input type="number" name="estimated_cost" class="form-control @error('estimated_cost') is-invalid @enderror"
                                       value="{{ old('estimated_cost') }}" step="0.01" min="0" required>
                                @error('estimated_cost')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Perkara Yang Diminta <span class="text-danger">*</span></label>
                            <textarea name="request_description" class="form-control @error('request_description') is-invalid @enderror"
                                      rows="3" placeholder="Nyatakan dengan jelas prosedur/ubatan/ujian yang diminta..." required>{{ old('request_description') }}</textarea>
                            @error('request_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nota Klinikal</label>
                            <textarea name="clinical_notes" class="form-control @error('clinical_notes') is-invalid @enderror"
                                      rows="3" placeholder="Diagnosis, simptom, justifikasi klinikal...">{{ old('clinical_notes') }}</textarea>
                            @error('clinical_notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.panel.pa.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send me-1"></i> Hantar Permintaan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="card card-default">
                <div class="card-header">
                    <h2><i class="bi bi-question-circle me-2"></i>Panduan</h2>
                </div>
                <div class="card-body">
                    <h6>Apakah Pre-Authorization?</h6>
                    <p class="small text-muted">
                        Pre-Authorization (PA) adalah kelulusan awal dari panel sebelum rawatan/prosedur tertentu boleh dilakukan. Ini memastikan kos rawatan dilindungi oleh panel.
                    </p>

                    <h6>Bila Perlu PA?</h6>
                    <ul class="small text-muted">
                        <li>Prosedur dengan kos melebihi had</li>
                        <li>Ubatan yang tiada dalam senarai panel</li>
                        <li>Ujian diagnostik khas</li>
                        <li>Rujukan ke pakar</li>
                    </ul>

                    <h6>Masa Pemprosesan</h6>
                    <p class="small text-muted mb-0">
                        Kebiasaannya 1-3 hari bekerja. Untuk kes kecemasan, hubungi panel secara langsung.
                    </p>
                </div>
            </div>

            <!-- Patient GL Info (will be populated via AJAX) -->
            <div class="card card-default mt-4" id="patientGLInfo" style="display: none;">
                <div class="card-header">
                    <h2><i class="bi bi-file-earmark-check me-2"></i>GL Pesakit</h2>
                </div>
                <div class="card-body" id="patientGLContent">
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

    // Load GLs when patient and panel are selected
    $('#patient_id, #panel_id').on('change', function() {
        loadPatientGLs();
    });

    // Load employees when panel changes
    $('#panel_id').on('change', function() {
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

    function loadPatientGLs() {
        var patientId = $('#patient_id').val();
        var panelId = $('#panel_id').val();

        $('#guarantee_letter_id').empty().append('<option value="">-- Tiada GL --</option>');
        $('#patientGLInfo').hide();

        if (patientId && panelId) {
            $.get('/admin/panel/gl/patient/' + patientId + '/panel/' + panelId, function(data) {
                if (data.length > 0) {
                    var html = '<ul class="list-unstyled mb-0">';
                    data.forEach(function(gl) {
                        var balance = parseFloat(gl.amount_balance).toFixed(2);
                        $('#guarantee_letter_id').append(
                            '<option value="' + gl.id + '">' +
                            gl.gl_number + ' - Baki: RM ' + balance +
                            '</option>'
                        );
                        html += '<li class="mb-2">';
                        html += '<code>' + gl.gl_number + '</code><br>';
                        html += '<small class="text-muted">Baki: RM ' + balance + '</small>';
                        html += '</li>';
                    });
                    html += '</ul>';
                    $('#patientGLContent').html(html);
                    $('#patientGLInfo').show();
                }
            });
        }
    }
});
</script>
@endpush
