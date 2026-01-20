@extends('layouts.admin')
@section('title', 'Butiran Log Audit')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper">
        <div>
            <h1>Butiran Log Audit</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span><a href="{{ route('admin.audit.index') }}">Audit</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>#{{ $auditLog->id }}</span>
            </p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Maklumat Asas</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-muted" width="150">ID Log:</td>
                            <td><strong>{{ $auditLog->id }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tarikh/Masa:</td>
                            <td>{{ $auditLog->created_at->format('d/m/Y H:i:s') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tindakan:</td>
                            <td>
                                @php
                                    $actionColors = [
                                        'create' => 'success',
                                        'update' => 'info',
                                        'delete' => 'danger',
                                        'login' => 'primary',
                                        'logout' => 'secondary',
                                        'failed_login' => 'warning',
                                    ];
                                    $color = $actionColors[$auditLog->action] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $color }}">{{ ucfirst($auditLog->action) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Penerangan:</td>
                            <td>{{ $auditLog->description }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Pengguna</h5>
                </div>
                <div class="card-body">
                    @if($auditLog->user)
                    <div class="d-flex align-items-center">
                        <div class="avatar-initial rounded-circle bg-primary me-3" style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
                            {{ strtoupper(substr($auditLog->user->name, 0, 2)) }}
                        </div>
                        <div>
                            <strong>{{ $auditLog->user->name }}</strong>
                            <br><small class="text-muted">{{ $auditLog->user->email }}</small>
                        </div>
                    </div>
                    @else
                    <p class="text-muted mb-0">Tindakan sistem (tiada pengguna)</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Maklumat Teknikal</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-muted" width="150">Alamat IP:</td>
                            <td><code>{{ $auditLog->ip_address ?? '-' }}</code></td>
                        </tr>
                        <tr>
                            <td class="text-muted">User Agent:</td>
                            <td><small>{{ $auditLog->user_agent ?? '-' }}</small></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Jenis Model:</td>
                            <td>{{ $auditLog->auditable_type ? class_basename($auditLog->auditable_type) : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">ID Model:</td>
                            <td>{{ $auditLog->auditable_id ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            @if($auditLog->old_values || $auditLog->new_values)
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Perubahan Data</h5>
                </div>
                <div class="card-body">
                    @if($auditLog->old_values)
                    <h6 class="text-muted">Nilai Lama:</h6>
                    <pre class="bg-light p-3 rounded"><code>{{ json_encode($auditLog->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                    @endif

                    @if($auditLog->new_values)
                    <h6 class="text-muted">Nilai Baru:</h6>
                    <pre class="bg-light p-3 rounded"><code>{{ json_encode($auditLog->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                    @endif
                </div>
            </div>
            @endif

            @if($auditLog->metadata)
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Metadata Tambahan</h5>
                </div>
                <div class="card-body">
                    <pre class="bg-light p-3 rounded mb-0"><code>{{ json_encode($auditLog->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                </div>
            </div>
            @endif
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('admin.audit.index') }}" class="btn btn-secondary">
            <i class="mdi mdi-arrow-left"></i> Kembali ke Senarai
        </a>
    </div>
</div>
@endsection
