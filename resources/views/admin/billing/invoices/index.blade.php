@extends('layouts.admin')
@section('title', 'Senarai Invois')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper breadcrumb-contacts">
        <div>
            <h1>Senarai Invois</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span><a href="{{ route('admin.billing.index') }}">Bil & Pembayaran</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>Invois</span>
            </p>
        </div>
        <div>
            <a href="{{ route('admin.billing.invoices.create') }}" class="btn btn-primary">
                <i class="mdi mdi-plus"></i> Invois Baharu
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Filters -->
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('admin.billing.invoices.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Carian</label>
                    <input type="text" name="search" class="form-control" placeholder="No. Invois / Nama / IC / MRN" value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draf</option>
                        <option value="issued" {{ request('status') == 'issued' ? 'selected' : '' }}>Dikeluarkan</option>
                        <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Bayar Sebahagian</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Selesai</option>
                        <option value="void" {{ request('status') == 'void' ? 'selected' : '' }}>Batal</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status Bayaran</label>
                    <select name="payment_status" class="form-select">
                        <option value="">Semua</option>
                        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Selesai Bayar</option>
                        <option value="partial" {{ request('payment_status') == 'partial' ? 'selected' : '' }}>Bayar Sebahagian</option>
                        <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Belum Bayar</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Dari Tarikh</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Hingga Tarikh</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="mdi mdi-magnify"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Invoice List -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No. Invois</th>
                            <th>Tarikh</th>
                            <th>Pesakit</th>
                            <th>Jumlah</th>
                            <th>Dibayar</th>
                            <th>Baki</th>
                            <th>Status</th>
                            <th class="text-end">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $invoice)
                        <tr>
                            <td>
                                <a href="{{ route('admin.billing.invoices.show', $invoice) }}">
                                    <strong>{{ $invoice->invoice_number }}</strong>
                                </a>
                            </td>
                            <td>{{ $invoice->invoice_date->format('d/m/Y') }}</td>
                            <td>
                                {{ $invoice->patient->name }}
                                <br><small class="text-muted">{{ $invoice->patient->mrn }}</small>
                            </td>
                            <td>RM {{ number_format($invoice->grand_total, 2) }}</td>
                            <td class="text-success">RM {{ number_format($invoice->paid_amount, 2) }}</td>
                            <td class="{{ $invoice->balance > 0 ? 'text-danger' : '' }}">
                                RM {{ number_format($invoice->balance, 2) }}
                            </td>
                            <td>
                                <span class="badge {{ $invoice->status_badge_class }}">
                                    {{ $invoice->status_label }}
                                </span>
                                @if($invoice->is_overdue)
                                <br><small class="text-danger">{{ $invoice->days_overdue }} hari tertunggak</small>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a href="{{ route('admin.billing.invoices.show', $invoice) }}" class="btn btn-sm btn-outline-primary" title="Lihat">
                                        <i class="mdi mdi-eye"></i>
                                    </a>
                                    @if($invoice->balance > 0 && $invoice->status !== 'void')
                                    <a href="{{ route('admin.billing.invoices.pay', $invoice) }}" class="btn btn-sm btn-outline-success" title="Bayar">
                                        <i class="mdi mdi-cash"></i>
                                    </a>
                                    @endif
                                    <a href="{{ route('admin.billing.invoices.print', $invoice) }}" class="btn btn-sm btn-outline-secondary" title="Cetak" target="_blank">
                                        <i class="mdi mdi-printer"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="mdi mdi-file-document-outline mdi-48px text-muted"></i>
                                <p class="text-muted mb-0">Tiada invois dijumpai</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $invoices->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
