@extends('layouts.admin')

@section('title', 'Rekod Gaji - ' . ($record->staff->user->name ?? $record->staff->name ?? 'Staf'))

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between">
        <div>
            <h1>Rekod Gaji Kakitangan</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.payroll.index') }}">Gaji</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.payroll.period.show', $record->payrollPeriod) }}">{{ $record->payrollPeriod->period_name }}</a></li>
                    <li class="breadcrumb-item active">{{ $record->staff->staff_no ?? '-' }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            @if($record->status === 'draft')
            <a href="{{ route('admin.payroll.record.edit', $record) }}" class="btn btn-primary">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
            @endif
            @if(in_array($record->status, ['approved', 'paid']))
            <a href="{{ route('admin.payroll.record.payslip', $record) }}" class="btn btn-success" target="_blank">
                <i class="bi bi-file-earmark-text me-1"></i> Cetak Payslip
            </a>
            @endif
            <a href="{{ route('admin.payroll.period.show', $record->payrollPeriod) }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Staff Info -->
        <div class="col-lg-4">
            <div class="card card-default mb-4">
                <div class="card-header">
                    <h2 class="mb-0">Maklumat Kakitangan</h2>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if($record->staff->user && $record->staff->user->avatar)
                        <img src="{{ asset('storage/'.$record->staff->user->avatar) }}" class="rounded-circle mb-3"
                             width="100" height="100" alt="">
                        @else
                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center mx-auto mb-3"
                             style="width:100px;height:100px;">
                            <i class="bi bi-person fs-1 text-white"></i>
                        </div>
                        @endif
                        <h4 class="mb-1">{{ $record->staff->user->name ?? $record->staff->name ?? '-' }}</h4>
                        <p class="text-muted mb-0">{{ $record->staff->staff_no ?? '-' }}</p>
                    </div>

                    <hr>

                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted">Jabatan</td>
                            <td class="text-end">{{ $record->staff->department->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Jawatan</td>
                            <td class="text-end">{{ $record->staff->position ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tempoh Gaji</td>
                            <td class="text-end">{{ $record->payrollPeriod->period_name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status</td>
                            <td class="text-end">
                                @switch($record->status)
                                    @case('draft')
                                        <span class="badge bg-secondary">Draf</span>
                                    @break
                                    @case('approved')
                                        <span class="badge bg-info">Diluluskan</span>
                                    @break
                                    @case('paid')
                                        <span class="badge bg-success">Dibayar</span>
                                    @break
                                @endswitch
                            </td>
                        </tr>
                        @if($record->paid_at)
                        <tr>
                            <td class="text-muted">Tarikh Bayaran</td>
                            <td class="text-end">{{ $record->paid_at->format('d/m/Y') }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            <!-- Net Salary Highlight -->
            <div class="card card-default bg-success text-white">
                <div class="card-body text-center">
                    <h6 class="text-white-50 mb-1">Gaji Bersih</h6>
                    <h2 class="mb-0">RM {{ number_format($record->net_salary, 2) }}</h2>
                </div>
            </div>
        </div>

        <!-- Salary Details -->
        <div class="col-lg-8">
            <!-- Earnings -->
            <div class="card card-default mb-4">
                <div class="card-header bg-success text-white">
                    <h2 class="text-white mb-0">
                        <i class="bi bi-plus-circle me-2"></i>
                        Pendapatan
                    </h2>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <tbody>
                            <tr>
                                <td>Gaji Asas</td>
                                <td class="text-end">RM {{ number_format($record->basic_salary, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Elaun Tetap</td>
                                <td class="text-end">RM {{ number_format($record->allowances, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Bayaran Lebih Masa (OT)</td>
                                <td class="text-end">RM {{ number_format($record->overtime_pay, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Komisen</td>
                                <td class="text-end">RM {{ number_format($record->commission, 2) }}</td>
                            </tr>
                        </tbody>
                        <tfoot class="table-success">
                            <tr class="fw-bold">
                                <td>JUMLAH PENDAPATAN KASAR</td>
                                <td class="text-end">RM {{ number_format($record->gross_salary, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Deductions -->
            <div class="card card-default mb-4">
                <div class="card-header bg-danger text-white">
                    <h2 class="text-white mb-0">
                        <i class="bi bi-dash-circle me-2"></i>
                        Potongan
                    </h2>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <tbody>
                            <tr>
                                <td>KWSP (Pekerja 11%)</td>
                                <td class="text-end text-danger">RM {{ number_format($record->employee_epf, 2) }}</td>
                            </tr>
                            <tr>
                                <td>PERKESO (Pekerja)</td>
                                <td class="text-end text-danger">RM {{ number_format($record->employee_socso, 2) }}</td>
                            </tr>
                            <tr>
                                <td>SIP/EIS (Pekerja)</td>
                                <td class="text-end text-danger">RM {{ number_format($record->employee_eis, 2) }}</td>
                            </tr>
                            <tr>
                                <td>PCB / Cukai Pendapatan</td>
                                <td class="text-end text-danger">RM {{ number_format($record->pcb, 2) }}</td>
                            </tr>
                            @if($record->other_deductions > 0)
                            <tr>
                                <td>Potongan Lain</td>
                                <td class="text-end text-danger">RM {{ number_format($record->other_deductions, 2) }}</td>
                            </tr>
                            @endif
                        </tbody>
                        <tfoot class="table-danger">
                            <tr class="fw-bold">
                                <td>JUMLAH POTONGAN</td>
                                <td class="text-end">RM {{ number_format($record->total_deductions, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Employer Contributions -->
            <div class="card card-default mb-4">
                <div class="card-header bg-info text-white">
                    <h2 class="text-white mb-0">
                        <i class="bi bi-building me-2"></i>
                        Caruman Majikan
                    </h2>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <tbody>
                            <tr>
                                <td>KWSP (Majikan 12%)</td>
                                <td class="text-end">RM {{ number_format($record->employer_epf, 2) }}</td>
                            </tr>
                            <tr>
                                <td>PERKESO (Majikan)</td>
                                <td class="text-end">RM {{ number_format($record->employer_socso, 2) }}</td>
                            </tr>
                            <tr>
                                <td>SIP/EIS (Majikan)</td>
                                <td class="text-end">RM {{ number_format($record->employer_eis, 2) }}</td>
                            </tr>
                        </tbody>
                        <tfoot class="table-info">
                            <tr class="fw-bold">
                                <td>JUMLAH CARUMAN MAJIKAN</td>
                                <td class="text-end">RM {{ number_format($record->employer_epf + $record->employer_socso + $record->employer_eis, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Summary -->
            <div class="card card-default border-primary border-2">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <h6 class="text-muted">Pendapatan Kasar</h6>
                            <h4 class="text-success">RM {{ number_format($record->gross_salary, 2) }}</h4>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-muted">Jumlah Potongan</h6>
                            <h4 class="text-danger">RM {{ number_format($record->total_deductions, 2) }}</h4>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-muted">Gaji Bersih</h6>
                            <h4 class="text-primary fw-bold">RM {{ number_format($record->net_salary, 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
