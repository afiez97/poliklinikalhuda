@extends('layouts.admin')
@section('title', 'Senarai Pulangan')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper breadcrumb-contacts">
        <div>
            <h1>Senarai Pulangan</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span><a href="{{ route('admin.billing.index') }}">Bil & Pembayaran</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>Pulangan</span>
            </p>
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

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.billing.refunds.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Carian</label>
                        <input type="text" name="search" class="form-control"
                            placeholder="No. pulangan / Nama pesakit" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Diluluskan</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                            <option value="processed" {{ request('status') == 'processed' ? 'selected' : '' }}>Diproses</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Dari Tarikh</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Hingga Tarikh</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Kaedah Pulangan</label>
                        <select name="refund_method" class="form-select">
                            <option value="">Semua</option>
                            <option value="cash" {{ request('refund_method') == 'cash' ? 'selected' : '' }}>Tunai</option>
                            <option value="card" {{ request('refund_method') == 'card' ? 'selected' : '' }}>Kad</option>
                            <option value="transfer" {{ request('refund_method') == 'transfer' ? 'selected' : '' }}>Pindahan</option>
                        </select>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="mdi mdi-magnify"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Refunds Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>No. Pulangan</th>
                            <th>Tarikh</th>
                            <th>No. Bayaran</th>
                            <th>Pesakit</th>
                            <th class="text-end">Jumlah</th>
                            <th>Sebab</th>
                            <th>Kaedah</th>
                            <th>Status</th>
                            <th class="text-center">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($refunds as $refund)
                        <tr>
                            <td>
                                <strong>{{ $refund->refund_number }}</strong>
                            </td>
                            <td>
                                {{ $refund->created_at->format('d/m/Y') }}
                                <br>
                                <small class="text-muted">{{ $refund->created_at->format('H:i') }}</small>
                            </td>
                            <td>
                                @if($refund->payment)
                                {{ $refund->payment->payment_number }}
                                @else
                                -
                                @endif
                            </td>
                            <td>
                                {{ $refund->payment->invoice->patient->name ?? '-' }}
                                <br>
                                <small class="text-muted">{{ $refund->payment->invoice->patient->mrn ?? '' }}</small>
                            </td>
                            <td class="text-end">
                                <strong class="text-danger">RM {{ number_format($refund->amount, 2) }}</strong>
                            </td>
                            <td>
                                <span title="{{ $refund->reason }}">
                                    {{ Str::limit($refund->reason, 30) }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $methodLabel = match($refund->refund_method) {
                                        'cash' => 'Tunai',
                                        'card' => 'Kad',
                                        'transfer' => 'Pindahan',
                                        default => $refund->refund_method
                                    };
                                @endphp
                                <span class="badge bg-secondary">{{ $methodLabel }}</span>
                            </td>
                            <td>
                                @php
                                    $statusClass = match($refund->status) {
                                        'pending' => 'bg-warning',
                                        'approved' => 'bg-info',
                                        'rejected' => 'bg-danger',
                                        'processed' => 'bg-success',
                                        default => 'bg-secondary'
                                    };
                                    $statusLabel = match($refund->status) {
                                        'pending' => 'Menunggu',
                                        'approved' => 'Diluluskan',
                                        'rejected' => 'Ditolak',
                                        'processed' => 'Selesai',
                                        default => $refund->status
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    @if($refund->status === 'pending')
                                    <form action="{{ route('admin.billing.refunds.approve', $refund) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-success" title="Luluskan">
                                            <i class="mdi mdi-check"></i>
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-outline-danger"
                                        data-bs-toggle="modal" data-bs-target="#rejectModal{{ $refund->id }}"
                                        title="Tolak">
                                        <i class="mdi mdi-close"></i>
                                    </button>
                                    @elseif($refund->status === 'approved')
                                    <button type="button" class="btn btn-outline-primary"
                                        data-bs-toggle="modal" data-bs-target="#processModal{{ $refund->id }}"
                                        title="Proses">
                                        <i class="mdi mdi-cash"></i> Proses
                                    </button>
                                    @elseif($refund->creditNote)
                                    <a href="{{ route('admin.billing.credit-notes.print', $refund->creditNote) ?? '#' }}"
                                        class="btn btn-outline-secondary" target="_blank" title="Cetak Nota Kredit">
                                        <i class="mdi mdi-printer"></i>
                                    </a>
                                    @endif
                                </div>

                                <!-- Reject Modal -->
                                <div class="modal fade" id="rejectModal{{ $refund->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.billing.refunds.reject', $refund) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Tolak Pulangan</h5>
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
                                                    <button type="submit" class="btn btn-danger">Tolak Pulangan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Process Modal -->
                                <div class="modal fade" id="processModal{{ $refund->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.billing.refunds.process', $refund) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Proses Pulangan</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="alert alert-info">
                                                        <strong>Jumlah Pulangan:</strong> RM {{ number_format($refund->amount, 2) }}<br>
                                                        <strong>Kaedah:</strong> {{ $methodLabel }}
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">No. Rujukan (jika ada)</label>
                                                        <input type="text" name="reference_number" class="form-control"
                                                            placeholder="No. transaksi pindahan/kad">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Proses Pulangan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="mdi mdi-cash-refund mdi-48px text-muted"></i>
                                <p class="text-muted mb-0">Tiada rekod pulangan</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($refunds->hasPages())
        <div class="card-footer">
            {{ $refunds->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
