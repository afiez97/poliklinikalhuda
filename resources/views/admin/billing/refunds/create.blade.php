@extends('layouts.admin')
@section('title', 'Permohonan Pulangan')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper breadcrumb-contacts">
        <div>
            <h1>Permohonan Pulangan</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span><a href="{{ route('admin.billing.index') }}">Bil & Pembayaran</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span><a href="{{ route('admin.billing.refunds.index') }}">Pulangan</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>Permohonan Baru</span>
            </p>
        </div>
    </div>

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <!-- Payment Info -->
        <div class="col-xl-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Maklumat Pembayaran</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="40%">No. Bayaran</td>
                            <td><strong>{{ $payment->payment_number }}</strong></td>
                        </tr>
                        <tr>
                            <td>Tarikh Bayaran</td>
                            <td>{{ $payment->payment_date->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td>No. Invois</td>
                            <td>
                                <a href="{{ route('admin.billing.invoices.show', $payment->invoice) }}">
                                    {{ $payment->invoice->invoice_number }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>Pesakit</td>
                            <td>
                                <strong>{{ $payment->invoice->patient->name }}</strong><br>
                                <small class="text-muted">{{ $payment->invoice->patient->mrn }}</small>
                            </td>
                        </tr>
                        <tr>
                            <td>Kaedah Bayaran</td>
                            <td>
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
                            </td>
                        </tr>
                        <tr>
                            <td>Jumlah Bayaran</td>
                            <td><strong class="text-success fs-5">RM {{ number_format($payment->amount, 2) }}</strong></td>
                        </tr>
                    </table>

                    <hr>

                    <div class="alert alert-info mb-0">
                        <h6><i class="mdi mdi-information"></i> Jumlah Boleh Dipulangkan</h6>
                        <h3 class="mb-0">RM {{ number_format($refundableAmount, 2) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Refund Form -->
        <div class="col-xl-7">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Butiran Pulangan</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.billing.refunds.store', $payment) }}" method="POST">
                        @csrf

                        <!-- Amount -->
                        <div class="mb-4">
                            <label class="form-label">Jumlah Pulangan <span class="text-danger">*</span></label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text">RM</span>
                                <input type="number" name="amount" class="form-control form-control-lg text-end"
                                    step="0.01" min="0.01" max="{{ $refundableAmount }}"
                                    value="{{ old('amount', $refundableAmount) }}" required>
                            </div>
                            <div class="form-text">Maksimum: RM {{ number_format($refundableAmount, 2) }}</div>
                            @error('amount')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Refund Method -->
                        <div class="mb-4">
                            <label class="form-label">Kaedah Pulangan <span class="text-danger">*</span></label>
                            <div class="row g-2">
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="refund_method" id="refund_cash" value="cash"
                                        {{ $payment->payment_method == 'cash' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-success w-100 py-3" for="refund_cash">
                                        <i class="mdi mdi-cash mdi-24px d-block mb-1"></i>
                                        Tunai
                                    </label>
                                </div>
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="refund_method" id="refund_card" value="card"
                                        {{ $payment->payment_method == 'card' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-primary w-100 py-3" for="refund_card">
                                        <i class="mdi mdi-credit-card mdi-24px d-block mb-1"></i>
                                        Kad
                                    </label>
                                </div>
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="refund_method" id="refund_transfer" value="transfer"
                                        {{ in_array($payment->payment_method, ['transfer', 'qr', 'ewallet']) ? 'checked' : '' }}>
                                    <label class="btn btn-outline-secondary w-100 py-3" for="refund_transfer">
                                        <i class="mdi mdi-bank-transfer mdi-24px d-block mb-1"></i>
                                        Pindahan
                                    </label>
                                </div>
                            </div>
                            @error('refund_method')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Reason -->
                        <div class="mb-4">
                            <label class="form-label">Sebab Pulangan <span class="text-danger">*</span></label>
                            <textarea name="reason" class="form-control" rows="4" required
                                placeholder="Nyatakan sebab pulangan dengan jelas">{{ old('reason') }}</textarea>
                            @error('reason')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Bank Details (for transfer) -->
                        <div id="bankDetails" class="mb-4" style="display: none;">
                            <h6>Maklumat Akaun Bank (untuk pindahan)</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nama Bank</label>
                                    <input type="text" name="bank_name" class="form-control"
                                        value="{{ old('bank_name') }}" placeholder="cth: Maybank">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">No. Akaun</label>
                                    <input type="text" name="bank_account" class="form-control"
                                        value="{{ old('bank_account') }}" placeholder="No. akaun bank">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Nama Pemegang Akaun</label>
                                    <input type="text" name="account_name" class="form-control"
                                        value="{{ old('account_name', $payment->invoice->patient->name) }}">
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-warning">
                            <i class="mdi mdi-alert"></i>
                            <strong>Nota:</strong> Permohonan pulangan melebihi had tertentu memerlukan kelulusan penyelia.
                        </div>

                        <!-- Submit -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-warning btn-lg flex-grow-1">
                                <i class="mdi mdi-cash-refund"></i> Hantar Permohonan Pulangan
                            </button>
                            <a href="{{ route('admin.billing.payments.index') }}" class="btn btn-secondary btn-lg">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const refundMethods = document.querySelectorAll('input[name="refund_method"]');
    const bankDetails = document.getElementById('bankDetails');

    refundMethods.forEach(method => {
        method.addEventListener('change', function() {
            bankDetails.style.display = this.value === 'transfer' ? 'block' : 'none';
        });

        // Check initial state
        if (method.checked && method.value === 'transfer') {
            bankDetails.style.display = 'block';
        }
    });
});
</script>
@endpush
