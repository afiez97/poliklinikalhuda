@extends('layouts.admin')
@section('title', 'Profil Pesakit - ' . $patient->mrn)

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper">
        <div>
            <h1>Profil Pesakit</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span><a href="{{ route('admin.patients.index') }}">Pesakit</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>{{ $patient->mrn }}</span>
            </p>
        </div>
        <div>
            <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#registerVisitModal">
                <i class="mdi mdi-plus"></i> Daftar Lawatan
            </button>
            <a href="{{ route('admin.patients.edit', $patient) }}" class="btn btn-primary">
                <i class="mdi mdi-pencil"></i> Edit
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <!-- Left Column - Patient Info -->
        <div class="col-lg-4">
            <!-- Profile Card -->
            <div class="card mb-4">
                <div class="card-body text-center">
                    <div class="avatar-initial rounded-circle bg-primary mx-auto mb-3" style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center; font-size: 36px; color: white;">
                        {{ strtoupper(substr($patient->name, 0, 2)) }}
                    </div>
                    <h4 class="mb-1">{{ $patient->name }}</h4>
                    <p class="text-muted mb-2">MRN: <strong>{{ $patient->mrn }}</strong></p>
                    <div class="mb-3">
                        @switch($patient->status)
                            @case('active')
                                <span class="badge bg-success">Aktif</span>
                                @break
                            @case('inactive')
                                <span class="badge bg-secondary">Tidak Aktif</span>
                                @break
                            @case('deceased')
                                <span class="badge bg-dark">Meninggal</span>
                                @break
                        @endswitch
                        @if($patient->has_panel && $patient->isPanelValid())
                        <span class="badge bg-info">Panel</span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-center gap-3">
                        <div class="text-center">
                            <h5 class="mb-0">{{ $patient->formatted_age }}</h5>
                            <small class="text-muted">Umur</small>
                        </div>
                        <div class="text-center">
                            <h5 class="mb-0">{{ $patient->gender_label }}</h5>
                            <small class="text-muted">Jantina</small>
                        </div>
                        <div class="text-center">
                            <h5 class="mb-0">{{ $patient->blood_type ?? '-' }}</h5>
                            <small class="text-muted">Darah</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Maklumat Hubungan</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">No. KP / Pasport</small>
                        <strong>{{ $patient->ic_number ?? $patient->passport_number ?? '-' }}</strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Telefon</small>
                        @if($patient->phone)
                        <a href="tel:{{ $patient->phone }}"><strong>{{ $patient->phone }}</strong></a>
                        @else
                        <span class="text-muted">-</span>
                        @endif
                    </div>
                    @if($patient->email)
                    <div class="mb-3">
                        <small class="text-muted d-block">Emel</small>
                        <a href="mailto:{{ $patient->email }}">{{ $patient->email }}</a>
                    </div>
                    @endif
                    <div>
                        <small class="text-muted d-block">Alamat</small>
                        <span>{{ $patient->full_address ?: '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Emergency Contact -->
            @if($patient->emergency_name)
            <div class="card mb-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="card-title mb-0"><i class="mdi mdi-alert me-1"></i> Hubungan Kecemasan</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>{{ $patient->emergency_name }}</strong>
                        <br><small class="text-muted">{{ $patient->emergency_relationship }}</small>
                    </div>
                    <a href="tel:{{ $patient->emergency_phone }}" class="btn btn-outline-danger btn-sm">
                        <i class="mdi mdi-phone"></i> {{ $patient->emergency_phone }}
                    </a>
                </div>
            </div>
            @endif

            <!-- Panel Info -->
            @if($patient->has_panel)
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0"><i class="mdi mdi-shield-account me-1"></i> Maklumat Panel</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <small class="text-muted d-block">Syarikat</small>
                        <strong>{{ $patient->panel_company }}</strong>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted d-block">No. Ahli</small>
                        <strong>{{ $patient->panel_member_id ?? '-' }}</strong>
                    </div>
                    <div>
                        <small class="text-muted d-block">Tamat Tempoh</small>
                        @if($patient->panel_expiry_date)
                        <strong class="{{ $patient->isPanelValid() ? 'text-success' : 'text-danger' }}">
                            {{ $patient->panel_expiry_date->format('d/m/Y') }}
                            @if(!$patient->isPanelValid())
                            (Tamat)
                            @endif
                        </strong>
                        @else
                        <span class="text-muted">-</span>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column -->
        <div class="col-lg-8">
            <!-- Medical Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="mdi mdi-medical-bag me-2"></i>Maklumat Perubatan</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="border rounded p-3 h-100">
                                <h6 class="text-danger"><i class="mdi mdi-alert-circle me-1"></i>Alahan</h6>
                                @if($patient->allergies)
                                <p class="mb-0">{{ $patient->allergies }}</p>
                                @else
                                <p class="text-muted mb-0">Tiada alahan direkodkan</p>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="border rounded p-3 h-100">
                                <h6 class="text-warning"><i class="mdi mdi-heart-pulse me-1"></i>Penyakit Kronik</h6>
                                @if($patient->chronic_diseases)
                                <p class="mb-0">{{ $patient->chronic_diseases }}</p>
                                @else
                                <p class="text-muted mb-0">Tiada</p>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="border rounded p-3 h-100">
                                <h6 class="text-info"><i class="mdi mdi-pill me-1"></i>Ubat Semasa</h6>
                                @if($patient->current_medications)
                                <p class="mb-0">{{ $patient->current_medications }}</p>
                                @else
                                <p class="text-muted mb-0">Tiada</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Visit Statistics -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card card-mini">
                        <div class="card-body text-center">
                            <h3 class="mb-0">{{ $visitStats['total'] }}</h3>
                            <small class="text-muted">Jumlah Lawatan</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-mini">
                        <div class="card-body text-center">
                            <h3 class="mb-0">{{ $visitStats['this_year'] }}</h3>
                            <small class="text-muted">Lawatan Tahun Ini</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-mini">
                        <div class="card-body text-center">
                            <h3 class="mb-0">{{ $visitStats['last_visit']?->format('d/m/Y') ?? '-' }}</h3>
                            <small class="text-muted">Lawatan Terakhir</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Visits -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Lawatan Terkini</h5>
                    <a href="{{ route('admin.patients.visits', $patient) }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>No. Lawatan</th>
                                    <th>Tarikh</th>
                                    <th>Jenis</th>
                                    <th>Status</th>
                                    <th>Doktor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($patient->visits as $visit)
                                <tr>
                                    <td><strong>{{ $visit->visit_no }}</strong></td>
                                    <td>{{ $visit->visit_date->format('d/m/Y') }}</td>
                                    <td>{{ $visit->visit_type_label }}</td>
                                    <td>
                                        @switch($visit->status)
                                            @case('completed')
                                                <span class="badge bg-success">Selesai</span>
                                                @break
                                            @case('waiting')
                                            @case('registered')
                                                <span class="badge bg-warning">Menunggu</span>
                                                @break
                                            @case('in_consultation')
                                                <span class="badge bg-primary">Dalam Rawatan</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ $visit->status_label }}</span>
                                        @endswitch
                                    </td>
                                    <td>{{ $visit->doctor?->user?->name ?? '-' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        Tiada lawatan direkodkan
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Register Visit Modal -->
<div class="modal fade" id="registerVisitModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.patients.registerVisit', $patient) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Daftar Lawatan Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Jenis Lawatan</label>
                        <select name="visit_type" class="form-select" required>
                            <option value="walk_in">Walk-in</option>
                            <option value="follow_up">Susulan</option>
                            <option value="emergency">Kecemasan</option>
                            <option value="referral">Rujukan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keutamaan</label>
                        <select name="priority" class="form-select" required>
                            <option value="normal">Biasa</option>
                            <option value="urgent">Segera</option>
                            <option value="emergency">Kecemasan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Aduan Utama</label>
                        <textarea name="chief_complaint" class="form-control" rows="3"></textarea>
                    </div>
                    @if($patient->has_panel && $patient->isPanelValid())
                    <div class="form-check">
                        <input type="checkbox" name="is_panel" value="1" class="form-check-input" id="isPanelVisit" checked>
                        <label class="form-check-label" for="isPanelVisit">
                            Gunakan Panel ({{ $patient->panel_company }})
                        </label>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Daftar Lawatan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
