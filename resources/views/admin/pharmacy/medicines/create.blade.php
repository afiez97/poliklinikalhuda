@extends('layouts.admin')
@section('title', 'Tambah Ubat Baru')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper breadcrumb-contacts">
        <div>
            <h1>Tambah Ubat Baru</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span><a href="{{ route('admin.pharmacy.medicines.index') }}">Farmasi</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>Tambah Ubat</span>
            </p>
        </div>
    </div>

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <form action="{{ route('admin.pharmacy.medicines.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <!-- Basic Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="mdi mdi-pill me-2"></i>Maklumat Asas</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nama Ubat <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nama Generik</label>
                                    <input type="text" name="name_generic" class="form-control @error('name_generic') is-invalid @enderror" value="{{ old('name_generic') }}">
                                    @error('name_generic')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Barcode</label>
                                    <input type="text" name="barcode" class="form-control @error('barcode') is-invalid @enderror" value="{{ old('barcode') }}">
                                    @error('barcode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Kategori</label>
                                    <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                                        <option value="">Pilih Kategori</option>
                                        @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Pengilang</label>
                                    <input type="text" name="manufacturer" class="form-control @error('manufacturer') is-invalid @enderror" value="{{ old('manufacturer') }}">
                                    @error('manufacturer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Bentuk Dos</label>
                                    <select name="dosage_form" class="form-select @error('dosage_form') is-invalid @enderror">
                                        <option value="">Pilih Bentuk</option>
                                        @foreach($dosageForms as $key => $label)
                                        <option value="{{ $key }}" {{ old('dosage_form') == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('dosage_form')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Kekuatan/Dos</label>
                                    <input type="text" name="strength" class="form-control @error('strength') is-invalid @enderror" value="{{ old('strength') }}" placeholder="cth: 500mg, 10ml">
                                    @error('strength')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Unit <span class="text-danger">*</span></label>
                                    <input type="text" name="unit" class="form-control @error('unit') is-invalid @enderror" value="{{ old('unit', 'unit') }}" required placeholder="cth: tablet, botol">
                                    @error('unit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pricing & Stock -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="mdi mdi-cash me-2"></i>Harga & Stok</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Harga Kos (RM) <span class="text-danger">*</span></label>
                                    <input type="number" name="cost_price" step="0.01" min="0" class="form-control @error('cost_price') is-invalid @enderror" value="{{ old('cost_price', '0.00') }}" required>
                                    @error('cost_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Harga Jualan (RM) <span class="text-danger">*</span></label>
                                    <input type="number" name="selling_price" step="0.01" min="0" class="form-control @error('selling_price') is-invalid @enderror" value="{{ old('selling_price', '0.00') }}" required>
                                    @error('selling_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Stok Permulaan <span class="text-danger">*</span></label>
                                    <input type="number" name="stock_quantity" min="0" class="form-control @error('stock_quantity') is-invalid @enderror" value="{{ old('stock_quantity', 0) }}" required>
                                    @error('stock_quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Paras Pesanan Semula <span class="text-danger">*</span></label>
                                    <input type="number" name="reorder_level" min="0" class="form-control @error('reorder_level') is-invalid @enderror" value="{{ old('reorder_level', 10) }}" required>
                                    @error('reorder_level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Amaran stok rendah bila mencapai paras ini</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Paras Stok Maksimum</label>
                                    <input type="number" name="max_stock_level" min="0" class="form-control @error('max_stock_level') is-invalid @enderror" value="{{ old('max_stock_level', 1000) }}">
                                    @error('max_stock_level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Tarikh Luput</label>
                                    <input type="date" name="expiry_date" class="form-control @error('expiry_date') is-invalid @enderror" value="{{ old('expiry_date') }}">
                                    @error('expiry_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Keadaan Penyimpanan</label>
                            <select name="storage_conditions" class="form-select @error('storage_conditions') is-invalid @enderror">
                                <option value="">Pilih Keadaan</option>
                                @foreach($storageConditions as $key => $label)
                                <option value="{{ $key }}" {{ old('storage_conditions') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                            @error('storage_conditions')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Clinical Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="mdi mdi-medical-bag me-2"></i>Maklumat Klinikal</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Arahan Dos</label>
                            <textarea name="dosage_instructions" class="form-control @error('dosage_instructions') is-invalid @enderror" rows="3">{{ old('dosage_instructions') }}</textarea>
                            @error('dosage_instructions')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kontraindikasi</label>
                            <textarea name="contraindications" class="form-control @error('contraindications') is-invalid @enderror" rows="2">{{ old('contraindications') }}</textarea>
                            @error('contraindications')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kesan Sampingan</label>
                            <textarea name="side_effects" class="form-control @error('side_effects') is-invalid @enderror" rows="2">{{ old('side_effects') }}</textarea>
                            @error('side_effects')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nota Tambahan</label>
                            <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="2">{{ old('notes') }}</textarea>
                            @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Control Settings -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="mdi mdi-shield-alert me-2"></i>Kawalan</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check form-switch mb-3">
                            <input type="hidden" name="requires_prescription" value="0">
                            <input type="checkbox" name="requires_prescription" value="1" class="form-check-input" id="requiresPrescription" {{ old('requires_prescription') ? 'checked' : '' }}>
                            <label class="form-check-label" for="requiresPrescription">Memerlukan Preskripsi</label>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input type="hidden" name="is_controlled" value="0">
                            <input type="checkbox" name="is_controlled" value="1" class="form-check-input" id="isControlled" {{ old('is_controlled') ? 'checked' : '' }}>
                            <label class="form-check-label" for="isControlled">Ubat Terkawal (Akta Racun 1952)</label>
                        </div>

                        <div class="mb-3" id="poisonScheduleField" style="{{ old('is_controlled') ? '' : 'display: none;' }}">
                            <label class="form-label">Jadual Racun</label>
                            <select name="poison_schedule" class="form-select">
                                <option value="">Pilih Jadual</option>
                                @foreach($poisonSchedules as $key => $label)
                                <option value="{{ $key }}" {{ old('poison_schedule') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-check form-switch">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="isActive" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isActive">Status Aktif</label>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="mdi mdi-content-save me-2"></i>Simpan Ubat
                            </button>
                            <a href="{{ route('admin.pharmacy.medicines.index') }}" class="btn btn-outline-secondary">
                                Batal
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('isControlled').addEventListener('change', function() {
    document.getElementById('poisonScheduleField').style.display = this.checked ? 'block' : 'none';
});
</script>
@endpush
