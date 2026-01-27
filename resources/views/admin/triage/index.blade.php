@extends('layouts.admin')

@section('title', 'AI Triage')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between">
        <div>
            <h1><i class="bi bi-heart-pulse me-2"></i>AI Triage Assessment</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">AI Triage</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.triage.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Triage Baru
            </a>
        </div>
    </div>

    <!-- Today's Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card card-default h-100">
                <div class="card-body text-center">
                    <h2 class="mb-0 text-primary">{{ $todayStats['total'] }}</h2>
                    <small class="text-muted">Jumlah Triage Hari Ini</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-default h-100 border-start border-danger border-3">
                <div class="card-body text-center">
                    <h2 class="mb-0 text-danger">{{ $todayStats['emergency'] }}</h2>
                    <small class="text-muted">Kecemasan</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-default h-100 border-start border-warning border-3">
                <div class="card-body text-center">
                    <h2 class="mb-0 text-warning">{{ $todayStats['urgent'] }}</h2>
                    <small class="text-muted">Segera</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-default h-100 border-start border-info border-3">
                <div class="card-body text-center">
                    <h2 class="mb-0 text-info">{{ $todayStats['pending'] }}</h2>
                    <small class="text-muted">Menunggu Semakan</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="card card-default mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.triage.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="date" class="form-label">Tarikh</label>
                    <input type="date" name="date" id="date" class="form-control"
                           value="{{ request('date', today()->format('Y-m-d')) }}">
                </div>
                <div class="col-md-3">
                    <label for="severity" class="form-label">Tahap Keterukan</label>
                    <select name="severity" id="severity" class="form-select">
                        <option value="">Semua Tahap</option>
                        @foreach($severityLevels as $key => $level)
                        <option value="{{ $key }}" {{ request('severity') == $key ? 'selected' : '' }}>
                            {{ $level['label'] }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Semakan</option>
                        <option value="reviewed" {{ request('status') == 'reviewed' ? 'selected' : '' }}>Disemak</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel me-1"></i> Tapis
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Assessments Table -->
    <div class="card card-default">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Senarai Penilaian Triage</h2>
            <span class="badge bg-secondary">{{ $assessments->total() }} rekod</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="100">Masa</th>
                            <th>Pesakit</th>
                            <th>Aduan Utama</th>
                            <th class="text-center">Tahap</th>
                            <th class="text-center">AI Score</th>
                            <th class="text-center">Red Flags</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" width="120">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assessments as $assessment)
                        @php $severityInfo = $severityLevels[$assessment->final_severity] ?? $severityLevels['standard']; @endphp
                        <tr class="{{ $assessment->hasRedFlags() ? 'table-danger' : '' }}">
                            <td>
                                <small>{{ $assessment->created_at->format('H:i') }}</small><br>
                                <small class="text-muted">{{ $assessment->created_at->format('d/m') }}</small>
                            </td>
                            <td>
                                <strong>{{ $assessment->patient->name ?? '-' }}</strong><br>
                                <small class="text-muted">{{ $assessment->patient->mrn ?? '-' }}</small>
                            </td>
                            <td>
                                <span title="{{ $assessment->chief_complaint }}">
                                    {{ Str::limit($assessment->chief_complaint, 40) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-{{ $severityInfo['color'] }} fs-6">
                                    {{ $severityInfo['label'] }}
                                </span>
                                @if($assessment->override_level)
                                <br><small class="text-muted">(override)</small>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-1">
                                    <div class="progress" style="width: 60px; height: 8px;">
                                        <div class="progress-bar bg-{{ $assessment->ai_confidence >= 70 ? 'success' : ($assessment->ai_confidence >= 50 ? 'warning' : 'danger') }}"
                                             style="width: {{ $assessment->ai_confidence }}%"></div>
                                    </div>
                                    <small>{{ $assessment->ai_confidence }}%</small>
                                </div>
                            </td>
                            <td class="text-center">
                                @if($assessment->hasRedFlags())
                                <span class="badge bg-danger">
                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                    {{ count($assessment->red_flags_detected) }}
                                </span>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @switch($assessment->status)
                                    @case('pending')
                                        <span class="badge bg-warning">Menunggu</span>
                                    @break
                                    @case('reviewed')
                                        <span class="badge bg-info">Disemak</span>
                                    @break
                                    @case('completed')
                                        <span class="badge bg-success">Selesai</span>
                                    @break
                                @endswitch
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.triage.show', $assessment) }}" class="btn btn-outline-primary" title="Lihat">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($assessment->status === 'pending')
                                    <a href="{{ route('admin.triage.edit', $assessment) }}" class="btn btn-outline-secondary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Tiada penilaian triage untuk hari ini
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($assessments->hasPages())
        <div class="card-footer">
            {{ $assessments->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
