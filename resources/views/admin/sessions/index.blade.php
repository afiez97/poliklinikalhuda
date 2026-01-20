@extends('layouts.admin')
@section('title', 'Pengurusan Sesi')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper breadcrumb-contacts">
        <div>
            <h1>Pengurusan Sesi Aktif</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>Sesi</span>
            </p>
        </div>
        <div>
            <form action="{{ route('admin.sessions.terminateOthers') }}" method="POST" class="d-inline" onsubmit="return confirm('Adakah anda pasti ingin menamatkan semua sesi lain anda?')">
                @csrf
                <button type="submit" class="btn btn-outline-warning">
                    <i class="mdi mdi-logout-variant"></i> Tamatkan Sesi Lain Saya
                </button>
            </form>
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
        <div class="col-xl-4 col-sm-6 mb-3">
            <div class="card card-mini">
                <div class="card-body">
                    <h2 class="mb-1">{{ $statistics['total_sessions'] }}</h2>
                    <p class="mb-0">Jumlah Sesi Aktif</p>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-6 mb-3">
            <div class="card card-mini">
                <div class="card-body">
                    <h2 class="mb-1">{{ $statistics['unique_users'] }}</h2>
                    <p class="mb-0">Pengguna Unik</p>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-6 mb-3">
            <div class="card card-mini">
                <div class="card-body">
                    <h2 class="mb-1 text-success">{{ $statistics['active_now'] }}</h2>
                    <p class="mb-0">Aktif Sekarang (5 min)</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Sessions Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Pengguna</th>
                            <th>Alamat IP</th>
                            <th>Pelayar</th>
                            <th>Aktiviti Terakhir</th>
                            <th>Status</th>
                            <th class="text-end">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sessions as $session)
                        <tr class="{{ $session->is_current ? 'table-info' : '' }}">
                            <td>
                                <strong>{{ $session->user_name }}</strong>
                                <br><small class="text-muted">{{ $session->user_email }}</small>
                            </td>
                            <td><code>{{ $session->ip_address ?? '-' }}</code></td>
                            <td>
                                @switch($session->browser)
                                    @case('Chrome')
                                        <i class="mdi mdi-google-chrome text-success"></i>
                                        @break
                                    @case('Firefox')
                                        <i class="mdi mdi-firefox text-warning"></i>
                                        @break
                                    @case('Safari')
                                        <i class="mdi mdi-apple-safari text-info"></i>
                                        @break
                                    @case('Edge')
                                        <i class="mdi mdi-microsoft-edge text-primary"></i>
                                        @break
                                    @default
                                        <i class="mdi mdi-web text-secondary"></i>
                                @endswitch
                                {{ $session->browser }}
                            </td>
                            <td>
                                {{ $session->last_activity_at->diffForHumans() }}
                                <br><small class="text-muted">{{ $session->last_activity_at->format('d/m/Y H:i:s') }}</small>
                            </td>
                            <td>
                                @if($session->is_current)
                                <span class="badge bg-info">Sesi Semasa</span>
                                @elseif($session->last_activity_at->gt(now()->subMinutes(5)))
                                <span class="badge bg-success">Aktif</span>
                                @elseif($session->last_activity_at->gt(now()->subMinutes(30)))
                                <span class="badge bg-warning">Tidak Aktif</span>
                                @else
                                <span class="badge bg-secondary">Lama</span>
                                @endif
                            </td>
                            <td class="text-end">
                                @if(!$session->is_current)
                                <form action="{{ route('admin.sessions.destroy', $session->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Adakah anda pasti ingin menamatkan sesi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="mdi mdi-logout"></i> Tamatkan
                                    </button>
                                </form>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="mdi mdi-account-off mdi-48px text-muted"></i>
                                <p class="text-muted mb-0">Tiada sesi aktif dijumpai</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-3">
                {{ $sessions->links() }}
            </div>
        </div>
    </div>

    <!-- Info Card -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title mb-0"><i class="mdi mdi-information-outline"></i> Maklumat</h5>
        </div>
        <div class="card-body">
            <ul class="mb-0">
                <li><strong>Sesi Semasa</strong> - Sesi yang sedang anda gunakan sekarang.</li>
                <li><strong>Aktif</strong> - Pengguna aktif dalam 5 minit terakhir.</li>
                <li><strong>Tidak Aktif</strong> - Tiada aktiviti dalam 5-30 minit.</li>
                <li><strong>Lama</strong> - Tiada aktiviti lebih dari 30 minit.</li>
            </ul>
        </div>
    </div>
</div>
@endsection
