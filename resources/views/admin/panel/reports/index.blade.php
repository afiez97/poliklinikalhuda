@extends('layouts.admin')

@section('title', 'Laporan Panel')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between">
        <div>
            <h1>Laporan Panel Insurans</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Laporan Panel</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card card-default">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0">{{ $statistics['total_panels'] ?? 0 }}</h3>
                            <small class="text-muted">Jumlah Panel</small>
                        </div>
                        <div class="icon-box bg-primary text-white rounded-circle p-3">
                            <i class="bi bi-building fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-default">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0">{{ $statistics['active_gls'] ?? 0 }}</h3>
                            <small class="text-muted">GL Aktif</small>
                        </div>
                        <div class="icon-box bg-success text-white rounded-circle p-3">
                            <i class="bi bi-file-earmark-check fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-default">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0">RM {{ number_format($statistics['total_claims_amount'] ?? 0, 0) }}</h3>
                            <small class="text-muted">Tuntutan Bulan Ini</small>
                        </div>
                        <div class="icon-box bg-info text-white rounded-circle p-3">
                            <i class="bi bi-receipt fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-default">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0">RM {{ number_format($statistics['outstanding_amount'] ?? 0, 0) }}</h3>
                            <small class="text-muted">Tertunggak</small>
                        </div>
                        <div class="icon-box bg-warning text-dark rounded-circle p-3">
                            <i class="bi bi-exclamation-triangle fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Filters -->
    <div class="card card-default mb-4">
        <div class="card-header">
            <h2><i class="bi bi-funnel me-2"></i>Penapis Laporan</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.panel.reports.index') }}" method="GET" id="reportFilterForm">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Jenis Laporan</label>
                        <select name="report_type" class="form-select" id="reportType">
                            <option value="summary" {{ ($filters['report_type'] ?? 'summary') == 'summary' ? 'selected' : '' }}>Ringkasan Panel</option>
                            <option value="claims" {{ ($filters['report_type'] ?? '') == 'claims' ? 'selected' : '' }}>Laporan Tuntutan</option>
                            <option value="gl" {{ ($filters['report_type'] ?? '') == 'gl' ? 'selected' : '' }}>Laporan GL</option>
                            <option value="aging" {{ ($filters['report_type'] ?? '') == 'aging' ? 'selected' : '' }}>Analisis Umur Hutang</option>
                            <option value="utilization" {{ ($filters['report_type'] ?? '') == 'utilization' ? 'selected' : '' }}>Penggunaan Manfaat</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Panel</label>
                        <select name="panel_id" class="form-select">
                            <option value="">Semua Panel</option>
                            @foreach($panels as $panel)
                            <option value="{{ $panel->id }}" {{ ($filters['panel_id'] ?? '') == $panel->id ? 'selected' : '' }}>
                                {{ $panel->panel_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Dari Tarikh</label>
                        <input type="date" name="date_from" class="form-control"
                               value="{{ $filters['date_from'] ?? date('Y-m-01') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Hingga Tarikh</label>
                        <input type="date" name="date_to" class="form-control"
                               value="{{ $filters['date_to'] ?? date('Y-m-d') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="bi bi-search me-1"></i> Jana
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <!-- Main Report Content -->
        <div class="col-lg-8">
            <!-- Panel Summary Report -->
            <div class="card card-default" id="reportContent">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2><i class="bi bi-file-earmark-bar-graph me-2"></i>Ringkasan Mengikut Panel</h2>
                    <div>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="exportReport('excel')">
                            <i class="bi bi-file-earmark-excel me-1"></i> Excel
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="exportReport('pdf')">
                            <i class="bi bi-file-earmark-pdf me-1"></i> PDF
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Panel</th>
                                    <th>Jenis</th>
                                    <th class="text-center">GL Aktif</th>
                                    <th class="text-center">Tuntutan</th>
                                    <th class="text-end">Tuntutan (RM)</th>
                                    <th class="text-end">Dibayar (RM)</th>
                                    <th class="text-end">Tertunggak (RM)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($panelSummary ?? [] as $summary)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.panel.panels.show', $summary) }}">
                                            {{ $summary->panel_name }}
                                        </a>
                                    </td>
                                    <td>
                                        @php
                                            $typeColors = [
                                                'corporate' => 'primary',
                                                'insurance' => 'success',
                                                'government' => 'info',
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $typeColors[$summary->panel_type] ?? 'secondary' }}">
                                            {{ ucfirst($summary->panel_type) }}
                                        </span>
                                    </td>
                                    <td class="text-center">{{ $summary->active_gls ?? 0 }}</td>
                                    <td class="text-center">{{ $summary->total_claims ?? 0 }}</td>
                                    <td class="text-end">{{ number_format($summary->claims_amount ?? 0, 2) }}</td>
                                    <td class="text-end text-success">{{ number_format($summary->paid_amount ?? 0, 2) }}</td>
                                    <td class="text-end text-danger">{{ number_format($summary->outstanding_amount ?? 0, 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        Tiada data untuk dipaparkan.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                            @if(isset($panelSummary) && $panelSummary->count())
                            <tfoot class="table-light">
                                <tr class="fw-bold">
                                    <td colspan="3">Jumlah</td>
                                    <td class="text-center">{{ $panelSummary->sum('total_claims') }}</td>
                                    <td class="text-end">{{ number_format($panelSummary->sum('claims_amount'), 2) }}</td>
                                    <td class="text-end text-success">{{ number_format($panelSummary->sum('paid_amount'), 2) }}</td>
                                    <td class="text-end text-danger">{{ number_format($panelSummary->sum('outstanding_amount'), 2) }}</td>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            <!-- Monthly Trend -->
            <div class="card card-default mt-4">
                <div class="card-header">
                    <h2><i class="bi bi-graph-up me-2"></i>Trend Bulanan (6 Bulan Terakhir)</h2>
                </div>
                <div class="card-body">
                    <canvas id="monthlyTrendChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Reports -->
            <div class="card card-default">
                <div class="card-header">
                    <h2><i class="bi bi-lightning me-2"></i>Laporan Pantas</h2>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.panel.reports.index', ['report_type' => 'claims', 'status' => 'pending']) }}"
                           class="btn btn-outline-warning">
                            <i class="bi bi-hourglass-split me-2"></i>Tuntutan Dalam Proses
                        </a>
                        <a href="{{ route('admin.panel.reports.index', ['report_type' => 'aging']) }}"
                           class="btn btn-outline-danger">
                            <i class="bi bi-exclamation-circle me-2"></i>Analisis Umur Hutang
                        </a>
                        <a href="{{ route('admin.panel.reports.index', ['report_type' => 'gl', 'status' => 'expiring']) }}"
                           class="btn btn-outline-info">
                            <i class="bi bi-calendar-x me-2"></i>GL Akan Tamat
                        </a>
                        <a href="{{ route('admin.panel.reports.index', ['report_type' => 'utilization']) }}"
                           class="btn btn-outline-primary">
                            <i class="bi bi-pie-chart me-2"></i>Penggunaan Manfaat
                        </a>
                    </div>
                </div>
            </div>

            <!-- Panel Type Distribution -->
            <div class="card card-default mt-4">
                <div class="card-header">
                    <h2><i class="bi bi-pie-chart me-2"></i>Taburan Jenis Panel</h2>
                </div>
                <div class="card-body">
                    <canvas id="panelTypeChart" height="250"></canvas>
                </div>
            </div>

            <!-- Claims Status Distribution -->
            <div class="card card-default mt-4">
                <div class="card-header">
                    <h2><i class="bi bi-bar-chart me-2"></i>Status Tuntutan</h2>
                </div>
                <div class="card-body">
                    <canvas id="claimStatusChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Monthly Trend Chart
var monthlyCtx = document.getElementById('monthlyTrendChart').getContext('2d');
new Chart(monthlyCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($monthlyTrend['labels'] ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']) !!},
        datasets: [{
            label: 'Tuntutan (RM)',
            data: {!! json_encode($monthlyTrend['claims'] ?? [0, 0, 0, 0, 0, 0]) !!},
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.1)',
            tension: 0.1,
            fill: true
        }, {
            label: 'Dibayar (RM)',
            data: {!! json_encode($monthlyTrend['paid'] ?? [0, 0, 0, 0, 0, 0]) !!},
            borderColor: 'rgb(54, 162, 235)',
            backgroundColor: 'rgba(54, 162, 235, 0.1)',
            tension: 0.1,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'RM ' + value.toLocaleString();
                    }
                }
            }
        }
    }
});

// Panel Type Chart
var panelTypeCtx = document.getElementById('panelTypeChart').getContext('2d');
new Chart(panelTypeCtx, {
    type: 'doughnut',
    data: {
        labels: ['Korporat', 'Insurans', 'Kerajaan'],
        datasets: [{
            data: {!! json_encode([
                $panelTypeDistribution['corporate'] ?? 0,
                $panelTypeDistribution['insurance'] ?? 0,
                $panelTypeDistribution['government'] ?? 0
            ]) !!},
            backgroundColor: [
                'rgb(54, 162, 235)',
                'rgb(75, 192, 192)',
                'rgb(255, 205, 86)'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Claim Status Chart
var claimStatusCtx = document.getElementById('claimStatusChart').getContext('2d');
new Chart(claimStatusCtx, {
    type: 'bar',
    data: {
        labels: ['Draf', 'Dihantar', 'Diluluskan', 'Ditolak', 'Dibayar'],
        datasets: [{
            label: 'Bil. Tuntutan',
            data: {!! json_encode([
                $claimStatusDistribution['draft'] ?? 0,
                $claimStatusDistribution['submitted'] ?? 0,
                $claimStatusDistribution['approved'] ?? 0,
                $claimStatusDistribution['rejected'] ?? 0,
                $claimStatusDistribution['paid'] ?? 0
            ]) !!},
            backgroundColor: [
                'rgb(108, 117, 125)',
                'rgb(13, 202, 240)',
                'rgb(25, 135, 84)',
                'rgb(220, 53, 69)',
                'rgb(13, 110, 253)'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

function exportReport(format) {
    var form = document.getElementById('reportFilterForm');
    var url = new URL(form.action);
    var formData = new FormData(form);

    for (var pair of formData.entries()) {
        url.searchParams.append(pair[0], pair[1]);
    }
    url.searchParams.append('export', format);

    window.location.href = url.toString();
}
</script>
@endpush
