@extends('layouts.admin')

@section('title', 'Tempoh Gaji - ' . $period->period_name)

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between">
        <div>
            <h1>Tempoh Gaji: {{ $period->period_name }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.payroll.index') }}">Gaji</a></li>
                    <li class="breadcrumb-item active">{{ $period->period_name }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            @if($period->status === 'draft')
            <form method="POST" action="{{ route('admin.payroll.period.generate', $period) }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success" onclick="return confirm('Jana rekod gaji untuk semua kakitangan aktif?')">
                    <i class="bi bi-gear me-1"></i> Jana Gaji
                </button>
            </form>
            @endif

            @if($period->status === 'processing')
            <form method="POST" action="{{ route('admin.payroll.period.finalize', $period) }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-warning" onclick="return confirm('Muktamadkan gaji untuk tempoh ini?')">
                    <i class="bi bi-check2-all me-1"></i> Muktamadkan
                </button>
            </form>
            @endif

            @if($period->status === 'finalized')
            <form method="POST" action="{{ route('admin.payroll.period.pay', $period) }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-primary" onclick="return confirm('Tandakan gaji sebagai dibayar?')">
                    <i class="bi bi-cash-coin me-1"></i> Tandakan Dibayar
                </button>
            </form>
            @endif

            <a href="{{ route('admin.payroll.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Period Info -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card card-default h-100">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <p class="text-muted mb-1">Tempoh</p>
                            <h5>{{ $period->start_date->format('d/m/Y') }} - {{ $period->end_date->format('d/m/Y') }}</h5>
                        </div>
                        <div class="col-md-3">
                            <p class="text-muted mb-1">Tarikh Bayaran</p>
                            <h5>{{ $period->payment_date->format('d/m/Y') }}</h5>
                        </div>
                        <div class="col-md-3">
                            <p class="text-muted mb-1">Status</p>
                            <h5>
                                @switch($period->status)
                                    @case('draft')
                                        <span class="badge bg-secondary fs-6">Draf</span>
                                    @break
                                    @case('processing')
                                        <span class="badge bg-warning fs-6">Pemprosesan</span>
                                    @break
                                    @case('finalized')
                                        <span class="badge bg-info fs-6">Dimuktamadkan</span>
                                    @break
                                    @case('paid')
                                        <span class="badge bg-success fs-6">Dibayar</span>
                                    @break
                                @endswitch
                            </h5>
                        </div>
                        <div class="col-md-3">
                            <p class="text-muted mb-1">Jumlah Kakitangan</p>
                            <h5>{{ $summary['total_staff'] }} orang</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-default h-100 border-start border-success border-3">
                <div class="card-body">
                    <p class="text-muted mb-1">Jumlah Gaji Bersih</p>
                    <h2 class="text-success mb-0">RM {{ number_format($summary['total_net'], 2) }}</h2>
                    <small class="text-muted">Kasar: RM {{ number_format($summary['total_gross'], 2) }}</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card card-default">
                <div class="card-body text-center">
                    <h4 class="mb-0">RM {{ number_format($summary['total_gross'], 2) }}</h4>
                    <small class="text-muted">Jumlah Kasar</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-default">
                <div class="card-body text-center">
                    <h4 class="mb-0 text-danger">RM {{ number_format($summary['total_deductions'], 2) }}</h4>
                    <small class="text-muted">Jumlah Potongan</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-default">
                <div class="card-body text-center">
                    <h4 class="mb-0 text-success">RM {{ number_format($summary['total_net'], 2) }}</h4>
                    <small class="text-muted">Jumlah Bersih</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-default">
                <div class="card-body text-center">
                    <h4 class="mb-0 text-info">RM {{ number_format($summary['total_employer_contribution'], 2) }}</h4>
                    <small class="text-muted">Caruman Majikan</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Payroll Records Table -->
    <div class="card card-default">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="mb-0">
                <i class="bi bi-list-ul me-2"></i>
                Rekod Gaji Kakitangan
            </h2>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No. Staf</th>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th class="text-end">Gaji Asas</th>
                            <th class="text-end">Elaun</th>
                            <th class="text-end">Kasar</th>
                            <th class="text-end">Potongan</th>
                            <th class="text-end">Bersih</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($records as $record)
                        <tr>
                            <td><code>{{ $record->staff->staff_no ?? '-' }}</code></td>
                            <td>
                                <strong>{{ $record->staff->user->name ?? $record->staff->name ?? '-' }}</strong>
                            </td>
                            <td>{{ $record->staff->department->name ?? '-' }}</td>
                            <td class="text-end">{{ number_format($record->basic_salary, 2) }}</td>
                            <td class="text-end">{{ number_format($record->allowances + $record->overtime_pay + $record->commission, 2) }}</td>
                            <td class="text-end"><strong>{{ number_format($record->gross_salary, 2) }}</strong></td>
                            <td class="text-end text-danger">{{ number_format($record->total_deductions, 2) }}</td>
                            <td class="text-end text-success"><strong>{{ number_format($record->net_salary, 2) }}</strong></td>
                            <td class="text-center">
                                @switch($record->status)
                                    @case('draft')
                                        <span class="badge bg-secondary">Draf</span>
                                    @break
                                    @case('approved')
                                        <span class="badge bg-info">Lulus</span>
                                    @break
                                    @case('paid')
                                        <span class="badge bg-success">Dibayar</span>
                                    @break
                                @endswitch
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.payroll.record.show', $record) }}" class="btn btn-outline-primary" title="Lihat">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($record->status === 'draft')
                                    <a href="{{ route('admin.payroll.record.edit', $record) }}" class="btn btn-outline-secondary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @endif
                                    @if(in_array($record->status, ['approved', 'paid']))
                                    <a href="{{ route('admin.payroll.record.payslip', $record) }}" class="btn btn-outline-success" title="Payslip" target="_blank">
                                        <i class="bi bi-file-earmark-text"></i>
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Tiada rekod gaji. Klik "Jana Gaji" untuk menjana rekod.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($records->count() > 0)
                    <tfoot class="table-light">
                        <tr class="fw-bold">
                            <td colspan="3">JUMLAH</td>
                            <td class="text-end">{{ number_format($records->sum('basic_salary'), 2) }}</td>
                            <td class="text-end">{{ number_format($records->sum('allowances') + $records->sum('overtime_pay') + $records->sum('commission'), 2) }}</td>
                            <td class="text-end">{{ number_format($records->sum('gross_salary'), 2) }}</td>
                            <td class="text-end text-danger">{{ number_format($records->sum('total_deductions'), 2) }}</td>
                            <td class="text-end text-success">{{ number_format($records->sum('net_salary'), 2) }}</td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
        @if($records->hasPages())
        <div class="card-footer">
            {{ $records->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
