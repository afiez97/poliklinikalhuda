@extends('layouts.admin')

@section('title', 'Maklumat Pembekal - ' . $supplier->name)

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between">
        <div>
            <h1>{{ $supplier->name }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.pharmacy.medicines.index') }}">Farmasi</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.pharmacy.suppliers.index') }}">Pembekal</a></li>
                    <li class="breadcrumb-item active">{{ $supplier->name }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.pharmacy.suppliers.edit', $supplier) }}" class="btn btn-primary">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
            <a href="{{ route('admin.pharmacy.suppliers.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Maklumat Utama -->
        <div class="col-lg-8">
            <div class="card card-default">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2><i class="bi bi-building me-2"></i>Maklumat Pembekal</h2>
                    @if($supplier->is_active)
                    <span class="badge bg-success">Aktif</span>
                    @else
                    <span class="badge bg-secondary">Tidak Aktif</span>
                    @endif
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-muted" width="40%">Kod:</td>
                                    <td><code>{{ $supplier->code }}</code></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Nama:</td>
                                    <td class="fw-semibold">{{ $supplier->name }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Pegawai Hubungan:</td>
                                    <td>{{ $supplier->contact_person ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Telefon:</td>
                                    <td>
                                        @if($supplier->phone)
                                        <a href="tel:{{ $supplier->phone }}">{{ $supplier->phone }}</a>
                                        @else
                                        -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Email:</td>
                                    <td>
                                        @if($supplier->email)
                                        <a href="mailto:{{ $supplier->email }}">{{ $supplier->email }}</a>
                                        @else
                                        -
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-muted" width="40%">No. SSM:</td>
                                    <td>{{ $supplier->registration_no ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">No. Cukai:</td>
                                    <td>{{ $supplier->tax_id ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Bank:</td>
                                    <td>{{ $supplier->bank_name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">No. Akaun:</td>
                                    <td>{{ $supplier->bank_account ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Terma Bayaran:</td>
                                    <td>{{ $supplier->payment_terms ? $supplier->payment_terms . ' hari' : '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($supplier->address || $supplier->city || $supplier->state)
                    <hr>
                    <h6 class="text-muted mb-3"><i class="bi bi-geo-alt me-1"></i> Alamat</h6>
                    <p class="mb-0">
                        {{ $supplier->address }}<br>
                        @if($supplier->postcode || $supplier->city || $supplier->state)
                        {{ $supplier->postcode }} {{ $supplier->city }}, {{ $supplier->state }}
                        @endif
                    </p>
                    @endif

                    @if($supplier->notes)
                    <hr>
                    <h6 class="text-muted mb-3"><i class="bi bi-sticky me-1"></i> Nota</h6>
                    <p class="mb-0">{{ $supplier->notes }}</p>
                    @endif
                </div>
            </div>

            <!-- Pesanan Pembelian Terkini -->
            <div class="card card-default mt-4">
                <div class="card-header">
                    <h2><i class="bi bi-cart me-2"></i>Pesanan Pembelian Terkini</h2>
                </div>
                <div class="card-body">
                    @if($supplier->purchaseOrders && $supplier->purchaseOrders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No. PO</th>
                                    <th>Tarikh</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($supplier->purchaseOrders as $po)
                                <tr>
                                    <td><code>{{ $po->po_number }}</code></td>
                                    <td>{{ $po->order_date?->format('d/m/Y') }}</td>
                                    <td>RM {{ number_format($po->total_amount, 2) }}</td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'draft' => 'secondary',
                                                'pending' => 'warning',
                                                'approved' => 'info',
                                                'ordered' => 'primary',
                                                'received' => 'success',
                                                'cancelled' => 'danger',
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$po->status] ?? 'secondary' }}">
                                            {{ ucfirst($po->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2 mb-0">Tiada pesanan pembelian terkini.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Statistik -->
            <div class="card card-default">
                <div class="card-header">
                    <h2><i class="bi bi-graph-up me-2"></i>Statistik</h2>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center py-2">
                        <span class="text-muted">Jumlah PO</span>
                        <span class="fw-semibold">{{ $supplier->purchaseOrders?->count() ?? 0 }}</span>
                    </div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between align-items-center py-2">
                        <span class="text-muted">Didaftarkan</span>
                        <span>{{ $supplier->created_at->format('d/m/Y') }}</span>
                    </div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between align-items-center py-2">
                        <span class="text-muted">Kemaskini Terakhir</span>
                        <span>{{ $supplier->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Tindakan Pantas -->
            <div class="card card-default mt-4">
                <div class="card-header">
                    <h2><i class="bi bi-lightning me-2"></i>Tindakan Pantas</h2>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.pharmacy.suppliers.edit', $supplier) }}" class="btn btn-outline-primary">
                            <i class="bi bi-pencil me-1"></i> Edit Pembekal
                        </a>
                        @if($supplier->phone)
                        <a href="tel:{{ $supplier->phone }}" class="btn btn-outline-success">
                            <i class="bi bi-telephone me-1"></i> Hubungi
                        </a>
                        @endif
                        @if($supplier->email)
                        <a href="mailto:{{ $supplier->email }}" class="btn btn-outline-info">
                            <i class="bi bi-envelope me-1"></i> Email
                        </a>
                        @endif
                        <button type="button" class="btn btn-outline-danger"
                                onclick="confirmDelete()">
                            <i class="bi bi-trash me-1"></i> Padam Pembekal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="delete-form" action="{{ route('admin.pharmacy.suppliers.destroy', $supplier) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script>
function confirmDelete() {
    if (confirm('Adakah anda pasti mahu memadam pembekal "{{ $supplier->name }}"?')) {
        document.getElementById('delete-form').submit();
    }
}
</script>
@endpush
@endsection
