@extends('layouts.admin')

@section('title', 'Edit Pembekal - ' . $supplier->name)

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between">
        <div>
            <h1>Edit Pembekal</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.pharmacy.medicines.index') }}">Farmasi</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.pharmacy.suppliers.index') }}">Pembekal</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.pharmacy.suppliers.show', $supplier) }}">{{ $supplier->name }}</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.pharmacy.suppliers.show', $supplier) }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <form action="{{ route('admin.pharmacy.suppliers.update', $supplier) }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="row">
            <!-- Maklumat Asas -->
            <div class="col-lg-8">
                <div class="card card-default">
                    <div class="card-header">
                        <h2><i class="bi bi-building me-2"></i>Maklumat Asas</h2>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label">Nama Pembekal <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $supplier->name) }}" required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Nama Pegawai Hubungan</label>
                                <input type="text" name="contact_person" class="form-control @error('contact_person') is-invalid @enderror"
                                       value="{{ old('contact_person', $supplier->contact_person) }}">
                                @error('contact_person')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">No. Telefon</label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone', $supplier->phone) }}" placeholder="03-12345678">
                                @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email', $supplier->email) }}" placeholder="sales@syarikat.com">
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Alamat -->
                <div class="card card-default mt-4">
                    <div class="card-header">
                        <h2><i class="bi bi-geo-alt me-2"></i>Alamat</h2>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Alamat</label>
                                <textarea name="address" class="form-control @error('address') is-invalid @enderror"
                                          rows="2">{{ old('address', $supplier->address) }}</textarea>
                                @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Bandar</label>
                                <input type="text" name="city" class="form-control @error('city') is-invalid @enderror"
                                       value="{{ old('city', $supplier->city) }}">
                                @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Negeri</label>
                                <select name="state" class="form-select @error('state') is-invalid @enderror">
                                    <option value="">Pilih Negeri</option>
                                    @php
                                        $states = ['Johor', 'Kedah', 'Kelantan', 'Melaka', 'Negeri Sembilan', 'Pahang',
                                                   'Perak', 'Perlis', 'Pulau Pinang', 'Sabah', 'Sarawak', 'Selangor',
                                                   'Terengganu', 'WP Kuala Lumpur', 'WP Labuan', 'WP Putrajaya'];
                                    @endphp
                                    @foreach($states as $state)
                                    <option value="{{ $state }}" {{ old('state', $supplier->state) == $state ? 'selected' : '' }}>
                                        {{ $state }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('state')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Poskod</label>
                                <input type="text" name="postcode" class="form-control @error('postcode') is-invalid @enderror"
                                       value="{{ old('postcode', $supplier->postcode) }}" maxlength="5">
                                @error('postcode')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Maklumat Perniagaan -->
                <div class="card card-default mt-4">
                    <div class="card-header">
                        <h2><i class="bi bi-briefcase me-2"></i>Maklumat Perniagaan</h2>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">No. Pendaftaran (SSM)</label>
                                <input type="text" name="registration_no" class="form-control @error('registration_no') is-invalid @enderror"
                                       value="{{ old('registration_no', $supplier->registration_no) }}">
                                @error('registration_no')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">No. Cukai (SST)</label>
                                <input type="text" name="tax_id" class="form-control @error('tax_id') is-invalid @enderror"
                                       value="{{ old('tax_id', $supplier->tax_id) }}">
                                @error('tax_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nama Bank</label>
                                <input type="text" name="bank_name" class="form-control @error('bank_name') is-invalid @enderror"
                                       value="{{ old('bank_name', $supplier->bank_name) }}">
                                @error('bank_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">No. Akaun Bank</label>
                                <input type="text" name="bank_account" class="form-control @error('bank_account') is-invalid @enderror"
                                       value="{{ old('bank_account', $supplier->bank_account) }}">
                                @error('bank_account')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Terma Pembayaran (Hari)</label>
                                <input type="number" name="payment_terms" class="form-control @error('payment_terms') is-invalid @enderror"
                                       value="{{ old('payment_terms', $supplier->payment_terms) }}" min="0">
                                @error('payment_terms')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="card card-default">
                    <div class="card-header">
                        <h2><i class="bi bi-gear me-2"></i>Tetapan</h2>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label text-muted">Kod Pembekal</label>
                            <input type="text" class="form-control" value="{{ $supplier->code }}" readonly disabled>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                   id="is_active" {{ old('is_active', $supplier->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Pembekal Aktif
                            </label>
                        </div>
                    </div>
                </div>

                <div class="card card-default mt-4">
                    <div class="card-header">
                        <h2><i class="bi bi-sticky me-2"></i>Nota</h2>
                    </div>
                    <div class="card-body">
                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror"
                                  rows="4" placeholder="Nota tambahan tentang pembekal...">{{ old('notes', $supplier->notes) }}</textarea>
                        @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="card card-default mt-4">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-lg me-1"></i> Kemaskini Pembekal
                            </button>
                            <a href="{{ route('admin.pharmacy.suppliers.show', $supplier) }}" class="btn btn-secondary">
                                <i class="bi bi-x-lg me-1"></i> Batal
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card border-danger mt-4">
                    <div class="card-header bg-danger text-white">
                        <h2 class="text-white"><i class="bi bi-exclamation-triangle me-2"></i>Zon Bahaya</h2>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small">Tindakan ini tidak boleh dibatalkan.</p>
                        <button type="button" class="btn btn-outline-danger w-100" onclick="confirmDelete()">
                            <i class="bi bi-trash me-1"></i> Padam Pembekal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<form id="delete-form" action="{{ route('admin.pharmacy.suppliers.destroy', $supplier) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script>
function confirmDelete() {
    if (confirm('Adakah anda pasti mahu memadam pembekal "{{ $supplier->name }}"? Tindakan ini tidak boleh dibatalkan.')) {
        document.getElementById('delete-form').submit();
    }
}
</script>
@endpush
@endsection
