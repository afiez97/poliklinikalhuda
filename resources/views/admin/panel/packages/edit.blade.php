@extends('layouts.admin')

@section('title', 'Edit Pakej - ' . $package->package_name)

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between">
        <div>
            <h1>Edit Pakej: {{ $package->package_name }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.panel.panels.index') }}">Panel</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.panel.panels.show', $panel) }}">{{ $panel->panel_name }}</a></li>
                    <li class="breadcrumb-item active">Edit Pakej</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card card-default">
                <div class="card-header">
                    <h2>Maklumat Pakej</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.panel.packages.update', [$panel, $package]) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kod Pakej <span class="text-danger">*</span></label>
                                <input type="text" name="package_code" class="form-control @error('package_code') is-invalid @enderror"
                                       value="{{ old('package_code', $package->package_code) }}" required>
                                @error('package_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Pakej <span class="text-danger">*</span></label>
                                <input type="text" name="package_name" class="form-control @error('package_name') is-invalid @enderror"
                                       value="{{ old('package_name', $package->package_name) }}" required>
                                @error('package_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Penerangan</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                      rows="2">{{ old('description', $package->description) }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">
                        <h5 class="mb-3">Had Manfaat (RM)</h5>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Had Tahunan</label>
                                <input type="number" name="annual_limit" class="form-control @error('annual_limit') is-invalid @enderror"
                                       value="{{ old('annual_limit', $package->annual_limit) }}" step="0.01" min="0">
                                @error('annual_limit')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Had Per Lawatan</label>
                                <input type="number" name="per_visit_limit" class="form-control @error('per_visit_limit') is-invalid @enderror"
                                       value="{{ old('per_visit_limit', $package->per_visit_limit) }}" step="0.01" min="0">
                                @error('per_visit_limit')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Had Konsultasi</label>
                                <input type="number" name="consultation_limit" class="form-control @error('consultation_limit') is-invalid @enderror"
                                       value="{{ old('consultation_limit', $package->consultation_limit) }}" step="0.01" min="0">
                                @error('consultation_limit')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Had Ubatan</label>
                                <input type="number" name="medication_limit" class="form-control @error('medication_limit') is-invalid @enderror"
                                       value="{{ old('medication_limit', $package->medication_limit) }}" step="0.01" min="0">
                                @error('medication_limit')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Had Prosedur</label>
                                <input type="number" name="procedure_limit" class="form-control @error('procedure_limit') is-invalid @enderror"
                                       value="{{ old('procedure_limit', $package->procedure_limit) }}" step="0.01" min="0">
                                @error('procedure_limit')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Had Makmal</label>
                                <input type="number" name="lab_limit" class="form-control @error('lab_limit') is-invalid @enderror"
                                       value="{{ old('lab_limit', $package->lab_limit) }}" step="0.01" min="0">
                                @error('lab_limit')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">
                        <h5 class="mb-3">Tetapan Co-Payment</h5>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Co-Payment (%)</label>
                                <input type="number" name="co_payment_percentage" class="form-control @error('co_payment_percentage') is-invalid @enderror"
                                       value="{{ old('co_payment_percentage', $package->co_payment_percentage) }}" step="0.01" min="0" max="100">
                                <small class="text-muted">0% = tiada co-payment</small>
                                @error('co_payment_percentage')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Deductible (RM)</label>
                                <input type="number" name="deductible_amount" class="form-control @error('deductible_amount') is-invalid @enderror"
                                       value="{{ old('deductible_amount', $package->deductible_amount) }}" step="0.01" min="0">
                                @error('deductible_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Jenis Deductible</label>
                                <select name="deductible_type" class="form-select @error('deductible_type') is-invalid @enderror">
                                    <option value="per_visit" {{ old('deductible_type', $package->deductible_type) == 'per_visit' ? 'selected' : '' }}>Per Lawatan</option>
                                    <option value="per_year" {{ old('deductible_type', $package->deductible_type) == 'per_year' ? 'selected' : '' }}>Per Tahun</option>
                                </select>
                                @error('deductible_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_default" value="1" id="isDefault"
                                           {{ old('is_default', $package->is_default) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="isDefault">
                                        Jadikan pakej default
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive"
                                           {{ old('is_active', $package->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="isActive">
                                        Aktif
                                    </label>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.panel.panels.show', $panel) }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i> Kemaskini
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="card card-default">
                <div class="card-header">
                    <h2><i class="bi bi-info-circle me-2"></i>Maklumat Pakej</h2>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td class="text-muted">Dicipta:</td>
                            <td>{{ $package->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Dikemaskini:</td>
                            <td>{{ $package->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status:</td>
                            <td>
                                <span class="badge bg-{{ $package->is_active ? 'success' : 'danger' }}">
                                    {{ $package->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </td>
                        </tr>
                        @if($package->is_default)
                        <tr>
                            <td colspan="2">
                                <span class="badge bg-primary">Pakej Default</span>
                            </td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            <div class="card card-default mt-4">
                <div class="card-header">
                    <h2><i class="bi bi-question-circle me-2"></i>Panduan</h2>
                </div>
                <div class="card-body">
                    <h6>Had Manfaat</h6>
                    <p class="small text-muted">
                        Tetapkan had maksimum untuk setiap kategori perkhidmatan. Masukkan 0 untuk tiada had.
                    </p>

                    <h6>Co-Payment</h6>
                    <p class="small text-muted">
                        Peratusan yang perlu dibayar oleh pesakit. Contoh: 20% bermaksud pesakit bayar 20%, panel bayar 80%.
                    </p>

                    <h6>Deductible</h6>
                    <p class="small text-muted mb-0">
                        Amaun yang perlu ditanggung pesakit sebelum manfaat bermula.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
