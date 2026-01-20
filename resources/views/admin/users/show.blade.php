@extends('layouts.admin')
@section('title', 'Maklumat Pengguna')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper">
        <div>
            <h1>Maklumat Pengguna</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span><a href="{{ route('admin.users.index') }}">Pengguna</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>{{ $user->name }}</span>
            </p>
        </div>
        <div>
            @can('update', $user)
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                <i class="mdi mdi-pencil"></i> Edit
            </a>
            @endcan
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <!-- Profile Card -->
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-3">
                        @if($user->avatar)
                        <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="rounded-circle" width="120" height="120">
                        @else
                        <div class="avatar-initial rounded-circle bg-primary mx-auto" style="width: 120px; height: 120px; display: flex; align-items: center; justify-content: center; font-size: 48px; color: white;">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        @endif
                    </div>
                    <h4 class="mb-1">{{ $user->name }}</h4>
                    <p class="text-muted mb-2">{{ '@' . $user->username }}</p>
                    <div class="mb-3">
                        @switch($user->status)
                            @case('active')
                                <span class="badge bg-success">Aktif</span>
                                @break
                            @case('inactive')
                                <span class="badge bg-secondary">Tidak Aktif</span>
                                @break
                            @case('suspended')
                                <span class="badge bg-danger">Digantung</span>
                                @break
                            @case('pending')
                                <span class="badge bg-warning">Menunggu</span>
                                @break
                        @endswitch
                        @if($user->isLocked())
                        <span class="badge bg-danger">Dikunci</span>
                        @endif
                    </div>
                    <div>
                        @foreach($user->roles as $role)
                        <span class="badge bg-info">{{ $role->name }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Maklumat Hubungan</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <i class="mdi mdi-email-outline me-3 text-muted"></i>
                        <div>
                            <small class="text-muted d-block">Emel</small>
                            <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                        </div>
                    </div>
                    @if($user->phone)
                    <div class="d-flex align-items-center">
                        <i class="mdi mdi-phone-outline me-3 text-muted"></i>
                        <div>
                            <small class="text-muted d-block">Telefon</small>
                            <a href="tel:{{ $user->phone }}">{{ $user->phone }}</a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <!-- Account Details -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Butiran Akaun</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="text-muted" width="150">ID Pengguna:</td>
                                    <td>{{ $user->id }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Dicipta:</td>
                                    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Dikemaskini:</td>
                                    <td>{{ $user->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Dicipta oleh:</td>
                                    <td>{{ $user->creator?->name ?? 'Sistem' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="text-muted" width="180">Log masuk terakhir:</td>
                                    <td>{{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">IP terakhir:</td>
                                    <td>{{ $user->last_login_ip ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Kata laluan ditukar:</td>
                                    <td>{{ $user->password_changed_at ? $user->password_changed_at->format('d/m/Y') : '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Percubaan gagal:</td>
                                    <td>{{ $user->failed_login_attempts }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Settings -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Tetapan Keselamatan</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                @if($user->mfa_enabled)
                                <span class="badge bg-success me-2"><i class="mdi mdi-shield-check"></i></span>
                                <div>
                                    <strong>MFA Aktif</strong>
                                    <small class="d-block text-muted">Pengesahan dua faktor diaktifkan</small>
                                </div>
                                @elseif($user->mfa_required)
                                <span class="badge bg-warning me-2"><i class="mdi mdi-shield-alert"></i></span>
                                <div>
                                    <strong>MFA Diperlukan</strong>
                                    <small class="d-block text-muted">Belum dikonfigurasi</small>
                                </div>
                                @else
                                <span class="badge bg-secondary me-2"><i class="mdi mdi-shield-off"></i></span>
                                <div>
                                    <strong>MFA Tidak Aktif</strong>
                                    <small class="d-block text-muted">Tidak wajib untuk akaun ini</small>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                @if($user->isPasswordExpired())
                                <span class="badge bg-danger me-2"><i class="mdi mdi-key-alert"></i></span>
                                <div>
                                    <strong>Kata Laluan Tamat Tempoh</strong>
                                    <small class="d-block text-muted">Perlu ditukar semasa log masuk</small>
                                </div>
                                @else
                                <span class="badge bg-success me-2"><i class="mdi mdi-key-check"></i></span>
                                <div>
                                    <strong>Kata Laluan Sah</strong>
                                    <small class="d-block text-muted">{{ $user->password_changed_at ? 'Ditukar ' . $user->password_changed_at->diffForHumans() : '-' }}</small>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Permissions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Kebenaran (Permissions)</h5>
                </div>
                <div class="card-body">
                    @php
                        $permissions = $user->getAllPermissions()->groupBy(function($permission) {
                            return explode('.', $permission->name)[0];
                        });
                    @endphp

                    @if($permissions->isEmpty())
                    <p class="text-muted mb-0">Tiada kebenaran diberikan</p>
                    @else
                    <div class="row">
                        @foreach($permissions as $group => $perms)
                        <div class="col-md-4 mb-3">
                            <h6 class="text-uppercase text-muted mb-2">{{ $group }}</h6>
                            @foreach($perms as $permission)
                            <span class="badge bg-light text-dark me-1 mb-1">{{ $permission->name }}</span>
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
