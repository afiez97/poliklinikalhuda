@extends('layouts.admin')

@section('title', 'Ubat Hampir Luput')

@section('content')
<div class="container-fluid">

    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Ubat Hampir Luput</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.medicine.index') }}">Inventori Ubat</a></li>
                        <li class="breadcrumb-item active">Hampir Luput</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Summary -->
    <div class="row">
        <div class="col-12">
            <div class="alert alert-danger d-flex align-items-center">
                <i class="bi bi-clock fs-3 me-3"></i>
                <div>
                    <h5 class="alert-heading mb-1">Amaran Tarikh Luput!</h5>
                    <p class="mb-0">Terdapat <strong>{{ $medicines->count() }}</strong> jenis ubat yang akan luput dalam tempoh 30 hari akan datang.</p>
                </div>
                <div class="ms-auto">
                    <a href="{{ route('admin.medicine.index') }}" class="btn btn-outline-danger">
                        <i class="bi bi-arrow-left"></i> Kembali ke Inventori
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Expiring Medicine List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-clock-history text-danger me-2"></i>
                        Ubat Yang Akan Luput
                    </h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.medicine.low-stock') }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-exclamation-triangle"></i> Stok Rendah
                        </a>
                        <a href="{{ route('admin.medicine.stock-report') }}" class="btn btn-info btn-sm">
                            <i class="bi bi-bar-chart"></i> Laporan Stok
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($medicines->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-danger">
                                    <tr>
                                        <th>Kod Ubat</th>
                                        <th>Nama Ubat</th>
                                        <th>Kategori</th>
                                        <th>Batch</th>
                                        <th>Tarikh Luput</th>
                                        <th>Baki Hari</th>
                                        <th>Stok</th>
                                        <th>Nilai Terjejas</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($medicines as $medicine)
                                        @php
                                            $daysToExpiry = \Carbon\Carbon::now()->diffInDays($medicine->expiry_date, false);
                                            $affectedValue = $medicine->stock_quantity * $medicine->unit_price;

                                            $rowClass = '';
                                            $urgencyBadge = '';

                                            if ($daysToExpiry <= 7) {
                                                $rowClass = 'table-danger';
                                                $urgencyBadge = '<span class="badge bg-danger">KRITIKAL</span>';
                                            } elseif ($daysToExpiry <= 14) {
                                                $rowClass = 'table-warning';
                                                $urgencyBadge = '<span class="badge bg-warning">SEGERA</span>';
                                            } else {
                                                $urgencyBadge = '<span class="badge bg-info">AMARAN</span>';
                                            }
                                        @endphp
                                        <tr class="{{ $rowClass }}">
                                            <td>
                                                <code>{{ $medicine->medicine_code }}</code>
                                            </td>
                                            <td>
                                                <strong>{{ $medicine->name }}</strong>
                                                @if($medicine->manufacturer)
                                                    <br><small class="text-muted">{{ $medicine->manufacturer }}</small>
                                                @endif
                                                @if($medicine->strength)
                                                    <br><small class="text-muted">{{ $medicine->strength }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $medicine->category_label }}</span>
                                            </td>
                                            <td>
                                                {{ $medicine->batch_number ?: '-' }}
                                            </td>
                                            <td>
                                                <strong>{{ $medicine->expiry_date->format('d/m/Y') }}</strong>
                                                <br><small class="text-muted">{{ $medicine->expiry_date->format('l') }}</small>
                                            </td>
                                            <td>
                                                <span class="fw-bold fs-5 {{ $daysToExpiry <= 7 ? 'text-danger' : ($daysToExpiry <= 14 ? 'text-warning' : 'text-info') }}">
                                                    {{ $daysToExpiry }}
                                                </span> hari
                                                <br>{!! $urgencyBadge !!}
                                            </td>
                                            <td>
                                                <span class="fw-bold">{{ $medicine->stock_quantity }}</span>
                                                {!! $medicine->stock_status !!}
                                            </td>
                                            <td>
                                                <span class="text-danger fw-bold">RM {{ number_format($affectedValue, 2) }}</span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-warning"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#updateStatusModal"
                                                            data-medicine-id="{{ $medicine->id }}"
                                                            data-medicine-name="{{ $medicine->name }}"
                                                            data-expiry-date="{{ $medicine->expiry_date->format('d/m/Y') }}"
                                                            data-days-to-expiry="{{ $daysToExpiry }}"
                                                            title="Tindakan">
                                                        <i class="bi bi-gear"></i>
                                                    </button>
                                                    <a href="{{ route('admin.medicine.show', $medicine) }}"
                                                       class="btn btn-sm btn-outline-info" title="Lihat Detail">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.medicine.edit', $medicine) }}"
                                                       class="btn btn-sm btn-outline-primary" title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Summary Statistics -->
                        <div class="row mt-4">
                            <div class="col-md-3">
                                <div class="card bg-danger bg-opacity-10 border-danger">
                                    <div class="card-body text-center">
                                        <h4 class="text-danger">{{ $medicines->filter(function($m) { return \Carbon\Carbon::now()->diffInDays($m->expiry_date, false) <= 7; })->count() }}</h4>
                                        <p class="mb-0">Luput ≤ 7 Hari</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning bg-opacity-10 border-warning">
                                    <div class="card-body text-center">
                                        <h4 class="text-warning">{{ $medicines->filter(function($m) { $days = \Carbon\Carbon::now()->diffInDays($m->expiry_date, false); return $days > 7 && $days <= 14; })->count() }}</h4>
                                        <p class="mb-0">Luput 8-14 Hari</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info bg-opacity-10 border-info">
                                    <div class="card-body text-center">
                                        <h4 class="text-info">{{ $medicines->filter(function($m) { return \Carbon\Carbon::now()->diffInDays($m->expiry_date, false) > 14; })->count() }}</h4>
                                        <p class="mb-0">Luput 15-30 Hari</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-secondary bg-opacity-10 border-secondary">
                                    <div class="card-body text-center">
                                        @php
                                            $totalAffectedValue = $medicines->sum('total_value');
                                        @endphp
                                        <h4 class="text-secondary">RM {{ number_format($totalAffectedValue, 0) }}</h4>
                                        <p class="mb-0">Jumlah Nilai Terjejas</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Recommendations -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <h6 class="mb-0"><i class="bi bi-lightbulb text-warning me-2"></i>Cadangan Tindakan</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <h6 class="text-danger">Kritikal (≤ 7 hari)</h6>
                                                <ul class="small">
                                                    <li>Guna ubat dengan segera</li>
                                                    <li>Tandakan sebagai 'expired' jika sudah luput</li>
                                                    <li>Pertimbangkan untuk membuang</li>
                                                </ul>
                                            </div>
                                            <div class="col-md-4">
                                                <h6 class="text-warning">Segera (8-14 hari)</h6>
                                                <ul class="small">
                                                    <li>Prioritaskan penggunaan</li>
                                                    <li>Beritahu farmasi/doktor</li>
                                                    <li>Kurangkan pembelian baru</li>
                                                </ul>
                                            </div>
                                            <div class="col-md-4">
                                                <h6 class="text-info">Amaran (15-30 hari)</h6>
                                                <ul class="small">
                                                    <li>Pantau penggunaan</li>
                                                    <li>Susun mengikut tarikh luput</li>
                                                    <li>Rancang penggunaan</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-check-circle display-1 text-success"></i>
                            <h5 class="mt-3 text-success">Tiada Ubat Hampir Luput</h5>
                            <p class="text-muted">Semua ubat masih dalam tempoh yang selamat untuk digunakan.</p>
                            <a href="{{ route('admin.medicine.index') }}" class="btn btn-primary">
                                <i class="bi bi-arrow-left"></i> Kembali ke Inventori
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="updateStatusForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title">Tindakan Ubat Hampir Luput</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <strong id="medicineName"></strong><br>
                        Tarikh Luput: <span id="expiryDate" class="fw-bold"></span><br>
                        Baki Hari: <span id="daysToExpiry" class="fw-bold"></span> hari
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Pilih Tindakan:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="action_type" value="expired" id="action_expired">
                            <label class="form-check-label text-danger" for="action_expired">
                                <strong>Tandakan sebagai Luput</strong> - Ubat sudah tidak selamat digunakan
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="action_type" value="reduce_stock" id="action_reduce">
                            <label class="form-check-label text-warning" for="action_reduce">
                                <strong>Kurangkan Stok</strong> - Buang sebahagian stok yang hampir luput
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="action_type" value="priority_use" id="action_priority">
                            <label class="form-check-label text-info" for="action_priority">
                                <strong>Tandakan Keutamaan</strong> - Guna ubat ini dahulu
                            </label>
                        </div>
                    </div>

                    <div id="reduceStockSection" style="display: none;">
                        <div class="mb-3">
                            <label for="reduce_quantity" class="form-label">Kuantiti untuk Dikurangkan</label>
                            <input type="number" class="form-control" id="reduce_quantity" name="reduce_quantity" min="1">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="action_reason" class="form-label">Catatan</label>
                        <textarea class="form-control" id="action_reason" name="action_reason" rows="3"
                                  placeholder="Sebab tindakan yang diambil..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Laksanakan Tindakan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const updateStatusModal = document.getElementById('updateStatusModal');
    const updateStatusForm = document.getElementById('updateStatusForm');
    const medicineName = document.getElementById('medicineName');
    const expiryDate = document.getElementById('expiryDate');
    const daysToExpiry = document.getElementById('daysToExpiry');
    const reduceStockSection = document.getElementById('reduceStockSection');
    const actionRadios = document.querySelectorAll('input[name="action_type"]');

    updateStatusModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const medicineId = button.getAttribute('data-medicine-id');
        const medicineName_ = button.getAttribute('data-medicine-name');
        const expiryDate_ = button.getAttribute('data-expiry-date');
        const daysToExpiry_ = button.getAttribute('data-days-to-expiry');

        medicineName.textContent = medicineName_;
        expiryDate.textContent = expiryDate_;
        daysToExpiry.textContent = daysToExpiry_;

        updateStatusForm.action = `/admin/medicine/${medicineId}/update-stock`;
    });

    actionRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'reduce_stock') {
                reduceStockSection.style.display = 'block';
            } else {
                reduceStockSection.style.display = 'none';
            }
        });
    });
});
</script>
@endpush
