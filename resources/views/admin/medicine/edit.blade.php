@extends('layouts.admin')

@section('title', __('medicine.edit') . ' ' . __('medicine.medicine') . ' - ' . $medicine->name)

@section('content')
<div class="container-fluid">

    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">{{ __('medicine.edit') }} {{ __('medicine.medicine') }}</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.medicine.index') }}">{{ __('medicine.medicine_inventory') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.medicine.show', $medicine) }}">{{ $medicine->name }}</a></li>
                        <li class="breadcrumb-item active">{{ __('medicine.edit') }}</li>
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
                        <i class="bi bi-pencil text-primary me-2"></i>
                        {{ __('medicine.edit_medicine_info') }}
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.medicine.update', $medicine) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="medicine_code" class="form-label">{{ __('medicine.medicine_code') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('medicine_code') is-invalid @enderror"
                                           id="medicine_code" name="medicine_code"
                                           value="{{ old('medicine_code', $medicine->medicine_code) }}" required>
                                    @error('medicine_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">{{ __('medicine.medicine_name') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name"
                                           value="{{ old('name', $medicine->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="description" class="form-label">{{ __('medicine.description') }}</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              id="description" name="description" rows="3"
                                              placeholder="{{ __('medicine.description') }}...">{{ old('description', $medicine->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Category & Details -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category" class="form-label">{{ __('medicine.category') }} <span class="text-danger">*</span></label>
                                    <select class="form-select @error('category') is-invalid @enderror"
                                            id="category" name="category" required>
                                        <option value="">{{ __('medicine.select_category') }}</option>
                                        <option value="tablet" {{ old('category', $medicine->category) == 'tablet' ? 'selected' : '' }}>{{ __('medicine.tablet') }}</option>
                                        <option value="capsule" {{ old('category', $medicine->category) == 'capsule' ? 'selected' : '' }}>{{ __('medicine.capsule') }}</option>
                                        <option value="syrup" {{ old('category', $medicine->category) == 'syrup' ? 'selected' : '' }}>{{ __('medicine.syrup') }}</option>
                                        <option value="injection" {{ old('category', $medicine->category) == 'injection' ? 'selected' : '' }}>{{ __('medicine.injection') }}</option>
                                        <option value="cream" {{ old('category', $medicine->category) == 'cream' ? 'selected' : '' }}>{{ __('medicine.cream') }}</option>
                                        <option value="drops" {{ old('category', $medicine->category) == 'drops' ? 'selected' : '' }}>{{ __('medicine.drops') }}</option>
                                        <option value="spray" {{ old('category', $medicine->category) == 'spray' ? 'selected' : '' }}>{{ __('medicine.spray') }}</option>
                                        <option value="patch" {{ old('category', $medicine->category) == 'patch' ? 'selected' : '' }}>{{ __('medicine.patch') }}</option>
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="strength" class="form-label">{{ __('medicine.strength') }}</label>
                                    <input type="text" class="form-control @error('strength') is-invalid @enderror"
                                           id="strength" name="strength"
                                           value="{{ old('strength', $medicine->strength) }}"
                                           placeholder="e.g., 500mg, 10ml, 5%">
                                    @error('strength')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="manufacturer" class="form-label">{{ __('medicine.manufacturer') }}</label>
                                    <input type="text" class="form-control @error('manufacturer') is-invalid @enderror"
                                           id="manufacturer" name="manufacturer"
                                           value="{{ old('manufacturer', $medicine->manufacturer) }}">
                                    @error('manufacturer')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="batch_number" class="form-label">{{ __('medicine.batch_number') }}</label>
                                    <input type="text" class="form-control @error('batch_number') is-invalid @enderror"
                                           id="batch_number" name="batch_number"
                                           value="{{ old('batch_number', $medicine->batch_number) }}">
                                    @error('batch_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Pricing & Stock -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="unit_price" class="form-label">{{ __('medicine.unit_price') }} (RM) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('unit_price') is-invalid @enderror"
                                           id="unit_price" name="unit_price"
                                           value="{{ old('unit_price', $medicine->unit_price) }}"
                                           step="0.01" min="0" required>
                                    @error('unit_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="stock_quantity" class="form-label">{{ __('medicine.stock_quantity') }} <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror"
                                           id="stock_quantity" name="stock_quantity"
                                           value="{{ old('stock_quantity', $medicine->stock_quantity) }}"
                                           min="0" required>
                                    @error('stock_quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="minimum_stock" class="form-label">{{ __('medicine.minimum_stock') }} <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('minimum_stock') is-invalid @enderror"
                                           id="minimum_stock" name="minimum_stock"
                                           value="{{ old('minimum_stock', $medicine->minimum_stock) }}"
                                           min="0" required>
                                    <div class="form-text">{{ __('medicine.alert_when_stock_below') }}</div>
                                    @error('minimum_stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Status & Expiry -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">{{ __('medicine.status') }} <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror"
                                            id="status" name="status" required>
                                        <option value="active" {{ old('status', $medicine->status) == 'active' ? 'selected' : '' }}>{{ __('medicine.active') }}</option>
                                        <option value="inactive" {{ old('status', $medicine->status) == 'inactive' ? 'selected' : '' }}>{{ __('medicine.inactive') }}</option>
                                        <option value="expired" {{ old('status', $medicine->status) == 'expired' ? 'selected' : '' }}>{{ __('medicine.expired') }}</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="expiry_date" class="form-label">{{ __('medicine.expiry_date') }}</label>
                                    <input type="date" class="form-control @error('expiry_date') is-invalid @enderror"
                                           id="expiry_date" name="expiry_date"
                                           value="{{ old('expiry_date', $medicine->expiry_date ? $medicine->expiry_date->format('Y-m-d') : '') }}"
                                           min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                    @error('expiry_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Total Value Display -->
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <strong>{{ __('medicine.total_stock_value') }}:</strong> RM <span id="total_value">{{ number_format($medicine->total_value, 2) }}</span>
                                    <small class="d-block">{{ __('medicine.auto_calculate') }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> {{ __('medicine.update') }} {{ __('medicine.medicine') }}
                            </button>
                            <a href="{{ route('admin.medicine.show', $medicine) }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> {{ __('medicine.back') }}
                            </a>
                            <a href="{{ route('admin.medicine.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-list"></i> {{ __('medicine.medicine_list') }}
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
    const totalValueDisplay = document.getElementById('total_value');

    function calculateTotalValue() {
        const unitPrice = parseFloat(unitPriceInput.value) || 0;
        const stockQuantity = parseInt(stockQuantityInput.value) || 0;
        const totalValue = unitPrice * stockQuantity;

        totalValueDisplay.textContent = totalValue.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    unitPriceInput.addEventListener('input', calculateTotalValue);
    stockQuantityInput.addEventListener('input', calculateTotalValue);

    // Initial calculation
    calculateTotalValue();
});
</script>
@endpush
