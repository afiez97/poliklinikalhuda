@extends('layouts.admin')
@section('title', 'Pengurusan Peranan')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper breadcrumb-contacts">
        <div>
            <h1>Pengurusan Peranan</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>Peranan</span>
            </p>
        </div>
        <div>
            @can('create', Spatie\Permission\Models\Role::class)
            <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                <i class="mdi mdi-plus"></i> Tambah Peranan
            </a>
            @endcan
            <a href="{{ route('admin.roles.matrix') }}" class="btn btn-outline-secondary">
                <i class="mdi mdi-table"></i> Matriks Kebenaran
            </a>
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
        @forelse($roles as $role)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        @if($role->name === 'super-admin')
                        <i class="mdi mdi-shield-crown text-warning"></i>
                        @else
                        <i class="mdi mdi-shield-account text-info"></i>
                        @endif
                        {{ $role->name }}
                    </h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="mdi mdi-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @can('view', $role)
                            <li><a class="dropdown-item" href="{{ route('admin.roles.show', $role) }}"><i class="mdi mdi-eye me-2"></i>Lihat</a></li>
                            @endcan
                            @can('update', $role)
                            <li><a class="dropdown-item" href="{{ route('admin.roles.edit', $role) }}"><i class="mdi mdi-pencil me-2"></i>Edit</a></li>
                            @endcan
                            @can('delete', $role)
                            @if($role->name !== 'super-admin')
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Adakah anda pasti ingin memadam peranan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="dropdown-item text-danger"><i class="mdi mdi-delete me-2"></i>Padam</button>
                                </form>
                            </li>
                            @endif
                            @endcan
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="text-center">
                            <h4 class="mb-0">{{ $role->users_count }}</h4>
                            <small class="text-muted">Pengguna</small>
                        </div>
                        <div class="text-center">
                            <h4 class="mb-0">{{ $role->permissions_count }}</h4>
                            <small class="text-muted">Kebenaran</small>
                        </div>
                    </div>

                    @if($role->name === 'super-admin')
                    <div class="alert alert-warning mb-0 py-2">
                        <small><i class="mdi mdi-information"></i> Peranan ini mempunyai akses penuh kepada semua fungsi sistem.</small>
                    </div>
                    @endif
                </div>
                <div class="card-footer bg-transparent">
                    <a href="{{ route('admin.roles.show', $role) }}" class="btn btn-outline-primary btn-sm w-100">
                        <i class="mdi mdi-eye"></i> Lihat Butiran
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="mdi mdi-shield-off mdi-48px text-muted"></i>
                    <p class="text-muted mb-0">Tiada peranan dijumpai</p>
                </div>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection
