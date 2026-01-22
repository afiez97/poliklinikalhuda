@extends('layouts.admin')

@section('title', 'Pre-Authorization')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between">
        <div>
            <h1>Pre-Authorization (PA)</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Pre-Authorization</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.panel.pa.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> PA Baru
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card card-default">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-warning">{{ $statistics['pending'] ?? 0 }}</h3>
                    <small class="text-muted">Menunggu Kelulusan</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-default">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-success">{{ $statistics['approved'] ?? 0 }}</h3>
                    <small class="text-muted">Diluluskan</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-default">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-danger">{{ $statistics['rejected'] ?? 0 }}</h3>
                    <small class="text-muted">Ditolak</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-default">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-secondary">{{ $statistics['expired'] ?? 0 }}</h3>
                    <small class="text-muted">Tamat Tempoh</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card card-default mb-4">
        <div class="card-body">
            <form action="{{ route('admin.panel.pa.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Carian</label>
                        <input type="text" name="search" class="form-control" placeholder="No. PA / Nama Pesakit..."
                               value="{{ $filters['search'] ?? '' }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Panel</label>
                        <select name="panel_id" class="form-select">
                            <option value="">Semua Panel</option>
                            @foreach($panels as $panel)
                            <option value="{{ $panel->id }}" {{ ($filters['panel_id'] ?? '') == $panel->id ? 'selected' : '' }}>
                                {{ $panel->panel_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ ($filters['status'] ?? '') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                            <option value="approved" {{ ($filters['status'] ?? '') == 'approved' ? 'selected' : '' }}>Diluluskan</option>
                            <option value="rejected" {{ ($filters['status'] ?? '') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                            <option value="expired" {{ ($filters['status'] ?? '') == 'expired' ? 'selected' : '' }}>Tamat Tempoh</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Jenis</label>
                        <select name="type" class="form-select">
                            <option value="">Semua Jenis</option>
                            <option value="procedure" {{ ($filters['type'] ?? '') == 'procedure' ? 'selected' : '' }}>Prosedur</option>
                            <option value="medication" {{ ($filters['type'] ?? '') == 'medication' ? 'selected' : '' }}>Ubatan</option>
                            <option value="investigation" {{ ($filters['type'] ?? '') == 'investigation' ? 'selected' : '' }}>Ujian</option>
                            <option value="referral" {{ ($filters['type'] ?? '') == 'referral' ? 'selected' : '' }}>Rujukan</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search me-1"></i> Cari
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- PA List -->
    <div class="card card-default">
        <div class="card-header">
            <h2>Senarai Pre-Authorization</h2>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No. PA</th>
                            <th>Pesakit</th>
                            <th>Panel</th>
                            <th>Jenis</th>
                            <th>Perkara</th>
                            <th class="text-end">Anggaran (RM)</th>
                            <th>Status</th>
                            <th>Tarikh</th>
                            <th class="text-center">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($preAuthorizations as $pa)
                        <tr>
                            <td>
                                <a href="{{ route('admin.panel.pa.show', $pa) }}">
                                    <code>{{ $pa->pa_number }}</code>
                                </a>
                            </td>
                            <td>
                                @if($pa->patient)
                                {{ $pa->patient->name }}
                                <br><small class="text-muted">{{ $pa->patient->mrn }}</small>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.panel.panels.show', $pa->panel) }}">
                                    {{ Str::limit($pa->panel->panel_name, 15) }}
                                </a>
                            </td>
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
                            <td>{{ Str::limit($pa->request_description, 30) }}</td>
                            <td class="text-end">
                                <strong>{{ number_format($pa->estimated_cost, 2) }}</strong>
                                @if($pa->approved_amount)
                                <br><small class="text-success">Lulus: {{ number_format($pa->approved_amount, 2) }}</small>
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusColors = [
                                        'pending' => 'warning',
                                        'approved' => 'success',
                                        'rejected' => 'danger',
                                        'expired' => 'secondary',
                                        'used' => 'info',
                                    ];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$pa->status] ?? 'secondary' }}">
                                    {{ $pa->status_name }}
                                </span>
                                @if($pa->status == 'approved' && $pa->isExpiringSoon())
                                <br><span class="badge bg-warning text-dark">{{ $pa->validity_end->diffForHumans() }}</span>
                                @endif
                            </td>
                            <td>
                                {{ $pa->request_date->format('d/m/Y') }}
                                @if($pa->approved_at)
                                <br><small class="text-success">Lulus: {{ $pa->approved_at->format('d/m/Y') }}</small>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.panel.pa.show', $pa) }}" class="btn btn-outline-primary" title="Lihat">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($pa->status == 'pending')
                                    <button type="button" class="btn btn-outline-success" title="Luluskan"
                                            onclick="quickApprove({{ $pa->id }})">
                                        <i class="bi bi-check"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger" title="Tolak"
                                            onclick="quickReject({{ $pa->id }})">
                                        <i class="bi bi-x"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Tiada pre-authorization dijumpai.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($preAuthorizations->hasPages())
        <div class="card-footer">
            {{ $preAuthorizations->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Quick Approve Modal -->
<div class="modal fade" id="quickApproveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="quickApproveForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Luluskan PA</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Amaun Diluluskan (RM)</label>
                        <input type="number" name="approved_amount" class="form-control" step="0.01" min="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sah Hingga</label>
                        <input type="date" name="validity_end" class="form-control" value="{{ date('Y-m-d', strtotime('+30 days')) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nota</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
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

<!-- Quick Reject Modal -->
<div class="modal fade" id="quickRejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="quickRejectForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tolak PA</h5>
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

@push('scripts')
<script>
function quickApprove(paId) {
    $('#quickApproveForm').attr('action', '/admin/panel/pa/' + paId + '/approve');
    new bootstrap.Modal(document.getElementById('quickApproveModal')).show();
}

function quickReject(paId) {
    $('#quickRejectForm').attr('action', '/admin/panel/pa/' + paId + '/reject');
    new bootstrap.Modal(document.getElementById('quickRejectModal')).show();
}
</script>
@endpush
