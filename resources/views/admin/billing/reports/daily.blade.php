@extends('layouts.admin')
@section('title', 'Laporan Kutipan Harian')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper breadcrumb-contacts">
        <div>
            <h1>Laporan Kutipan Harian</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span><a href="{{ route('admin.billing.index') }}">Bil & Pembayaran</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span><a href="{{ route('admin.billing.reports') }}">Laporan</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>Kutipan Harian</span>
            </p>
        </div>
        <div>
            <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
                <i class="mdi mdi-printer"></i> Cetak
            </button>
        </div>
    </div>

    <!-- Date Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.billing.reports.daily') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Tarikh</label>
                    <input type="date" name="date" class="form-control" value="{{ $date }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-magnify"></i> Cari
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h6 class="text-white-50">Jumlah Kutipan</h6>
                    <h2 class="mb-0">RM {{ number_format($summary['total'] ?? 0, 2) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h6 class="text-white-50">Bilangan Transaksi</h6>
                    <h2 class="mb-0">{{ $summary['count'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h6 class="text-white-50">Purata Transaksi</h6>
                    <h2 class="mb-0">RM {{ number_format($summary['average'] ?? 0, 2) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h6 class="text-white-50">Kutipan Tunai</h6>
                    <h2 class="mb-0">RM {{ number_format($summary['by_method']['cash'] ?? 0, 2) }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Payment by Method -->
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Kutipan Mengikut Kaedah</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><i class="mdi mdi-cash text-success"></i> Tunai</td>
                            <td class="text-end"><strong>RM {{ number_format($summary['by_method']['cash'] ?? 0, 2) }}</strong></td>
                        </tr>
                        <tr>
                            <td><i class="mdi mdi-credit-card text-primary"></i> Kad</td>
                            <td class="text-end"><strong>RM {{ number_format($summary['by_method']['card'] ?? 0, 2) }}</strong></td>
                        </tr>
                        <tr>
                            <td><i class="mdi mdi-qrcode text-info"></i> QR Pay</td>
                            <td class="text-end"><strong>RM {{ number_format($summary['by_method']['qr'] ?? 0, 2) }}</strong></td>
                        </tr>
                        <tr>
                            <td><i class="mdi mdi-wallet text-warning"></i> E-Wallet</td>
                            <td class="text-end"><strong>RM {{ number_format($summary['by_method']['ewallet'] ?? 0, 2) }}</strong></td>
                        </tr>
                        <tr>
                            <td><i class="mdi mdi-bank-transfer text-secondary"></i> Pindahan</td>
                            <td class="text-end"><strong>RM {{ number_format($summary['by_method']['transfer'] ?? 0, 2) }}</strong></td>
                        </tr>
                        <tr>
                            <td><i class="mdi mdi-shield-check text-danger"></i> Panel</td>
                            <td class="text-end"><strong>RM {{ number_format($summary['by_method']['panel'] ?? 0, 2) }}</strong></td>
                        </tr>
                        <tr class="table-primary">
                            <td><strong>Jumlah</strong></td>
                            <td class="text-end"><strong>RM {{ number_format($summary['total'] ?? 0, 2) }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Transaction List -->
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Senarai Transaksi - {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Masa</th>
                                    <th>No. Bayaran</th>
                                    <th>No. Invois</th>
                                    <th>Pesakit</th>
                                    <th>Kaedah</th>
                                    <th class="text-end">Jumlah</th>
                                    <th>Juruwang</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payments as $payment)
                                <tr>
                                    <td>{{ $payment->created_at->format('H:i') }}</td>
                                    <td>{{ $payment->payment_number }}</td>
                                    <td>
                                        <a href="{{ route('admin.billing.invoices.show', $payment->invoice) }}">
                                            {{ $payment->invoice->invoice_number }}
                                        </a>
                                    </td>
                                    <td>{{ $payment->invoice->patient->name ?? '-' }}</td>
                                    <td>
                                        @php
                                            $methodLabel = match($payment->payment_method) {
                                                'cash' => 'Tunai',
                                                'card' => 'Kad',
                                                'qr' => 'QR',
                                                'ewallet' => 'E-Wallet',
                                                'transfer' => 'Pindahan',
                                                'panel' => 'Panel',
                                                default => $payment->payment_method
                                            };
                                        @endphp
                                        <span class="badge bg-secondary">{{ $methodLabel }}</span>
                                    </td>
                                    <td class="text-end text-success">
                                        <strong>RM {{ number_format($payment->amount, 2) }}</strong>
                                    </td>
                                    <td>{{ $payment->receivedBy->name ?? '-' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="mdi mdi-cash-off mdi-48px text-muted"></i>
                                        <p class="text-muted mb-0">Tiada transaksi pada tarikh ini</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                            @if($payments->count() > 0)
                            <tfoot class="table-success">
                                <tr>
                                    <td colspan="5" class="text-end"><strong>Jumlah Kutipan:</strong></td>
                                    <td class="text-end"><strong>RM {{ number_format($payments->sum('amount'), 2) }}</strong></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
@media print {
    .breadcrumb-wrapper, .card-header button, form {
        display: none !important;
    }
    .card {
        border: 1px solid #ddd !important;
    }
}
</style>
@endpush
@endsection
