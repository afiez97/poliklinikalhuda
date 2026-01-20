@extends('layouts.admin')
@section('title', 'Log Audit')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper breadcrumb-contacts">
        <div>
            <h1>Log Audit</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>Audit</span>
            </p>
        </div>
        <div>
            @can('export', App\Models\AuditLog::class)
            <a href="{{ route('admin.audit.export', request()->all()) }}" class="btn btn-outline-primary">
                <i class="mdi mdi-download"></i> Eksport CSV
            </a>
            @endcan
            <a href="{{ route('admin.audit.statistics') }}" class="btn btn-outline-secondary">
                <i class="mdi mdi-chart-bar"></i> Statistik
            </a>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.audit.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Pengguna</label>
                        <select name="user_id" class="form-select">
                            <option value="">Semua Pengguna</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Tindakan</label>
                        <select name="action" class="form-select">
                            <option value="">Semua</option>
                            @foreach($actions as $action)
                            <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>{{ ucfirst($action) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Dari Tarikh</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Hingga Tarikh</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Alamat IP</label>
                        <input type="text" name="ip_address" class="form-control" placeholder="192.168.1.1" value="{{ request('ip_address') }}">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="mdi mdi-magnify"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Audit Logs Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Tarikh/Masa</th>
                            <th>Pengguna</th>
                            <th>Tindakan</th>
                            <th>Penerangan</th>
                            <th>Alamat IP</th>
                            <th class="text-end">Butiran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr>
                            <td>
                                <small>{{ $log->created_at->format('d/m/Y') }}</small>
                                <br><small class="text-muted">{{ $log->created_at->format('H:i:s') }}</small>
                            </td>
                            <td>
                                @if($log->user)
                                <strong>{{ $log->user->name }}</strong>
                                <br><small class="text-muted">{{ $log->user->email }}</small>
                                @else
                                <span class="text-muted">Sistem</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $actionColors = [
                                        'create' => 'success',
                                        'update' => 'info',
                                        'delete' => 'danger',
                                        'login' => 'primary',
                                        'logout' => 'secondary',
                                        'failed_login' => 'warning',
                                        'password_reset' => 'warning',
                                        'export' => 'info',
                                    ];
                                    $color = $actionColors[$log->action] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $color }}">{{ ucfirst($log->action) }}</span>
                            </td>
                            <td>
                                <span title="{{ $log->description }}">{{ Str::limit($log->description, 50) }}</span>
                                @if($log->auditable_type)
                                <br><small class="text-muted">{{ class_basename($log->auditable_type) }} #{{ $log->auditable_id }}</small>
                                @endif
                            </td>
                            <td>
                                <code>{{ $log->ip_address ?? '-' }}</code>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.audit.show', $log) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="mdi mdi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="mdi mdi-file-document-outline mdi-48px text-muted"></i>
                                <p class="text-muted mb-0">Tiada log audit dijumpai</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Menunjukkan {{ $logs->firstItem() ?? 0 }} hingga {{ $logs->lastItem() ?? 0 }} daripada {{ $logs->total() }} log
                </div>
                <div>
                    {{ $logs->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
