@extends('layouts.admin')
@section('title', 'Pengurusan Pengguna')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper breadcrumb-contacts">
        <div>
            <h1>Pengurusan Pengguna</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>Pengguna</span>
            </p>
        </div>
        <div>
            @can('create', App\Models\User::class)
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="mdi mdi-plus"></i> Tambah Pengguna
            </a>
            @endcan
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

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card card-mini">
                <div class="card-body">
                    <h2 class="mb-1">{{ $statistics['total'] }}</h2>
                    <p class="mb-0">Jumlah Pengguna</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card card-mini">
                <div class="card-body">
                    <h2 class="mb-1 text-success">{{ $statistics['active'] }}</h2>
                    <p class="mb-0">Pengguna Aktif</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card card-mini">
                <div class="card-body">
                    <h2 class="mb-1 text-warning">{{ $statistics['locked'] }}</h2>
                    <p class="mb-0">Akaun Dikunci</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card card-mini">
                <div class="card-body">
                    <h2 class="mb-1 text-info">{{ $statistics['recently_active'] }}</h2>
                    <p class="mb-0">Aktif (30 min)</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.users.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama, emel, atau username..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        @foreach(['active' => 'Aktif', 'inactive' => 'Tidak Aktif', 'suspended' => 'Digantung', 'pending' => 'Menunggu'] as $value => $label)
                        <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="role" class="form-select">
                        <option value="">Semua Peranan</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="mdi mdi-magnify"></i> Cari
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Pengguna</th>
                            <th>Username</th>
                            <th>Peranan</th>
                            <th>Status</th>
                            <th>MFA</th>
                            <th>Log Masuk Terakhir</th>
                            <th class="text-end">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-2">
                                        @if($user->avatar)
                                        <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="rounded-circle" width="40" height="40">
                                        @else
                                        <div class="avatar-initial rounded-circle bg-primary" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                        @endif
                                    </div>
                                    <div>
                                        <strong>{{ $user->name }}</strong>
                                        <br><small class="text-muted">{{ $user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->username }}</td>
                            <td>
                                @foreach($user->roles as $role)
                                <span class="badge bg-info">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td>
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
                            </td>
                            <td>
                                @if($user->mfa_enabled)
                                <span class="text-success"><i class="mdi mdi-shield-check"></i> Aktif</span>
                                @elseif($user->mfa_required)
                                <span class="text-warning"><i class="mdi mdi-shield-alert"></i> Diperlukan</span>
                                @else
                                <span class="text-muted"><i class="mdi mdi-shield-off"></i> Tidak</span>
                                @endif
                            </td>
                            <td>
                                @if($user->last_login_at)
                                {{ $user->last_login_at->diffForHumans() }}
                                <br><small class="text-muted">{{ $user->last_login_ip }}</small>
                                @else
                                <span class="text-muted">Belum log masuk</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="mdi mdi-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        @can('view', $user)
                                        <li><a class="dropdown-item" href="{{ route('admin.users.show', $user) }}"><i class="mdi mdi-eye me-2"></i>Lihat</a></li>
                                        @endcan
                                        @can('update', $user)
                                        <li><a class="dropdown-item" href="{{ route('admin.users.edit', $user) }}"><i class="mdi mdi-pencil me-2"></i>Edit</a></li>
                                        @endcan
                                        @if(auth()->id() !== $user->id)
                                            @can('update', $user)
                                            @if($user->status === 'active')
                                            <li>
                                                <form action="{{ route('admin.users.deactivate', $user) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="dropdown-item"><i class="mdi mdi-account-off me-2"></i>Nyahaktif</button>
                                                </form>
                                            </li>
                                            @else
                                            <li>
                                                <form action="{{ route('admin.users.activate', $user) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="dropdown-item"><i class="mdi mdi-account-check me-2"></i>Aktifkan</button>
                                                </form>
                                            </li>
                                            @endif
                                            @endcan
                                            @can('delete', $user)
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Adakah anda pasti ingin memadam pengguna ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger"><i class="mdi mdi-delete me-2"></i>Padam</button>
                                                </form>
                                            </li>
                                            @endcan
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="mdi mdi-account-off mdi-48px text-muted"></i>
                                <p class="text-muted mb-0">Tiada pengguna dijumpai</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Menunjukkan {{ $users->firstItem() ?? 0 }} hingga {{ $users->lastItem() ?? 0 }} daripada {{ $users->total() }} pengguna
                </div>
                <div>
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
