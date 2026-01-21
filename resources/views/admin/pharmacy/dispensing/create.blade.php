@extends('layouts.admin')

@section('title', 'Dispensing Baru - Farmasi')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between">
        <div>
            <h1>Dispensing Baru</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.pharmacy.medicines.index') }}">Farmasi</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.pharmacy.dispensing.index') }}">Dispensing</a></li>
                    <li class="breadcrumb-item active">Baru</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.pharmacy.dispensing.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <form action="{{ route('admin.pharmacy.dispensing.store') }}" method="POST" id="dispensing-form">
        @csrf

        <div class="row">
            <div class="col-lg-8">
                <!-- Maklumat Pesakit -->
                <div class="card card-default">
                    <div class="card-header">
                        <h2><i class="bi bi-person me-2"></i>Maklumat Pesakit</h2>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label">Cari Pesakit <span class="text-danger">*</span></label>
                                <select name="patient_id" id="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                                    <option value="">Taip nama atau No. MRN pesakit...</option>
                                </select>
                                @error('patient_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div id="patient-info" class="mt-3 d-none">
                            <div class="alert alert-info mb-0">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Nama:</strong> <span id="patient-name">-</span><br>
                                        <strong>No. MRN:</strong> <span id="patient-mrn">-</span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Umur:</strong> <span id="patient-age">-</span><br>
                                        <strong>Jantina:</strong> <span id="patient-gender">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Item Ubat -->
                <div class="card card-default mt-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2><i class="bi bi-capsule me-2"></i>Item Ubat</h2>
                        <button type="button" class="btn btn-sm btn-primary" onclick="addMedicineRow()">
                            <i class="bi bi-plus-lg me-1"></i> Tambah Ubat
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" id="medicines-table">
                                <thead>
                                    <tr>
                                        <th width="35%">Ubat</th>
                                        <th width="15%">Kuantiti</th>
                                        <th width="30%">Arahan Dos</th>
                                        <th width="15%">Harga (RM)</th>
                                        <th width="5%"></th>
                                    </tr>
                                </thead>
                                <tbody id="medicines-body">
                                    <tr class="medicine-row" data-index="0">
                                        <td>
                                            <select name="items[0][medicine_id]" class="form-select medicine-select" required>
                                                <option value="">Pilih ubat...</option>
                                                @foreach($medicines ?? [] as $medicine)
                                                <option value="{{ $medicine->id }}"
                                                        data-price="{{ $medicine->selling_price }}"
                                                        data-stock="{{ $medicine->stock_quantity }}"
                                                        data-dosage="{{ $medicine->dosage_instructions }}">
                                                    {{ $medicine->name }} ({{ $medicine->strength }}) - Stok: {{ $medicine->stock_quantity }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="items[0][quantity]" class="form-control quantity-input"
                                                   value="1" min="1" required onchange="calculateTotal()">
                                        </td>
                                        <td>
                                            <input type="text" name="items[0][dosage_instructions]" class="form-control dosage-input"
                                                   placeholder="cth: 1 tablet 3x sehari">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control price-display" readonly value="0.00">
                                            <input type="hidden" name="items[0][unit_price]" class="unit-price-input" value="0">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeMedicineRow(this)" disabled>
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">Jumlah:</td>
                                        <td>
                                            <input type="text" id="total-display" class="form-control fw-bold" readonly value="RM 0.00">
                                            <input type="hidden" name="total_amount" id="total-amount" value="0">
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Nota -->
                <div class="card card-default mt-4">
                    <div class="card-header">
                        <h2><i class="bi bi-sticky me-2"></i>Nota</h2>
                    </div>
                    <div class="card-body">
                        <textarea name="notes" class="form-control" rows="3"
                                  placeholder="Nota tambahan untuk dispensing...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="card card-default">
                    <div class="card-header">
                        <h2><i class="bi bi-info-circle me-2"></i>Maklumat Dispensing</h2>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Tarikh</label>
                            <input type="date" name="dispensed_date" class="form-control"
                                   value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Pharmacist</label>
                            <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
                        </div>
                    </div>
                </div>

                <div class="card card-default mt-4">
                    <div class="card-header">
                        <h2><i class="bi bi-calculator me-2"></i>Ringkasan</h2>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Jumlah Item:</span>
                            <span id="item-count">1</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold">
                            <span>Jumlah:</span>
                            <span id="summary-total">RM 0.00</span>
                        </div>
                    </div>
                </div>

                <div class="card card-default mt-4">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-lg me-1"></i> Simpan Dispensing
                            </button>
                            <a href="{{ route('admin.pharmacy.dispensing.index') }}" class="btn btn-secondary">
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
let rowIndex = 1;

$(document).ready(function() {
    // Initialize Select2 for patient search
    $('#patient_id').select2({
        theme: 'bootstrap-5',
        placeholder: 'Taip nama atau No. MRN pesakit...',
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
                            text: patient.mrn + ' - ' + patient.name,
                            patient: patient
                        };
                    })
                };
            }
        }
    }).on('select2:select', function(e) {
        var patient = e.params.data.patient;
        $('#patient-name').text(patient.name);
        $('#patient-mrn').text(patient.mrn);
        $('#patient-age').text(patient.age ? patient.age + ' tahun' : '-');
        $('#patient-gender').text(patient.gender == 'male' ? 'Lelaki' : (patient.gender == 'female' ? 'Perempuan' : '-'));
        $('#patient-info').removeClass('d-none');
    }).on('select2:clear', function() {
        $('#patient-info').addClass('d-none');
    });

    // Initialize medicine select change handler
    $('.medicine-select').on('change', function() {
        updateMedicineRow($(this).closest('tr'));
    });
});

function addMedicineRow() {
    const tbody = document.getElementById('medicines-body');
    const newRow = document.createElement('tr');
    newRow.className = 'medicine-row';
    newRow.dataset.index = rowIndex;

    newRow.innerHTML = `
        <td>
            <select name="items[${rowIndex}][medicine_id]" class="form-select medicine-select" required>
                <option value="">Pilih ubat...</option>
                @foreach($medicines ?? [] as $medicine)
                <option value="{{ $medicine->id }}"
                        data-price="{{ $medicine->selling_price }}"
                        data-stock="{{ $medicine->stock_quantity }}"
                        data-dosage="{{ $medicine->dosage_instructions }}">
                    {{ $medicine->name }} ({{ $medicine->strength }}) - Stok: {{ $medicine->stock_quantity }}
                </option>
                @endforeach
            </select>
        </td>
        <td>
            <input type="number" name="items[${rowIndex}][quantity]" class="form-control quantity-input"
                   value="1" min="1" required onchange="calculateTotal()">
        </td>
        <td>
            <input type="text" name="items[${rowIndex}][dosage_instructions]" class="form-control dosage-input"
                   placeholder="cth: 1 tablet 3x sehari">
        </td>
        <td>
            <input type="text" class="form-control price-display" readonly value="0.00">
            <input type="hidden" name="items[${rowIndex}][unit_price]" class="unit-price-input" value="0">
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeMedicineRow(this)">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    `;

    tbody.appendChild(newRow);

    // Add change handler for new medicine select
    $(newRow).find('.medicine-select').on('change', function() {
        updateMedicineRow($(this).closest('tr'));
    });

    rowIndex++;
    updateItemCount();

    // Enable delete buttons if more than one row
    updateDeleteButtons();
}

function removeMedicineRow(button) {
    const row = button.closest('tr');
    row.remove();
    updateItemCount();
    calculateTotal();
    updateDeleteButtons();
}

function updateMedicineRow(row) {
    const select = row.find('.medicine-select');
    const option = select.find(':selected');
    const price = parseFloat(option.data('price')) || 0;
    const dosage = option.data('dosage') || '';

    row.find('.unit-price-input').val(price);
    row.find('.price-display').val(price.toFixed(2));
    row.find('.dosage-input').val(dosage);

    calculateTotal();
}

function calculateTotal() {
    let total = 0;

    $('.medicine-row').each(function() {
        const price = parseFloat($(this).find('.unit-price-input').val()) || 0;
        const quantity = parseInt($(this).find('.quantity-input').val()) || 0;
        const lineTotal = price * quantity;
        $(this).find('.price-display').val(lineTotal.toFixed(2));
        total += lineTotal;
    });

    $('#total-display').val('RM ' + total.toFixed(2));
    $('#total-amount').val(total.toFixed(2));
    $('#summary-total').text('RM ' + total.toFixed(2));
}

function updateItemCount() {
    const count = $('.medicine-row').length;
    $('#item-count').text(count);
}

function updateDeleteButtons() {
    const rows = $('.medicine-row');
    if (rows.length === 1) {
        rows.find('.btn-outline-danger').prop('disabled', true);
    } else {
        rows.find('.btn-outline-danger').prop('disabled', false);
    }
}
</script>
@endpush
@endsection
