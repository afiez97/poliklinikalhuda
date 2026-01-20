@extends('layouts.admin')
@section('title', 'Maklumat Klinik')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper breadcrumb-contacts">
        <div>
            <h1>Maklumat Klinik</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span><a href="{{ route('admin.settings.index') }}">Tetapan</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>Maklumat Klinik</span>
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
                        <a href="{{ route('admin.settings.clinic') }}" class="list-group-item list-group-item-action active">
                            <i class="mdi mdi-hospital-building me-2"></i> Maklumat Klinik
                        </a>
                        <a href="{{ route('admin.settings.security') }}" class="list-group-item list-group-item-action">
                            <i class="mdi mdi-shield-lock me-2"></i> Keselamatan
                        </a>
                        <a href="{{ route('admin.settings.notifications') }}" class="list-group-item list-group-item-action">
                            <i class="mdi mdi-bell me-2"></i> Notifikasi
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Settings Content -->
        <div class="col-lg-9">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="mdi mdi-hospital-building me-2"></i>Maklumat Klinik</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        @if($settings->count() > 0)
                            @php $index = 0; @endphp
                            @foreach($settings as $key => $setting)
                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-4 col-form-label">
                                    {{ $setting->description ?? $key }}
                                </label>
                                <div class="col-sm-8">
                                    <input type="hidden" name="settings[{{ $index }}][key]" value="{{ $setting->key }}">

                                    @if(str_contains($key, 'logo') || str_contains($key, 'image'))
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="settings[{{ $index }}][value]" value="{{ $setting->value }}" placeholder="Path ke fail logo">
                                        </div>
                                        @if($setting->value)
                                        <small class="text-muted">Semasa: {{ $setting->value }}</small>
                                        @endif
                                    @elseif(str_contains($key, 'address') || str_contains($key, 'alamat'))
                                        <textarea class="form-control" name="settings[{{ $index }}][value]" rows="3">{{ $setting->value }}</textarea>
                                    @elseif(str_contains($key, 'hours') || str_contains($key, 'waktu'))
                                        <textarea class="form-control" name="settings[{{ $index }}][value]" rows="4" placeholder="Format JSON: {&quot;isnin&quot;: &quot;8:00 - 17:00&quot;, ...}">{{ is_array($setting->value) ? json_encode($setting->value, JSON_PRETTY_PRINT) : $setting->value }}</textarea>
                                    @elseif($setting->type === 'boolean')
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="settings[{{ $index }}][value]" value="1" {{ $setting->value ? 'checked' : '' }}>
                                        </div>
                                    @else
                                        <input type="text" class="form-control" name="settings[{{ $index }}][value]" value="{{ $setting->value }}">
                                    @endif
                                </div>
                            </div>
                            @php $index++; @endphp
                            @endforeach

                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="mdi mdi-check me-2"></i>Simpan Maklumat
                                </button>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="mdi mdi-hospital-building mdi-48px text-muted"></i>
                                <p class="text-muted mb-3">Tiada maklumat klinik dikonfigurasi.</p>
                                <p class="text-muted small">
                                    Sila muat tetapan lalai dari halaman <a href="{{ route('admin.settings.index') }}">Tetapan</a> terlebih dahulu.
                                </p>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Clinic Info Preview -->
            @if($settings->count() > 0)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="mdi mdi-eye me-2"></i>Pratonton</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="fw-bold">{{ $settings->get('clinic_name')?->value ?? 'Nama Klinik' }}</h5>
                            <p class="mb-1">
                                <i class="mdi mdi-map-marker me-2"></i>
                                {!! nl2br(e($settings->get('clinic_address')?->value ?? 'Alamat tidak ditetapkan')) !!}
                            </p>
                            <p class="mb-1">
                                <i class="mdi mdi-phone me-2"></i>
                                {{ $settings->get('clinic_phone')?->value ?? '-' }}
                            </p>
                            <p class="mb-1">
                                <i class="mdi mdi-email me-2"></i>
                                {{ $settings->get('clinic_email')?->value ?? '-' }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6>Waktu Operasi</h6>
                            @php
                                $hours = $settings->get('clinic_operating_hours')?->value;
                                if (is_string($hours)) {
                                    $hours = json_decode($hours, true);
                                }
                            @endphp
                            @if($hours && is_array($hours))
                            <table class="table table-sm">
                                @foreach($hours as $day => $time)
                                <tr>
                                    <td>{{ ucfirst($day) }}</td>
                                    <td>{{ $time }}</td>
                                </tr>
                                @endforeach
                            </table>
                            @else
                            <p class="text-muted">Waktu operasi tidak ditetapkan</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
