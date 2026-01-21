@extends('layouts.admin')
@section('title', 'Senarai Pembayaran')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper breadcrumb-contacts">
        <div>
            <h1>Senarai Pembayaran</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span><a href="{{ route('admin.billing.index') }}">Bil & Pembayaran</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>Pembayaran</span>
            </p>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.billing.payments.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Carian</label>
                        <input type="text" name="search" class="form-control"
                            placeholder="No. bayaran / Nama pesakit" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Kaedah Bayaran</label>
                        <select name="payment_method" class="form-select">
                            <option value="">Semua</option>
                            <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Tunai</option>
                            <option value="card" {{ request('payment_method') == 'card' ? 'selected' : '' }}>Kad</option>
                            <option value="qr" {{ request('payment_method') == 'qr' ? 'selected' : '' }}>QR Pay</option>
                            <option value="ewallet" {{ request('payment_method') == 'ewallet' ? 'selected' : '' }}>E-Wallet</option>
                            <option value="transfer" {{ request('payment_method') == 'transfer' ? 'selected' : '' }}>Pindahan</option>
                            <option value="panel" {{ request('payment_method') == 'panel' ? 'selected' : '' }}>Panel</option>
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
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                            <option value="voided" {{ request('status') == 'voided' ? 'selected' : '' }}>Batal</option>
                        </select>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="mdi mdi-magnify"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>No. Bayaran</th>
                            <th>Tarikh</th>
                            <th>Invois</th>
                            <th>Pesakit</th>
                            <th>Kaedah</th>
                            <th>Rujukan</th>
                            <th class="text-end">Jumlah</th>
                            <th>Status</th>
                            <th class="text-center">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                        <tr class="{{ $payment->status === 'voided' ? 'table-danger' : '' }}">
                            <td>
                                <strong>{{ $payment->payment_number }}</strong>
                            </td>
                            <td>
                                {{ $payment->payment_date->format('d/m/Y') }}
                                <br>
                                <small class="text-muted">{{ $payment->payment_date->format('H:i') }}</small>
                            </td>
                            <td>
                                @if($payment->invoice)
                                <a href="{{ route('admin.billing.invoices.show', $payment->invoice) }}">
                                    {{ $payment->invoice->invoice_number }}
                                </a>
                                @else
                                -
                                @endif
                            </td>
                            <td>
                                {{ $payment->invoice->patient->name ?? '-' }}
                                <br>
                                <small class="text-muted">{{ $payment->invoice->patient->mrn ?? '' }}</small>
                            </td>
                            <td>
                                @php
                                    $methodClass = match($payment->payment_method) {
                                        'cash' => 'bg-success',
                                        'card' => 'bg-primary',
                                        'qr' => 'bg-info',
                                        'ewallet' => 'bg-warning',
                                        'transfer' => 'bg-secondary',
                                        'panel' => 'bg-danger',
                                        default => 'bg-secondary'
                                    };
                                    $methodLabel = match($payment->payment_method) {
                                        'cash' => 'Tunai',
                                        'card' => 'Kad',
                                        'qr' => 'QR Pay',
                                        'ewallet' => 'E-Wallet',
                                        'transfer' => 'Pindahan',
                                        'panel' => 'Panel',
                                        default => $payment->payment_method
                                    };
                                @endphp
                                <span class="badge {{ $methodClass }}">{{ $methodLabel }}</span>
                                @if($payment->card_type)
                                <br><small>{{ $payment->card_type }} ****{{ $payment->card_last_four }}</small>
                                @endif
                            </td>
                            <td>{{ $payment->reference_number ?? '-' }}</td>
                            <td class="text-end">
                                <strong class="{{ $payment->status === 'voided' ? 'text-decoration-line-through' : 'text-success' }}">
                                    RM {{ number_format($payment->amount, 2) }}
                                </strong>
                            </td>
                            <td>
                                @if($payment->status === 'completed')
                                <span class="badge bg-success">Selesai</span>
                                @elseif($payment->status === 'voided')
                                <span class="badge bg-danger">Batal</span>
                                @else
                                <span class="badge bg-secondary">{{ $payment->status }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    @if($payment->receipt && $payment->status === 'completed')
                                    <a href="{{ route('admin.billing.receipts.print', $payment->receipt) }}"
                                        class="btn btn-outline-primary" target="_blank" title="Cetak Resit">
                                        <i class="mdi mdi-printer"></i>
                                    </a>
                                    @endif
                                    @if($payment->status === 'completed')
                                    <a href="{{ route('admin.billing.refunds.create', $payment) }}"
                                        class="btn btn-outline-warning" title="Pulangan">
                                        <i class="mdi mdi-cash-refund"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-danger"
                                        data-bs-toggle="modal" data-bs-target="#voidModal{{ $payment->id }}"
                                        title="Batalkan">
                                        <i class="mdi mdi-close-circle"></i>
                                    </button>
                                    @endif
                                </div>

                                <!-- Void Modal -->
                                <div class="modal fade" id="voidModal{{ $payment->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.billing.payments.void', $payment) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Batalkan Pembayaran</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="alert alert-warning">
                                                        <i class="mdi mdi-alert"></i> Pembayaran <strong>{{ $payment->payment_number }}</strong>
                                                        sebanyak <strong>RM {{ number_format($payment->amount, 2) }}</strong> akan dibatalkan.
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Sebab Pembatalan <span class="text-danger">*</span></label>
                                                        <textarea name="reason" class="form-control" rows="3" required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                    <button type="submit" class="btn btn-danger">Batalkan Pembayaran</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="mdi mdi-cash-off mdi-48px text-muted"></i>
                                <p class="text-muted mb-0">Tiada rekod pembayaran</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($payments->hasPages())
        <div class="card-footer">
            {{ $payments->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
