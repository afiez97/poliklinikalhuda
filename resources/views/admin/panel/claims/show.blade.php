@extends('layouts.admin')

@section('title', 'Tuntutan: ' . $claim->claim_number)

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between">
        <div>
            <h1>{{ $claim->claim_number }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.panel.claims.index') }}">Tuntutan</a></li>
                    <li class="breadcrumb-item active">{{ $claim->claim_number }}</li>
                </ol>
            </nav>
        </div>
        <div>
            @if($claim->status == 'draft')
            <form action="{{ route('admin.panel.claims.submit', $claim) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success" onclick="return confirm('Hantar tuntutan ini?')">
                    <i class="bi bi-send me-1"></i> Hantar
                </button>
            </form>
            <a href="{{ route('admin.panel.claims.edit', $claim) }}" class="btn btn-primary">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
            @endif
            @if(in_array($claim->status, ['approved', 'partial']))
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#paymentModal">
                <i class="bi bi-cash me-1"></i> Rekod Bayaran
            </button>
            @endif
            <a href="{{ route('admin.panel.claims.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Maklumat Tuntutan -->
            <div class="card card-default">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2><i class="bi bi-receipt me-2"></i>Maklumat Tuntutan</h2>
                    @php
                        $statusColors = [
                            'draft' => 'secondary',
                            'submitted' => 'info',
                            'pending' => 'warning',
                            'approved' => 'success',
                            'rejected' => 'danger',
                            'paid' => 'primary',
                            'partial' => 'warning',
                            'appealed' => 'info',
                        ];
                    @endphp
                    <span class="badge bg-{{ $statusColors[$claim->status] ?? 'secondary' }} fs-6">
                        {{ $claim->status_name }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td class="text-muted" width="40%">No. Tuntutan:</td>
                                    <td><code>{{ $claim->claim_number }}</code></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Panel:</td>
                                    <td>
                                        <a href="{{ route('admin.panel.panels.show', $claim->panel) }}">
                                            {{ $claim->panel->panel_name ?? '-' }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">No. Invois:</td>
                                    <td>
                                        @if($claim->invoice)
                                        <a href="{{ route('admin.billing.invoices.show', $claim->invoice) }}">
                                            {{ $claim->invoice->invoice_no }}
                                        </a>
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">No. GL:</td>
                                    <td>
                                        @if($claim->guaranteeLetter)
                                        <a href="{{ route('admin.panel.gl.show', $claim->guaranteeLetter) }}">
                                            {{ $claim->guaranteeLetter->gl_number }}
                                        </a>
                                        @else
                                        <span class="text-muted">Tiada GL</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Tarikh Tuntutan:</td>
                                    <td>{{ $claim->claim_date->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Tarikh Rawatan:</td>
                                    <td>{{ $claim->treatment_date?->format('d/m/Y') ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td class="text-muted" width="40%">Tarikh Hantar:</td>
                                    <td>{{ $claim->submitted_at?->format('d/m/Y H:i') ?? '-' }}</td>
                                </tr>
                                @if($claim->processed_at)
                                <tr>
                                    <td class="text-muted">Tarikh Proses:</td>
                                    <td>{{ $claim->processed_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                @endif
                                @if($claim->due_date)
                                <tr>
                                    <td class="text-muted">Tarikh Jangka Bayar:</td>
                                    <td>
                                        {{ $claim->due_date->format('d/m/Y') }}
                                        @if($claim->isOverdue())
                                        <span class="badge bg-danger ms-1">Lewat</span>
                                        @endif
                                    </td>
                                </tr>
                                @endif
                                @if($claim->paid_at)
                                <tr>
                                    <td class="text-muted">Tarikh Bayar:</td>
                                    <td>{{ $claim->paid_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    <!-- Amaun -->
                    <hr>
                    <div class="row text-center">
                        <div class="col-md-4">
                            <h4>RM {{ number_format($claim->claim_amount, 2) }}</h4>
                            <small class="text-muted">Amaun Tuntutan</small>
                        </div>
                        <div class="col-md-4">
                            <h4 class="text-{{ $claim->approved_amount ? 'success' : 'muted' }}">
                                RM {{ number_format($claim->approved_amount ?? 0, 2) }}
                            </h4>
                            <small class="text-muted">Amaun Diluluskan</small>
                        </div>
                        <div class="col-md-4">
                            <h4 class="text-{{ $claim->paid_amount ? 'primary' : 'muted' }}">
                                RM {{ number_format($claim->paid_amount ?? 0, 2) }}
                            </h4>
                            <small class="text-muted">Amaun Dibayar</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Diagnosis & Rawatan -->
            @if($claim->diagnosis || $claim->treatment)
            <div class="card card-default mt-4">
                <div class="card-header">
                    <h2><i class="bi bi-journal-medical me-2"></i>Maklumat Klinikal</h2>
                </div>
                <div class="card-body">
                    @if($claim->diagnosis)
                    <div class="mb-3">
                        <strong>Diagnosis:</strong>
                        <p class="mb-0">{{ $claim->diagnosis }}</p>
                    </div>
                    @endif
                    @if($claim->treatment)
                    <div class="mb-0">
                        <strong>Rawatan:</strong>
                        <p class="mb-0">{{ $claim->treatment }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Dokumen -->
            <div class="card card-default mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2><i class="bi bi-paperclip me-2"></i>Dokumen Sokongan</h2>
                    @if($claim->status == 'draft')
                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#uploadDocModal">
                        <i class="bi bi-upload me-1"></i> Muat Naik
                    </button>
                    @endif
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Dokumen</th>
                                    <th>Jenis</th>
                                    <th>Saiz</th>
                                    <th>Tarikh Muat Naik</th>
                                    <th class="text-center">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($claim->documents as $doc)
                                <tr>
                                    <td>{{ $doc->original_name }}</td>
                                    <td>{{ strtoupper($doc->file_extension) }}</td>
                                    <td>{{ number_format($doc->file_size / 1024, 2) }} KB</td>
                                    <td>{{ $doc->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="text-center">
                                        <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3 text-muted">Tiada dokumen.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Sejarah Status -->
            <div class="card card-default mt-4">
                <div class="card-header">
                    <h2><i class="bi bi-clock-history me-2"></i>Sejarah Status</h2>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @if($claim->paid_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <strong>Dibayar</strong>
                                <p class="text-muted mb-0">{{ $claim->paid_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        @endif
                        @if($claim->processed_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-{{ $claim->status == 'approved' ? 'success' : ($claim->status == 'rejected' ? 'danger' : 'warning') }}"></div>
                            <div class="timeline-content">
                                <strong>{{ $claim->status == 'approved' ? 'Diluluskan' : ($claim->status == 'rejected' ? 'Ditolak' : 'Diproses') }}</strong>
                                <p class="text-muted mb-0">{{ $claim->processed_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        @endif
                        @if($claim->submitted_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <strong>Dihantar</strong>
                                <p class="text-muted mb-0">{{ $claim->submitted_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        @endif
                        <div class="timeline-item">
                            <div class="timeline-marker bg-secondary"></div>
                            <div class="timeline-content">
                                <strong>Dicipta</strong>
                                <p class="text-muted mb-0">{{ $claim->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
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
                    @if($claim->patient)
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td class="text-muted">Nama:</td>
                            <td class="fw-semibold">{{ $claim->patient->name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">No. MRN:</td>
                            <td><code>{{ $claim->patient->mrn }}</code></td>
                        </tr>
                        <tr>
                            <td class="text-muted">No. IC:</td>
                            <td>{{ $claim->patient->ic_number ?? '-' }}</td>
                        </tr>
                    </table>
                    <a href="{{ route('admin.patients.show', $claim->patient) }}" class="btn btn-sm btn-outline-primary w-100">
                        Lihat Profil Pesakit
                    </a>
                    @else
                    <p class="text-muted mb-0">Maklumat pesakit tidak tersedia.</p>
                    @endif
                </div>
            </div>

            <!-- Panel Employee -->
            @if($claim->panelEmployee)
            <div class="card card-default mt-4">
                <div class="card-header">
                    <h2><i class="bi bi-person-badge me-2"></i>Pekerja Panel</h2>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td class="text-muted">Nama:</td>
                            <td>{{ $claim->panelEmployee->name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">ID Pekerja:</td>
                            <td>{{ $claim->panelEmployee->employee_id }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Jabatan:</td>
                            <td>{{ $claim->panelEmployee->department ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            @endif

            <!-- Rejection Info -->
            @if($claim->status == 'rejected' && $claim->rejections->count())
            <div class="card card-default mt-4 border-danger">
                <div class="card-header bg-danger text-white">
                    <h2><i class="bi bi-x-circle me-2"></i>Maklumat Penolakan</h2>
                </div>
                <div class="card-body">
                    @php $latestRejection = $claim->rejections->first(); @endphp
                    <p><strong>Sebab:</strong><br>{{ $latestRejection->reason }}</p>
                    @if($latestRejection->rejection_code)
                    <p class="mb-0"><strong>Kod:</strong> {{ $latestRejection->rejection_code }}</p>
                    @endif
                </div>
            </div>
            @endif

            <!-- Notes -->
            @if($claim->notes)
            <div class="card card-default mt-4">
                <div class="card-header">
                    <h2><i class="bi bi-sticky me-2"></i>Nota</h2>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $claim->notes }}</p>
                </div>
            </div>
            @endif

            <!-- Quick Actions -->
            @if(in_array($claim->status, ['submitted', 'pending']))
            <div class="card card-default mt-4">
                <div class="card-header">
                    <h2><i class="bi bi-lightning me-2"></i>Tindakan Pantas</h2>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveModal">
                            <i class="bi bi-check-circle me-1"></i> Luluskan
                        </button>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="bi bi-x-circle me-1"></i> Tolak
                        </button>
                    </div>
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
            <form action="{{ route('admin.panel.claims.approve', $claim) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Luluskan Tuntutan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Amaun Diluluskan (RM) <span class="text-danger">*</span></label>
                        <input type="number" name="approved_amount" class="form-control"
                               value="{{ $claim->claim_amount }}" step="0.01" min="0" max="{{ $claim->claim_amount }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nota</label>
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
            <form action="{{ route('admin.panel.claims.reject', $claim) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tolak Tuntutan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Sebab Penolakan <span class="text-danger">*</span></label>
                        <textarea name="reason" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kod Penolakan</label>
                        <input type="text" name="rejection_code" class="form-control" placeholder="cth: DOC_MISSING">
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

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.panel.claims.record-payment', $claim) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Rekod Bayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Amaun Bayaran (RM) <span class="text-danger">*</span></label>
                        <input type="number" name="amount" class="form-control"
                               value="{{ $claim->approved_amount - ($claim->paid_amount ?? 0) }}"
                               step="0.01" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tarikh Bayaran <span class="text-danger">*</span></label>
                        <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. Rujukan Bayaran</label>
                        <input type="text" name="reference" class="form-control" placeholder="cth: TRX123456">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nota</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i> Rekod Bayaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}
.timeline-item {
    position: relative;
    padding-bottom: 20px;
}
.timeline-item:last-child {
    padding-bottom: 0;
}
.timeline-item::before {
    content: '';
    position: absolute;
    left: -24px;
    top: 10px;
    bottom: -10px;
    width: 2px;
    background: #e9ecef;
}
.timeline-item:last-child::before {
    display: none;
}
.timeline-marker {
    position: absolute;
    left: -30px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
}
</style>
@endsection
