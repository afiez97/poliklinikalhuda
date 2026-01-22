@extends('layouts.admin')

@section('title', 'Edit Panel - ' . $panel->panel_name)

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between">
        <div>
            <h1>Edit Panel</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.panel.panels.index') }}">Panel</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.panel.panels.show', $panel) }}">{{ $panel->panel_code }}</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.panel.panels.show', $panel) }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <form action="{{ route('admin.panel.panels.update', $panel) }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="row">
            <div class="col-lg-8">
                <!-- Maklumat Asas -->
                <div class="card card-default">
                    <div class="card-header">
                        <h2><i class="bi bi-building me-2"></i>Maklumat Asas</h2>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label">Nama Panel <span class="text-danger">*</span></label>
                                <input type="text" name="panel_name" class="form-control @error('panel_name') is-invalid @enderror"
                                       value="{{ old('panel_name', $panel->panel_name) }}" required>
                                @error('panel_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Jenis Panel <span class="text-danger">*</span></label>
                                <select name="panel_type" class="form-select @error('panel_type') is-invalid @enderror" required>
                                    @foreach(\App\Models\Panel::TYPES as $value => $label)
                                    <option value="{{ $value }}" {{ old('panel_type', $panel->panel_type) == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('panel_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nama Pegawai Hubungan</label>
                                <input type="text" name="contact_person" class="form-control @error('contact_person') is-invalid @enderror"
                                       value="{{ old('contact_person', $panel->contact_person) }}">
                                @error('contact_person')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">No. Telefon</label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone', $panel->phone) }}">
                                @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email', $panel->email) }}">
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
                                          rows="2">{{ old('address', $panel->address) }}</textarea>
                                @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Bandar</label>
                                <input type="text" name="city" class="form-control @error('city') is-invalid @enderror"
                                       value="{{ old('city', $panel->city) }}">
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
                                    <option value="{{ $state }}" {{ old('state', $panel->state) == $state ? 'selected' : '' }}>
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
                                       value="{{ old('postcode', $panel->postcode) }}" maxlength="5">
                                @error('postcode')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Nota -->
                <div class="card card-default mt-4">
                    <div class="card-header">
                        <h2><i class="bi bi-sticky me-2"></i>Nota</h2>
                    </div>
                    <div class="card-body">
                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror"
                                  rows="3">{{ old('notes', $panel->notes) }}</textarea>
                        @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="card card-default">
                    <div class="card-header">
                        <h2><i class="bi bi-info-circle me-2"></i>Maklumat</h2>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label text-muted">Kod Panel</label>
                            <input type="text" class="form-control" value="{{ $panel->panel_code }}" readonly disabled>
                        </div>
                    </div>
                </div>

                <div class="card card-default mt-4">
                    <div class="card-header">
                        <h2><i class="bi bi-clock me-2"></i>Terma & SLA</h2>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Terma Bayaran (Hari)</label>
                            <input type="number" name="payment_terms_days" class="form-control @error('payment_terms_days') is-invalid @enderror"
                                   value="{{ old('payment_terms_days', $panel->payment_terms_days) }}" min="0">
                            @error('payment_terms_days')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">SLA Kelulusan (Hari)</label>
                            <input type="number" name="sla_approval_days" class="form-control @error('sla_approval_days') is-invalid @enderror"
                                   value="{{ old('sla_approval_days', $panel->sla_approval_days) }}" min="1">
                            @error('sla_approval_days')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">SLA Bayaran (Hari)</label>
                            <input type="number" name="sla_payment_days" class="form-control @error('sla_payment_days') is-invalid @enderror"
                                   value="{{ old('sla_payment_days', $panel->sla_payment_days) }}" min="1">
                            @error('sla_payment_days')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card card-default mt-4">
                    <div class="card-header">
                        <h2><i class="bi bi-gear me-2"></i>Status</h2>
                    </div>
                    <div class="card-body">
                        <select name="status" class="form-select @error('status') is-invalid @enderror">
                            @foreach(\App\Models\Panel::STATUSES as $value => $label)
                            <option value="{{ $value }}" {{ old('status', $panel->status) == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                            @endforeach
                        </select>
                        @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="card card-default mt-4">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-lg me-1"></i> Kemaskini Panel
                            </button>
                            <a href="{{ route('admin.panel.panels.show', $panel) }}" class="btn btn-secondary">
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
