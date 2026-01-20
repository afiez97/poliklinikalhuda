@extends('layouts.admin')
@section('title', 'Senarai Pesakit')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper breadcrumb-contacts">
        <div>
            <h1>Pendaftaran Pesakit</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>Pesakit</span>
            </p>
        </div>
        <div>
            <a href="{{ route('admin.patients.create') }}" class="btn btn-primary">
                <i class="mdi mdi-plus"></i> Daftar Pesakit Baru
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card card-mini">
                <div class="card-body">
                    <h2 class="mb-1">{{ number_format($statistics['total']) }}</h2>
                    <p class="mb-0">Jumlah Pesakit</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card card-mini">
                <div class="card-body">
                    <h2 class="mb-1 text-success">{{ number_format($statistics['active']) }}</h2>
                    <p class="mb-0">Pesakit Aktif</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card card-mini">
                <div class="card-body">
                    <h2 class="mb-1 text-info">{{ number_format($statistics['panel']) }}</h2>
                    <p class="mb-0">Pesakit Panel</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card card-mini">
                <div class="card-body">
                    <h2 class="mb-1 text-primary">{{ number_format($statistics['today']) }}</h2>
                    <p class="mb-0">Daftar Hari Ini</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.patients.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="mdi mdi-magnify"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Cari MRN, No. KP, Nama, Telefon..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                        <option value="deceased" {{ request('status') == 'deceased' ? 'selected' : '' }}>Meninggal</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="gender" class="form-select">
                        <option value="">Semua Jantina</option>
                        <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Lelaki</option>
                        <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="has_panel" class="form-select">
                        <option value="">Panel</option>
                        <option value="1" {{ request('has_panel') === '1' ? 'selected' : '' }}>Ya</option>
                        <option value="0" {{ request('has_panel') === '0' ? 'selected' : '' }}>Tidak</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-secondary w-100">
                        <i class="mdi mdi-magnify"></i> Cari
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Patients Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>MRN</th>
                            <th>Nama</th>
                            <th>No. KP</th>
                            <th>Umur/Jantina</th>
                            <th>Telefon</th>
                            <th>Panel</th>
                            <th>Status</th>
                            <th class="text-end">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($patients as $patient)
                        <tr>
                            <td>
                                <a href="{{ route('admin.patients.show', $patient) }}" class="fw-bold text-primary">
                                    {{ $patient->mrn }}
                                </a>
                            </td>
                            <td>
                                <strong>{{ $patient->name }}</strong>
                            </td>
                            <td>{{ $patient->ic_number ?? $patient->passport_number ?? '-' }}</td>
                            <td>
                                {{ $patient->formatted_age }}
                                <br><small class="text-muted">{{ $patient->gender_label }}</small>
                            </td>
                            <td>{{ $patient->phone ?? '-' }}</td>
                            <td>
                                @if($patient->has_panel)
                                <span class="badge bg-info">{{ $patient->panel_company ?? 'Panel' }}</span>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
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
                                    @case('transferred')
                                        <span class="badge bg-warning">Dipindahkan</span>
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
                                            <a class="dropdown-item" href="{{ route('admin.patients.show', $patient) }}">
                                                <i class="mdi mdi-eye me-2"></i> Lihat Profil
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.patients.edit', $patient) }}">
                                                <i class="mdi mdi-pencil me-2"></i> Edit
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <button type="button" class="dropdown-item text-primary" data-bs-toggle="modal" data-bs-target="#registerVisitModal{{ $patient->id }}">
                                                <i class="mdi mdi-plus-circle me-2"></i> Daftar Lawatan
                                            </button>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.patients.visits', $patient) }}">
                                                <i class="mdi mdi-history me-2"></i> Sejarah Lawatan
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>

                        <!-- Register Visit Modal -->
                        <div class="modal fade" id="registerVisitModal{{ $patient->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.patients.registerVisit', $patient) }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Daftar Lawatan - {{ $patient->name }}</h5>
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
                                            @if($patient->has_panel)
                                            <div class="form-check">
                                                <input type="checkbox" name="is_panel" value="1" class="form-check-input" id="isPanel{{ $patient->id }}" checked>
                                                <label class="form-check-label" for="isPanel{{ $patient->id }}">
                                                    Gunakan Panel ({{ $patient->panel_company }})
                                                </label>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Daftar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="mdi mdi-account-search mdi-48px text-muted"></i>
                                <p class="text-muted mb-0">Tiada pesakit dijumpai</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $patients->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
