@extends('layouts.admin')

@section('title', 'GL: ' . $gl->gl_number)

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between">
        <div>
            <h1>{{ $gl->gl_number }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.panel.gl.index') }}">Guarantee Letter</a></li>
                    <li class="breadcrumb-item active">{{ $gl->gl_number }}</li>
                </ol>
            </nav>
        </div>
        <div>
            @if($gl->verification_status === 'pending')
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#verifyModal">
                <i class="bi bi-check-circle me-1"></i> Sahkan GL
            </button>
            @endif
            <a href="{{ route('admin.panel.gl.edit', $gl) }}" class="btn btn-primary">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
            <a href="{{ route('admin.panel.gl.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Maklumat GL -->
            <div class="card card-default">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2><i class="bi bi-file-earmark-text me-2"></i>Maklumat GL</h2>
                    @php
                        $statusColors = [
                            'active' => 'success',
                            'utilized' => 'info',
                            'expired' => 'secondary',
                            'cancelled' => 'danger',
                        ];
                    @endphp
                    <span class="badge bg-{{ $statusColors[$gl->status] ?? 'secondary' }} fs-6">
                        {{ $gl->status_name }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td class="text-muted" width="40%">No. GL:</td>
                                    <td><code>{{ $gl->gl_number }}</code></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Panel:</td>
                                    <td>
                                        <a href="{{ route('admin.panel.panels.show', $gl->panel) }}">
                                            {{ $gl->panel->panel_name ?? '-' }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Tarikh Mula:</td>
                                    <td>{{ $gl->effective_date->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Tarikh Tamat:</td>
                                    <td>
                                        {{ $gl->expiry_date->format('d/m/Y') }}
                                        @if($gl->isExpired())
                                        <span class="badge bg-danger ms-1">Tamat Tempoh</span>
                                        @elseif($gl->isExpiringSoon())
                                        <span class="badge bg-warning text-dark ms-1">{{ $gl->expiry_date->diffForHumans() }}</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td class="text-muted" width="40%">Pengesahan:</td>
                                    <td>
                                        @php
                                            $verificationColors = [
                                                'pending' => 'warning',
                                                'verified' => 'success',
                                                'rejected' => 'danger',
                                                'expired' => 'secondary',
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $verificationColors[$gl->verification_status] ?? 'secondary' }}">
                                            {{ $gl->verification_status_name }}
                                        </span>
                                    </td>
                                </tr>
                                @if($gl->verification_status === 'verified')
                                <tr>
                                    <td class="text-muted">Disahkan Oleh:</td>
                                    <td>{{ $gl->verifiedByUser->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Tarikh Sahkan:</td>
                                    <td>{{ $gl->verified_at?->format('d/m/Y H:i') ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Kaedah:</td>
                                    <td>{{ $gl->method_name ?? '-' }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    @if($gl->document_path)
                    <hr>
                    <div>
                        <strong>Dokumen GL:</strong>
                        <a href="{{ Storage::url($gl->document_path) }}" target="_blank" class="btn btn-sm btn-outline-primary ms-2">
                            <i class="bi bi-file-earmark-pdf me-1"></i> Lihat Dokumen
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Had Manfaat -->
            <div class="card card-default mt-4">
                <div class="card-header">
                    <h2><i class="bi bi-graph-up me-2"></i>Had Manfaat</h2>
                </div>
                <div class="card-body">
                    @php
                        $percentage = $gl->utilization_percentage;
                        $colorClass = match($gl->utilization_level) {
                            'exceeded' => 'danger',
                            'critical' => 'danger',
                            'warning' => 'warning',
                            default => 'success',
                        };
                    @endphp
                    <div class="row mb-3">
                        <div class="col-md-4 text-center">
                            <h4>RM {{ number_format($gl->coverage_limit, 2) }}</h4>
                            <small class="text-muted">Had Liputan</small>
                        </div>
                        <div class="col-md-4 text-center">
                            <h4 class="text-{{ $colorClass }}">RM {{ number_format($gl->amount_used, 2) }}</h4>
                            <small class="text-muted">Digunakan</small>
                        </div>
                        <div class="col-md-4 text-center">
                            <h4>RM {{ number_format($gl->amount_balance, 2) }}</h4>
                            <small class="text-muted">Baki</small>
                        </div>
                    </div>
                    <div class="progress" style="height: 25px;">
                        <div class="progress-bar bg-{{ $colorClass }}" role="progressbar"
                             style="width: {{ min($percentage, 100) }}%">
                            {{ $percentage }}%
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sejarah Penggunaan -->
            <div class="card card-default mt-4">
                <div class="card-header">
                    <h2><i class="bi bi-clock-history me-2"></i>Sejarah Penggunaan</h2>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Tarikh</th>
                                    <th>Rujukan</th>
                                    <th>Jenis</th>
                                    <th class="text-end">Amaun</th>
                                    <th class="text-end">Baki</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($gl->utilizations as $util)
                                <tr>
                                    <td>{{ $util->utilization_date->format('d/m/Y') }}</td>
                                    <td>{{ $util->invoice?->invoice_no ?? '-' }}</td>
                                    <td>{{ ucfirst($util->reference_type) }}</td>
                                    <td class="text-end">RM {{ number_format($util->amount, 2) }}</td>
                                    <td class="text-end">RM {{ number_format($util->running_balance, 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3 text-muted">Tiada rekod penggunaan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Pesakit -->
            <div class="card card-default">
                <div class="card-header">
                    <h2><i class="bi bi-person me-2"></i>Maklumat Pesakit</h2>
                </div>
                <div class="card-body">
                    @if($gl->patient)
                    <div class="text-center mb-3">
                        <div class="avatar avatar-lg bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <span class="fs-4">{{ strtoupper(substr($gl->patient->name, 0, 1)) }}</span>
                        </div>
                    </div>
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td class="text-muted">Nama:</td>
                            <td class="fw-semibold">{{ $gl->patient->name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">No. MRN:</td>
                            <td><code>{{ $gl->patient->mrn }}</code></td>
                        </tr>
                        <tr>
                            <td class="text-muted">No. IC:</td>
                            <td>{{ $gl->patient->ic_number ?? '-' }}</td>
                        </tr>
                    </table>
                    <a href="{{ route('admin.patients.show', $gl->patient) }}" class="btn btn-sm btn-outline-primary w-100">
                        Lihat Profil Pesakit
                    </a>
                    @else
                    <p class="text-muted mb-0">Maklumat pesakit tidak tersedia.</p>
                    @endif
                </div>
            </div>

            <!-- Tuntutan Berkaitan -->
            <div class="card card-default mt-4">
                <div class="card-header">
                    <h2><i class="bi bi-receipt me-2"></i>Tuntutan Berkaitan</h2>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($gl->claims->take(5) as $claim)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <a href="{{ route('admin.panel.claims.show', $claim) }}">{{ $claim->claim_number }}</a>
                                <br><small class="text-muted">{{ $claim->claim_date->format('d/m/Y') }}</small>
                            </div>
                            <span class="badge bg-{{ $claim->claim_status == 'paid' ? 'success' : 'secondary' }}">
                                {{ $claim->status_name }}
                            </span>
                        </li>
                        @empty
                        <li class="list-group-item text-center text-muted">Tiada tuntutan.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            @if($gl->diagnoses_covered || $gl->special_remarks)
            <!-- Nota -->
            <div class="card card-default mt-4">
                <div class="card-header">
                    <h2><i class="bi bi-sticky me-2"></i>Nota</h2>
                </div>
                <div class="card-body">
                    @if($gl->diagnoses_covered)
                    <p><strong>Diagnosis Dilindungi:</strong><br>{{ $gl->diagnoses_covered }}</p>
                    @endif
                    @if($gl->special_remarks)
                    <p class="mb-0"><strong>Catatan Khas:</strong><br>{{ $gl->special_remarks }}</p>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Sahkan GL -->
<div class="modal fade" id="verifyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.panel.gl.verify', $gl) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Sahkan Guarantee Letter</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kaedah Pengesahan <span class="text-danger">*</span></label>
                        <select name="verification_method" class="form-select" required>
                            @foreach(\App\Models\GuaranteeLetter::METHODS as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Pengesah (dari Panel)</label>
                        <input type="text" name="verification_person" class="form-control" placeholder="Nama pegawai panel">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nota Pengesahan</label>
                        <textarea name="verification_notes" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i> Sahkan GL
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
