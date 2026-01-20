@extends('layouts.admin')
@section('title', 'Tetapan Sistem')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper breadcrumb-contacts">
        <div>
            <h1>Tetapan Sistem</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>Tetapan</span>
            </p>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
                        <a href="{{ route('admin.settings.notifications') }}" class="list-group-item list-group-item-action">
                            <i class="mdi mdi-bell me-2"></i> Notifikasi
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Settings Content -->
        <div class="col-lg-9">
            @forelse($settings as $group => $groupSettings)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0 text-uppercase">{{ $group }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf
                        @method('PATCH')

                        @foreach($groupSettings as $setting)
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-4 col-form-label">
                                {{ $setting->description ?? $setting->key }}
                            </label>
                            <div class="col-sm-8">
                                <input type="hidden" name="settings[{{ $loop->index }}][key]" value="{{ $setting->key }}">

                                @switch($setting->type)
                                    @case('boolean')
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="settings[{{ $loop->index }}][value]" value="1" {{ $setting->value ? 'checked' : '' }}>
                                        </div>
                                        @break
                                    @case('integer')
                                        <input type="number" class="form-control" name="settings[{{ $loop->index }}][value]" value="{{ $setting->value }}">
                                        @break
                                    @case('json')
                                        <textarea class="form-control" name="settings[{{ $loop->index }}][value]" rows="3">{{ is_array($setting->value) ? json_encode($setting->value, JSON_PRETTY_PRINT) : $setting->value }}</textarea>
                                        @break
                                    @default
                                        <input type="text" class="form-control" name="settings[{{ $loop->index }}][value]" value="{{ $setting->value }}">
                                @endswitch
                            </div>
                        </div>
                        @endforeach

                        @can('update', App\Models\SystemSetting::class)
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-check"></i> Simpan Tetapan {{ ucfirst($group) }}
                            </button>
                        </div>
                        @endcan
                    </form>
                </div>
            </div>
            @empty
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="mdi mdi-cog-off mdi-48px text-muted"></i>
                    <p class="text-muted mb-3">Tiada tetapan dikonfigurasi</p>
                    @can('create', App\Models\SystemSetting::class)
                    <a href="{{ route('admin.settings.initialize') }}" class="btn btn-primary">
                        <i class="mdi mdi-plus"></i> Mula Tetapan Lalai
                    </a>
                    @endcan
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
