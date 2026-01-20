@extends('layouts.admin')
@section('title', 'Maklumat Peranan')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper">
        <div>
            <h1>Maklumat Peranan</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span><a href="{{ route('admin.roles.index') }}">Peranan</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>{{ $role->name }}</span>
            </p>
        </div>
        <div>
            @can('update', $role)
            <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-primary">
                <i class="mdi mdi-pencil"></i> Edit
            </a>
            @endcan
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <!-- Role Info Card -->
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-3">
                        @if($role->name === 'super-admin')
                        <i class="mdi mdi-shield-crown mdi-48px text-warning"></i>
                        @else
                        <i class="mdi mdi-shield-account mdi-48px text-info"></i>
                        @endif
                    </div>
                    <h4 class="mb-1">{{ $role->name }}</h4>
                    <p class="text-muted">{{ $role->guard_name }}</p>
                    <div class="row mt-4">
                        <div class="col-6">
                            <h3 class="mb-0">{{ $role->users->count() }}</h3>
                            <small class="text-muted">Pengguna</small>
                        </div>
                        <div class="col-6">
                            <h3 class="mb-0">{{ $role->permissions->count() }}</h3>
                            <small class="text-muted">Kebenaran</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Users with this role -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Pengguna dengan Peranan Ini</h5>
                </div>
                <div class="card-body p-0">
                    @if($role->users->isEmpty())
                    <div class="text-center py-4">
                        <p class="text-muted mb-0">Tiada pengguna</p>
                    </div>
                    @else
                    <ul class="list-group list-group-flush">
                        @foreach($role->users->take(10) as $user)
                        <li class="list-group-item d-flex align-items-center">
                            <div class="avatar-initial rounded-circle bg-primary me-2" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; font-size: 12px;">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <div>
                                <strong>{{ $user->name }}</strong>
                                <small class="d-block text-muted">{{ $user->email }}</small>
                            </div>
                        </li>
                        @endforeach
                        @if($role->users->count() > 10)
                        <li class="list-group-item text-center">
                            <a href="{{ route('admin.users.index', ['role' => $role->name]) }}">
                                Lihat semua {{ $role->users->count() }} pengguna
                            </a>
                        </li>
                        @endif
                    </ul>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <!-- Permissions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Kebenaran (Permissions)</h5>
                </div>
                <div class="card-body">
                    @if($permissionsByGroup->isEmpty())
                    <div class="text-center py-4">
                        <i class="mdi mdi-shield-off mdi-48px text-muted"></i>
                        <p class="text-muted mb-0">Tiada kebenaran diberikan</p>
                    </div>
                    @else
                    @foreach($permissionsByGroup as $group => $perms)
                    <div class="mb-4">
                        <h6 class="text-uppercase text-muted mb-3">
                            <i class="mdi mdi-folder-outline me-1"></i> {{ $group }}
                            <span class="badge bg-secondary ms-1">{{ $perms->count() }}</span>
                        </h6>
                        <div class="row">
                            @foreach($perms as $permission)
                            <div class="col-md-4 mb-2">
                                <span class="badge bg-light text-dark">
                                    <i class="mdi mdi-check text-success"></i>
                                    {{ str_replace($group . '.', '', $permission->name) }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @if(!$loop->last)
                    <hr>
                    @endif
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
