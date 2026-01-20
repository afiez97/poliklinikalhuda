@extends('layouts.admin')
@section('title', 'Senarai Encounter')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper breadcrumb-contacts">
        <div>
            <h1>Rekod Perubatan Elektronik (EMR)</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>EMR</span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>Encounters</span>
            </p>
        </div>
        <div>
            <a href="{{ route('admin.emr.encounters.create') }}" class="btn btn-primary">
                <i class="mdi mdi-plus"></i> Encounter Baru
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-xl-2 col-sm-4 mb-3">
            <div class="card card-mini">
                <div class="card-body">
                    <h2 class="mb-1">{{ number_format($statistics['total_encounters'] ?? 0) }}</h2>
                    <p class="mb-0 text-muted">Minggu Ini</p>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-sm-4 mb-3">
            <div class="card card-mini">
                <div class="card-body">
                    <h2 class="mb-1 text-primary">{{ number_format($statistics['today'] ?? 0) }}</h2>
                    <p class="mb-0 text-muted">Hari Ini</p>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-sm-4 mb-3">
            <div class="card card-mini">
                <div class="card-body">
                    <h2 class="mb-1 text-warning">{{ number_format($statistics['in_progress'] ?? 0) }}</h2>
                    <p class="mb-0 text-muted">Sedang Rawatan</p>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-sm-4 mb-3">
            <div class="card card-mini">
                <div class="card-body">
                    <h2 class="mb-1 text-success">{{ number_format($statistics['completed'] ?? 0) }}</h2>
                    <p class="mb-0 text-muted">Selesai</p>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-sm-4 mb-3">
            <div class="card card-mini">
                <div class="card-body">
                    <h2 class="mb-1 text-secondary">{{ number_format($statistics['draft'] ?? 0) }}</h2>
                    <p class="mb-0 text-muted">Draf</p>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-sm-4 mb-3">
            <div class="card card-mini">
                <div class="card-body">
                    <h2 class="mb-1 text-info">{{ $statistics['avg_duration'] ?? '-' }}</h2>
                    <p class="mb-0 text-muted">Purata (min)</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.emr.encounters.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="mdi mdi-magnify"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Cari No. Encounter, MRN, Nama..." value="{{ $filters['search'] ?? '' }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <select name="doctor_id" class="form-select">
                        <option value="">Semua Doktor</option>
                        @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}" {{ ($filters['doctor_id'] ?? '') == $doctor->id ? 'selected' : '' }}>
                            {{ $doctor->user->name ?? $doctor->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        @foreach($statuses as $key => $label)
                        <option value="{{ $key }}" {{ ($filters['status'] ?? '') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_from" class="form-control" placeholder="Dari Tarikh" value="{{ $filters['date_from'] ?? '' }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_to" class="form-control" placeholder="Hingga Tarikh" value="{{ $filters['date_to'] ?? '' }}">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-secondary w-100">
                        <i class="mdi mdi-magnify"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Encounters Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No. Encounter</th>
                            <th>Tarikh/Masa</th>
                            <th>Pesakit</th>
                            <th>Doktor</th>
                            <th>Aduan Utama</th>
                            <th>Status</th>
                            <th class="text-end">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($encounters as $encounter)
                        <tr>
                            <td>
                                <a href="{{ route('admin.emr.encounters.show', $encounter) }}" class="fw-bold text-primary">
                                    {{ $encounter->encounter_no }}
                                </a>
                            </td>
                            <td>
                                {{ $encounter->encounter_date->format('d/m/Y') }}
                                <br><small class="text-muted">{{ $encounter->encounter_date->format('H:i') }}</small>
                            </td>
                            <td>
                                <strong>{{ $encounter->patient->name ?? '-' }}</strong>
                                <br><small class="text-muted">{{ $encounter->patient->mrn ?? '-' }}</small>
                            </td>
                            <td>
                                {{ $encounter->doctor->user->name ?? $encounter->doctor->name ?? '-' }}
                            </td>
                            <td>
                                <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $encounter->chief_complaint }}">
                                    {{ Str::limit($encounter->chief_complaint, 50) }}
                                </span>
                            </td>
                            <td>
                                @switch($encounter->status)
                                    @case('draft')
                                        <span class="badge bg-secondary">Draf</span>
                                        @break
                                    @case('in_progress')
                                        <span class="badge bg-warning">Sedang Rawatan</span>
                                        @break
                                    @case('pending_review')
                                        <span class="badge bg-info">Menunggu Semakan</span>
                                        @break
                                    @case('completed')
                                        <span class="badge bg-success">Selesai</span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge bg-danger">Dibatalkan</span>
                                        @break
                                @endswitch
                            </td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        Tindakan
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.emr.encounters.show', $encounter) }}">
                                                <i class="mdi mdi-eye me-2"></i> Lihat
                                            </a>
                                        </li>
                                        @if($encounter->status !== 'completed')
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.emr.encounters.edit', $encounter) }}">
                                                <i class="mdi mdi-pencil me-2"></i> Edit
                                            </a>
                                        </li>
                                        @endif
                                        @if($encounter->status === 'draft')
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('admin.emr.encounters.start', $encounter) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="dropdown-item text-primary">
                                                    <i class="mdi mdi-play me-2"></i> Mulakan
                                                </button>
                                            </form>
                                        </li>
                                        @elseif($encounter->status === 'in_progress')
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('admin.emr.encounters.complete', $encounter) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="dropdown-item text-success">
                                                    <i class="mdi mdi-check me-2"></i> Selesaikan
                                                </button>
                                            </form>
                                        </li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="mdi mdi-file-document-outline mdi-48px text-muted"></i>
                                <p class="text-muted mb-0">Tiada encounter dijumpai</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $encounters->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
