@extends('layouts.admin')

@section('title', 'PA: ' . $pa->pa_number)

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between">
        <div>
            <h1>{{ $pa->pa_number }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.panel.pa.index') }}">Pre-Authorization</a></li>
                    <li class="breadcrumb-item active">{{ $pa->pa_number }}</li>
                </ol>
            </nav>
        </div>
        <div>
            @if($pa->status == 'pending')
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveModal">
                <i class="bi bi-check-circle me-1"></i> Luluskan
            </button>
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                <i class="bi bi-x-circle me-1"></i> Tolak
            </button>
            @endif
            <a href="{{ route('admin.panel.pa.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Maklumat PA -->
            <div class="card card-default">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2><i class="bi bi-file-earmark-check me-2"></i>Maklumat Pre-Authorization</h2>
                    @php
                        $statusColors = [
                            'pending' => 'warning',
                            'approved' => 'success',
                            'rejected' => 'danger',
                            'expired' => 'secondary',
                            'used' => 'info',
                        ];
                    @endphp
                    <span class="badge bg-{{ $statusColors[$pa->status] ?? 'secondary' }} fs-6">
                        {{ $pa->status_name }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td class="text-muted" width="40%">No. PA:</td>
                                    <td><code>{{ $pa->pa_number }}</code></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Panel:</td>
                                    <td>
                                        <a href="{{ route('admin.panel.panels.show', $pa->panel) }}">
                                            {{ $pa->panel->panel_name ?? '-' }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Jenis Permintaan:</td>
                                    <td>
                                        @php
                                            $typeColors = [
                                                'procedure' => 'primary',
                                                'medication' => 'success',
                                                'investigation' => 'info',
                                                'referral' => 'warning',
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $typeColors[$pa->request_type] ?? 'secondary' }}">
                                            {{ $pa->type_name }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Tarikh Permintaan:</td>
                                    <td>{{ $pa->request_date->format('d/m/Y') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                @if($pa->approved_at)
                                <tr>
                                    <td class="text-muted" width="40%">Tarikh Kelulusan:</td>
                                    <td>{{ $pa->approved_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                @endif
                                @if($pa->validity_end)
                                <tr>
                                    <td class="text-muted">Sah Hingga:</td>
                                    <td>
                                        {{ $pa->validity_end->format('d/m/Y') }}
                                        @if($pa->isExpired())
                                        <span class="badge bg-danger ms-1">Tamat Tempoh</span>
                                        @elseif($pa->isExpiringSoon())
                                        <span class="badge bg-warning text-dark ms-1">{{ $pa->validity_end->diffForHumans() }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @endif
                                @if($pa->approvedByUser)
                                <tr>
                                    <td class="text-muted">Diluluskan Oleh:</td>
                                    <td>{{ $pa->approvedByUser->name }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    <!-- Perkara & Kos -->
                    <hr>
                    <div class="mb-3">
                        <strong>Perkara Diminta:</strong>
                        <p class="mb-0">{{ $pa->request_description }}</p>
                    </div>

                    @if($pa->clinical_notes)
                    <div class="mb-3">
                        <strong>Nota Klinikal:</strong>
                        <p class="mb-0">{{ $pa->clinical_notes }}</p>
                    </div>
                    @endif

                    <div class="row text-center mt-4">
                        <div class="col-md-6">
                            <h4>RM {{ number_format($pa->estimated_cost, 2) }}</h4>
                            <small class="text-muted">Anggaran Kos</small>
                        </div>
                        <div class="col-md-6">
                            <h4 class="text-{{ $pa->approved_amount ? 'success' : 'muted' }}">
                                RM {{ number_format($pa->approved_amount ?? 0, 2) }}
                            </h4>
                            <small class="text-muted">Amaun Diluluskan</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rejection Info -->
            @if($pa->status == 'rejected')
            <div class="card card-default mt-4 border-danger">
                <div class="card-header bg-danger text-white">
                    <h2><i class="bi bi-x-circle me-2"></i>Maklumat Penolakan</h2>
                </div>
                <div class="card-body">
                    <p><strong>Sebab:</strong><br>{{ $pa->rejection_reason }}</p>
                    @if($pa->rejectedByUser)
                    <p class="mb-0"><strong>Ditolak Oleh:</strong> {{ $pa->rejectedByUser->name }}</p>
                    @endif
                </div>
            </div>
            @endif

            <!-- Related GL -->
            @if($pa->guaranteeLetter)
            <div class="card card-default mt-4">
                <div class="card-header">
                    <h2><i class="bi bi-file-earmark-text me-2"></i>Guarantee Letter Berkaitan</h2>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td class="text-muted" width="30%">No. GL:</td>
                            <td>
                                <a href="{{ route('admin.panel.gl.show', $pa->guaranteeLetter) }}">
                                    {{ $pa->guaranteeLetter->gl_number }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Had Liputan:</td>
                            <td>RM {{ number_format($pa->guaranteeLetter->coverage_limit, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Baki:</td>
                            <td>RM {{ number_format($pa->guaranteeLetter->amount_balance, 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Pesakit -->
            <div class="card card-default">
                <div class="card-header">
                    <h2><i class="bi bi-person me-2"></i>Maklumat Pesakit</h2>
                </div>
                <div class="card-body">
                    @if($pa->patient)
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td class="text-muted">Nama:</td>
                            <td class="fw-semibold">{{ $pa->patient->name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">No. MRN:</td>
                            <td><code>{{ $pa->patient->mrn }}</code></td>
                        </tr>
                        <tr>
                            <td class="text-muted">No. IC:</td>
                            <td>{{ $pa->patient->ic_number ?? '-' }}</td>
                        </tr>
                    </table>
                    <a href="{{ route('admin.patients.show', $pa->patient) }}" class="btn btn-sm btn-outline-primary w-100">
                        Lihat Profil Pesakit
                    </a>
                    @else
                    <p class="text-muted mb-0">Maklumat pesakit tidak tersedia.</p>
                    @endif
                </div>
            </div>

            <!-- Pekerja Panel -->
            @if($pa->panelEmployee)
            <div class="card card-default mt-4">
                <div class="card-header">
                    <h2><i class="bi bi-person-badge me-2"></i>Pekerja Panel</h2>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td class="text-muted">Nama:</td>
                            <td>{{ $pa->panelEmployee->name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">ID Pekerja:</td>
                            <td>{{ $pa->panelEmployee->employee_id }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            @endif

            <!-- Doktor -->
            @if($pa->requestedByUser)
            <div class="card card-default mt-4">
                <div class="card-header">
                    <h2><i class="bi bi-person-check me-2"></i>Diminta Oleh</h2>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $pa->requestedByUser->name }}</p>
                </div>
            </div>
            @endif

            <!-- Approval Notes -->
            @if($pa->approval_notes)
            <div class="card card-default mt-4">
                <div class="card-header">
                    <h2><i class="bi bi-sticky me-2"></i>Nota Kelulusan</h2>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $pa->approval_notes }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.panel.pa.approve', $pa) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Luluskan Pre-Authorization</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Amaun Diluluskan (RM)</label>
                        <input type="number" name="approved_amount" class="form-control"
                               value="{{ $pa->estimated_cost }}" step="0.01" min="0">
                        <small class="text-muted">Biarkan kosong untuk meluluskan tanpa had.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sah Hingga <span class="text-danger">*</span></label>
                        <input type="date" name="validity_end" class="form-control"
                               value="{{ date('Y-m-d', strtotime('+30 days')) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nota Kelulusan</label>
                        <textarea name="notes" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i> Luluskan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.panel.pa.reject', $pa) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tolak Pre-Authorization</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Sebab Penolakan <span class="text-danger">*</span></label>
                        <textarea name="rejection_reason" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-x-circle me-1"></i> Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
