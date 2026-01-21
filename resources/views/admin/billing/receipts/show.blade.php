@extends('layouts.admin')
@section('title', 'Resit ' . $receipt->receipt_number)

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper breadcrumb-contacts">
        <div>
            <h1>Resit {{ $receipt->receipt_number }}</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span><a href="{{ route('admin.billing.index') }}">Bil & Pembayaran</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>Resit</span>
            </p>
        </div>
        <div>
            <a href="{{ route('admin.billing.receipts.print', $receipt) }}" class="btn btn-primary" target="_blank">
                <i class="mdi mdi-printer"></i> Cetak Resit
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header bg-success text-white text-center">
                    <h4 class="mb-0"><i class="mdi mdi-check-circle"></i> Pembayaran Berjaya</h4>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h2 class="text-success">RM {{ number_format($payment->amount, 2) }}</h2>
                        <p class="text-muted">{{ $receipt->receipt_number }}</p>
                    </div>

                    <hr>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">Maklumat Pesakit</h6>
                            <p class="mb-1"><strong>{{ $invoice->patient->name }}</strong></p>
                            <p class="mb-1">MRN: {{ $invoice->patient->mrn }}</p>
                            <p class="mb-0">IC: {{ $invoice->patient->ic_number }}</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h6 class="text-muted">Maklumat Pembayaran</h6>
                            <p class="mb-1">No. Invois: <a href="{{ route('admin.billing.invoices.show', $invoice) }}">{{ $invoice->invoice_number }}</a></p>
                            <p class="mb-1">Tarikh: {{ $payment->payment_date->format('d/m/Y H:i') }}</p>
                            <p class="mb-0">
                                Kaedah:
                                @php
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
                                <span class="badge bg-secondary">{{ $methodLabel }}</span>
                            </p>
                        </div>
                    </div>

                    <table class="table">
                        <tr>
                            <td>Jumlah Invois</td>
                            <td class="text-end">RM {{ number_format($invoice->grand_total, 2) }}</td>
                        </tr>
                        <tr class="table-success">
                            <td><strong>Jumlah Dibayar</strong></td>
                            <td class="text-end"><strong>RM {{ number_format($payment->amount, 2) }}</strong></td>
                        </tr>
                        @if($invoice->balance > 0)
                        <tr class="table-warning">
                            <td><strong>Baki Belum Bayar</strong></td>
                            <td class="text-end"><strong>RM {{ number_format($invoice->balance, 2) }}</strong></td>
                        </tr>
                        @else
                        <tr class="table-success">
                            <td colspan="2" class="text-center text-success">
                                <i class="mdi mdi-check-circle"></i> <strong>DIBAYAR PENUH</strong>
                            </td>
                        </tr>
                        @endif
                    </table>

                    @if($payment->reference_number)
                    <div class="alert alert-info">
                        <strong>No. Rujukan:</strong> {{ $payment->reference_number }}
                    </div>
                    @endif
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.billing.invoices.show', $invoice) }}" class="btn btn-outline-secondary">
                            <i class="mdi mdi-arrow-left"></i> Kembali ke Invois
                        </a>
                        <a href="{{ route('admin.billing.receipts.print', $receipt) }}" class="btn btn-primary" target="_blank">
                            <i class="mdi mdi-printer"></i> Cetak Resit
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
