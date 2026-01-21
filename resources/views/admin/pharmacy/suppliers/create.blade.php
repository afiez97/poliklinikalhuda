@extends('layouts.admin')

@section('title', 'Tambah Pembekal Baru - Farmasi')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between">
        <div>
            <h1>Tambah Pembekal Baru</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.pharmacy.medicines.index') }}">Farmasi</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.pharmacy.suppliers.index') }}">Pembekal</a></li>
                    <li class="breadcrumb-item active">Tambah Baru</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.pharmacy.suppliers.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <form action="{{ route('admin.pharmacy.suppliers.store') }}" method="POST">
        @csrf

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
                                       value="{{ old('name') }}" required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Nama Pegawai Hubungan</label>
                                <input type="text" name="contact_person" class="form-control @error('contact_person') is-invalid @enderror"
                                       value="{{ old('contact_person') }}">
                                @error('contact_person')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">No. Telefon</label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone') }}" placeholder="03-12345678">
                                @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}" placeholder="sales@syarikat.com">
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
                                          rows="2">{{ old('address') }}</textarea>
                                @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Bandar</label>
                                <input type="text" name="city" class="form-control @error('city') is-invalid @enderror"
                                       value="{{ old('city') }}">
                                @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Negeri</label>
                                <select name="state" class="form-select @error('state') is-invalid @enderror">
                                    <option value="">Pilih Negeri</option>
                                    <option value="Johor" {{ old('state') == 'Johor' ? 'selected' : '' }}>Johor</option>
                                    <option value="Kedah" {{ old('state') == 'Kedah' ? 'selected' : '' }}>Kedah</option>
                                    <option value="Kelantan" {{ old('state') == 'Kelantan' ? 'selected' : '' }}>Kelantan</option>
                                    <option value="Melaka" {{ old('state') == 'Melaka' ? 'selected' : '' }}>Melaka</option>
                                    <option value="Negeri Sembilan" {{ old('state') == 'Negeri Sembilan' ? 'selected' : '' }}>Negeri Sembilan</option>
                                    <option value="Pahang" {{ old('state') == 'Pahang' ? 'selected' : '' }}>Pahang</option>
                                    <option value="Perak" {{ old('state') == 'Perak' ? 'selected' : '' }}>Perak</option>
                                    <option value="Perlis" {{ old('state') == 'Perlis' ? 'selected' : '' }}>Perlis</option>
                                    <option value="Pulau Pinang" {{ old('state') == 'Pulau Pinang' ? 'selected' : '' }}>Pulau Pinang</option>
                                    <option value="Sabah" {{ old('state') == 'Sabah' ? 'selected' : '' }}>Sabah</option>
                                    <option value="Sarawak" {{ old('state') == 'Sarawak' ? 'selected' : '' }}>Sarawak</option>
                                    <option value="Selangor" {{ old('state') == 'Selangor' ? 'selected' : '' }}>Selangor</option>
                                    <option value="Terengganu" {{ old('state') == 'Terengganu' ? 'selected' : '' }}>Terengganu</option>
                                    <option value="WP Kuala Lumpur" {{ old('state') == 'WP Kuala Lumpur' ? 'selected' : '' }}>WP Kuala Lumpur</option>
                                    <option value="WP Labuan" {{ old('state') == 'WP Labuan' ? 'selected' : '' }}>WP Labuan</option>
                                    <option value="WP Putrajaya" {{ old('state') == 'WP Putrajaya' ? 'selected' : '' }}>WP Putrajaya</option>
                                </select>
                                @error('state')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Poskod</label>
                                <input type="text" name="postcode" class="form-control @error('postcode') is-invalid @enderror"
                                       value="{{ old('postcode') }}" maxlength="5">
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
                                       value="{{ old('registration_no') }}">
                                @error('registration_no')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">No. Cukai (SST)</label>
                                <input type="text" name="tax_id" class="form-control @error('tax_id') is-invalid @enderror"
                                       value="{{ old('tax_id') }}">
                                @error('tax_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nama Bank</label>
                                <input type="text" name="bank_name" class="form-control @error('bank_name') is-invalid @enderror"
                                       value="{{ old('bank_name') }}">
                                @error('bank_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">No. Akaun Bank</label>
                                <input type="text" name="bank_account" class="form-control @error('bank_account') is-invalid @enderror"
                                       value="{{ old('bank_account') }}">
                                @error('bank_account')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Terma Pembayaran (Hari)</label>
                                <input type="number" name="payment_terms" class="form-control @error('payment_terms') is-invalid @enderror"
                                       value="{{ old('payment_terms', 30) }}" min="0">
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
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                   id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
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
                                  rows="4" placeholder="Nota tambahan tentang pembekal...">{{ old('notes') }}</textarea>
                        @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="card card-default mt-4">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-lg me-1"></i> Simpan Pembekal
                            </button>
                            <a href="{{ route('admin.pharmacy.suppliers.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-lg me-1"></i> Batal
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
