@extends('layouts.admin')

@section('title', 'Ubat Stok Rendah')

@section('content')
<div class="container-fluid">

    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Ubat Stok Rendah</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.medicine.index') }}">Inventori Ubat</a></li>
                        <li class="breadcrumb-item active">Stok Rendah</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Summary -->
    <div class="row">
        <div class="col-12">
            <div class="alert alert-warning d-flex align-items-center">
                <i class="bi bi-exclamation-triangle fs-3 me-3"></i>
                <div>
                    <h5 class="alert-heading mb-1">Amaran Stok Rendah!</h5>
                    <p class="mb-0">Terdapat <strong>{{ $medicines->count() }}</strong> jenis ubat yang mencapai paras stok minimum atau lebih rendah.</p>
                </div>
                <div class="ms-auto">
                    <a href="{{ route('admin.medicine.index') }}" class="btn btn-outline-warning">
                        <i class="bi bi-arrow-left"></i> Kembali ke Inventori
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Low Stock Medicine List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-exclamation-circle text-warning me-2"></i>
                        Ubat Yang Memerlukan Perhatian
                    </h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.medicine.create') }}" class="btn btn-success btn-sm">
                            <i class="bi bi-plus-circle"></i> Tambah Ubat Baru
                        </a>
                        <a href="{{ route('admin.medicine.stock-report') }}" class="btn btn-info btn-sm">
                            <i class="bi bi-bar-chart"></i> Laporan Stok
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($medicines->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-warning">
                                    <tr>
                                        <th>Kod Ubat</th>
                                        <th>Nama Ubat</th>
                                        <th>Kategori</th>
                                        <th>Stok Semasa</th>
                                        <th>Stok Minimum</th>
                                        <th>Kekurangan</th>
                                        <th>Nilai Terjejas</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($medicines as $medicine)
                                        @php
                                            $shortage = max(0, $medicine->minimum_stock - $medicine->stock_quantity);
                                            $affectedValue = $shortage * $medicine->unit_price;
                                        @endphp
                                        <tr class="{{ $medicine->stock_quantity == 0 ? 'table-danger' : '' }}">
                                            <td>
                                                <code>{{ $medicine->medicine_code }}</code>
                                            </td>
                                            <td>
                                                <strong>{{ $medicine->name }}</strong>
                                                @if($medicine->manufacturer)
                                                    <br><small class="text-muted">{{ $medicine->manufacturer }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $medicine->category_label }}</span>
                                            </td>
                                            <td>
                                                <span class="fw-bold fs-5 {{ $medicine->stock_quantity == 0 ? 'text-danger' : 'text-warning' }}">
                                                    {{ $medicine->stock_quantity }}
                                                </span>
                                                @if($medicine->stock_quantity == 0)
                                                    <br><span class="badge bg-danger">HABIS</span>
                                                @else
                                                    <br><span class="badge bg-warning">RENDAH</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="text-muted">{{ $medicine->minimum_stock }}</span>
                                            </td>
                                            <td>
                                                @if($shortage > 0)
                                                    <span class="text-danger fw-bold">{{ $shortage }}</span>
                                                @else
                                                    <span class="text-success">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($affectedValue > 0)
                                                    <span class="text-danger">RM {{ number_format($affectedValue, 2) }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-success"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#addStockModal"
                                                            data-medicine-id="{{ $medicine->id }}"
                                                            data-medicine-name="{{ $medicine->name }}"
                                                            data-current-stock="{{ $medicine->stock_quantity }}"
                                                            data-minimum-stock="{{ $medicine->minimum_stock }}"
                                                            title="{{ __('medicine.add_stock') }}">
                                                        <i class="bi bi-plus-circle"></i>
                                                    </button>
                                                    <a href="{{ route('admin.medicine.show', $medicine) }}"
                                                       class="btn btn-sm btn-info" title="{{ __('medicine.view') }}">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.medicine.edit', $medicine) }}"
                                                       class="btn btn-sm btn-primary" title="{{ __('medicine.edit') }}">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Summary Statistics -->
                        <div class="row mt-4">
                            <div class="col-md-3">
                                <div class="card bg-warning bg-opacity-10 border-warning">
                                    <div class="card-body text-center">
                                        <h4 class="text-warning">{{ $medicines->count() }}</h4>
                                        <p class="mb-0">Ubat Stok Rendah</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-danger bg-opacity-10 border-danger">
                                    <div class="card-body text-center">
                                        <h4 class="text-danger">{{ $medicines->where('stock_quantity', 0)->count() }}</h4>
                                        <p class="mb-0">Ubat Habis</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info bg-opacity-10 border-info">
                                    <div class="card-body text-center">
                                        <h4 class="text-info">RM {{ number_format($medicines->sum('total_value'), 0) }}</h4>
                                        <p class="mb-0">Nilai Stok Semasa</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-secondary bg-opacity-10 border-secondary">
                                    <div class="card-body text-center">
                                        @php
                                            $totalShortageValue = $medicines->sum(function($medicine) {
                                                $shortage = max(0, $medicine->minimum_stock - $medicine->stock_quantity);
                                                return $shortage * $medicine->unit_price;
                                            });
                                        @endphp
                                        <h4 class="text-secondary">RM {{ number_format($totalShortageValue, 0) }}</h4>
                                        <p class="mb-0">Nilai Kekurangan</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-check-circle display-1 text-success"></i>
                            <h5 class="mt-3 text-success">Tiada Ubat Stok Rendah</h5>
                            <p class="text-muted">Semua ubat mempunyai stok yang mencukupi.</p>
                            <a href="{{ route('admin.medicine.index') }}" class="btn btn-primary">
                                <i class="bi bi-arrow-left"></i> {{ __('medicine.back') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Stock Modal -->
<div class="modal fade" id="addStockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="addStockForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Stok Ubat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong id="medicineName"></strong><br>
                        Stok Semasa: <span id="currentStock" class="fw-bold"></span> unit<br>
                        Stok Minimum: <span id="minimumStock" class="fw-bold"></span> unit<br>
                        Cadangan Tambah: <span id="suggestedQuantity" class="fw-bold text-success"></span> unit
                    </div>

                    <input type="hidden" name="action" value="add">

                    <div class="mb-3">
                        <label for="quantity" class="form-label">Kuantiti Untuk Ditambah</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
                        <div class="form-text">Masukkan kuantiti ubat yang ingin ditambah ke stok</div>
                    </div>

                    <div class="mb-3">
                        <label for="reason" class="form-label">Sebab Penambahan</label>
                        <input type="text" class="form-control" id="reason" name="reason"
                               placeholder="Pembelian baru, penambahan stok, dll..." required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('medicine.cancel') }}</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-plus-circle"></i> {{ __('medicine.add_stock') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const addStockModal = document.getElementById('addStockModal');
    const addStockForm = document.getElementById('addStockForm');
    const medicineName = document.getElementById('medicineName');
    const currentStock = document.getElementById('currentStock');
    const minimumStock = document.getElementById('minimumStock');
    const suggestedQuantity = document.getElementById('suggestedQuantity');
    const quantityInput = document.getElementById('quantity');

    addStockModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const medicineId = button.getAttribute('data-medicine-id');
        const medicineName_ = button.getAttribute('data-medicine-name');
        const currentStock_ = parseInt(button.getAttribute('data-current-stock'));
        const minimumStock_ = parseInt(button.getAttribute('data-minimum-stock'));

        const suggested = Math.max(0, minimumStock_ - currentStock_) + 50; // Tambah 50 extra

        medicineName.textContent = medicineName_;
        currentStock.textContent = currentStock_;
        minimumStock.textContent = minimumStock_;
        suggestedQuantity.textContent = suggested;
        quantityInput.value = suggested;

        addStockForm.action = `/admin/medicine/${medicineId}/update-stock`;
    });
});
</script>
@endpush
