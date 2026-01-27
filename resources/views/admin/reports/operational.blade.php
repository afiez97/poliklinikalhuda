@extends('layouts.admin')

@section('title', 'Dashboard Operasi')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between">
        <div>
            <h1>Dashboard Operasi <span class="badge bg-success">Real-time</span></h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Laporan</a></li>
                    <li class="breadcrumb-item active">Operasi</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" onclick="refreshDashboard()">
                <i class="bi bi-arrow-clockwise me-1"></i> Muat Semula
            </button>
            <a href="{{ route('admin.reports.executive') }}" class="btn btn-outline-secondary">
                <i class="bi bi-graph-up me-1"></i> Eksekutif
            </a>
        </div>
    </div>

    <!-- Alerts -->
    @if(!empty($data['alerts']))
    <div class="row mb-4">
        @foreach($data['alerts'] as $alert)
        <div class="col-12">
            <div class="alert alert-{{ $alert['type'] }} d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <div class="flex-grow-1">
                    <strong>{{ $alert['message'] }}</strong>
                    @if(isset($alert['value']))
                    <span class="ms-2 badge bg-{{ $alert['type'] }}">{{ $alert['value'] }}</span>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Today's Stats -->
    <div class="row mb-4">
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card card-default h-100 border-start border-primary border-3">
                <div class="card-body text-center">
                    <h2 class="mb-0 text-primary">{{ $data['today_stats']['patients_registered'] ?? 0 }}</h2>
                    <small class="text-muted">Pesakit Baru</small>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card card-default h-100 border-start border-success border-3">
                <div class="card-body text-center">
                    <h2 class="mb-0 text-success">{{ $data['today_stats']['encounters'] ?? 0 }}</h2>
                    <small class="text-muted">Jumlah Lawatan</small>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card card-default h-100 border-start border-info border-3">
                <div class="card-body text-center">
                    <h2 class="mb-0 text-info">{{ $data['today_stats']['completed_encounters'] ?? 0 }}</h2>
                    <small class="text-muted">Selesai</small>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card card-default h-100 border-start border-warning border-3">
                <div class="card-body text-center">
                    <h2 class="mb-0 text-warning">{{ $data['today_stats']['invoices_created'] ?? 0 }}</h2>
                    <small class="text-muted">Invois</small>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card card-default h-100 border-start border-secondary border-3">
                <div class="card-body text-center">
                    <h2 class="mb-0">{{ $data['today_stats']['payments_received'] ?? 0 }}</h2>
                    <small class="text-muted">Bayaran</small>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card card-default h-100 border-start border-danger border-3">
                <div class="card-body text-center">
                    <h2 class="mb-0 text-danger">RM {{ number_format($data['today_stats']['total_collected'] ?? 0, 0) }}</h2>
                    <small class="text-muted">Kutipan</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Queue Status -->
        <div class="col-lg-4">
            <div class="card card-default mb-4">
                <div class="card-header">
                    <h2><i class="bi bi-people-fill me-2"></i>Status Giliran</h2>
                </div>
                <div class="card-body">
                    <div class="row text-center g-3">
                        <div class="col-4">
                            <div class="p-3 bg-warning bg-opacity-10 rounded">
                                <h3 class="mb-0 text-warning">{{ $data['queue_status']['waiting'] ?? 0 }}</h3>
                                <small>Menunggu</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 bg-primary bg-opacity-10 rounded">
                                <h3 class="mb-0 text-primary">{{ $data['queue_status']['serving'] ?? 0 }}</h3>
                                <small>Sedang Dilayan</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 bg-success bg-opacity-10 rounded">
                                <h3 class="mb-0 text-success">{{ $data['queue_status']['completed'] ?? 0 }}</h3>
                                <small>Selesai</small>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Purata Masa Menunggu:</span>
                        <strong class="{{ ($data['queue_status']['avg_wait_time'] ?? 0) > 30 ? 'text-danger' : 'text-success' }}">
                            {{ number_format($data['queue_status']['avg_wait_time'] ?? 0, 0) }} minit
                        </strong>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.queue.index') }}" class="btn btn-sm btn-outline-primary w-100">
                        <i class="bi bi-arrow-right me-1"></i> Lihat Giliran
                    </a>
                </div>
            </div>

            <!-- Staff Status -->
            <div class="card card-default mb-4">
                <div class="card-header">
                    <h2><i class="bi bi-person-badge me-2"></i>Staf Bertugas</h2>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Doktor</span>
                        <strong>{{ $data['staff_status']['doctors'] ?? 0 }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Jururawat</span>
                        <strong>{{ $data['staff_status']['nurses'] ?? 0 }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Admin</span>
                        <strong>{{ $data['staff_status']['admin'] ?? 0 }}</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span>Jumlah</span>
                        <strong class="text-primary">{{ $data['staff_status']['total_on_duty'] ?? 0 }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hourly Distribution -->
        <div class="col-lg-8">
            <div class="card card-default mb-4">
                <div class="card-header">
                    <h2><i class="bi bi-clock me-2"></i>Lawatan Mengikut Jam</h2>
                </div>
                <div class="card-body">
                    <canvas id="hourlyVisitsChart" height="200"></canvas>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="card card-default">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2><i class="bi bi-receipt me-2"></i>Transaksi Terkini</h2>
                    <a href="{{ route('admin.billing.payments.index') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Masa</th>
                                    <th>Resit</th>
                                    <th>Pesakit</th>
                                    <th>Kaedah</th>
                                    <th class="text-end">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data['recent_transactions'] ?? [] as $tx)
                                <tr>
                                    <td>{{ $tx['time'] }}</td>
                                    <td><code>{{ $tx['receipt_no'] }}</code></td>
                                    <td>{{ $tx['patient'] }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ ucfirst($tx['method']) }}</span>
                                    </td>
                                    <td class="text-end">RM {{ number_format($tx['amount'], 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Tiada transaksi</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Hourly Visits Chart
const hourlyCtx = document.getElementById('hourlyVisitsChart').getContext('2d');
new Chart(hourlyCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($data['hourly_visits']['labels'] ?? []) !!},
        datasets: [{
            label: 'Lawatan',
            data: {!! json_encode($data['hourly_visits']['values'] ?? []) !!},
            backgroundColor: 'rgba(54, 162, 235, 0.8)',
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
                ticks: { stepSize: 1 }
            }
        }
    }
});

function refreshDashboard() {
    location.reload();
}

// Auto refresh every 60 seconds
setInterval(refreshDashboard, 60000);
</script>
@endpush
