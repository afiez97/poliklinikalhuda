@extends('layouts.admin')

@section('title', 'Pemadanan Bayaran')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between">
        <div>
            <h1>Pemadanan Bayaran Panel</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Pemadanan Bayaran</li>
                </ol>
            </nav>
        </div>
        <div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#recordPaymentModal">
                <i class="bi bi-plus-circle me-1"></i> Rekod Bayaran
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card card-default bg-warning text-dark">
                <div class="card-body">
                    <h3 class="mb-0">RM {{ number_format($statistics['outstanding'] ?? 0, 2) }}</h3>
                    <small>Tertunggak</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-default bg-primary text-white">
                <div class="card-body">
                    <h3 class="mb-0">RM {{ number_format($statistics['received_this_month'] ?? 0, 2) }}</h3>
                    <small>Diterima Bulan Ini</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-default bg-success text-white">
                <div class="card-body">
                    <h3 class="mb-0">{{ $statistics['claims_pending'] ?? 0 }}</h3>
                    <small>Tuntutan Belum Dibayar</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-default bg-danger text-white">
                <div class="card-body">
                    <h3 class="mb-0">{{ $statistics['overdue_count'] ?? 0 }}</h3>
                    <small>Lewat Bayar</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card card-default mb-4">
        <div class="card-body">
            <form action="{{ route('admin.panel.reconciliation.index') }}" method="GET">
                <div class="row g-3">
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
                        <label class="form-label">Dari Tarikh</label>
                        <input type="date" name="date_from" class="form-control" value="{{ $filters['date_from'] ?? '' }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Hingga Tarikh</label>
                        <input type="date" name="date_to" class="form-control" value="{{ $filters['date_to'] ?? '' }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua</option>
                            <option value="pending" {{ ($filters['status'] ?? '') == 'pending' ? 'selected' : '' }}>Belum Dibayar</option>
                            <option value="partial" {{ ($filters['status'] ?? '') == 'partial' ? 'selected' : '' }}>Separa</option>
                            <option value="paid" {{ ($filters['status'] ?? '') == 'paid' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="bi bi-search me-1"></i> Cari
                        </button>
                        <a href="{{ route('admin.panel.reconciliation.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-clockwise"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <!-- Outstanding Claims -->
        <div class="col-lg-7">
            <div class="card card-default">
                <div class="card-header">
                    <h2><i class="bi bi-clock-history me-2"></i>Tuntutan Tertunggak</h2>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>
                                        <input type="checkbox" id="selectAllClaims">
                                    </th>
                                    <th>No. Tuntutan</th>
                                    <th>Panel</th>
                                    <th class="text-end">Diluluskan</th>
                                    <th class="text-end">Tertunggak</th>
                                    <th>Umur</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($outstandingClaims as $claim)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="claim-select" value="{{ $claim->id }}"
                                               data-amount="{{ $claim->approved_amount - ($claim->paid_amount ?? 0) }}">
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.panel.claims.show', $claim) }}">
                                            <code>{{ $claim->claim_number }}</code>
                                        </a>
                                    </td>
                                    <td>{{ Str::limit($claim->panel->panel_name, 15) }}</td>
                                    <td class="text-end">RM {{ number_format($claim->approved_amount, 2) }}</td>
                                    <td class="text-end">
                                        <strong>RM {{ number_format($claim->approved_amount - ($claim->paid_amount ?? 0), 2) }}</strong>
                                    </td>
                                    <td>
                                        @php
                                            $age = $claim->submitted_at?->diffInDays(now()) ?? 0;
                                            $ageClass = $age > 60 ? 'danger' : ($age > 30 ? 'warning' : 'success');
                                        @endphp
                                        <span class="badge bg-{{ $ageClass }}">{{ $age }} hari</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-3 text-muted">Tiada tuntutan tertunggak.</td>
                                </tr>
                                @endforelse
                            </tbody>
                            @if($outstandingClaims->count())
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Jumlah Dipilih:</strong></td>
                                    <td class="text-end"><strong id="selectedTotal">RM 0.00</strong></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
                @if($outstandingClaims->hasPages())
                <div class="card-footer">
                    {{ $outstandingClaims->withQueryString()->links() }}
                </div>
                @endif
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="col-lg-5">
            <div class="card card-default">
                <div class="card-header">
                    <h2><i class="bi bi-cash-stack me-2"></i>Bayaran Terkini</h2>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Tarikh</th>
                                    <th>Panel</th>
                                    <th>Rujukan</th>
                                    <th class="text-end">Amaun</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPayments as $payment)
                                <tr>
                                    <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                                    <td>{{ Str::limit($payment->panel->panel_name, 12) }}</td>
                                    <td><code>{{ $payment->reference_number ?? '-' }}</code></td>
                                    <td class="text-end text-success">
                                        <strong>RM {{ number_format($payment->amount, 2) }}</strong>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3 text-muted">Tiada bayaran terkini.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Aging Summary -->
            <div class="card card-default mt-4">
                <div class="card-header">
                    <h2><i class="bi bi-bar-chart me-2"></i>Analisis Umur Hutang</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Tempoh</th>
                                    <th class="text-end">Jumlah</th>
                                    <th class="text-end">Bil</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="badge bg-success">0-30 hari</span></td>
                                    <td class="text-end">RM {{ number_format($aging['0_30'] ?? 0, 2) }}</td>
                                    <td class="text-end">{{ $agingCount['0_30'] ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-warning text-dark">31-60 hari</span></td>
                                    <td class="text-end">RM {{ number_format($aging['31_60'] ?? 0, 2) }}</td>
                                    <td class="text-end">{{ $agingCount['31_60'] ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-danger">61-90 hari</span></td>
                                    <td class="text-end">RM {{ number_format($aging['61_90'] ?? 0, 2) }}</td>
                                    <td class="text-end">{{ $agingCount['61_90'] ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-dark">&gt; 90 hari</span></td>
                                    <td class="text-end">RM {{ number_format($aging['90_plus'] ?? 0, 2) }}</td>
                                    <td class="text-end">{{ $agingCount['90_plus'] ?? 0 }}</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr class="table-light">
                                    <th>Jumlah</th>
                                    <th class="text-end">RM {{ number_format(array_sum($aging ?? []), 2) }}</th>
                                    <th class="text-end">{{ array_sum($agingCount ?? []) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Record Payment Modal -->
<div class="modal fade" id="recordPaymentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.panel.reconciliation.store') }}" method="POST" id="recordPaymentForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Rekod Bayaran Panel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Panel <span class="text-danger">*</span></label>
                            <select name="panel_id" class="form-select" required>
                                <option value="">-- Pilih Panel --</option>
                                @foreach($panels as $panel)
                                <option value="{{ $panel->id }}">{{ $panel->panel_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tarikh Bayaran <span class="text-danger">*</span></label>
                            <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Amaun (RM) <span class="text-danger">*</span></label>
                            <input type="number" name="amount" id="paymentAmount" class="form-control" step="0.01" min="0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No. Rujukan Bayaran</label>
                            <input type="text" name="reference_number" class="form-control" placeholder="cth: CIMB-TRF-123456">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kaedah Bayaran</label>
                        <select name="payment_method" class="form-select">
                            <option value="bank_transfer">Pindahan Bank</option>
                            <option value="cheque">Cek</option>
                            <option value="cash">Tunai</option>
                            <option value="online">Online Banking</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tuntutan Yang Dibayar</label>
                        <div id="selectedClaimsList" class="border rounded p-2 bg-light">
                            <span class="text-muted">Tiada tuntutan dipilih. Pilih dari senarai di sebelah kiri.</span>
                        </div>
                        <div id="claimIdsContainer"></div>
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
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Select all claims
    $('#selectAllClaims').on('change', function() {
        $('.claim-select').prop('checked', $(this).prop('checked'));
        updateSelectedTotal();
    });

    // Individual claim selection
    $('.claim-select').on('change', function() {
        updateSelectedTotal();
    });

    function updateSelectedTotal() {
        var total = 0;
        var selectedClaims = [];

        $('.claim-select:checked').each(function() {
            total += parseFloat($(this).data('amount'));
            selectedClaims.push({
                id: $(this).val(),
                amount: $(this).data('amount')
            });
        });

        $('#selectedTotal').text('RM ' + total.toFixed(2));
        $('#paymentAmount').val(total.toFixed(2));

        // Update modal
        if (selectedClaims.length > 0) {
            var html = '<ul class="mb-0">';
            $('#claimIdsContainer').empty();

            selectedClaims.forEach(function(claim) {
                html += '<li>Tuntutan #' + claim.id + ' - RM ' + claim.amount.toFixed(2) + '</li>';
                $('#claimIdsContainer').append('<input type="hidden" name="claim_ids[]" value="' + claim.id + '">');
            });

            html += '</ul>';
            $('#selectedClaimsList').html(html);
        } else {
            $('#selectedClaimsList').html('<span class="text-muted">Tiada tuntutan dipilih.</span>');
            $('#claimIdsContainer').empty();
        }
    }
});
</script>
@endpush
