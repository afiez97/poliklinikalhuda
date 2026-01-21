@extends('layouts.admin')
@section('title', 'Invois ' . $invoice->invoice_number)

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper breadcrumb-contacts">
        <div>
            <h1>Invois {{ $invoice->invoice_number }}</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span><a href="{{ route('admin.billing.index') }}">Bil & Pembayaran</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span><a href="{{ route('admin.billing.invoices.index') }}">Invois</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>{{ $invoice->invoice_number }}</span>
            </p>
        </div>
        <div>
            @if($invoice->balance > 0 && $invoice->status !== 'void')
            <a href="{{ route('admin.billing.invoices.pay', $invoice) }}" class="btn btn-success">
                <i class="mdi mdi-cash"></i> Terima Bayaran
            </a>
            @endif
            <a href="{{ route('admin.billing.invoices.print', $invoice) }}" class="btn btn-outline-secondary" target="_blank">
                <i class="mdi mdi-printer"></i> Cetak
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

    <div class="row">
        <!-- Invoice Details -->
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Maklumat Invois</h5>
                    <span class="badge {{ $invoice->status_badge_class }} fs-6">{{ $invoice->status_label }}</span>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Maklumat Pesakit</h6>
                            <p class="mb-1"><strong>{{ $invoice->patient->name }}</strong></p>
                            <p class="mb-1">MRN: {{ $invoice->patient->mrn }}</p>
                            <p class="mb-1">IC: {{ $invoice->patient->ic_number }}</p>
                            <p class="mb-0">Tel: {{ $invoice->patient->phone ?? '-' }}</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h6 class="text-muted mb-2">Maklumat Invois</h6>
                            <p class="mb-1">No. Invois: <strong>{{ $invoice->invoice_number }}</strong></p>
                            <p class="mb-1">Tarikh: {{ $invoice->invoice_date->format('d/m/Y') }}</p>
                            <p class="mb-1">Tarikh Akhir: {{ $invoice->due_date->format('d/m/Y') }}</p>
                            @if($invoice->is_overdue)
                            <p class="mb-0 text-danger"><strong>Tertunggak {{ $invoice->days_overdue }} hari</strong></p>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <!-- Invoice Items -->
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th class="text-center">Kuantiti</th>
                                    <th class="text-end">Harga Unit</th>
                                    <th class="text-end">Diskaun</th>
                                    <th class="text-end">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoice->items as $item)
                                <tr>
                                    <td>
                                        {{ $item->item_name }}
                                        @if($item->item_code)
                                        <br><small class="text-muted">{{ $item->item_code }}</small>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">RM {{ number_format($item->unit_price, 2) }}</td>
                                    <td class="text-end">
                                        @if($item->discount_amount > 0)
                                        <span class="text-danger">-RM {{ number_format($item->discount_amount, 2) }}</span>
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td class="text-end">RM {{ number_format($item->line_total, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Jumlah Kecil:</strong></td>
                                    <td class="text-end">RM {{ number_format($invoice->subtotal, 2) }}</td>
                                </tr>
                                @if($invoice->discount_amount > 0)
                                <tr>
                                    <td colspan="4" class="text-end text-danger">
                                        <strong>Diskaun
                                            @if($invoice->discount_type === 'percentage')
                                            ({{ $invoice->discount_value }}%):
                                            @else
                                            :
                                            @endif
                                        </strong>
                                        @if($invoice->promoCode)
                                        <br><small>Kod: {{ $invoice->promoCode->code }}</small>
                                        @endif
                                    </td>
                                    <td class="text-end text-danger">-RM {{ number_format($invoice->discount_amount, 2) }}</td>
                                </tr>
                                @endif
                                @if($invoice->tax_amount > 0)
                                <tr>
                                    <td colspan="4" class="text-end"><strong>SST ({{ $invoice->tax_rate }}%):</strong></td>
                                    <td class="text-end">RM {{ number_format($invoice->tax_amount, 2) }}</td>
                                </tr>
                                @endif
                                @if($invoice->rounding_amount != 0)
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Pembundaran:</strong></td>
                                    <td class="text-end">RM {{ number_format($invoice->rounding_amount, 2) }}</td>
                                </tr>
                                @endif
                                <tr class="table-primary">
                                    <td colspan="4" class="text-end"><strong>JUMLAH BESAR:</strong></td>
                                    <td class="text-end"><strong>RM {{ number_format($invoice->grand_total, 2) }}</strong></td>
                                </tr>
                                <tr class="table-success">
                                    <td colspan="4" class="text-end"><strong>Dibayar:</strong></td>
                                    <td class="text-end"><strong>RM {{ number_format($invoice->paid_amount, 2) }}</strong></td>
                                </tr>
                                @if($invoice->balance > 0)
                                <tr class="table-danger">
                                    <td colspan="4" class="text-end"><strong>Baki:</strong></td>
                                    <td class="text-end"><strong>RM {{ number_format($invoice->balance, 2) }}</strong></td>
                                </tr>
                                @endif
                            </tfoot>
                        </table>
                    </div>

                    @if($invoice->notes)
                    <div class="mt-3">
                        <h6>Catatan:</h6>
                        <p class="text-muted">{{ $invoice->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Payment History -->
            @if($invoice->payments->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Sejarah Pembayaran</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>No. Bayaran</th>
                                    <th>Tarikh</th>
                                    <th>Kaedah</th>
                                    <th>Rujukan</th>
                                    <th class="text-end">Jumlah</th>
                                    <th>Status</th>
                                    <th>Resit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoice->payments as $payment)
                                <tr class="{{ $payment->status === 'voided' ? 'table-danger text-decoration-line-through' : '' }}">
                                    <td>{{ $payment->payment_number }}</td>
                                    <td>{{ $payment->payment_date->format('d/m/Y H:i') }}</td>
                                    <td><span class="badge bg-secondary">{{ $payment->method_label }}</span></td>
                                    <td>{{ $payment->reference_number ?? '-' }}</td>
                                    <td class="text-end">RM {{ number_format($payment->amount, 2) }}</td>
                                    <td>
                                        <span class="badge {{ $payment->status === 'completed' ? 'bg-success' : 'bg-danger' }}">
                                            {{ $payment->status === 'completed' ? 'Selesai' : 'Batal' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($payment->receipt && $payment->status === 'completed')
                                        <a href="{{ route('admin.billing.receipts.print', $payment->receipt) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="mdi mdi-printer"></i>
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar Actions -->
        <div class="col-xl-4">
            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Tindakan</h5>
                </div>
                <div class="card-body">
                    @if($invoice->status !== 'void' && $invoice->status !== 'paid')
                    <!-- Apply Promo Code -->
                    @if(!$invoice->promo_code_id && $invoice->discount_amount == 0)
                    <form action="{{ route('admin.billing.invoices.apply-promo', $invoice) }}" method="POST" class="mb-3">
                        @csrf
                        <label class="form-label">Kod Promo</label>
                        <div class="input-group">
                            <input type="text" name="promo_code" class="form-control" placeholder="Masukkan kod">
                            <button type="submit" class="btn btn-outline-primary">Guna</button>
                        </div>
                    </form>
                    @endif

                    <!-- Request Discount -->
                    @if($invoice->discount_amount == 0)
                    <button type="button" class="btn btn-outline-warning w-100 mb-2" data-bs-toggle="modal" data-bs-target="#discountModal">
                        <i class="mdi mdi-percent"></i> Mohon Diskaun
                    </button>
                    @endif

                    <!-- Void Invoice -->
                    @if($invoice->paid_amount == 0)
                    <button type="button" class="btn btn-outline-danger w-100" data-bs-toggle="modal" data-bs-target="#voidModal">
                        <i class="mdi mdi-close-circle"></i> Batalkan Invois
                    </button>
                    @endif
                    @endif
                </div>
            </div>

            <!-- Invoice Summary -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Ringkasan</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Jumlah Item:</span>
                            <strong>{{ $invoice->items->count() }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Jumlah Besar:</span>
                            <strong>RM {{ number_format($invoice->grand_total, 2) }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between text-success">
                            <span>Dibayar:</span>
                            <strong>RM {{ number_format($invoice->paid_amount, 2) }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between {{ $invoice->balance > 0 ? 'text-danger' : 'text-success' }}">
                            <span>Baki:</span>
                            <strong>RM {{ number_format($invoice->balance, 2) }}</strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Discount Modal -->
<div class="modal fade" id="discountModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.billing.invoices.request-discount', $invoice) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Mohon Diskaun</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Jenis Diskaun</label>
                        <select name="discount_type" class="form-select" required>
                            <option value="percentage">Peratus (%)</option>
                            <option value="fixed">Tetap (RM)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nilai Diskaun</label>
                        <input type="number" name="discount_value" class="form-control" step="0.01" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sebab</label>
                        <textarea name="reason" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Hantar Permohonan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Void Modal -->
<div class="modal fade" id="voidModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.billing.invoices.void', $invoice) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Batalkan Invois</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="mdi mdi-alert"></i> Tindakan ini tidak boleh dibatalkan!
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sebab Pembatalan</label>
                        <textarea name="reason" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Batalkan Invois</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
