@extends('layouts.admin')
@section('title', 'Encounter Belum Selesai')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper breadcrumb-contacts">
        <div>
            <h1>Encounter Belum Selesai</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span><a href="{{ route('admin.emr.encounters.index') }}">EMR</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>Belum Selesai</span>
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
        <div class="col-md-4">
            <div class="card card-mini bg-warning-subtle">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0">{{ $encounters->where('status', 'in_progress')->count() }}</h3>
                            <small class="text-muted">Sedang Rawatan</small>
                        </div>
                        <i class="mdi mdi-stethoscope mdi-36px text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-mini bg-secondary-subtle">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0">{{ $encounters->where('status', 'draft')->count() }}</h3>
                            <small class="text-muted">Draf</small>
                        </div>
                        <i class="mdi mdi-file-document-outline mdi-36px text-secondary"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-mini bg-info-subtle">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0">{{ $encounters->count() }}</h3>
                            <small class="text-muted">Jumlah Belum Selesai</small>
                        </div>
                        <i class="mdi mdi-clipboard-list mdi-36px text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Encounters List -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Senarai Encounter Belum Selesai</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No. Encounter</th>
                            <th>Pesakit</th>
                            <th>Doktor</th>
                            <th>Aduan Utama</th>
                            <th>Mula</th>
                            <th>Status</th>
                            <th class="text-end">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($encounters as $encounter)
                        <tr>
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
                                    {{ Str::limit($encounter->chief_complaint, 40) }}
                                @else
                                    <span class="text-muted fst-italic">Belum diisi</span>
                                @endif
                            </td>
                            <td>
                                {{ $encounter->encounter_date->format('d/m/Y') }}
                                <br>
                                <small class="text-muted">
                                    @if($encounter->started_at)
                                        {{ $encounter->started_at->format('H:i') }}
                                    @else
                                        -
                                    @endif
                                </small>
                            </td>
                            <td>
                                @switch($encounter->status)
                                    @case('draft')
                                        <span class="badge bg-secondary">Draf</span>
                                        @break
                                    @case('in_progress')
                                        <span class="badge bg-warning">Sedang Rawatan</span>
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
                                @else
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
                                <i class="mdi mdi-check-circle mdi-48px text-success"></i>
                                <p class="text-muted mb-0 mt-2">Tiada encounter yang belum selesai</p>
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
