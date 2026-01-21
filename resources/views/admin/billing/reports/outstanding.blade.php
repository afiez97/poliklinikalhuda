@extends('layouts.admin')
@section('title', 'Laporan Tunggakan')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper breadcrumb-contacts">
        <div>
            <h1>Laporan Tunggakan</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span><a href="{{ route('admin.billing.index') }}">Bil & Pembayaran</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span><a href="{{ route('admin.billing.reports') }}">Laporan</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>Tunggakan</span>
            </p>
        </div>
        <div>
            <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
                <i class="mdi mdi-printer"></i> Cetak
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card bg-danger text-white">
                <div class="card-body text-center py-3">
                    <h6 class="text-white-50 mb-1">Jumlah Tertunggak</h6>
                    <h4 class="mb-0">RM {{ number_format($summary['total'], 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-success text-white">
                <div class="card-body text-center py-3">
                    <h6 class="text-white-50 mb-1">Semasa</h6>
                    <h4 class="mb-0">RM {{ number_format($summary['current'], 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-warning text-white">
                <div class="card-body text-center py-3">
                    <h6 class="text-white-50 mb-1">1-30 Hari</h6>
                    <h4 class="mb-0">RM {{ number_format($summary['overdue_30'], 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card" style="background-color: #fd7e14; color: white;">
                <div class="card-body text-center py-3">
                    <h6 class="text-white-50 mb-1">31-60 Hari</h6>
                    <h4 class="mb-0">RM {{ number_format($summary['overdue_60'], 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-danger text-white">
                <div class="card-body text-center py-3">
                    <h6 class="text-white-50 mb-1">61-90 Hari</h6>
                    <h4 class="mb-0">RM {{ number_format($summary['overdue_90'], 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-dark text-white">
                <div class="card-body text-center py-3">
                    <h6 class="text-white-50 mb-1">> 90 Hari</h6>
                    <h4 class="mb-0">RM {{ number_format($summary['overdue_90_plus'], 2) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Outstanding List -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Senarai Invois Tertunggak ({{ $summary['count'] }} invois)</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>No. Invois</th>
                            <th>Tarikh</th>
                            <th>Pesakit</th>
                            <th>Telefon</th>
                            <th class="text-end">Jumlah</th>
                            <th class="text-end">Dibayar</th>
                            <th class="text-end">Baki</th>
                            <th class="text-center">Hari Tertunggak</th>
                            <th>Penuaan</th>
                            <th class="text-center">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $invoice)
                        @php
                            $daysOverdue = $invoice->days_overdue;
                            $agingClass = 'bg-success';
                            $agingLabel = 'Semasa';

                            if ($daysOverdue > 90) {
                                $agingClass = 'bg-dark';
                                $agingLabel = '> 90 hari';
                            } elseif ($daysOverdue > 60) {
                                $agingClass = 'bg-danger';
                                $agingLabel = '61-90 hari';
                            } elseif ($daysOverdue > 30) {
                                $agingClass = 'bg-orange';
                                $agingLabel = '31-60 hari';
                            } elseif ($daysOverdue > 0) {
                                $agingClass = 'bg-warning';
                                $agingLabel = '1-30 hari';
                            }
                        @endphp
                        <tr>
                            <td>
                                <a href="{{ route('admin.billing.invoices.show', $invoice) }}">
                                    <strong>{{ $invoice->invoice_number }}</strong>
                                </a>
                            </td>
                            <td>
                                {{ $invoice->invoice_date->format('d/m/Y') }}
                                <br>
                                <small class="text-muted">Jatuh: {{ $invoice->due_date->format('d/m/Y') }}</small>
                            </td>
                            <td>
                                {{ $invoice->patient->name }}
                                <br>
                                <small class="text-muted">{{ $invoice->patient->mrn }}</small>
                            </td>
                            <td>{{ $invoice->patient->phone ?? '-' }}</td>
                            <td class="text-end">RM {{ number_format($invoice->grand_total, 2) }}</td>
                            <td class="text-end text-success">RM {{ number_format($invoice->paid_amount, 2) }}</td>
                            <td class="text-end text-danger"><strong>RM {{ number_format($invoice->balance, 2) }}</strong></td>
                            <td class="text-center">
                                @if($daysOverdue > 0)
                                <span class="text-danger"><strong>{{ $daysOverdue }}</strong></span>
                                @else
                                <span class="text-muted">0</span>
                                @endif
                            </td>
                            <td><span class="badge {{ $agingClass }}">{{ $agingLabel }}</span></td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.billing.invoices.pay', $invoice) }}"
                                        class="btn btn-outline-success" title="Terima Bayaran">
                                        <i class="mdi mdi-cash"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-info" title="Hantar Peringatan"
                                        data-bs-toggle="modal" data-bs-target="#reminderModal{{ $invoice->id }}">
                                        <i class="mdi mdi-bell"></i>
                                    </button>
                                </div>

                                <!-- Reminder Modal -->
                                <div class="modal fade" id="reminderModal{{ $invoice->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Hantar Peringatan</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Pesakit:</strong> {{ $invoice->patient->name }}</p>
                                                <p><strong>No. Invois:</strong> {{ $invoice->invoice_number }}</p>
                                                <p><strong>Baki:</strong> RM {{ number_format($invoice->balance, 2) }}</p>

                                                <hr>

                                                <div class="mb-3">
                                                    <label class="form-label">Kaedah Peringatan</label>
                                                    <select class="form-select">
                                                        <option value="sms">SMS</option>
                                                        <option value="whatsapp">WhatsApp</option>
                                                        <option value="email">E-mel</option>
                                                        <option value="call">Panggilan</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="button" class="btn btn-info">Hantar Peringatan</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">
                                <i class="mdi mdi-check-circle mdi-48px text-success"></i>
                                <p class="text-muted mb-0">Tiada invois tertunggak</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($invoices->hasPages())
        <div class="card-footer">
            {{ $invoices->links() }}
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
.bg-orange {
    background-color: #fd7e14 !important;
}
@media print {
    .breadcrumb-wrapper, .btn-group, button {
        display: none !important;
    }
}
</style>
@endpush
@endsection
