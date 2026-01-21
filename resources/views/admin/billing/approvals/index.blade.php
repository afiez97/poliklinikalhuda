@extends('layouts.admin')
@section('title', 'Kelulusan Diskaun')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper breadcrumb-contacts">
        <div>
            <h1>Kelulusan Diskaun</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span><a href="{{ route('admin.billing.index') }}">Bil & Pembayaran</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>Kelulusan</span>
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

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Permohonan Menunggu Kelulusan</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Tarikh</th>
                            <th>No. Invois</th>
                            <th>Pesakit</th>
                            <th>Dimohon Oleh</th>
                            <th class="text-end">Jumlah Invois</th>
                            <th class="text-end">Diskaun</th>
                            <th>Sebab</th>
                            <th class="text-center">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($approvals as $approval)
                        <tr>
                            <td>
                                {{ $approval->created_at->format('d/m/Y') }}
                                <br>
                                <small class="text-muted">{{ $approval->created_at->format('H:i') }}</small>
                            </td>
                            <td>
                                <a href="{{ route('admin.billing.invoices.show', $approval->invoice) }}">
                                    {{ $approval->invoice->invoice_number }}
                                </a>
                            </td>
                            <td>
                                {{ $approval->invoice->patient->name ?? '-' }}
                                <br>
                                <small class="text-muted">{{ $approval->invoice->patient->mrn ?? '' }}</small>
                            </td>
                            <td>{{ $approval->requestedBy->name ?? '-' }}</td>
                            <td class="text-end">RM {{ number_format($approval->invoice->subtotal, 2) }}</td>
                            <td class="text-end">
                                @if($approval->discount_type === 'percentage')
                                <strong class="text-danger">{{ $approval->discount_value }}%</strong>
                                <br>
                                <small class="text-muted">RM {{ number_format($approval->discount_amount, 2) }}</small>
                                @else
                                <strong class="text-danger">RM {{ number_format($approval->discount_amount, 2) }}</strong>
                                @endif
                            </td>
                            <td>
                                <span title="{{ $approval->reason }}">
                                    {{ Str::limit($approval->reason, 40) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <form action="{{ route('admin.billing.approvals.approve', $approval) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success" title="Luluskan">
                                            <i class="mdi mdi-check"></i> Lulus
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-danger"
                                        data-bs-toggle="modal" data-bs-target="#rejectModal{{ $approval->id }}"
                                        title="Tolak">
                                        <i class="mdi mdi-close"></i> Tolak
                                    </button>
                                </div>

                                <!-- Reject Modal -->
                                <div class="modal fade" id="rejectModal{{ $approval->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.billing.approvals.reject', $approval) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Tolak Permohonan Diskaun</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="alert alert-info">
                                                        <strong>Invois:</strong> {{ $approval->invoice->invoice_number }}<br>
                                                        <strong>Diskaun Dimohon:</strong>
                                                        @if($approval->discount_type === 'percentage')
                                                        {{ $approval->discount_value }}% (RM {{ number_format($approval->discount_amount, 2) }})
                                                        @else
                                                        RM {{ number_format($approval->discount_amount, 2) }}
                                                        @endif
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Sebab Penolakan <span class="text-danger">*</span></label>
                                                        <textarea name="rejection_reason" class="form-control" rows="3" required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-danger">Tolak Permohonan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="mdi mdi-check-circle mdi-48px text-success"></i>
                                <p class="text-muted mb-0">Tiada permohonan menunggu kelulusan</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($approvals->hasPages())
        <div class="card-footer">
            {{ $approvals->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
