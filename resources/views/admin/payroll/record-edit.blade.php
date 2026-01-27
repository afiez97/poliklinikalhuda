@extends('layouts.admin')

@section('title', 'Edit Rekod Gaji - ' . ($record->staff->user->name ?? $record->staff->name ?? 'Staf'))

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between">
        <div>
            <h1>Edit Rekod Gaji</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.payroll.index') }}">Gaji</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.payroll.period.show', $record->payrollPeriod) }}">{{ $record->payrollPeriod->period_name }}</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.payroll.record.show', $record) }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Staff Info Card -->
        <div class="col-lg-3">
            <div class="card card-default mb-4">
                <div class="card-body text-center">
                    @if($record->staff->user && $record->staff->user->avatar)
                    <img src="{{ asset('storage/'.$record->staff->user->avatar) }}" class="rounded-circle mb-3"
                         width="80" height="80" alt="">
                    @else
                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center mx-auto mb-3"
                         style="width:80px;height:80px;">
                        <i class="bi bi-person fs-1 text-white"></i>
                    </div>
                    @endif
                    <h5 class="mb-1">{{ $record->staff->user->name ?? $record->staff->name ?? '-' }}</h5>
                    <p class="text-muted mb-2"><code>{{ $record->staff->staff_no ?? '-' }}</code></p>
                    <span class="badge bg-secondary">{{ $record->payrollPeriod->period_name }}</span>
                </div>
            </div>

            <!-- Calculated Summary -->
            <div class="card card-default">
                <div class="card-header">
                    <h6 class="mb-0">Ringkasan Pengiraan</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td>Kasar</td>
                            <td class="text-end" id="displayGross">RM 0.00</td>
                        </tr>
                        <tr>
                            <td>Potongan</td>
                            <td class="text-end text-danger" id="displayDeductions">RM 0.00</td>
                        </tr>
                        <tr class="border-top">
                            <td><strong>Bersih</strong></td>
                            <td class="text-end text-success" id="displayNet"><strong>RM 0.00</strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="col-lg-9">
            <form method="POST" action="{{ route('admin.payroll.record.update', $record) }}">
                @csrf
                @method('PATCH')

                <!-- Earnings -->
                <div class="card card-default mb-4">
                    <div class="card-header bg-success text-white">
                        <h2 class="text-white mb-0">
                            <i class="bi bi-plus-circle me-2"></i>
                            Pendapatan
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="basic_salary" class="form-label">Gaji Asas <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">RM</span>
                                    <input type="number" step="0.01" name="basic_salary" id="basic_salary"
                                           class="form-control earnings @error('basic_salary') is-invalid @enderror"
                                           value="{{ old('basic_salary', $record->basic_salary) }}" required>
                                </div>
                                @error('basic_salary')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="allowances" class="form-label">Elaun Tetap <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">RM</span>
                                    <input type="number" step="0.01" name="allowances" id="allowances"
                                           class="form-control earnings @error('allowances') is-invalid @enderror"
                                           value="{{ old('allowances', $record->allowances) }}" required>
                                </div>
                                @error('allowances')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="overtime_pay" class="form-label">Bayaran Lebih Masa (OT) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">RM</span>
                                    <input type="number" step="0.01" name="overtime_pay" id="overtime_pay"
                                           class="form-control earnings @error('overtime_pay') is-invalid @enderror"
                                           value="{{ old('overtime_pay', $record->overtime_pay) }}" required>
                                </div>
                                @error('overtime_pay')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="commission" class="form-label">Komisen <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">RM</span>
                                    <input type="number" step="0.01" name="commission" id="commission"
                                           class="form-control earnings @error('commission') is-invalid @enderror"
                                           value="{{ old('commission', $record->commission) }}" required>
                                </div>
                                @error('commission')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Deductions -->
                <div class="card card-default mb-4">
                    <div class="card-header bg-danger text-white">
                        <h2 class="text-white mb-0">
                            <i class="bi bi-dash-circle me-2"></i>
                            Potongan Pekerja
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="employee_epf" class="form-label">KWSP (11%) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">RM</span>
                                    <input type="number" step="0.01" name="employee_epf" id="employee_epf"
                                           class="form-control deductions @error('employee_epf') is-invalid @enderror"
                                           value="{{ old('employee_epf', $record->employee_epf) }}" required>
                                </div>
                                @error('employee_epf')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="employee_socso" class="form-label">PERKESO <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">RM</span>
                                    <input type="number" step="0.01" name="employee_socso" id="employee_socso"
                                           class="form-control deductions @error('employee_socso') is-invalid @enderror"
                                           value="{{ old('employee_socso', $record->employee_socso) }}" required>
                                </div>
                                @error('employee_socso')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="employee_eis" class="form-label">SIP/EIS <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">RM</span>
                                    <input type="number" step="0.01" name="employee_eis" id="employee_eis"
                                           class="form-control deductions @error('employee_eis') is-invalid @enderror"
                                           value="{{ old('employee_eis', $record->employee_eis) }}" required>
                                </div>
                                @error('employee_eis')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="pcb" class="form-label">PCB / Cukai <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">RM</span>
                                    <input type="number" step="0.01" name="pcb" id="pcb"
                                           class="form-control deductions @error('pcb') is-invalid @enderror"
                                           value="{{ old('pcb', $record->pcb) }}" required>
                                </div>
                                @error('pcb')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="other_deductions" class="form-label">Potongan Lain <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">RM</span>
                                    <input type="number" step="0.01" name="other_deductions" id="other_deductions"
                                           class="form-control deductions @error('other_deductions') is-invalid @enderror"
                                           value="{{ old('other_deductions', $record->other_deductions) }}" required>
                                </div>
                                @error('other_deductions')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-lg flex-fill">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.payroll.record.show', $record) }}" class="btn btn-outline-secondary btn-lg">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const earningsInputs = document.querySelectorAll('.earnings');
    const deductionsInputs = document.querySelectorAll('.deductions');

    function calculateTotals() {
        let gross = 0;
        let deductions = 0;

        earningsInputs.forEach(input => {
            gross += parseFloat(input.value) || 0;
        });

        deductionsInputs.forEach(input => {
            deductions += parseFloat(input.value) || 0;
        });

        const net = gross - deductions;

        document.getElementById('displayGross').textContent = 'RM ' + gross.toFixed(2);
        document.getElementById('displayDeductions').textContent = 'RM ' + deductions.toFixed(2);
        document.getElementById('displayNet').innerHTML = '<strong>RM ' + net.toFixed(2) + '</strong>';
    }

    earningsInputs.forEach(input => input.addEventListener('input', calculateTotals));
    deductionsInputs.forEach(input => input.addEventListener('input', calculateTotals));

    // Initial calculation
    calculateTotals();
});
</script>
@endpush
