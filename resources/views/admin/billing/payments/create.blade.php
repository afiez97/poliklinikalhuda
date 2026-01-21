@extends('layouts.admin')
@section('title', 'Terima Bayaran')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper breadcrumb-contacts">
        <div>
            <h1>Terima Bayaran</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span><a href="{{ route('admin.billing.index') }}">Bil & Pembayaran</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>Terima Bayaran</span>
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
        <!-- Invoice Summary -->
        <div class="col-xl-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Maklumat Invois</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="40%">No. Invois</td>
                            <td><strong>{{ $invoice->invoice_number }}</strong></td>
                        </tr>
                        <tr>
                            <td>Tarikh</td>
                            <td>{{ $invoice->invoice_date->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <td>Pesakit</td>
                            <td>
                                <strong>{{ $invoice->patient->name }}</strong><br>
                                <small class="text-muted">{{ $invoice->patient->mrn }} | {{ $invoice->patient->ic_number }}</small>
                            </td>
                        </tr>
                    </table>

                    <hr>

                    <h6 class="mb-3">Item</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoice->items as $item)
                                <tr>
                                    <td>{{ $item->item_name }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">RM {{ number_format($item->line_total, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <hr>

                    <table class="table table-borderless mb-0">
                        <tr>
                            <td>Jumlah Kecil</td>
                            <td class="text-end">RM {{ number_format($invoice->subtotal, 2) }}</td>
                        </tr>
                        @if($invoice->discount_amount > 0)
                        <tr class="text-danger">
                            <td>Diskaun</td>
                            <td class="text-end">-RM {{ number_format($invoice->discount_amount, 2) }}</td>
                        </tr>
                        @endif
                        @if($invoice->tax_amount > 0)
                        <tr>
                            <td>SST ({{ $invoice->tax_rate }}%)</td>
                            <td class="text-end">RM {{ number_format($invoice->tax_amount, 2) }}</td>
                        </tr>
                        @endif
                        @if($invoice->rounding_amount != 0)
                        <tr>
                            <td>Pembundaran</td>
                            <td class="text-end">RM {{ number_format($invoice->rounding_amount, 2) }}</td>
                        </tr>
                        @endif
                        <tr class="table-primary">
                            <td><strong>Jumlah Besar</strong></td>
                            <td class="text-end"><strong>RM {{ number_format($invoice->grand_total, 2) }}</strong></td>
                        </tr>
                        <tr class="table-success">
                            <td><strong>Sudah Dibayar</strong></td>
                            <td class="text-end"><strong>RM {{ number_format($invoice->paid_amount, 2) }}</strong></td>
                        </tr>
                        <tr class="table-danger">
                            <td><strong>Baki</strong></td>
                            <td class="text-end"><strong class="fs-4">RM {{ number_format($invoice->balance, 2) }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Payment Form -->
        <div class="col-xl-7">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Butiran Pembayaran</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.billing.invoices.process-payment', $invoice) }}" method="POST" id="paymentForm">
                        @csrf

                        <!-- Amount -->
                        <div class="mb-4">
                            <label class="form-label">Jumlah Bayaran <span class="text-danger">*</span></label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text">RM</span>
                                <input type="number" name="amount" id="amount" class="form-control form-control-lg text-end"
                                    step="0.01" min="0.01" max="{{ $invoice->balance }}"
                                    value="{{ old('amount', $invoice->balance) }}" required>
                            </div>
                            <div class="form-text">Baki: RM {{ number_format($invoice->balance, 2) }}</div>
                            @error('amount')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Payment Method -->
                        <div class="mb-4">
                            <label class="form-label">Kaedah Bayaran <span class="text-danger">*</span></label>
                            <div class="row g-2">
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="payment_method" id="method_cash" value="cash" checked>
                                    <label class="btn btn-outline-success w-100 py-3" for="method_cash">
                                        <i class="mdi mdi-cash mdi-24px d-block mb-1"></i>
                                        Tunai
                                    </label>
                                </div>
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="payment_method" id="method_card" value="card">
                                    <label class="btn btn-outline-primary w-100 py-3" for="method_card">
                                        <i class="mdi mdi-credit-card mdi-24px d-block mb-1"></i>
                                        Kad
                                    </label>
                                </div>
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="payment_method" id="method_qr" value="qr">
                                    <label class="btn btn-outline-info w-100 py-3" for="method_qr">
                                        <i class="mdi mdi-qrcode mdi-24px d-block mb-1"></i>
                                        QR Pay
                                    </label>
                                </div>
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="payment_method" id="method_ewallet" value="ewallet">
                                    <label class="btn btn-outline-warning w-100 py-3" for="method_ewallet">
                                        <i class="mdi mdi-wallet mdi-24px d-block mb-1"></i>
                                        E-Wallet
                                    </label>
                                </div>
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="payment_method" id="method_transfer" value="transfer">
                                    <label class="btn btn-outline-secondary w-100 py-3" for="method_transfer">
                                        <i class="mdi mdi-bank-transfer mdi-24px d-block mb-1"></i>
                                        Pindahan
                                    </label>
                                </div>
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="payment_method" id="method_panel" value="panel">
                                    <label class="btn btn-outline-danger w-100 py-3" for="method_panel">
                                        <i class="mdi mdi-shield-check mdi-24px d-block mb-1"></i>
                                        Panel
                                    </label>
                                </div>
                            </div>
                            @error('payment_method')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Card Details (shown when card is selected) -->
                        <div id="cardDetails" class="mb-4" style="display: none;">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Jenis Kad</label>
                                    <select name="card_type" class="form-select">
                                        <option value="">Pilih jenis kad</option>
                                        <option value="visa">Visa</option>
                                        <option value="mastercard">Mastercard</option>
                                        <option value="amex">American Express</option>
                                        <option value="debit">Debit</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">4 Digit Terakhir Kad</label>
                                    <input type="text" name="card_last_four" class="form-control"
                                        maxlength="4" pattern="[0-9]{4}" placeholder="1234">
                                </div>
                            </div>
                        </div>

                        <!-- Transfer/QR/eWallet Details -->
                        <div id="transferDetails" class="mb-4" style="display: none;">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nama Bank / Platform</label>
                                    <input type="text" name="bank_name" class="form-control" placeholder="cth: Maybank, TnG, GrabPay">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">No. Rujukan</label>
                                    <input type="text" name="reference_number" class="form-control" placeholder="No. transaksi">
                                </div>
                            </div>
                        </div>

                        <!-- Panel Details -->
                        <div id="panelDetails" class="mb-4" style="display: none;">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label">Nama Panel / Syarikat</label>
                                    <input type="text" name="panel_name" class="form-control" placeholder="Nama panel korporat">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">No. Rujukan GL / PO</label>
                                    <input type="text" name="reference_number" class="form-control" placeholder="No. GL / Purchase Order">
                                </div>
                            </div>
                        </div>

                        <!-- Cash Calculator -->
                        <div id="cashCalculator" class="mb-4">
                            <label class="form-label">Kalkulator Baki (Tunai)</label>
                            <div class="row g-2 mb-2">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text">Diterima</span>
                                        <span class="input-group-text">RM</span>
                                        <input type="number" id="cashReceived" class="form-control text-end"
                                            step="0.01" min="0" placeholder="0.00">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text">Baki</span>
                                        <span class="input-group-text">RM</span>
                                        <input type="text" id="changeAmount" class="form-control text-end bg-light"
                                            readonly value="0.00">
                                    </div>
                                </div>
                            </div>
                            <div class="btn-group w-100">
                                <button type="button" class="btn btn-outline-secondary cash-btn" data-amount="1">RM1</button>
                                <button type="button" class="btn btn-outline-secondary cash-btn" data-amount="5">RM5</button>
                                <button type="button" class="btn btn-outline-secondary cash-btn" data-amount="10">RM10</button>
                                <button type="button" class="btn btn-outline-secondary cash-btn" data-amount="20">RM20</button>
                                <button type="button" class="btn btn-outline-secondary cash-btn" data-amount="50">RM50</button>
                                <button type="button" class="btn btn-outline-secondary cash-btn" data-amount="100">RM100</button>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mb-4">
                            <label class="form-label">Catatan</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Catatan tambahan (pilihan)"></textarea>
                        </div>

                        <!-- Submit -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success btn-lg flex-grow-1">
                                <i class="mdi mdi-check"></i> Terima Bayaran
                            </button>
                            <a href="{{ route('admin.billing.invoices.show', $invoice) }}" class="btn btn-secondary btn-lg">
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
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const cardDetails = document.getElementById('cardDetails');
    const transferDetails = document.getElementById('transferDetails');
    const panelDetails = document.getElementById('panelDetails');
    const cashCalculator = document.getElementById('cashCalculator');
    const amountInput = document.getElementById('amount');
    const cashReceived = document.getElementById('cashReceived');
    const changeAmount = document.getElementById('changeAmount');

    // Toggle payment method details
    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            cardDetails.style.display = 'none';
            transferDetails.style.display = 'none';
            panelDetails.style.display = 'none';
            cashCalculator.style.display = 'none';

            switch(this.value) {
                case 'cash':
                    cashCalculator.style.display = 'block';
                    break;
                case 'card':
                    cardDetails.style.display = 'block';
                    break;
                case 'qr':
                case 'ewallet':
                case 'transfer':
                    transferDetails.style.display = 'block';
                    break;
                case 'panel':
                    panelDetails.style.display = 'block';
                    break;
            }
        });
    });

    // Cash calculator
    function calculateChange() {
        const amount = parseFloat(amountInput.value) || 0;
        const received = parseFloat(cashReceived.value) || 0;
        const change = received - amount;
        changeAmount.value = change >= 0 ? change.toFixed(2) : '0.00';

        if (change < 0 && received > 0) {
            changeAmount.classList.add('text-danger');
        } else {
            changeAmount.classList.remove('text-danger');
        }
    }

    amountInput.addEventListener('input', calculateChange);
    cashReceived.addEventListener('input', calculateChange);

    // Quick cash buttons
    document.querySelectorAll('.cash-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const currentValue = parseFloat(cashReceived.value) || 0;
            cashReceived.value = (currentValue + parseInt(this.dataset.amount)).toFixed(2);
            calculateChange();
        });
    });
});
</script>
@endpush
