@extends('layouts.admin')

@section('title', 'Laporan Kewangan')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between">
        <div>
            <h1>Laporan Kewangan</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Laporan</a></li>
                    <li class="breadcrumb-item active">Kewangan</li>
                </ol>
            </nav>
        </div>
        <div>
            <button class="btn btn-outline-primary" onclick="exportReport('excel')">
                <i class="bi bi-file-earmark-excel me-1"></i> Excel
            </button>
            <button class="btn btn-outline-danger" onclick="exportReport('pdf')">
                <i class="bi bi-file-earmark-pdf me-1"></i> PDF
            </button>
        </div>
    </div>

    <!-- Filters -->
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
                <div class="col-md-2">
                    <label class="form-label">Kumpulan</label>
                    <select name="group_by" class="form-select">
                        <option value="day" {{ $filters['group_by'] == 'day' ? 'selected' : '' }}>Harian</option>
                        <option value="week" {{ $filters['group_by'] == 'week' ? 'selected' : '' }}>Mingguan</option>
                        <option value="month" {{ $filters['group_by'] == 'month' ? 'selected' : '' }}>Bulanan</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel me-1"></i> Tapis
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card card-default bg-success text-white">
                <div class="card-body">
                    <h4 class="text-white mb-0">RM {{ number_format($data['total_revenue'] ?? 0, 2) }}</h4>
                    <small>Jumlah Kutipan</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-default bg-warning">
                <div class="card-body">
                    <h4 class="mb-0">RM {{ number_format($data['outstanding'] ?? 0, 2) }}</h4>
                    <small>Tertunggak</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-default bg-info text-white">
                <div class="card-body">
                    <h4 class="text-white mb-0">{{ count($data['daily_revenue'] ?? []) }}</h4>
                    <small>Jumlah Hari</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Revenue Trend -->
        <div class="col-lg-8">
            <div class="card card-default mb-4">
                <div class="card-header">
                    <h2><i class="bi bi-graph-up me-2"></i>Trend Hasil</h2>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Payment Method -->
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

    <!-- Daily Revenue Table -->
    <div class="card card-default mb-4">
        <div class="card-header">
            <h2><i class="bi bi-table me-2"></i>Butiran Hasil</h2>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Tarikh</th>
                            <th class="text-end">Jumlah (RM)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data['daily_revenue'] ?? [] as $day)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($day->date)->format('d/m/Y (l)') }}</td>
                            <td class="text-end">{{ number_format($day->total, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted py-4">Tiada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th>Jumlah</th>
                            <th class="text-end">RM {{ number_format($data['total_revenue'] ?? 0, 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Panel Claims -->
    @if(!empty($data['panel_claims']))
    <div class="card card-default">
        <div class="card-header">
            <h2><i class="bi bi-shield-check me-2"></i>Tuntutan Panel</h2>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Status</th>
                            <th class="text-center">Bilangan</th>
                            <th class="text-end">Jumlah (RM)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data['panel_claims'] as $claim)
                        <tr>
                            <td>
                                <span class="badge bg-{{ $claim->claim_status == 'paid' ? 'success' : ($claim->claim_status == 'approved' ? 'info' : 'secondary') }}">
                                    {{ ucfirst($claim->claim_status) }}
                                </span>
                            </td>
                            <td class="text-center">{{ $claim->count }}</td>
                            <td class="text-end">{{ number_format($claim->total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Revenue Chart
const dailyRevenue = @json($data['daily_revenue'] ?? []);
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revenueCtx, {
    type: 'bar',
    data: {
        labels: dailyRevenue.map(d => d.date),
        datasets: [{
            label: 'Hasil (RM)',
            data: dailyRevenue.map(d => d.total),
            backgroundColor: 'rgba(75, 192, 192, 0.8)',
            borderRadius: 4,
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

// Payment Method Chart
const paymentMethods = @json($data['by_method'] ?? []);
const methodCtx = document.getElementById('paymentMethodChart').getContext('2d');
new Chart(methodCtx, {
    type: 'doughnut',
    data: {
        labels: paymentMethods.map(p => p.payment_method || 'Lain-lain'),
        datasets: [{
            data: paymentMethods.map(p => p.total),
            backgroundColor: [
                'rgb(54, 162, 235)',
                'rgb(75, 192, 192)',
                'rgb(255, 205, 86)',
                'rgb(255, 99, 132)',
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

function exportReport(format) {
    const params = new URLSearchParams(window.location.search);
    params.set('export', format);
    window.location.href = window.location.pathname + '?' + params.toString();
}
</script>
@endpush
