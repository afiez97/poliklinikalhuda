@extends('layouts.admin')
@section('title', 'Laporan Kewangan')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper breadcrumb-contacts">
        <div>
            <h1>Laporan Kewangan</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span><a href="{{ route('admin.billing.index') }}">Bil & Pembayaran</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>Laporan</span>
            </p>
        </div>
    </div>

    <!-- Report Links -->
    <div class="row mb-4">
        <div class="col-md-3">
            <a href="{{ route('admin.billing.reports.daily') }}" class="card card-link h-100">
                <div class="card-body text-center py-4">
                    <i class="mdi mdi-calendar-today mdi-48px text-primary"></i>
                    <h5 class="mt-3 mb-0">Kutipan Harian</h5>
                    <p class="text-muted mb-0">Laporan kutipan hari per hari</p>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.billing.reports.outstanding') }}" class="card card-link h-100">
                <div class="card-body text-center py-4">
                    <i class="mdi mdi-clock-alert mdi-48px text-warning"></i>
                    <h5 class="mt-3 mb-0">Tunggakan</h5>
                    <p class="text-muted mb-0">Laporan invois tertunggak</p>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body text-center py-4">
                    <i class="mdi mdi-chart-line mdi-48px text-success"></i>
                    <h5 class="mt-3 mb-0">Trend Bulanan</h5>
                    <p class="text-muted mb-0">Analisis trend kutipan</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body text-center py-4">
                    <i class="mdi mdi-cash-refund mdi-48px text-danger"></i>
                    <h5 class="mt-3 mb-0">Pulangan</h5>
                    <p class="text-muted mb-0">Laporan pulangan wang</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Summary -->
    <div class="row">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Ringkasan Bulanan ({{ now()->format('F Y') }})</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center p-3 border rounded">
                                <h6 class="text-muted">Jumlah Kutipan</h6>
                                <h2 class="text-success mb-0">RM {{ number_format($monthlySummary['total'] ?? 0, 2) }}</h2>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 border rounded">
                                <h6 class="text-muted">Bilangan Transaksi</h6>
                                <h2 class="text-primary mb-0">{{ number_format($monthlySummary['count'] ?? 0) }}</h2>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 border rounded">
                                <h6 class="text-muted">Purata Transaksi</h6>
                                <h2 class="text-info mb-0">RM {{ number_format($monthlySummary['average'] ?? 0, 2) }}</h2>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <h6>Kutipan Mengikut Kaedah</h6>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Kaedah</th>
                                    <th class="text-end">Jumlah</th>
                                    <th class="text-end">Bil. Transaksi</th>
                                    <th class="text-end">%</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $methods = [
                                        'cash' => ['label' => 'Tunai', 'icon' => 'mdi-cash', 'color' => 'success'],
                                        'card' => ['label' => 'Kad', 'icon' => 'mdi-credit-card', 'color' => 'primary'],
                                        'qr' => ['label' => 'QR Pay', 'icon' => 'mdi-qrcode', 'color' => 'info'],
                                        'ewallet' => ['label' => 'E-Wallet', 'icon' => 'mdi-wallet', 'color' => 'warning'],
                                        'transfer' => ['label' => 'Pindahan', 'icon' => 'mdi-bank-transfer', 'color' => 'secondary'],
                                        'panel' => ['label' => 'Panel', 'icon' => 'mdi-shield-check', 'color' => 'danger'],
                                    ];
                                    $total = $monthlySummary['total'] ?? 1;
                                @endphp
                                @foreach($methods as $key => $method)
                                <tr>
                                    <td>
                                        <i class="mdi {{ $method['icon'] }} text-{{ $method['color'] }}"></i>
                                        {{ $method['label'] }}
                                    </td>
                                    <td class="text-end">RM {{ number_format($monthlySummary['by_method'][$key] ?? 0, 2) }}</td>
                                    <td class="text-end">{{ $monthlySummary['count_by_method'][$key] ?? 0 }}</td>
                                    <td class="text-end">
                                        {{ $total > 0 ? number_format((($monthlySummary['by_method'][$key] ?? 0) / $total) * 100, 1) : 0 }}%
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-primary">
                                <tr>
                                    <td><strong>Jumlah</strong></td>
                                    <td class="text-end"><strong>RM {{ number_format($monthlySummary['total'] ?? 0, 2) }}</strong></td>
                                    <td class="text-end"><strong>{{ $monthlySummary['count'] ?? 0 }}</strong></td>
                                    <td class="text-end"><strong>100%</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <!-- Outstanding Summary -->
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h5 class="card-title mb-0">Ringkasan Tertunggak</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <h2 class="text-danger">RM {{ number_format($outstandingSummary['total'] ?? 0, 2) }}</h2>
                        <p class="text-muted mb-0">{{ $outstandingSummary['count'] ?? 0 }} invois</p>
                    </div>

                    <table class="table table-sm">
                        <tr>
                            <td><span class="badge bg-success">Semasa</span></td>
                            <td class="text-end">RM {{ number_format($outstandingSummary['current'] ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <td><span class="badge bg-warning">1-30 hari</span></td>
                            <td class="text-end">RM {{ number_format($outstandingSummary['overdue_30'] ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <td><span class="badge bg-orange">31-60 hari</span></td>
                            <td class="text-end">RM {{ number_format($outstandingSummary['overdue_60'] ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <td><span class="badge bg-danger">61-90 hari</span></td>
                            <td class="text-end">RM {{ number_format($outstandingSummary['overdue_90'] ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <td><span class="badge bg-dark">> 90 hari</span></td>
                            <td class="text-end">RM {{ number_format($outstandingSummary['overdue_90_plus'] ?? 0, 2) }}</td>
                        </tr>
                    </table>

                    <a href="{{ route('admin.billing.reports.outstanding') }}" class="btn btn-warning w-100">
                        Lihat Laporan Penuh
                    </a>
                </div>
            </div>

            <!-- Refund Stats -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Statistik Pulangan</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <h4 class="text-danger">{{ $refundStats['count'] ?? 0 }}</h4>
                            <small class="text-muted">Bilangan</small>
                        </div>
                        <div class="col-6">
                            <h4 class="text-danger">RM {{ number_format($refundStats['total'] ?? 0, 2) }}</h4>
                            <small class="text-muted">Jumlah</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card-link {
    text-decoration: none;
    color: inherit;
    transition: transform 0.2s, box-shadow 0.2s;
}
.card-link:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
</style>
@endsection
