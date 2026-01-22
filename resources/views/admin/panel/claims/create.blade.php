@extends('layouts.admin')

@section('title', 'Tuntutan Baru')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between">
        <div>
            <h1>Tuntutan Baru</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.panel.claims.index') }}">Tuntutan</a></li>
                    <li class="breadcrumb-item active">Baru</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card card-default">
                <div class="card-header">
                    <h2>Maklumat Tuntutan</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.panel.claims.store') }}" method="POST" enctype="multipart/form-data">
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
                                <label class="form-label">Tarikh Tuntutan <span class="text-danger">*</span></label>
                                <input type="date" name="claim_date" class="form-control @error('claim_date') is-invalid @enderror"
                                       value="{{ old('claim_date', date('Y-m-d')) }}" required>
                                @error('claim_date')
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
                                <label class="form-label">Invois <span class="text-danger">*</span></label>
                                <select name="invoice_id" id="invoice_id" class="form-select @error('invoice_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih pesakit dahulu --</option>
                                </select>
                                @error('invoice_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Guarantee Letter</label>
                                <select name="guarantee_letter_id" id="guarantee_letter_id" class="form-select @error('guarantee_letter_id') is-invalid @enderror">
                                    <option value="">-- Tiada GL --</option>
                                </select>
                                @error('guarantee_letter_id')
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

                        <hr class="my-4">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Amaun Tuntutan (RM) <span class="text-danger">*</span></label>
                                <input type="number" name="claim_amount" id="claim_amount" class="form-control @error('claim_amount') is-invalid @enderror"
                                       value="{{ old('claim_amount') }}" step="0.01" min="0" required readonly>
                                <small class="text-muted">Amaun akan diambil dari invois yang dipilih.</small>
                                @error('claim_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tarikh Rawatan</label>
                                <input type="date" name="treatment_date" class="form-control @error('treatment_date') is-invalid @enderror"
                                       value="{{ old('treatment_date') }}">
                                @error('treatment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Diagnosis</label>
                            <textarea name="diagnosis" class="form-control @error('diagnosis') is-invalid @enderror"
                                      rows="2" placeholder="Diagnosis pesakit...">{{ old('diagnosis') }}</textarea>
                            @error('diagnosis')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Rawatan</label>
                            <textarea name="treatment" class="form-control @error('treatment') is-invalid @enderror"
                                      rows="2" placeholder="Rawatan yang diberikan...">{{ old('treatment') }}</textarea>
                            @error('treatment')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nota</label>
                            <textarea name="notes" class="form-control @error('notes') is-invalid @enderror"
                                      rows="2" placeholder="Nota tambahan...">{{ old('notes') }}</textarea>
                            @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <div class="mb-3">
                            <label class="form-label">Dokumen Sokongan</label>
                            <input type="file" name="documents[]" class="form-control @error('documents') is-invalid @enderror"
                                   accept=".pdf,.jpg,.jpeg,.png" multiple>
                            <small class="text-muted">Format: PDF, JPG, PNG. Maks: 5MB setiap fail. Boleh pilih berbilang fail.</small>
                            @error('documents')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @error('documents.*')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.panel.claims.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Batal
                            </a>
                            <button type="submit" name="action" value="draft" class="btn btn-outline-primary">
                                <i class="bi bi-save me-1"></i> Simpan Draf
                            </button>
                            <button type="submit" name="action" value="submit" class="btn btn-success">
                                <i class="bi bi-send me-1"></i> Simpan & Hantar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Help -->
        <div class="col-lg-4">
            <div class="card card-default">
                <div class="card-header">
                    <h2><i class="bi bi-question-circle me-2"></i>Panduan</h2>
                </div>
                <div class="card-body">
                    <h6>Langkah-langkah:</h6>
                    <ol class="mb-4">
                        <li>Pilih <strong>Panel</strong> yang berkenaan</li>
                        <li>Cari dan pilih <strong>Pesakit</strong></li>
                        <li>Pilih <strong>Invois</strong> yang hendak dituntut</li>
                        <li>Pilih <strong>GL</strong> jika ada</li>
                        <li>Lampirkan dokumen sokongan</li>
                        <li>Simpan sebagai draf atau terus hantar</li>
                    </ol>

                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        <strong>Tip:</strong> Anda boleh menyimpan sebagai draf dahulu dan hantar kemudian setelah semua maklumat lengkap.
                    </div>
                </div>
            </div>

            <!-- Invoice Preview -->
            <div class="card card-default mt-4" id="invoicePreview" style="display: none;">
                <div class="card-header">
                    <h2><i class="bi bi-receipt me-2"></i>Maklumat Invois</h2>
                </div>
                <div class="card-body" id="invoicePreviewContent">
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

    // Load invoices when patient changes
    $('#patient_id').on('change', function() {
        var patientId = $(this).val();
        var panelId = $('#panel_id').val();

        $('#invoice_id').empty().append('<option value="">-- Pilih Invois --</option>');
        $('#guarantee_letter_id').empty().append('<option value="">-- Tiada GL --</option>');
        $('#claim_amount').val('');
        $('#invoicePreview').hide();

        if (patientId) {
            // Load patient's unpaid invoices
            $.get('/admin/billing/invoices/patient/' + patientId + '/unpaid', function(data) {
                data.forEach(function(inv) {
                    $('#invoice_id').append(
                        '<option value="' + inv.id + '" data-amount="' + inv.total_amount + '">' +
                        inv.invoice_no + ' - RM ' + parseFloat(inv.total_amount).toFixed(2) +
                        ' (' + inv.date + ')' +
                        '</option>'
                    );
                });
            });

            // Load patient's valid GLs for the selected panel
            if (panelId) {
                loadPatientGLs(patientId, panelId);
            }
        }
    });

    // Load GLs when panel changes
    $('#panel_id').on('change', function() {
        var panelId = $(this).val();
        var patientId = $('#patient_id').val();

        $('#panel_employee_id').empty().append('<option value="">-- Pilih Pekerja --</option>');
        $('#guarantee_letter_id').empty().append('<option value="">-- Tiada GL --</option>');

        if (panelId) {
            // Load panel employees
            $.get('/admin/panel/panels/' + panelId + '/employees', function(data) {
                data.forEach(function(emp) {
                    $('#panel_employee_id').append(
                        '<option value="' + emp.id + '">' + emp.employee_id + ' - ' + emp.name + '</option>'
                    );
                });
            });

            // Load patient's GLs for this panel
            if (patientId) {
                loadPatientGLs(patientId, panelId);
            }
        }
    });

    // Update claim amount when invoice changes
    $('#invoice_id').on('change', function() {
        var selected = $(this).find(':selected');
        var amount = selected.data('amount') || 0;
        $('#claim_amount').val(parseFloat(amount).toFixed(2));

        // Show invoice preview
        if ($(this).val()) {
            $.get('/admin/billing/invoices/' + $(this).val() + '/preview', function(data) {
                $('#invoicePreviewContent').html(data);
                $('#invoicePreview').show();
            });
        } else {
            $('#invoicePreview').hide();
        }
    });

    function loadPatientGLs(patientId, panelId) {
        $.get('/admin/panel/gl/patient/' + patientId + '/panel/' + panelId, function(data) {
            data.forEach(function(gl) {
                var balance = parseFloat(gl.amount_balance).toFixed(2);
                $('#guarantee_letter_id').append(
                    '<option value="' + gl.id + '">' +
                    gl.gl_number + ' - Baki: RM ' + balance +
                    '</option>'
                );
            });
        });
    }
});
</script>
@endpush
