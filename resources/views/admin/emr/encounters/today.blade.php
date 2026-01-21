@extends('layouts.admin')
@section('title', 'Encounter Hari Ini')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper breadcrumb-contacts">
        <div>
            <h1>Encounter Hari Ini</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span><a href="{{ route('admin.emr.encounters.index') }}">EMR</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>Hari Ini</span>
            </p>
        </div>
        <div>
            <a href="{{ route('admin.emr.encounters.index') }}" class="btn btn-outline-secondary me-2">
                <i class="mdi mdi-format-list-bulleted"></i> Semua Encounter
            </a>
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

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card card-mini bg-primary-subtle">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0">{{ $encounters->count() }}</h3>
                            <small class="text-muted">Jumlah Hari Ini</small>
                        </div>
                        <i class="mdi mdi-calendar-today mdi-36px text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-mini bg-warning-subtle">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0">{{ $encounters->whereIn('status', ['draft', 'in_progress'])->count() }}</h3>
                            <small class="text-muted">Sedang Berjalan</small>
                        </div>
                        <i class="mdi mdi-progress-clock mdi-36px text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-mini bg-success-subtle">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0">{{ $encounters->where('status', 'completed')->count() }}</h3>
                            <small class="text-muted">Selesai</small>
                        </div>
                        <i class="mdi mdi-check-circle mdi-36px text-success"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-mini">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0">{{ now()->format('d M Y') }}</h3>
                            <small class="text-muted">{{ now()->translatedFormat('l') }}</small>
                        </div>
                        <i class="mdi mdi-clock-outline mdi-36px text-secondary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Encounters List -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Senarai Encounter Hari Ini</h5>
            <span class="badge bg-primary">{{ $encounters->count() }} encounter</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Masa</th>
                            <th>No. Encounter</th>
                            <th>Pesakit</th>
                            <th>Doktor</th>
                            <th>Aduan Utama</th>
                            <th>Status</th>
                            <th class="text-end">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($encounters->sortBy('started_at') as $encounter)
                        <tr class="{{ $encounter->status === 'in_progress' ? 'table-warning' : '' }}">
                            <td>
                                @if($encounter->started_at)
                                    <strong>{{ $encounter->started_at->format('H:i') }}</strong>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.emr.encounters.show', $encounter) }}" class="fw-bold">
                                    {{ $encounter->encounter_no }}
                                </a>
                            </td>
                            <td>
                                <div>
                                    <a href="{{ route('admin.patients.show', $encounter->patient) }}">
                                        {{ $encounter->patient->name }}
                                    </a>
                                    <br>
                                    <small class="text-muted">{{ $encounter->patient->mrn }}</small>
                                </div>
                            </td>
                            <td>{{ $encounter->doctor?->user?->name ?? '-' }}</td>
                            <td>
                                @if($encounter->chief_complaint)
                                    {{ Str::limit($encounter->chief_complaint, 30) }}
                                @else
                                    <span class="text-muted fst-italic">-</span>
                                @endif
                            </td>
                            <td>
                                @switch($encounter->status)
                                    @case('draft')
                                        <span class="badge bg-secondary">Draf</span>
                                        @break
                                    @case('in_progress')
                                        <span class="badge bg-warning">
                                            <i class="mdi mdi-loading mdi-spin me-1"></i>Rawatan
                                        </span>
                                        @break
                                    @case('completed')
                                        <span class="badge bg-success">Selesai</span>
                                        @if($encounter->completed_at)
                                            <br><small class="text-muted">{{ $encounter->completed_at->format('H:i') }}</small>
                                        @endif
                                        @break
                                    @default
                                        <span class="badge bg-info">{{ ucfirst($encounter->status) }}</span>
                                @endswitch
                            </td>
                            <td class="text-end">
                                @if($encounter->status === 'draft')
                                <form action="{{ route('admin.emr.encounters.start', $encounter) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        <i class="mdi mdi-play"></i> Mula
                                    </button>
                                </form>
                                @elseif($encounter->status === 'in_progress')
                                <a href="{{ route('admin.emr.encounters.edit', $encounter) }}" class="btn btn-sm btn-warning">
                                    <i class="mdi mdi-pencil"></i> Sambung
                                </a>
                                @endif
                                <a href="{{ route('admin.emr.encounters.show', $encounter) }}" class="btn btn-sm btn-outline-info">
                                    <i class="mdi mdi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="mdi mdi-calendar-blank mdi-48px text-muted"></i>
                                <p class="text-muted mb-0 mt-2">Tiada encounter untuk hari ini</p>
                                <a href="{{ route('admin.emr.encounters.create') }}" class="btn btn-primary mt-3">
                                    <i class="mdi mdi-plus"></i> Buat Encounter Baru
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
