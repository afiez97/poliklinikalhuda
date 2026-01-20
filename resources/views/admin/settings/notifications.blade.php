@extends('layouts.admin')
@section('title', 'Tetapan Notifikasi')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper breadcrumb-contacts">
        <div>
            <h1>Tetapan Notifikasi</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span><a href="{{ route('admin.settings.index') }}">Tetapan</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>Notifikasi</span>
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
                        <a href="{{ route('admin.settings.security') }}" class="list-group-item list-group-item-action">
                            <i class="mdi mdi-shield-lock me-2"></i> Keselamatan
                        </a>
                        <a href="{{ route('admin.settings.notifications') }}" class="list-group-item list-group-item-action active">
                            <i class="mdi mdi-bell me-2"></i> Notifikasi
                        </a>
                    </div>
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
                            if (str_contains($item->key, 'email')) return 'email';
                            if (str_contains($item->key, 'sms')) return 'sms';
                            if (str_contains($item->key, 'whatsapp')) return 'whatsapp';
                            return 'other';
                        });
                        $globalIndex = 0;
                    @endphp

                    <!-- Email Notifications -->
                    @if($groupedSettings->has('email'))
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="mdi mdi-email me-2"></i>Notifikasi E-mel</h5>
                        </div>
                        <div class="card-body">
                            @foreach($groupedSettings->get('email') as $setting)
                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-5 col-form-label">{{ $setting->description ?? $setting->key }}</label>
                                <div class="col-sm-7">
                                    <input type="hidden" name="settings[{{ $globalIndex }}][key]" value="{{ $setting->key }}">
                                    @if($setting->type === 'boolean')
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="settings[{{ $globalIndex }}][value]" value="1" {{ $setting->value ? 'checked' : '' }}>
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

                    <!-- SMS Notifications -->
                    @if($groupedSettings->has('sms'))
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="mdi mdi-message-text me-2"></i>Notifikasi SMS</h5>
                        </div>
                        <div class="card-body">
                            @foreach($groupedSettings->get('sms') as $setting)
                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-5 col-form-label">{{ $setting->description ?? $setting->key }}</label>
                                <div class="col-sm-7">
                                    <input type="hidden" name="settings[{{ $globalIndex }}][key]" value="{{ $setting->key }}">
                                    @if($setting->type === 'boolean')
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="settings[{{ $globalIndex }}][value]" value="1" {{ $setting->value ? 'checked' : '' }}>
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

                    <!-- WhatsApp Notifications -->
                    @if($groupedSettings->has('whatsapp'))
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="mdi mdi-whatsapp me-2"></i>Notifikasi WhatsApp</h5>
                        </div>
                        <div class="card-body">
                            @foreach($groupedSettings->get('whatsapp') as $setting)
                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-5 col-form-label">{{ $setting->description ?? $setting->key }}</label>
                                <div class="col-sm-7">
                                    <input type="hidden" name="settings[{{ $globalIndex }}][key]" value="{{ $setting->key }}">
                                    @if($setting->type === 'boolean')
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="settings[{{ $globalIndex }}][value]" value="1" {{ $setting->value ? 'checked' : '' }}>
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

                    <!-- Other Notifications -->
                    @if($groupedSettings->has('other'))
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="mdi mdi-bell-ring me-2"></i>Notifikasi Lain</h5>
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
                            <i class="mdi mdi-check me-2"></i>Simpan Tetapan Notifikasi
                        </button>
                    </div>
                @else
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="mdi mdi-bell-off-outline mdi-48px text-muted"></i>
                            <p class="text-muted mb-3">Tiada tetapan notifikasi dikonfigurasi.</p>
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
