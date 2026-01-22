@extends('layouts.admin')

@section('title', 'Senarai Guarantee Letter')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between">
        <div>
            <h1>Guarantee Letter (GL)</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.panel.panels.index') }}">Panel</a></li>
                    <li class="breadcrumb-item active">Guarantee Letter</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.panel.gl.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Tambah GL
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="card card-default">
                <div class="card-body text-center">
                    <h3 class="mb-1 text-primary">{{ $statistics['total'] ?? 0 }}</h3>
                    <small class="text-muted">Jumlah GL</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card card-default">
                <div class="card-body text-center">
                    <h3 class="mb-1 text-success">{{ $statistics['active'] ?? 0 }}</h3>
                    <small class="text-muted">Aktif</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card card-default">
                <div class="card-body text-center">
                    <h3 class="mb-1 text-warning">{{ $statistics['pending_verification'] ?? 0 }}</h3>
                    <small class="text-muted">Menunggu Pengesahan</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card card-default">
                <div class="card-body text-center">
                    <h3 class="mb-1 text-danger">{{ $statistics['expiring_7_days'] ?? 0 }}</h3>
                    <small class="text-muted">Tamat 7 Hari</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card card-default mb-4">
        <div class="card-body">
            <form action="{{ route('admin.panel.gl.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Carian</label>
                    <input type="text" name="search" class="form-control" placeholder="No. GL atau nama pesakit..."
                           value="{{ $filters['search'] ?? '' }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Panel</label>
                    <select name="panel_id" class="form-select">
                        <option value="">Semua Panel</option>
                        @foreach($panels as $panel)
                        <option value="{{ $panel->id }}" {{ ($filters['panel_id'] ?? '') == $panel->id ? 'selected' : '' }}>
                            {{ $panel->panel_name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        @foreach(\App\Models\GuaranteeLetter::STATUSES as $value => $label)
                        <option value="{{ $value }}" {{ ($filters['status'] ?? '') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Pengesahan</label>
                    <select name="verification_status" class="form-select">
                        <option value="">Semua</option>
                        @foreach(\App\Models\GuaranteeLetter::VERIFICATION_STATUSES as $value => $label)
                        <option value="{{ $value }}" {{ ($filters['verification_status'] ?? '') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-search"></i> Cari
                    </button>
                    <a href="{{ route('admin.panel.gl.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- GL List -->
    <div class="card card-default">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No. GL</th>
                            <th>Panel</th>
                            <th>Pesakit</th>
                            <th>Had Liputan</th>
                            <th>Baki</th>
                            <th>Tempoh Sah</th>
                            <th>Pengesahan</th>
                            <th>Status</th>
                            <th width="100">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($guaranteeLetters as $gl)
                        <tr>
                            <td>
                                <a href="{{ route('admin.panel.gl.show', $gl) }}" class="fw-semibold">
                                    {{ $gl->gl_number }}
                                </a>
                            </td>
                            <td>{{ $gl->panel->panel_name ?? '-' }}</td>
                            <td>
                                <div>{{ $gl->patient->name ?? '-' }}</div>
                                <small class="text-muted">{{ $gl->patient->mrn ?? '' }}</small>
                            </td>
                            <td>RM {{ number_format($gl->coverage_limit, 2) }}</td>
                            <td>
                                @php
                                    $percentage = $gl->utilization_percentage;
                                    $colorClass = match($gl->utilization_level) {
                                        'exceeded' => 'danger',
                                        'critical' => 'danger',
                                        'warning' => 'warning',
                                        default => 'success',
                                    };
                                @endphp
                                <div class="d-flex align-items-center">
                                    <div class="progress flex-grow-1 me-2" style="height: 6px;">
                                        <div class="progress-bar bg-{{ $colorClass }}" style="width: {{ min($percentage, 100) }}%"></div>
                                    </div>
                                    <small>{{ $percentage }}%</small>
                                </div>
                                <small class="text-muted">RM {{ number_format($gl->amount_balance, 2) }}</small>
                            </td>
                            <td>
                                <div>{{ $gl->effective_date->format('d/m/Y') }}</div>
                                <small class="text-muted">hingga {{ $gl->expiry_date->format('d/m/Y') }}</small>
                                @if($gl->isExpiringSoon())
                                <br><span class="badge bg-warning text-dark">Tamat {{ $gl->expiry_date->diffForHumans() }}</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $verificationColors = [
                                        'pending' => 'warning',
                                        'verified' => 'success',
                                        'rejected' => 'danger',
                                        'expired' => 'secondary',
                                    ];
                                @endphp
                                <span class="badge bg-{{ $verificationColors[$gl->verification_status] ?? 'secondary' }}">
                                    {{ $gl->verification_status_name }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $statusColors = [
                                        'active' => 'success',
                                        'utilized' => 'info',
                                        'expired' => 'secondary',
                                        'cancelled' => 'danger',
                                    ];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$gl->status] ?? 'secondary' }}">
                                    {{ $gl->status_name }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.panel.gl.show', $gl) }}" class="btn btn-outline-primary" title="Lihat">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.panel.gl.edit', $gl) }}" class="btn btn-outline-secondary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">
                                <i class="bi bi-file-earmark-text fs-1 d-block mb-2"></i>
                                Tiada Guarantee Letter dijumpai.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($guaranteeLetters->hasPages())
        <div class="card-footer">
            {{ $guaranteeLetters->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
