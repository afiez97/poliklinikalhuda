@extends('layouts.admin')

@section('title', 'Kontrak Baru - ' . $panel->panel_name)

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between">
        <div>
            <h1>Kontrak Baru</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.panel.panels.index') }}">Panel</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.panel.panels.show', $panel) }}">{{ $panel->panel_name }}</a></li>
                    <li class="breadcrumb-item active">Kontrak Baru</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card card-default">
                <div class="card-header">
                    <h2>Maklumat Kontrak</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.panel.contracts.store', $panel) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">No. Kontrak</label>
                                <input type="text" name="contract_number" class="form-control @error('contract_number') is-invalid @enderror"
                                       value="{{ old('contract_number', 'CON-' . $panel->panel_code . '-' . date('Y')) }}"
                                       placeholder="cth: CON-PAN-0001-2026">
                                <small class="text-muted">Biarkan kosong untuk jana automatik.</small>
                                @error('contract_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Had Tahunan (RM)</label>
                                <input type="number" name="annual_cap" class="form-control @error('annual_cap') is-invalid @enderror"
                                       value="{{ old('annual_cap', 500000) }}" step="0.01" min="0">
                                <small class="text-muted">Jumlah maksimum tuntutan setahun.</small>
                                @error('annual_cap')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Tarikh Mula <span class="text-danger">*</span></label>
                                <input type="date" name="effective_date" class="form-control @error('effective_date') is-invalid @enderror"
                                       value="{{ old('effective_date', date('Y-01-01')) }}" required>
                                @error('effective_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Tarikh Tamat <span class="text-danger">*</span></label>
                                <input type="date" name="expiry_date" class="form-control @error('expiry_date') is-invalid @enderror"
                                       value="{{ old('expiry_date', date('Y-12-31')) }}" required>
                                @error('expiry_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Tarikh Pembaharuan</label>
                                <input type="date" name="renewal_date" class="form-control @error('renewal_date') is-invalid @enderror"
                                       value="{{ old('renewal_date', date('Y-11-30')) }}">
                                <small class="text-muted">Tarikh untuk mula proses pembaharuan.</small>
                                @error('renewal_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Terma & Syarat</label>
                            <textarea name="terms_conditions" class="form-control @error('terms_conditions') is-invalid @enderror"
                                      rows="5" placeholder="Masukkan terma dan syarat kontrak...">{{ old('terms_conditions') }}</textarea>
                            @error('terms_conditions')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nota</label>
                            <textarea name="notes" class="form-control @error('notes') is-invalid @enderror"
                                      rows="2" placeholder="Nota tambahan...">{{ old('notes') }}</textarea>
                            @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Dokumen Kontrak</label>
                            <input type="file" name="document" class="form-control @error('document') is-invalid @enderror"
                                   accept=".pdf,.doc,.docx">
                            <small class="text-muted">Format: PDF, DOC, DOCX. Maks: 10MB</small>
                            @error('document')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draf</option>
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Aktif</option>
                            </select>
                            @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.panel.panels.show', $panel) }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i> Simpan
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
                    <h2><i class="bi bi-building me-2"></i>Maklumat Panel</h2>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td class="text-muted">Nama:</td>
                            <td class="fw-semibold">{{ $panel->panel_name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Kod:</td>
                            <td><code>{{ $panel->panel_code }}</code></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Jenis:</td>
                            <td>{{ $panel->type_name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status:</td>
                            <td>
                                <span class="badge bg-{{ $panel->status == 'active' ? 'success' : 'secondary' }}">
                                    {{ $panel->status_name }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            @if($panel->activeContract)
            <div class="card card-default mt-4 border-warning">
                <div class="card-header bg-warning text-dark">
                    <h2><i class="bi bi-exclamation-triangle me-2"></i>Kontrak Semasa</h2>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        Panel ini sudah mempunyai kontrak aktif:
                    </p>
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td class="text-muted">No:</td>
                            <td>{{ $panel->activeContract->contract_number }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tamat:</td>
                            <td>{{ $panel->activeContract->expiry_date->format('d/m/Y') }}</td>
                        </tr>
                    </table>
                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        Kontrak baru akan menggantikan kontrak semasa sekiranya diaktifkan.
                    </div>
                </div>
            </div>
            @endif

            <div class="card card-default mt-4">
                <div class="card-header">
                    <h2><i class="bi bi-question-circle me-2"></i>Panduan</h2>
                </div>
                <div class="card-body">
                    <h6>Had Tahunan</h6>
                    <p class="small text-muted">
                        Jumlah maksimum tuntutan yang boleh dibuat dalam setahun. Setelah mencapai had ini, tuntutan baru tidak akan diterima.
                    </p>

                    <h6>Tarikh Pembaharuan</h6>
                    <p class="small text-muted">
                        Sistem akan menghantar peringatan untuk membaharui kontrak pada tarikh ini.
                    </p>

                    <h6>Status</h6>
                    <p class="small text-muted mb-0">
                        <strong>Draf:</strong> Kontrak belum berkuatkuasa<br>
                        <strong>Aktif:</strong> Kontrak sedang berkuatkuasa
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
