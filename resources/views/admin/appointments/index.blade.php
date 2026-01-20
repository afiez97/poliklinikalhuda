@extends('layouts.admin')
@section('title', 'Pengurusan Temujanji')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper breadcrumb-contacts">
        <div>
            <h1>Pengurusan Temujanji</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>Temujanji</span>
            </p>
        </div>
        <div>
            <a href="{{ route('admin.appointments.calendar') }}" class="btn btn-outline-primary me-2">
                <i class="mdi mdi-calendar"></i> Kalendar
            </a>
            <a href="{{ route('admin.appointments.create') }}" class="btn btn-primary">
                <i class="mdi mdi-plus"></i> Buat Temujanji
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Date Navigation & Statistics -->
    <div class="row mb-4">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.appointments.index') }}" method="GET" class="d-flex gap-2">
                        <input type="date" name="date" class="form-control" value="{{ $date->format('Y-m-d') }}" onchange="this.form.submit()">
                        <a href="{{ route('admin.appointments.index', ['date' => $date->copy()->subDay()->format('Y-m-d')]) }}" class="btn btn-outline-secondary">
                            <i class="mdi mdi-chevron-left"></i>
                        </a>
                        <a href="{{ route('admin.appointments.index', ['date' => $date->copy()->addDay()->format('Y-m-d')]) }}" class="btn btn-outline-secondary">
                            <i class="mdi mdi-chevron-right"></i>
                        </a>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="row">
                <div class="col-3">
                    <div class="card card-mini">
                        <div class="card-body text-center">
                            <h3 class="mb-0">{{ $statistics['total'] }}</h3>
                            <small>Jumlah</small>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card card-mini">
                        <div class="card-body text-center">
                            <h3 class="mb-0 text-warning">{{ $statistics['scheduled'] + $statistics['confirmed'] }}</h3>
                            <small>Menunggu</small>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card card-mini">
                        <div class="card-body text-center">
                            <h3 class="mb-0 text-success">{{ $statistics['completed'] }}</h3>
                            <small>Selesai</small>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card card-mini">
                        <div class="card-body text-center">
                            <h3 class="mb-0 text-danger">{{ $statistics['cancelled'] }}</h3>
                            <small>Batal</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.appointments.index') }}" method="GET" class="row g-3">
                <input type="hidden" name="date" value="{{ $date->format('Y-m-d') }}">
                <div class="col-md-3">
                    <select name="doctor_id" class="form-select">
                        <option value="">Semua Doktor</option>
                        @foreach($doctors as $doc)
                        <option value="{{ $doc->id }}" {{ request('doctor_id') == $doc->id ? 'selected' : '' }}>
                            {{ $doc->user->name ?? $doc->staff_no }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Dijadualkan</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Disahkan</option>
                        <option value="arrived" {{ request('status') == 'arrived' ? 'selected' : '' }}>Telah Tiba</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        <option value="no_show" {{ request('status') == 'no_show' ? 'selected' : '' }}>Tidak Hadir</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-secondary">
                        <i class="mdi mdi-magnify"></i> Tapis
                    </button>
                    <a href="{{ route('admin.appointments.index', ['view' => 'all']) }}" class="btn btn-outline-info">
                        Lihat Semua
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Appointments Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Masa</th>
                            <th>No. Temujanji</th>
                            <th>Pesakit</th>
                            <th>Doktor</th>
                            <th>Jenis</th>
                            <th>Status</th>
                            <th class="text-end">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments as $apt)
                        <tr>
                            <td>
                                <strong>{{ $apt->formatted_time }}</strong>
                                <br><small class="text-muted">{{ $apt->appointment_date->format('d/m/Y') }}</small>
                            </td>
                            <td>
                                <a href="{{ route('admin.appointments.show', $apt) }}" class="fw-bold">
                                    {{ $apt->appointment_no }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('admin.patients.show', $apt->patient) }}">
                                    {{ $apt->patient->name }}
                                </a>
                                <br><small class="text-muted">{{ $apt->patient->mrn }}</small>
                            </td>
                            <td>{{ $apt->doctor?->user?->name ?? '-' }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ $apt->type_label }}</span>
                                @if($apt->priority === 'urgent')
                                <span class="badge bg-danger">Segera</span>
                                @endif
                            </td>
                            <td>
                                @switch($apt->status)
                                    @case('scheduled')
                                        <span class="badge bg-secondary">Dijadualkan</span>
                                        @break
                                    @case('confirmed')
                                        <span class="badge bg-info">Disahkan</span>
                                        @break
                                    @case('arrived')
                                        <span class="badge bg-primary">Tiba</span>
                                        @break
                                    @case('in_progress')
                                        <span class="badge bg-warning">Sedang Berlangsung</span>
                                        @break
                                    @case('completed')
                                        <span class="badge bg-success">Selesai</span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge bg-danger">Dibatalkan</span>
                                        @break
                                    @case('no_show')
                                        <span class="badge bg-dark">Tidak Hadir</span>
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
                                            <a class="dropdown-item" href="{{ route('admin.appointments.show', $apt) }}">
                                                <i class="mdi mdi-eye me-2"></i> Lihat
                                            </a>
                                        </li>
                                        @if($apt->status === 'scheduled')
                                        <li>
                                            <form action="{{ route('admin.appointments.confirm', $apt) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="dropdown-item text-info">
                                                    <i class="mdi mdi-check me-2"></i> Sahkan
                                                </button>
                                            </form>
                                        </li>
                                        @endif
                                        @if(in_array($apt->status, ['scheduled', 'confirmed']))
                                        <li>
                                            <form action="{{ route('admin.appointments.arrived', $apt) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="dropdown-item text-primary">
                                                    <i class="mdi mdi-account-check me-2"></i> Tanda Tiba
                                                </button>
                                            </form>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.appointments.edit', $apt) }}">
                                                <i class="mdi mdi-pencil me-2"></i> Edit
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <button type="button" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#cancelModal{{ $apt->id }}">
                                                <i class="mdi mdi-close-circle me-2"></i> Batalkan
                                            </button>
                                        </li>
                                        @endif
                                        @if($apt->appointment_date->isToday() && in_array($apt->status, ['scheduled', 'confirmed']))
                                        <li>
                                            <form action="{{ route('admin.appointments.noShow', $apt) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="dropdown-item text-warning" onclick="return confirm('Tandakan sebagai tidak hadir?')">
                                                    <i class="mdi mdi-account-remove me-2"></i> Tidak Hadir
                                                </button>
                                            </form>
                                        </li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>

                        <!-- Cancel Modal -->
                        @if($apt->canBeCancelled())
                        <div class="modal fade" id="cancelModal{{ $apt->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.appointments.cancel', $apt) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Batalkan Temujanji</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Adakah anda pasti ingin membatalkan temujanji <strong>{{ $apt->appointment_no }}</strong>?</p>
                                            <div class="mb-3">
                                                <label class="form-label">Sebab Pembatalan <span class="text-danger">*</span></label>
                                                <textarea name="cancellation_reason" class="form-control" rows="3" required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                                            <button type="submit" class="btn btn-danger">Ya, Batalkan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="mdi mdi-calendar-blank mdi-48px text-muted"></i>
                                <p class="text-muted mb-0">Tiada temujanji untuk tarikh ini</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $appointments->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
