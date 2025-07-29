@extends('layouts.admin')

@section('title', 'Tambah Ubat Baru')

@section('content')
<div class="container-fluid">

    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Tambah Ubat Baru</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.medicine.index') }}">Inventori Ubat</a></li>
                        <li class="breadcrumb-item active">Tambah Ubat</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-plus-circle text-success me-2"></i>
                        Maklumat Ubat Baru
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.medicine.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="medicine_code" class="form-label">Kod Ubat</label>
                                    <input type="text" class="form-control @error('medicine_code') is-invalid @enderror"
                                           id="medicine_code" name="medicine_code" value="{{ old('medicine_code') }}"
                                           placeholder="Kosongkan untuk auto-generate">
                                    <div class="form-text">Biarkan kosong untuk kod automatik</div>
                                    @error('medicine_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Ubat <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Keterangan</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              id="description" name="description" rows="3"
                                              placeholder="Keterangan lengkap tentang ubat ini...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Category & Details -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category" class="form-label">Kategori <span class="text-danger">*</span></label>
                                    <select class="form-select @error('category') is-invalid @enderror"
                                            id="category" name="category" required>
                                        <option value="">Pilih kategori...</option>
                                        <option value="tablet" {{ old('category') == 'tablet' ? 'selected' : '' }}>Tablet</option>
                                        <option value="capsule" {{ old('category') == 'capsule' ? 'selected' : '' }}>Kapsul</option>
                                        <option value="syrup" {{ old('category') == 'syrup' ? 'selected' : '' }}>Sirap</option>
                                        <option value="injection" {{ old('category') == 'injection' ? 'selected' : '' }}>Suntikan</option>
                                        <option value="cream" {{ old('category') == 'cream' ? 'selected' : '' }}>Krim</option>
                                        <option value="drops" {{ old('category') == 'drops' ? 'selected' : '' }}>Titisan</option>
                                        <option value="spray" {{ old('category') == 'spray' ? 'selected' : '' }}>Semburan</option>
                                        <option value="patch" {{ old('category') == 'patch' ? 'selected' : '' }}>Tampalan</option>
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="strength" class="form-label">Kekuatan</label>
                                    <input type="text" class="form-control @error('strength') is-invalid @enderror"
                                           id="strength" name="strength" value="{{ old('strength') }}"
                                           placeholder="e.g., 500mg, 10ml, 5%">
                                    @error('strength')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="manufacturer" class="form-label">Pengeluar</label>
                                    <input type="text" class="form-control @error('manufacturer') is-invalid @enderror"
                                           id="manufacturer" name="manufacturer" value="{{ old('manufacturer') }}">
                                    @error('manufacturer')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="batch_number" class="form-label">Nombor Batch</label>
                                    <input type="text" class="form-control @error('batch_number') is-invalid @enderror"
                                           id="batch_number" name="batch_number" value="{{ old('batch_number') }}">
                                    @error('batch_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Pricing & Stock -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="unit_price" class="form-label">Harga Per Unit (RM) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('unit_price') is-invalid @enderror"
                                           id="unit_price" name="unit_price" value="{{ old('unit_price') }}"
                                           step="0.01" min="0" required>
                                    @error('unit_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="stock_quantity" class="form-label">Kuantiti Stok <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror"
                                           id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity') }}"
                                           min="0" required>
                                    @error('stock_quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="minimum_stock" class="form-label">Stok Minimum <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('minimum_stock') is-invalid @enderror"
                                           id="minimum_stock" name="minimum_stock" value="{{ old('minimum_stock', 10) }}"
                                           min="0" required>
                                    <div class="form-text">Alert apabila stok kurang dari nilai ini</div>
                                    @error('minimum_stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Expiry Date -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="expiry_date" class="form-label">Tarikh Luput</label>
                                    <input type="date" class="form-control @error('expiry_date') is-invalid @enderror"
                                           id="expiry_date" name="expiry_date" value="{{ old('expiry_date') }}"
                                           min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                    @error('expiry_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nilai Total Stok</label>
                                    <div class="input-group">
                                        <span class="input-group-text">RM</span>
                                        <input type="text" class="form-control" id="total_value" readonly>
                                    </div>
                                    <div class="form-text">Dikira automatik: Harga × Kuantiti</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Simpan Ubat
                            </button>
                            <a href="{{ route('admin.medicine.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
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
    const unitPriceInput = document.getElementById('unit_price');
    const stockQuantityInput = document.getElementById('stock_quantity');
    const totalValueInput = document.getElementById('total_value');

    function calculateTotalValue() {
        const unitPrice = parseFloat(unitPriceInput.value) || 0;
        const stockQuantity = parseInt(stockQuantityInput.value) || 0;
        const totalValue = unitPrice * stockQuantity;

        totalValueInput.value = totalValue.toFixed(2);
    }

    unitPriceInput.addEventListener('input', calculateTotalValue);
    stockQuantityInput.addEventListener('input', calculateTotalValue);

    // Initial calculation
    calculateTotalValue();
});
</script>
@endpush
