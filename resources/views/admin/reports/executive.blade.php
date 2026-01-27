@extends('layouts.admin')

@section('title', 'Dashboard Eksekutif')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between">
        <div>
            <h1>Dashboard Eksekutif</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Laporan</a></li>
                    <li class="breadcrumb-item active">Eksekutif</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.reports.operational') }}" class="btn btn-outline-primary">
                <i class="bi bi-activity me-1"></i> Operasi
            </a>
            <a href="{{ route('admin.reports.clinical') }}" class="btn btn-outline-primary">
                <i class="bi bi-heart-pulse me-1"></i> Klinikal
            </a>
        </div>
    </div>

    <!-- Date Filter -->
    <div class="card card-default mb-4">
        <div class="card-body py-3">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Dari Tarikh</label>
                    <input type="date" name="date_from" class="form-control" value="{{ $filters['date_from'] }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Hingga Tarikh</label>
                    <input type="date" name="date_to" class="form-control" value="{{ $filters['date_to'] }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-funnel me-1"></i> Tapis
                    </button>
                    <button type="button" class="btn btn-outline-secondary" onclick="exportDashboard()">
                        <i class="bi bi-download me-1"></i> Eksport
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card card-default h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1">Hasil Hari Ini</p>
                            <h3 class="mb-0">RM {{ number_format($data['summary']['today_revenue'] ?? 0, 2) }}</h3>
                        </div>
                        <div class="icon-box bg-primary bg-opacity-10 text-primary rounded p-2">
                            <i class="bi bi-cash-stack fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">Pesakit: {{ $data['summary']['today_patients'] ?? 0 }}</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-default h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1">Hasil Tempoh</p>
                            <h3 class="mb-0">RM {{ number_format($data['summary']['period_revenue'] ?? 0, 2) }}</h3>
                        </div>
                        <div class="icon-box bg-success bg-opacity-10 text-success rounded p-2">
                            <i class="bi bi-graph-up-arrow fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        @php $change = $data['summary']['revenue_change'] ?? 0; @endphp
                        <small class="{{ $change >= 0 ? 'text-success' : 'text-danger' }}">
                            <i class="bi bi-{{ $change >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                            {{ abs($change) }}% vs tempoh sebelum
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-default h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1">Kadar Kutipan</p>
                            <h3 class="mb-0">{{ number_format($data['summary']['collection_rate'] ?? 0, 1) }}%</h3>
                        </div>
                        <div class="icon-box bg-info bg-opacity-10 text-info rounded p-2">
                            <i class="bi bi-percent fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">
                            Tertunggak: RM {{ number_format($data['summary']['outstanding'] ?? 0, 2) }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-default h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1">Jumlah Pesakit</p>
                            <h3 class="mb-0">{{ number_format($data['summary']['period_patients'] ?? 0) }}</h3>
                        </div>
                        <div class="icon-box bg-warning bg-opacity-10 text-warning rounded p-2">
                            <i class="bi bi-people fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        @php $pChange = $data['summary']['patient_change'] ?? 0; @endphp
                        <small class="{{ $pChange >= 0 ? 'text-success' : 'text-danger' }}">
                            <i class="bi bi-{{ $pChange >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                            {{ abs($pChange) }}% vs tempoh sebelum
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Revenue Trend Chart -->
        <div class="col-lg-8">
            <div class="card card-default mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2><i class="bi bi-graph-up me-2"></i>Trend Hasil</h2>
                </div>
                <div class="card-body">
                    <canvas id="revenueTrendChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Payment Method Distribution -->
        <div class="col-lg-4">
            <div class="card card-default mb-4">
                <div class="card-header">
                    <h2><i class="bi bi-pie-chart me-2"></i>Kaedah Bayaran</h2>
                </div>
                <div class="card-body">
                    <canvas id="paymentMethodChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Patient Trend -->
        <div class="col-lg-6">
            <div class="card card-default mb-4">
                <div class="card-header">
                    <h2><i class="bi bi-people me-2"></i>Trend Lawatan Pesakit</h2>
                </div>
                <div class="card-body">
                    <canvas id="patientTrendChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Services -->
        <div class="col-lg-6">
            <div class="card card-default mb-4">
                <div class="card-header">
                    <h2><i class="bi bi-star me-2"></i>Perkhidmatan Teratas</h2>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Perkhidmatan</th>
                                    <th class="text-end">Jumlah (RM)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data['top_services'] ?? [] as $index => $service)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $service->description ?? 'N/A' }}</td>
                                    <td class="text-end">{{ number_format($service->total ?? 0, 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">Tiada data</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Summary -->
    @if(!empty($data['kpis']))
    <div class="card card-default">
        <div class="card-header">
            <h2><i class="bi bi-speedometer2 me-2"></i>Petunjuk Prestasi Utama (KPI)</h2>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @foreach($data['kpis'] as $kpi)
                <div class="col-md-4 col-lg-3">
                    <div class="border rounded p-3 h-100">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <small class="text-muted">{{ $kpi['name'] }}</small>
                            <span class="badge bg-{{ $kpi['status'] === 'good' ? 'success' : ($kpi['status'] === 'warning' ? 'warning' : 'danger') }}">
                                {{ $kpi['status'] === 'good' ? 'Baik' : ($kpi['status'] === 'warning' ? 'Amaran' : 'Kritikal') }}
                            </span>
                        </div>
                        <h4 class="mb-1">{{ $kpi['formatted'] }}</h4>
                        @if($kpi['target'])
                        <small class="text-muted">Sasaran: {{ number_format($kpi['target'], 0) }} {{ $kpi['unit'] }}</small>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Revenue Trend Chart
const revenueTrendCtx = document.getElementById('revenueTrendChart').getContext('2d');
new Chart(revenueTrendCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($data['revenue_trend']['labels'] ?? []) !!},
        datasets: [{
            label: 'Hasil (RM)',
            data: {!! json_encode($data['revenue_trend']['values'] ?? []) !!},
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.1)',
            tension: 0.3,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false }
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

// Patient Trend Chart
const patientTrendCtx = document.getElementById('patientTrendChart').getContext('2d');
new Chart(patientTrendCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($data['patient_trend']['labels'] ?? []) !!},
        datasets: [{
            label: 'Pesakit',
            data: {!! json_encode($data['patient_trend']['values'] ?? []) !!},
            backgroundColor: 'rgba(54, 162, 235, 0.8)',
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: { beginAtZero: true }
        }
    }
});

// Payment Method Chart
const paymentMethodCtx = document.getElementById('paymentMethodChart').getContext('2d');
const paymentData = @json($data['payment_methods'] ?? []);
new Chart(paymentMethodCtx, {
    type: 'doughnut',
    data: {
        labels: paymentData.map(p => p.payment_method || 'Lain-lain'),
        datasets: [{
            data: paymentData.map(p => p.total),
            backgroundColor: [
                'rgb(54, 162, 235)',
                'rgb(75, 192, 192)',
                'rgb(255, 205, 86)',
                'rgb(255, 99, 132)',
                'rgb(153, 102, 255)'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});

function exportDashboard() {
    window.print();
}
</script>
@endpush
