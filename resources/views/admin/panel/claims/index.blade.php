@extends('layouts.admin')

@section('title', 'Senarai Tuntutan Panel')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between">
        <div>
            <h1>Tuntutan Panel</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Tuntutan Panel</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.panel.claims.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Tuntutan Baru
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card card-default">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-secondary">{{ $statistics['draft'] ?? 0 }}</h3>
                    <small class="text-muted">Draf</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card card-default">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-info">{{ $statistics['submitted'] ?? 0 }}</h3>
                    <small class="text-muted">Dihantar</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card card-default">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-warning">{{ $statistics['pending'] ?? 0 }}</h3>
                    <small class="text-muted">Dalam Proses</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card card-default">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-success">{{ $statistics['approved'] ?? 0 }}</h3>
                    <small class="text-muted">Diluluskan</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card card-default">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-danger">{{ $statistics['rejected'] ?? 0 }}</h3>
                    <small class="text-muted">Ditolak</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card card-default">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-primary">{{ $statistics['paid'] ?? 0 }}</h3>
                    <small class="text-muted">Dibayar</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card card-default mb-4">
        <div class="card-body">
            <form action="{{ route('admin.panel.claims.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Carian</label>
                        <input type="text" name="search" class="form-control" placeholder="No. Tuntutan / Invois..."
                               value="{{ $filters['search'] ?? '' }}">
                    </div>
                    <div class="col-md-2">
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
                            <option value="draft" {{ ($filters['status'] ?? '') == 'draft' ? 'selected' : '' }}>Draf</option>
                            <option value="submitted" {{ ($filters['status'] ?? '') == 'submitted' ? 'selected' : '' }}>Dihantar</option>
                            <option value="pending" {{ ($filters['status'] ?? '') == 'pending' ? 'selected' : '' }}>Dalam Proses</option>
                            <option value="approved" {{ ($filters['status'] ?? '') == 'approved' ? 'selected' : '' }}>Diluluskan</option>
                            <option value="rejected" {{ ($filters['status'] ?? '') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                            <option value="paid" {{ ($filters['status'] ?? '') == 'paid' ? 'selected' : '' }}>Dibayar</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Dari Tarikh</label>
                        <input type="date" name="date_from" class="form-control" value="{{ $filters['date_from'] ?? '' }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Hingga Tarikh</label>
                        <input type="date" name="date_to" class="form-control" value="{{ $filters['date_to'] ?? '' }}">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Claims List -->
    <div class="card card-default">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2>Senarai Tuntutan</h2>
            @if(request('status') == 'draft')
            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#batchSubmitModal">
                <i class="bi bi-send me-1"></i> Hantar Sekali Gus
            </button>
            @endif
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>
                                @if(request('status') == 'draft')
                                <input type="checkbox" id="selectAll">
                                @endif
                            </th>
                            <th>No. Tuntutan</th>
                            <th>Panel</th>
                            <th>Pesakit</th>
                            <th>Invois</th>
                            <th class="text-end">Amaun</th>
                            <th>Status</th>
                            <th>Tarikh</th>
                            <th class="text-center">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($claims as $claim)
                        <tr>
                            <td>
                                @if($claim->status == 'draft')
                                <input type="checkbox" name="claim_ids[]" value="{{ $claim->id }}" class="claim-checkbox">
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.panel.claims.show', $claim) }}">
                                    <code>{{ $claim->claim_number }}</code>
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('admin.panel.panels.show', $claim->panel) }}">
                                    {{ Str::limit($claim->panel->panel_name, 20) }}
                                </a>
                            </td>
                            <td>
                                @if($claim->patient)
                                {{ $claim->patient->name }}
                                <br><small class="text-muted">{{ $claim->patient->mrn }}</small>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($claim->invoice)
                                <code>{{ $claim->invoice->invoice_no }}</code>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <strong>RM {{ number_format($claim->claim_amount, 2) }}</strong>
                                @if($claim->approved_amount && $claim->approved_amount != $claim->claim_amount)
                                <br><small class="text-success">Lulus: RM {{ number_format($claim->approved_amount, 2) }}</small>
                                @endif
                            </td>
                            <td>
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
                                <span class="badge bg-{{ $statusColors[$claim->status] ?? 'secondary' }}">
                                    {{ $claim->status_name }}
                                </span>
                                @if($claim->isOverdue())
                                <br><span class="badge bg-danger">Lewat Bayar</span>
                                @endif
                            </td>
                            <td>
                                {{ $claim->claim_date->format('d/m/Y') }}
                                @if($claim->submitted_at)
                                <br><small class="text-muted">Hantar: {{ $claim->submitted_at->format('d/m/Y') }}</small>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.panel.claims.show', $claim) }}" class="btn btn-outline-primary" title="Lihat">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($claim->status == 'draft')
                                    <a href="{{ route('admin.panel.claims.edit', $claim) }}" class="btn btn-outline-secondary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.panel.claims.submit', $claim) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-success" title="Hantar" onclick="return confirm('Hantar tuntutan ini?')">
                                            <i class="bi bi-send"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Tiada tuntutan dijumpai.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($claims->hasPages())
        <div class="card-footer">
            {{ $claims->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Batch Submit Modal -->
<div class="modal fade" id="batchSubmitModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.panel.claims.batch-submit') }}" method="POST" id="batchSubmitForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Hantar Tuntutan Sekali Gus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Anda akan menghantar <strong id="selectedCount">0</strong> tuntutan yang dipilih.</p>
                    <div id="selectedClaimsContainer"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success" id="batchSubmitBtn" disabled>
                        <i class="bi bi-send me-1"></i> Hantar Semua
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Select all checkbox
    $('#selectAll').on('change', function() {
        $('.claim-checkbox').prop('checked', $(this).prop('checked'));
        updateSelectedCount();
    });

    // Individual checkbox
    $('.claim-checkbox').on('change', function() {
        updateSelectedCount();
    });

    function updateSelectedCount() {
        var selected = $('.claim-checkbox:checked');
        var count = selected.length;

        $('#selectedCount').text(count);
        $('#batchSubmitBtn').prop('disabled', count === 0);

        // Update hidden inputs
        $('#selectedClaimsContainer').empty();
        selected.each(function() {
            $('#selectedClaimsContainer').append(
                '<input type="hidden" name="claim_ids[]" value="' + $(this).val() + '">'
            );
        });
    }
});
</script>
@endpush
