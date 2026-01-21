@extends('layouts.admin')
@section('title', 'Tetapan Billing')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper breadcrumb-contacts">
        <div>
            <h1>Tetapan Billing</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span><a href="{{ route('admin.billing.index') }}">Bil & Pembayaran</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>Tetapan</span>
            </p>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <form action="{{ route('admin.billing.settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Tax Settings -->
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="mdi mdi-percent"></i> Tetapan Cukai</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="sst_enabled" id="sst_enabled"
                                    value="1" {{ ($settings['sst_enabled'] ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="sst_enabled">Aktifkan SST</label>
                            </div>
                            <small class="text-muted">Cukai Jualan & Perkhidmatan (SST)</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kadar SST (%)</label>
                            <div class="input-group">
                                <input type="number" name="sst_rate" class="form-control"
                                    step="0.01" min="0" max="100"
                                    value="{{ $settings['sst_rate'] ?? 6 }}">
                                <span class="input-group-text">%</span>
                            </div>
                            <small class="text-muted">Kadar semasa: 6%</small>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="mdi mdi-decimal"></i> Tetapan Pembundaran</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="rounding_enabled" id="rounding_enabled"
                                    value="1" {{ ($settings['rounding_enabled'] ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="rounding_enabled">Aktifkan Pembundaran</label>
                            </div>
                            <small class="text-muted">Pembundaran mengikut garis panduan Bank Negara Malaysia</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ketepatan Pembundaran</label>
                            <select name="rounding_precision" class="form-select">
                                <option value="5" {{ ($settings['rounding_precision'] ?? 5) == 5 ? 'selected' : '' }}>
                                    5 sen (RM0.05)
                                </option>
                                <option value="10" {{ ($settings['rounding_precision'] ?? 5) == 10 ? 'selected' : '' }}>
                                    10 sen (RM0.10)
                                </option>
                            </select>
                            <small class="text-muted">Pembundaran ke sen terdekat</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Discount & Payment Settings -->
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="mdi mdi-tag-outline"></i> Tetapan Diskaun</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Had Diskaun Tanpa Kelulusan (%)</label>
                            <div class="input-group">
                                <input type="number" name="discount_approval_threshold" class="form-control"
                                    step="1" min="0" max="100"
                                    value="{{ $settings['discount_approval_threshold'] ?? 10 }}">
                                <span class="input-group-text">%</span>
                            </div>
                            <small class="text-muted">Diskaun melebihi had ini memerlukan kelulusan</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Diskaun Maksimum (%)</label>
                            <div class="input-group">
                                <input type="number" name="max_discount_percentage" class="form-control"
                                    step="1" min="0" max="100"
                                    value="{{ $settings['max_discount_percentage'] ?? 50 }}">
                                <span class="input-group-text">%</span>
                            </div>
                            <small class="text-muted">Had maksimum diskaun yang boleh diberikan</small>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="mdi mdi-cash"></i> Tetapan Pembayaran</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Terma Pembayaran (Hari)</label>
                            <div class="input-group">
                                <input type="number" name="payment_terms_days" class="form-control"
                                    min="0" max="365"
                                    value="{{ $settings['payment_terms_days'] ?? 30 }}">
                                <span class="input-group-text">hari</span>
                            </div>
                            <small class="text-muted">Tempoh bayaran dari tarikh invois</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Baki Pembukaan Kaunter</label>
                            <div class="input-group">
                                <span class="input-group-text">RM</span>
                                <input type="number" name="default_opening_balance" class="form-control"
                                    step="0.01" min="0"
                                    value="{{ $settings['default_opening_balance'] ?? 200 }}">
                            </div>
                            <small class="text-muted">Baki tunai standard untuk pembukaan kaunter</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <button type="submit" class="btn btn-primary">
                    <i class="mdi mdi-content-save"></i> Simpan Tetapan
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
