@extends('layouts.admin')

@section('title', 'Senarai Panel')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between">
        <div>
            <h1>Pengurusan Panel</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Panel</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.panel.panels.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Tambah Panel
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-2 col-sm-4 col-6">
            <div class="card card-default">
                <div class="card-body text-center">
                    <h3 class="mb-1 text-primary">{{ $statistics['total_panels'] }}</h3>
                    <small class="text-muted">Jumlah Panel</small>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 col-6">
            <div class="card card-default">
                <div class="card-body text-center">
                    <h3 class="mb-1 text-success">{{ $statistics['active_panels'] }}</h3>
                    <small class="text-muted">Aktif</small>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 col-6">
            <div class="card card-default">
                <div class="card-body text-center">
                    <h3 class="mb-1 text-info">{{ $statistics['corporate_panels'] }}</h3>
                    <small class="text-muted">Korporat</small>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 col-6">
            <div class="card card-default">
                <div class="card-body text-center">
                    <h3 class="mb-1 text-secondary">{{ $statistics['insurance_panels'] }}</h3>
                    <small class="text-muted">Insurans</small>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 col-6">
            <div class="card card-default">
                <div class="card-body text-center">
                    <h3 class="mb-1 text-dark">{{ $statistics['government_panels'] }}</h3>
                    <small class="text-muted">Kerajaan</small>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 col-6">
            <div class="card card-default">
                <div class="card-body text-center">
                    <h3 class="mb-1 text-warning">{{ $statistics['expiring_contracts'] }}</h3>
                    <small class="text-muted">Kontrak Tamat</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card card-default mb-4">
        <div class="card-body">
            <form action="{{ route('admin.panel.panels.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Carian</label>
                    <input type="text" name="search" class="form-control" placeholder="Kod atau nama panel..."
                           value="{{ $filters['search'] ?? '' }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jenis</label>
                    <select name="type" class="form-select">
                        <option value="">Semua Jenis</option>
                        @foreach(\App\Models\Panel::TYPES as $value => $label)
                        <option value="{{ $value }}" {{ ($filters['type'] ?? '') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        @foreach(\App\Models\Panel::STATUSES as $value => $label)
                        <option value="{{ $value }}" {{ ($filters['status'] ?? '') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-search"></i> Cari
                    </button>
                    <a href="{{ route('admin.panel.panels.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Panel List -->
    <div class="card card-default">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Kod</th>
                            <th>Nama Panel</th>
                            <th>Jenis</th>
                            <th>Hubungan</th>
                            <th>Terma Bayaran</th>
                            <th>Status</th>
                            <th width="120">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($panels as $panel)
                        <tr>
                            <td>
                                <a href="{{ route('admin.panel.panels.show', $panel) }}" class="fw-semibold">
                                    {{ $panel->panel_code }}
                                </a>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $panel->panel_name }}</div>
                                @if($panel->email)
                                <small class="text-muted">{{ $panel->email }}</small>
                                @endif
                            </td>
                            <td>
                                @php
                                    $typeColors = [
                                        'corporate' => 'info',
                                        'insurance' => 'primary',
                                        'government' => 'success',
                                    ];
                                @endphp
                                <span class="badge bg-{{ $typeColors[$panel->panel_type] ?? 'secondary' }}">
                                    {{ $panel->type_name }}
                                </span>
                            </td>
                            <td>
                                @if($panel->contact_person)
                                <div>{{ $panel->contact_person }}</div>
                                @endif
                                @if($panel->phone)
                                <small class="text-muted">{{ $panel->phone }}</small>
                                @endif
                            </td>
                            <td>{{ $panel->payment_terms_days }} hari</td>
                            <td>
                                @php
                                    $statusColors = [
                                        'active' => 'success',
                                        'inactive' => 'secondary',
                                        'suspended' => 'danger',
                                    ];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$panel->status] ?? 'secondary' }}">
                                    {{ $panel->status_name }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.panel.panels.show', $panel) }}" class="btn btn-outline-primary" title="Lihat">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.panel.panels.edit', $panel) }}" class="btn btn-outline-secondary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="bi bi-building fs-1 d-block mb-2"></i>
                                Tiada panel dijumpai.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($panels->hasPages())
        <div class="card-footer">
            {{ $panels->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
