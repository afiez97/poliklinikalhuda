@extends('layouts.admin')

@section('title', 'Sejarah Lawatan - ' . $patient->name)

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between">
        <div>
            <h1>Sejarah Lawatan</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.patients.index') }}">Pesakit</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.patients.show', $patient) }}">{{ $patient->mrn }}</a></li>
                    <li class="breadcrumb-item active">Sejarah Lawatan</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.patients.show', $patient) }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Patient Info Card -->
    <div class="card card-default mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-1">
                    <div class="avatar avatar-lg bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <span class="fs-4">{{ strtoupper(substr($patient->name, 0, 1)) }}</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <h5 class="mb-0">{{ $patient->name }}</h5>
                    <small class="text-muted">{{ $patient->mrn }} | {{ $patient->ic_number ?? '-' }}</small>
                </div>
                <div class="col-md-2">
                    <small class="text-muted d-block">Jantina</small>
                    <span>{{ $patient->gender_label }}</span>
                </div>
                <div class="col-md-2">
                    <small class="text-muted d-block">Umur</small>
                    <span>{{ $patient->formatted_age }}</span>
                </div>
                <div class="col-md-2">
                    <small class="text-muted d-block">Telefon</small>
                    <span>{{ $patient->phone ?? '-' }}</span>
                </div>
                <div class="col-md-2">
                    <small class="text-muted d-block">Status</small>
                    <span class="badge bg-{{ $patient->status == 'active' ? 'success' : 'secondary' }}">
                        {{ $patient->status_label }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Visits List -->
    <div class="card card-default">
        <div class="card-header">
            <h2><i class="bi bi-clock-history me-2"></i>Sejarah Lawatan ({{ $visits->total() }} rekod)</h2>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Tarikh</th>
                            <th>No. Giliran</th>
                            <th>Jenis</th>
                            <th>Doktor</th>
                            <th>Aduan</th>
                            <th>Status</th>
                            <th class="text-center">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($visits as $visit)
                        <tr>
                            <td>
                                {{ $visit->visit_date->format('d/m/Y') }}
                                <br><small class="text-muted">{{ $visit->check_in_time }}</small>
                            </td>
                            <td>
                                <span class="badge bg-{{ $visit->priority == 'emergency' ? 'danger' : ($visit->priority == 'urgent' ? 'warning' : 'primary') }}">
                                    {{ $visit->full_queue_number }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $typeColors = [
                                        'walk_in' => 'secondary',
                                        'appointment' => 'primary',
                                        'emergency' => 'danger',
                                        'follow_up' => 'info',
                                        'referral' => 'warning',
                                    ];
                                @endphp
                                <span class="badge bg-{{ $typeColors[$visit->visit_type] ?? 'secondary' }}">
                                    {{ $visit->visit_type_label }}
                                </span>
                                @if($visit->is_panel)
                                <span class="badge bg-success ms-1">Panel</span>
                                @endif
                            </td>
                            <td>{{ $visit->doctor?->user?->name ?? '-' }}</td>
                            <td>{{ Str::limit($visit->chief_complaint, 30) ?? '-' }}</td>
                            <td>
                                @php
                                    $statusColors = [
                                        'waiting' => 'warning',
                                        'called' => 'info',
                                        'in_consultation' => 'primary',
                                        'completed' => 'success',
                                        'cancelled' => 'danger',
                                        'no_show' => 'secondary',
                                    ];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$visit->status] ?? 'secondary' }}">
                                    {{ $visit->status_label }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($visit->encounter)
                                <a href="{{ route('admin.emr.encounters.show', $visit->encounter) }}" class="btn btn-sm btn-outline-primary" title="Lihat Encounter">
                                    <i class="bi bi-journal-medical"></i>
                                </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Tiada rekod lawatan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($visits->hasPages())
        <div class="card-footer">
            {{ $visits->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
