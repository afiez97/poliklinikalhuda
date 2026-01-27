@extends('layouts.admin')

@section('title', 'Dashboard Farmasi')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between">
        <div>
            <h1>Dashboard Farmasi</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Laporan</a></li>
                    <li class="breadcrumb-item active">Farmasi</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.pharmacy.medicines.index') }}" class="btn btn-primary">
                <i class="bi bi-capsule me-1"></i> Senarai Ubat
            </a>
        </div>
    </div>

    <!-- Dispensing Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card card-default h-100 border-start border-warning border-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0 text-warning">{{ $data['dispensing_stats']['pending'] ?? 0 }}</h3>
                            <small class="text-muted">Menunggu Dispens</small>
                        </div>
                        <i class="bi bi-hourglass-split fs-1 text-warning opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-default h-100 border-start border-success border-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0 text-success">{{ $data['dispensing_stats']['completed'] ?? 0 }}</h3>
                            <small class="text-muted">Selesai Hari Ini</small>
                        </div>
                        <i class="bi bi-check-circle fs-1 text-success opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-default h-100 border-start border-danger border-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0 text-danger">{{ count($data['stock_alerts'] ?? []) }}</h3>
                            <small class="text-muted">Stok Rendah</small>
                        </div>
                        <i class="bi bi-exclamation-triangle fs-1 text-danger opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-default h-100 border-start border-info border-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0 text-info">{{ count($data['expiry_alerts'] ?? []) }}</h3>
                            <small class="text-muted">Hampir Luput</small>
                        </div>
                        <i class="bi bi-calendar-x fs-1 text-info opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Stock Alerts -->
        <div class="col-lg-6">
            <div class="card card-default mb-4">
                <div class="card-header bg-danger text-white">
                    <h2 class="text-white mb-0"><i class="bi bi-exclamation-triangle me-2"></i>Amaran Stok Rendah</h2>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-hover mb-0">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th>Kod</th>
                                    <th>Nama Ubat</th>
                                    <th class="text-center">Stok</th>
                                    <th class="text-center">Min</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data['stock_alerts'] ?? [] as $medicine)
                                <tr>
                                    <td><code>{{ $medicine->code }}</code></td>
                                    <td>{{ $medicine->name }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $medicine->stock_quantity == 0 ? 'danger' : 'warning' }}">
                                            {{ $medicine->stock_quantity }}
                                        </span>
                                    </td>
                                    <td class="text-center text-muted">{{ $medicine->reorder_level }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="bi bi-check-circle text-success fs-1 d-block mb-2"></i>
                                        Tiada amaran stok
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if(count($data['stock_alerts'] ?? []) > 0)
                <div class="card-footer">
                    <a href="{{ route('admin.pharmacy.medicines.index', ['filter' => 'low_stock']) }}" class="btn btn-sm btn-outline-danger w-100">
                        Lihat Semua Stok Rendah
                    </a>
                </div>
                @endif
            </div>
        </div>

        <!-- Expiry Alerts -->
        <div class="col-lg-6">
            <div class="card card-default mb-4">
                <div class="card-header bg-warning">
                    <h2 class="mb-0"><i class="bi bi-calendar-x me-2"></i>Amaran Tarikh Luput</h2>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-hover mb-0">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th>Kod</th>
                                    <th>Nama Ubat</th>
                                    <th class="text-center">Stok</th>
                                    <th>Tarikh Luput</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data['expiry_alerts'] ?? [] as $medicine)
                                @php
                                    $expiryDate = \Carbon\Carbon::parse($medicine->expiry_date);
                                    $daysLeft = now()->diffInDays($expiryDate, false);
                                @endphp
                                <tr>
                                    <td><code>{{ $medicine->code }}</code></td>
                                    <td>{{ $medicine->name }}</td>
                                    <td class="text-center">{{ $medicine->stock_quantity }}</td>
                                    <td>
                                        <span class="badge bg-{{ $daysLeft < 0 ? 'danger' : ($daysLeft <= 7 ? 'warning' : 'info') }}">
                                            {{ $expiryDate->format('d/m/Y') }}
                                        </span>
                                        @if($daysLeft < 0)
                                        <small class="text-danger d-block">Sudah luput!</small>
                                        @elseif($daysLeft <= 7)
                                        <small class="text-warning d-block">{{ $daysLeft }} hari lagi</small>
                                        @else
                                        <small class="text-muted d-block">{{ $daysLeft }} hari lagi</small>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="bi bi-check-circle text-success fs-1 d-block mb-2"></i>
                                        Tiada ubat hampir luput
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if(count($data['expiry_alerts'] ?? []) > 0)
                <div class="card-footer">
                    <a href="{{ route('admin.pharmacy.medicines.index', ['filter' => 'expiring']) }}" class="btn btn-sm btn-outline-warning w-100">
                        Lihat Semua Hampir Luput
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Top Medicines -->
    <div class="card card-default">
        <div class="card-header">
            <h2><i class="bi bi-bar-chart me-2"></i>Ubat Paling Banyak Digunakan (Bulan Ini)</h2>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-8">
                    <canvas id="topMedicinesChart" height="300"></canvas>
                </div>
                <div class="col-lg-4">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Ubat</th>
                                    <th class="text-end">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data['top_medicines'] ?? [] as $index => $medicine)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $medicine->name }}</td>
                                    <td class="text-end">{{ number_format($medicine->total_qty) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Tiada data</td>
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
const topMedicines = @json($data['top_medicines'] ?? []);
const topMedicinesCtx = document.getElementById('topMedicinesChart').getContext('2d');
new Chart(topMedicinesCtx, {
    type: 'bar',
    data: {
        labels: topMedicines.map(m => m.name),
        datasets: [{
            label: 'Jumlah Dispensed',
            data: topMedicines.map(m => m.total_qty),
            backgroundColor: 'rgba(75, 192, 192, 0.8)',
            borderRadius: 4,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        indexAxis: 'y',
        plugins: {
            legend: { display: false }
        }
    }
});
</script>
@endpush
