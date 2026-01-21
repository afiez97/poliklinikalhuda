@extends('layouts.admin')
@section('title', 'Bil & Pembayaran')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper breadcrumb-contacts">
        <div>
            <h1>Bil & Pembayaran</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>Bil & Pembayaran</span>
            </p>
        </div>
        <div>
            <a href="{{ route('admin.billing.invoices.create') }}" class="btn btn-primary">
                <i class="mdi mdi-plus"></i> Invois Baharu
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card card-stats bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title text-white-50">Kutipan Hari Ini</h5>
                            <h2 class="mb-0">RM {{ number_format($todaySummary['total_amount'], 2) }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="mdi mdi-cash-multiple mdi-36px"></i>
                        </div>
                    </div>
                    <small>{{ $todaySummary['total_transactions'] }} transaksi</small>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-stats bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title text-white-50">Tertunggak</h5>
                            <h2 class="mb-0">RM {{ number_format($outstandingSummary['total'], 2) }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="mdi mdi-clock-alert mdi-36px"></i>
                        </div>
                    </div>
                    <small>{{ $outstandingSummary['count'] }} invois</small>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-stats bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title text-white-50">Pulangan Menunggu</h5>
                            <h2 class="mb-0">{{ $pendingRefunds }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="mdi mdi-cash-refund mdi-36px"></i>
                        </div>
                    </div>
                    <a href="{{ route('admin.billing.refunds.index') }}" class="text-white">Lihat semua <i class="mdi mdi-arrow-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-stats bg-secondary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title text-white-50">Kelulusan Diskaun</h5>
                            <h2 class="mb-0">{{ $pendingDiscounts }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="mdi mdi-percent mdi-36px"></i>
                        </div>
                    </div>
                    <a href="{{ route('admin.billing.approvals.index') }}" class="text-white">Lihat semua <i class="mdi mdi-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Methods Summary -->
    <div class="row">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Kutipan Mengikut Kaedah Bayaran (Hari Ini)</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col">
                            <div class="p-3 border rounded">
                                <i class="mdi mdi-cash mdi-24px text-success"></i>
                                <h4 class="mt-2 mb-0">RM {{ number_format($todaySummary['by_method']['cash'], 2) }}</h4>
                                <small class="text-muted">Tunai</small>
                            </div>
                        </div>
                        <div class="col">
                            <div class="p-3 border rounded">
                                <i class="mdi mdi-credit-card mdi-24px text-primary"></i>
                                <h4 class="mt-2 mb-0">RM {{ number_format($todaySummary['by_method']['card'], 2) }}</h4>
                                <small class="text-muted">Kad</small>
                            </div>
                        </div>
                        <div class="col">
                            <div class="p-3 border rounded">
                                <i class="mdi mdi-qrcode mdi-24px text-info"></i>
                                <h4 class="mt-2 mb-0">RM {{ number_format($todaySummary['by_method']['qr'], 2) }}</h4>
                                <small class="text-muted">QR Pay</small>
                            </div>
                        </div>
                        <div class="col">
                            <div class="p-3 border rounded">
                                <i class="mdi mdi-wallet mdi-24px text-warning"></i>
                                <h4 class="mt-2 mb-0">RM {{ number_format($todaySummary['by_method']['ewallet'], 2) }}</h4>
                                <small class="text-muted">e-Wallet</small>
                            </div>
                        </div>
                        <div class="col">
                            <div class="p-3 border rounded">
                                <i class="mdi mdi-bank-transfer mdi-24px text-secondary"></i>
                                <h4 class="mt-2 mb-0">RM {{ number_format($todaySummary['by_method']['transfer'], 2) }}</h4>
                                <small class="text-muted">Pindahan</small>
                            </div>
                        </div>
                        <div class="col">
                            <div class="p-3 border rounded">
                                <i class="mdi mdi-shield-check mdi-24px text-danger"></i>
                                <h4 class="mt-2 mb-0">RM {{ number_format($todaySummary['by_method']['panel'], 2) }}</h4>
                                <small class="text-muted">Panel</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Penuaan Tunggakan</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tbody>
                            <tr>
                                <td>Semasa (belum jatuh tempo)</td>
                                <td class="text-end"><strong>RM {{ number_format($outstandingSummary['current'], 2) }}</strong></td>
                            </tr>
                            <tr>
                                <td>1-30 hari</td>
                                <td class="text-end text-warning"><strong>RM {{ number_format($outstandingSummary['overdue_30'], 2) }}</strong></td>
                            </tr>
                            <tr>
                                <td>31-60 hari</td>
                                <td class="text-end text-orange"><strong>RM {{ number_format($outstandingSummary['overdue_60'], 2) }}</strong></td>
                            </tr>
                            <tr>
                                <td>61-90 hari</td>
                                <td class="text-end text-danger"><strong>RM {{ number_format($outstandingSummary['overdue_90'], 2) }}</strong></td>
                            </tr>
                            <tr>
                                <td>> 90 hari</td>
                                <td class="text-end text-danger"><strong>RM {{ number_format($outstandingSummary['overdue_90_plus'], 2) }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                    <a href="{{ route('admin.billing.reports.outstanding') }}" class="btn btn-outline-primary btn-sm w-100">
                        Lihat Laporan Penuh
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Invois Terkini</h5>
                    <a href="{{ route('admin.billing.invoices.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>No. Invois</th>
                                    <th>Pesakit</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentInvoices as $invoice)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.billing.invoices.show', $invoice) }}">
                                            {{ $invoice->invoice_number }}
                                        </a>
                                    </td>
                                    <td>{{ $invoice->patient->name }}</td>
                                    <td>RM {{ number_format($invoice->grand_total, 2) }}</td>
                                    <td><span class="badge {{ $invoice->status_badge_class }}">{{ $invoice->status_label }}</span></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3">Tiada invois</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Pembayaran Terkini</h5>
                    <a href="{{ route('admin.billing.payments.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>No. Bayaran</th>
                                    <th>Pesakit</th>
                                    <th>Kaedah</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPayments as $payment)
                                <tr>
                                    <td>{{ $payment->payment_number }}</td>
                                    <td>{{ $payment->invoice->patient->name }}</td>
                                    <td><span class="badge bg-secondary">{{ $payment->method_label }}</span></td>
                                    <td class="text-success"><strong>RM {{ number_format($payment->amount, 2) }}</strong></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3">Tiada pembayaran</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Tindakan Pantas</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-2">
                    <a href="{{ route('admin.billing.invoices.index') }}" class="btn btn-outline-primary w-100 py-3">
                        <i class="mdi mdi-file-document-outline mdi-24px d-block mb-1"></i>
                        Senarai Invois
                    </a>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.billing.payments.index') }}" class="btn btn-outline-success w-100 py-3">
                        <i class="mdi mdi-cash-check mdi-24px d-block mb-1"></i>
                        Senarai Bayaran
                    </a>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.billing.cashier.index') }}" class="btn btn-outline-info w-100 py-3">
                        <i class="mdi mdi-cash-register mdi-24px d-block mb-1"></i>
                        Tutup Kaunter
                    </a>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.billing.reports') }}" class="btn btn-outline-warning w-100 py-3">
                        <i class="mdi mdi-chart-bar mdi-24px d-block mb-1"></i>
                        Laporan
                    </a>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.billing.reports.daily') }}" class="btn btn-outline-secondary w-100 py-3">
                        <i class="mdi mdi-calendar-today mdi-24px d-block mb-1"></i>
                        Kutipan Harian
                    </a>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.billing.settings') }}" class="btn btn-outline-dark w-100 py-3">
                        <i class="mdi mdi-cog mdi-24px d-block mb-1"></i>
                        Tetapan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
