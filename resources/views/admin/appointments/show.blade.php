@extends('layouts.admin')
@section('title', 'Butiran Temujanji')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper breadcrumb-contacts">
        <div>
            <h1>Butiran Temujanji</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span><a href="{{ route('admin.appointments') }}">Temujanji</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>{{ $appointment->appointment_no }}</span>
            </p>
        </div>
        <div>
            @if($appointment->canBeRescheduled())
            <a href="{{ route('admin.appointments.edit', $appointment) }}" class="btn btn-outline-primary me-2">
                <i class="mdi mdi-pencil"></i> Edit
            </a>
            @endif
            <a href="{{ route('admin.appointments') }}" class="btn btn-secondary">
                <i class="mdi mdi-arrow-left"></i> Kembali
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

    <div class="row">
        <div class="col-lg-8">
            <!-- Appointment Details -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Maklumat Temujanji</h5>
                    @switch($appointment->status)
                        @case('scheduled')
                            <span class="badge bg-secondary fs-6">Dijadualkan</span>
                            @break
                        @case('confirmed')
                            <span class="badge bg-info fs-6">Disahkan</span>
                            @break
                        @case('arrived')
                            <span class="badge bg-primary fs-6">Telah Tiba</span>
                            @break
                        @case('in_progress')
                            <span class="badge bg-warning fs-6">Sedang Berlangsung</span>
                            @break
                        @case('completed')
                            <span class="badge bg-success fs-6">Selesai</span>
                            @break
                        @case('cancelled')
                            <span class="badge bg-danger fs-6">Dibatalkan</span>
                            @break
                        @case('no_show')
                            <span class="badge bg-dark fs-6">Tidak Hadir</span>
                            @break
                        @case('rescheduled')
                            <span class="badge bg-warning fs-6">Dijadualkan Semula</span>
                            @break
                    @endswitch
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">No. Temujanji</th>
                                    <td><strong>{{ $appointment->appointment_no }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Tarikh</th>
                                    <td>{{ $appointment->appointment_date->translatedFormat('l, d F Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Masa</th>
                                    <td>{{ $appointment->formatted_time }}</td>
                                </tr>
                                <tr>
                                    <th>Tempoh</th>
                                    <td>{{ $appointment->duration_minutes }} minit</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Jenis</th>
                                    <td>
                                        <span class="badge bg-secondary">{{ $appointment->type_label }}</span>
                                        @if($appointment->priority === 'urgent')
                                        <span class="badge bg-danger">Segera</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Doktor</th>
                                    <td>{{ $appointment->doctor?->user?->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Sumber Tempahan</th>
                                    <td>{{ ucfirst($appointment->booking_source) }}</td>
                                </tr>
                                <tr>
                                    <th>Panel</th>
                                    <td>{{ $appointment->is_panel ? 'Ya' : 'Tidak' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($appointment->reason)
                    <div class="mt-3">
                        <strong>Sebab Lawatan:</strong>
                        <p class="mb-0">{{ $appointment->reason }}</p>
                    </div>
                    @endif

                    @if($appointment->notes)
                    <div class="mt-3">
                        <strong>Nota:</strong>
                        <p class="mb-0">{{ $appointment->notes }}</p>
                    </div>
                    @endif

                    @if($appointment->status === 'cancelled' && $appointment->cancellation_reason)
                    <div class="alert alert-danger mt-3 mb-0">
                        <strong>Sebab Pembatalan:</strong> {{ $appointment->cancellation_reason }}
                        <br><small>Dibatalkan oleh: {{ $appointment->canceller?->name ?? '-' }}</small>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Patient Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Maklumat Pesakit</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-lg me-3">
                            <span class="avatar-initial rounded-circle bg-primary">
                                {{ strtoupper(substr($appointment->patient->name, 0, 2)) }}
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-1">
                                <a href="{{ route('admin.patients.show', $appointment->patient) }}">
                                    {{ $appointment->patient->name }}
                                </a>
                            </h5>
                            <p class="mb-0 text-muted">
                                MRN: {{ $appointment->patient->mrn }} |
                                IC: {{ $appointment->patient->ic_number }} |
                                Tel: {{ $appointment->patient->phone ?? '-' }}
                            </p>
                        </div>
                        <a href="{{ route('admin.patients.show', $appointment->patient) }}" class="btn btn-outline-primary btn-sm">
                            Lihat Profil
                        </a>
                    </div>
                </div>
            </div>

            <!-- Timeline / History -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Sejarah</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex mb-3">
                            <div class="me-3">
                                <span class="badge bg-secondary rounded-circle p-2">
                                    <i class="mdi mdi-plus"></i>
                                </span>
                            </div>
                            <div>
                                <strong>Temujanji dicipta</strong>
                                <br><small class="text-muted">{{ $appointment->created_at->translatedFormat('d M Y, H:i') }} oleh {{ $appointment->creator?->name ?? 'Sistem' }}</small>
                            </div>
                        </li>
                        @if($appointment->status === 'confirmed')
                        <li class="d-flex mb-3">
                            <div class="me-3">
                                <span class="badge bg-info rounded-circle p-2">
                                    <i class="mdi mdi-check"></i>
                                </span>
                            </div>
                            <div>
                                <strong>Temujanji disahkan</strong>
                                <br><small class="text-muted">{{ $appointment->confirmed_at?->translatedFormat('d M Y, H:i') ?? '-' }}</small>
                            </div>
                        </li>
                        @endif
                        @if($appointment->arrived_at)
                        <li class="d-flex mb-3">
                            <div class="me-3">
                                <span class="badge bg-primary rounded-circle p-2">
                                    <i class="mdi mdi-account-check"></i>
                                </span>
                            </div>
                            <div>
                                <strong>Pesakit tiba</strong>
                                <br><small class="text-muted">{{ $appointment->arrived_at->translatedFormat('d M Y, H:i') }}</small>
                            </div>
                        </li>
                        @endif
                        @if($appointment->status === 'completed')
                        <li class="d-flex mb-3">
                            <div class="me-3">
                                <span class="badge bg-success rounded-circle p-2">
                                    <i class="mdi mdi-check-all"></i>
                                </span>
                            </div>
                            <div>
                                <strong>Temujanji selesai</strong>
                                <br><small class="text-muted">{{ $appointment->completed_at?->translatedFormat('d M Y, H:i') ?? '-' }}</small>
                            </div>
                        </li>
                        @endif
                        @if($appointment->status === 'cancelled')
                        <li class="d-flex mb-3">
                            <div class="me-3">
                                <span class="badge bg-danger rounded-circle p-2">
                                    <i class="mdi mdi-close"></i>
                                </span>
                            </div>
                            <div>
                                <strong>Temujanji dibatalkan</strong>
                                <br><small class="text-muted">{{ $appointment->cancelled_at?->translatedFormat('d M Y, H:i') ?? '-' }}</small>
                            </div>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Tindakan Pantas</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($appointment->status === 'scheduled')
                        <form action="{{ route('admin.appointments.confirm', $appointment) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-info w-100">
                                <i class="mdi mdi-check me-1"></i> Sahkan Temujanji
                            </button>
                        </form>
                        @endif

                        @if(in_array($appointment->status, ['scheduled', 'confirmed']))
                        <form action="{{ route('admin.appointments.arrived', $appointment) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="mdi mdi-account-check me-1"></i> Tanda Pesakit Tiba
                            </button>
                        </form>
                        @endif

                        @if($appointment->status === 'arrived')
                        <a href="{{ route('admin.emr.encounters.create', ['patient_id' => $appointment->patient_id, 'appointment_id' => $appointment->id]) }}" class="btn btn-success w-100">
                            <i class="mdi mdi-stethoscope me-1"></i> Mula Encounter
                        </a>
                        @endif

                        @if($appointment->canBeCancelled())
                        <button type="button" class="btn btn-outline-danger w-100" data-bs-toggle="modal" data-bs-target="#cancelModal">
                            <i class="mdi mdi-close-circle me-1"></i> Batalkan Temujanji
                        </button>
                        @endif

                        @if($appointment->appointment_date->isToday() && in_array($appointment->status, ['scheduled', 'confirmed']))
                        <form action="{{ route('admin.appointments.noShow', $appointment) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-outline-warning w-100" onclick="return confirm('Tandakan sebagai tidak hadir?')">
                                <i class="mdi mdi-account-remove me-1"></i> Tanda Tidak Hadir
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Related Visit -->
            @if($appointment->visit)
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Lawatan Berkaitan</h6>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>No. Lawatan:</strong> {{ $appointment->visit->visit_no }}</p>
                    <p class="mb-1"><strong>Status:</strong> {{ $appointment->visit->status_label }}</p>
                    <a href="{{ route('admin.patients.visits.show', [$appointment->patient, $appointment->visit]) }}" class="btn btn-sm btn-outline-primary mt-2">
                        Lihat Lawatan
                    </a>
                </div>
            </div>
            @endif

            <!-- Rescheduled From -->
            @if($appointment->originalAppointment)
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Dijadualkan Semula Dari</h6>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>No. Asal:</strong> {{ $appointment->originalAppointment->appointment_no }}</p>
                    <p class="mb-0"><strong>Tarikh Asal:</strong> {{ $appointment->originalAppointment->appointment_date->format('d/m/Y') }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Cancel Modal -->
@if($appointment->canBeCancelled())
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.appointments.cancel', $appointment) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title">Batalkan Temujanji</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Adakah anda pasti ingin membatalkan temujanji <strong>{{ $appointment->appointment_no }}</strong>?</p>
                    <div class="mb-3">
                        <label class="form-label">Sebab Pembatalan <span class="text-danger">*</span></label>
                        <textarea name="cancellation_reason" class="form-control" rows="3" required placeholder="Nyatakan sebab pembatalan..."></textarea>
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
@endsection
