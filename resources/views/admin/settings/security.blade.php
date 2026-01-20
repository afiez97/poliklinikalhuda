@extends('layouts.admin')
@section('title', 'Tetapan Keselamatan')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper breadcrumb-contacts">
        <div>
            <h1>Tetapan Keselamatan</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span><a href="{{ route('admin.settings.index') }}">Tetapan</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>Keselamatan</span>
            </p>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <!-- Settings Navigation -->
        <div class="col-lg-3 mb-4">
            <div class="card">
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('admin.settings.general') }}" class="list-group-item list-group-item-action">
                            <i class="mdi mdi-cog me-2"></i> Tetapan Am
                        </a>
                        <a href="{{ route('admin.settings.clinic') }}" class="list-group-item list-group-item-action">
                            <i class="mdi mdi-hospital-building me-2"></i> Maklumat Klinik
                        </a>
                        <a href="{{ route('admin.settings.security') }}" class="list-group-item list-group-item-action active">
                            <i class="mdi mdi-shield-lock me-2"></i> Keselamatan
                        </a>
                        <a href="{{ route('admin.settings.notifications') }}" class="list-group-item list-group-item-action">
                            <i class="mdi mdi-bell me-2"></i> Notifikasi
                        </a>
                    </div>
                </div>
            </div>

            <!-- Security Info -->
            <div class="card mt-4">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0"><i class="mdi mdi-alert me-2"></i>Penting</h6>
                </div>
                <div class="card-body">
                    <small class="text-muted">
                        Perubahan pada tetapan keselamatan akan mempengaruhi semua pengguna sistem.
                        Pastikan anda memahami implikasi sebelum mengubah tetapan ini.
                    </small>
                </div>
            </div>
        </div>

        <!-- Settings Content -->
        <div class="col-lg-9">
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                @method('PATCH')

                @if($settings->count() > 0)
                    @php
                        $groupedSettings = $settings->groupBy(function($item) {
                            if (str_contains($item->key, 'password')) return 'password';
                            if (str_contains($item->key, 'mfa') || str_contains($item->key, '2fa')) return 'mfa';
                            if (str_contains($item->key, 'session')) return 'session';
                            if (str_contains($item->key, 'login') || str_contains($item->key, 'lockout')) return 'login';
                            return 'other';
                        });
                        $globalIndex = 0;
                    @endphp

                    <!-- Password Policy -->
                    @if($groupedSettings->has('password'))
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="mdi mdi-lock me-2"></i>Polisi Kata Laluan</h5>
                        </div>
                        <div class="card-body">
                            @foreach($groupedSettings->get('password') as $setting)
                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-5 col-form-label">{{ $setting->description ?? $setting->key }}</label>
                                <div class="col-sm-7">
                                    <input type="hidden" name="settings[{{ $globalIndex }}][key]" value="{{ $setting->key }}">
                                    @if($setting->type === 'boolean')
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="settings[{{ $globalIndex }}][value]" value="1" {{ $setting->value ? 'checked' : '' }}>
                                        </div>
                                    @elseif($setting->type === 'integer')
                                        <input type="number" class="form-control" name="settings[{{ $globalIndex }}][value]" value="{{ $setting->value }}" min="0">
                                    @else
                                        <input type="text" class="form-control" name="settings[{{ $globalIndex }}][value]" value="{{ $setting->value }}">
                                    @endif
                                </div>
                            </div>
                            @php $globalIndex++; @endphp
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Login & Lockout -->
                    @if($groupedSettings->has('login'))
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="mdi mdi-login me-2"></i>Log Masuk & Kunci Akaun</h5>
                        </div>
                        <div class="card-body">
                            @foreach($groupedSettings->get('login') as $setting)
                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-5 col-form-label">{{ $setting->description ?? $setting->key }}</label>
                                <div class="col-sm-7">
                                    <input type="hidden" name="settings[{{ $globalIndex }}][key]" value="{{ $setting->key }}">
                                    @if($setting->type === 'boolean')
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="settings[{{ $globalIndex }}][value]" value="1" {{ $setting->value ? 'checked' : '' }}>
                                        </div>
                                    @elseif($setting->type === 'integer')
                                        <input type="number" class="form-control" name="settings[{{ $globalIndex }}][value]" value="{{ $setting->value }}" min="0">
                                    @else
                                        <input type="text" class="form-control" name="settings[{{ $globalIndex }}][value]" value="{{ $setting->value }}">
                                    @endif
                                </div>
                            </div>
                            @php $globalIndex++; @endphp
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Session Management -->
                    @if($groupedSettings->has('session'))
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="mdi mdi-account-clock me-2"></i>Pengurusan Sesi</h5>
                        </div>
                        <div class="card-body">
                            @foreach($groupedSettings->get('session') as $setting)
                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-5 col-form-label">{{ $setting->description ?? $setting->key }}</label>
                                <div class="col-sm-7">
                                    <input type="hidden" name="settings[{{ $globalIndex }}][key]" value="{{ $setting->key }}">
                                    @if($setting->type === 'boolean')
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="settings[{{ $globalIndex }}][value]" value="1" {{ $setting->value ? 'checked' : '' }}>
                                        </div>
                                    @elseif($setting->type === 'integer')
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="settings[{{ $globalIndex }}][value]" value="{{ $setting->value }}" min="1">
                                            <span class="input-group-text">minit</span>
                                        </div>
                                    @else
                                        <input type="text" class="form-control" name="settings[{{ $globalIndex }}][value]" value="{{ $setting->value }}">
                                    @endif
                                </div>
                            </div>
                            @php $globalIndex++; @endphp
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- MFA Settings -->
                    @if($groupedSettings->has('mfa'))
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="mdi mdi-two-factor-authentication me-2"></i>Pengesahan Dua Faktor (MFA)</h5>
                        </div>
                        <div class="card-body">
                            @foreach($groupedSettings->get('mfa') as $setting)
                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-5 col-form-label">{{ $setting->description ?? $setting->key }}</label>
                                <div class="col-sm-7">
                                    <input type="hidden" name="settings[{{ $globalIndex }}][key]" value="{{ $setting->key }}">
                                    @if($setting->type === 'boolean')
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="settings[{{ $globalIndex }}][value]" value="1" {{ $setting->value ? 'checked' : '' }}>
                                        </div>
                                    @elseif($setting->type === 'integer')
                                        <input type="number" class="form-control" name="settings[{{ $globalIndex }}][value]" value="{{ $setting->value }}" min="0">
                                    @elseif($setting->type === 'array' || $setting->type === 'json')
                                        <textarea class="form-control" name="settings[{{ $globalIndex }}][value]" rows="2">{{ is_array($setting->value) ? json_encode($setting->value) : $setting->value }}</textarea>
                                        <small class="text-muted">Format JSON array: ["super-admin", "admin"]</small>
                                    @else
                                        <input type="text" class="form-control" name="settings[{{ $globalIndex }}][value]" value="{{ $setting->value }}">
                                    @endif
                                </div>
                            </div>
                            @php $globalIndex++; @endphp
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Other Settings -->
                    @if($groupedSettings->has('other'))
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="mdi mdi-cog me-2"></i>Tetapan Lain</h5>
                        </div>
                        <div class="card-body">
                            @foreach($groupedSettings->get('other') as $setting)
                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-5 col-form-label">{{ $setting->description ?? $setting->key }}</label>
                                <div class="col-sm-7">
                                    <input type="hidden" name="settings[{{ $globalIndex }}][key]" value="{{ $setting->key }}">
                                    @if($setting->type === 'boolean')
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="settings[{{ $globalIndex }}][value]" value="1" {{ $setting->value ? 'checked' : '' }}>
                                        </div>
                                    @elseif($setting->type === 'integer')
                                        <input type="number" class="form-control" name="settings[{{ $globalIndex }}][value]" value="{{ $setting->value }}" min="0">
                                    @else
                                        <input type="text" class="form-control" name="settings[{{ $globalIndex }}][value]" value="{{ $setting->value }}">
                                    @endif
                                </div>
                            </div>
                            @php $globalIndex++; @endphp
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-check me-2"></i>Simpan Tetapan Keselamatan
                        </button>
                    </div>
                @else
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="mdi mdi-shield-lock-outline mdi-48px text-muted"></i>
                            <p class="text-muted mb-3">Tiada tetapan keselamatan dikonfigurasi.</p>
                            <p class="text-muted small">
                                Sila muat tetapan lalai dari halaman <a href="{{ route('admin.settings.index') }}">Tetapan</a> terlebih dahulu.
                            </p>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>
@endsection
