@extends('layouts.admin')
@section('title', 'Tutup Kaunter')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper breadcrumb-contacts">
        <div>
            <h1>Tutup Kaunter</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span><a href="{{ route('admin.billing.index') }}">Bil & Pembayaran</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>Tutup Kaunter</span>
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

    <div class="row">
        <!-- Live Session Card -->
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-cash-register"></i> Sesi Hari Ini
                    </h5>
                </div>
                <div class="card-body">
                    @if($todaySession && $todaySession->status === 'draft')
                    <div class="alert alert-success">
                        <i class="mdi mdi-check-circle"></i> Sesi aktif
                    </div>
                    @else
                    <div class="alert alert-warning">
                        <i class="mdi mdi-alert"></i> Tiada sesi aktif
                    </div>
                    <form action="{{ route('admin.billing.cashier.start') }}" method="POST" class="mb-3">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Baki Pembukaan</label>
                            <div class="input-group">
                                <span class="input-group-text">RM</span>
                                <input type="number" name="opening_balance" class="form-control text-end"
                                    step="0.01" value="200.00">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="mdi mdi-play"></i> Mulakan Sesi
                        </button>
                    </form>
                    @endif

                    @if($todaySession && $todaySession->status === 'draft')
                    <h6 class="mb-3">Ringkasan Langsung</h6>
                    <table class="table table-sm">
                        <tr>
                            <td>Baki Pembukaan</td>
                            <td class="text-end">RM {{ number_format($liveTotals['opening_balance'], 2) }}</td>
                        </tr>
                        <tr class="table-success">
                            <td><i class="mdi mdi-cash text-success"></i> Tunai</td>
                            <td class="text-end">RM {{ number_format($liveTotals['cash_sales'], 2) }}</td>
                        </tr>
                        <tr>
                            <td><i class="mdi mdi-credit-card text-primary"></i> Kad</td>
                            <td class="text-end">RM {{ number_format($liveTotals['card_sales'], 2) }}</td>
                        </tr>
                        <tr>
                            <td><i class="mdi mdi-qrcode text-info"></i> QR Pay</td>
                            <td class="text-end">RM {{ number_format($liveTotals['qr_sales'], 2) }}</td>
                        </tr>
                        <tr>
                            <td><i class="mdi mdi-wallet text-warning"></i> E-Wallet</td>
                            <td class="text-end">RM {{ number_format($liveTotals['ewallet_sales'], 2) }}</td>
                        </tr>
                        <tr>
                            <td><i class="mdi mdi-bank-transfer text-secondary"></i> Pindahan</td>
                            <td class="text-end">RM {{ number_format($liveTotals['transfer_sales'], 2) }}</td>
                        </tr>
                        <tr>
                            <td><i class="mdi mdi-shield-check text-danger"></i> Panel</td>
                            <td class="text-end">RM {{ number_format($liveTotals['panel_sales'], 2) }}</td>
                        </tr>
                        <tr class="table-primary">
                            <td><strong>Jumlah Kutipan</strong></td>
                            <td class="text-end"><strong>RM {{ number_format($liveTotals['total_sales'], 2) }}</strong></td>
                        </tr>
                        <tr class="text-danger">
                            <td>Pulangan</td>
                            <td class="text-end">-RM {{ number_format($liveTotals['total_refunds'], 2) }}</td>
                        </tr>
                        <tr class="table-info">
                            <td><strong>Kutipan Bersih</strong></td>
                            <td class="text-end"><strong>RM {{ number_format($liveTotals['net_sales'], 2) }}</strong></td>
                        </tr>
                    </table>

                    <div class="alert alert-secondary">
                        <div class="d-flex justify-content-between">
                            <span>Tunai Dijangka:</span>
                            <strong>RM {{ number_format($liveTotals['expected_cash'], 2) }}</strong>
                        </div>
                    </div>

                    <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#closeSessionModal">
                        <i class="mdi mdi-stop"></i> Tutup Sesi
                    </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- History -->
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Sejarah Tutup Kaunter</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Tarikh</th>
                                    <th>Juruwang</th>
                                    <th class="text-end">Kutipan</th>
                                    <th class="text-end">Tunai Dijangka</th>
                                    <th class="text-end">Tunai Sebenar</th>
                                    <th class="text-end">Perbezaan</th>
                                    <th>Status</th>
                                    <th class="text-center">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($closings as $closing)
                                <tr class="{{ $closing->cash_difference != 0 ? 'table-warning' : '' }}">
                                    <td>{{ $closing->closing_date->format('d/m/Y') }}</td>
                                    <td>{{ $closing->cashier->name ?? '-' }}</td>
                                    <td class="text-end">RM {{ number_format($closing->total_sales, 2) }}</td>
                                    <td class="text-end">RM {{ number_format($closing->expected_cash, 2) }}</td>
                                    <td class="text-end">RM {{ number_format($closing->actual_cash, 2) }}</td>
                                    <td class="text-end">
                                        @if($closing->cash_difference > 0)
                                        <span class="text-success">+RM {{ number_format($closing->cash_difference, 2) }}</span>
                                        @elseif($closing->cash_difference < 0)
                                        <span class="text-danger">-RM {{ number_format(abs($closing->cash_difference), 2) }}</span>
                                        @else
                                        <span class="text-muted">RM 0.00</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = match($closing->status) {
                                                'draft' => 'bg-secondary',
                                                'submitted' => 'bg-warning',
                                                'verified' => 'bg-success',
                                                default => 'bg-secondary'
                                            };
                                            $statusLabel = match($closing->status) {
                                                'draft' => 'Draf',
                                                'submitted' => 'Dihantar',
                                                'verified' => 'Disahkan',
                                                default => $closing->status
                                            };
                                        @endphp
                                        <span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($closing->status === 'submitted')
                                        <form action="{{ route('admin.billing.cashier.verify', $closing) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Sahkan">
                                                <i class="mdi mdi-check"></i>
                                            </button>
                                        </form>
                                        @endif
                                        <button type="button" class="btn btn-sm btn-outline-info" title="Lihat"
                                            data-bs-toggle="modal" data-bs-target="#detailModal{{ $closing->id }}">
                                            <i class="mdi mdi-eye"></i>
                                        </button>
                                    </td>
                                </tr>

                                <!-- Detail Modal -->
                                <div class="modal fade" id="detailModal{{ $closing->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Butiran Tutup Kaunter</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <table class="table table-sm">
                                                    <tr>
                                                        <td>Tarikh</td>
                                                        <td class="text-end"><strong>{{ $closing->closing_date->format('d/m/Y') }}</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Juruwang</td>
                                                        <td class="text-end">{{ $closing->cashier->name ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Baki Pembukaan</td>
                                                        <td class="text-end">RM {{ number_format($closing->opening_balance, 2) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Tunai</td>
                                                        <td class="text-end">RM {{ number_format($closing->cash_sales, 2) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Kad</td>
                                                        <td class="text-end">RM {{ number_format($closing->card_sales, 2) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>QR Pay</td>
                                                        <td class="text-end">RM {{ number_format($closing->qr_sales, 2) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>E-Wallet</td>
                                                        <td class="text-end">RM {{ number_format($closing->ewallet_sales, 2) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Pindahan</td>
                                                        <td class="text-end">RM {{ number_format($closing->transfer_sales, 2) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Panel</td>
                                                        <td class="text-end">RM {{ number_format($closing->panel_sales, 2) }}</td>
                                                    </tr>
                                                    <tr class="table-primary">
                                                        <td><strong>Jumlah Kutipan</strong></td>
                                                        <td class="text-end"><strong>RM {{ number_format($closing->total_sales, 2) }}</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Pulangan</td>
                                                        <td class="text-end text-danger">-RM {{ number_format($closing->total_refunds, 2) }}</td>
                                                    </tr>
                                                    <tr class="table-info">
                                                        <td><strong>Tunai Dijangka</strong></td>
                                                        <td class="text-end"><strong>RM {{ number_format($closing->expected_cash, 2) }}</strong></td>
                                                    </tr>
                                                    <tr class="table-secondary">
                                                        <td><strong>Tunai Sebenar</strong></td>
                                                        <td class="text-end"><strong>RM {{ number_format($closing->actual_cash, 2) }}</strong></td>
                                                    </tr>
                                                    <tr class="{{ $closing->cash_difference != 0 ? 'table-warning' : 'table-success' }}">
                                                        <td><strong>Perbezaan</strong></td>
                                                        <td class="text-end">
                                                            <strong>
                                                                @if($closing->cash_difference >= 0)
                                                                RM {{ number_format($closing->cash_difference, 2) }}
                                                                @else
                                                                -RM {{ number_format(abs($closing->cash_difference), 2) }}
                                                                @endif
                                                            </strong>
                                                        </td>
                                                    </tr>
                                                </table>
                                                @if($closing->notes)
                                                <div class="alert alert-secondary">
                                                    <strong>Catatan:</strong><br>
                                                    {{ $closing->notes }}
                                                </div>
                                                @endif
                                                @if($closing->verified_by)
                                                <div class="text-muted small">
                                                    Disahkan oleh: {{ $closing->verifiedBy->name ?? '-' }}<br>
                                                    Pada: {{ $closing->verified_at?->format('d/m/Y H:i') }}
                                                </div>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="mdi mdi-cash-register mdi-48px text-muted"></i>
                                        <p class="text-muted mb-0">Tiada rekod tutup kaunter</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($closings->hasPages())
                <div class="card-footer">
                    {{ $closings->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Close Session Modal -->
<div class="modal fade" id="closeSessionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.billing.cashier.close') }}" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="mdi mdi-stop"></i> Tutup Sesi Kaunter</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Tunai Dijangka:</strong>
                        <span class="fs-4 float-end">RM {{ number_format($liveTotals['expected_cash'] ?? 0, 2) }}</span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tunai Sebenar Dalam Laci <span class="text-danger">*</span></label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text">RM</span>
                            <input type="number" name="actual_cash" class="form-control text-end"
                                step="0.01" min="0" required placeholder="0.00">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-control" rows="3"
                            placeholder="Catatan jika ada perbezaan tunai"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tutup Sesi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
