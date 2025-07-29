@extends('layouts.admin')

@section('title', 'Laporan Stok Ubat')

@section('content')
<div class="container-fluid">

    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Laporan Stok Ubat</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.medicine.index') }}">Inventori Ubat</a></li>
                        <li class="breadcrumb-item active">Laporan Stok</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="card bg-primary bg-opacity-10 border-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm rounded-circle bg-primary flex-shrink-0">
                            <span class="avatar-title">
                                <i class="bi bi-capsule text-white"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">{{ $totalMedicines }}</h5>
                            <p class="text-muted mb-0">Jumlah Jenis Ubat</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card bg-success bg-opacity-10 border-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm rounded-circle bg-success flex-shrink-0">
                            <span class="avatar-title">
                                <i class="bi bi-check-circle text-white"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">{{ $activeMedicines }}</h5>
                            <p class="text-muted mb-0">Ubat Aktif</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card bg-warning bg-opacity-10 border-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm rounded-circle bg-warning flex-shrink-0">
                            <span class="avatar-title">
                                <i class="bi bi-exclamation-triangle text-white"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">{{ $lowStockMedicines }}</h5>
                            <p class="text-muted mb-0">Stok Rendah</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card bg-danger bg-opacity-10 border-danger">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm rounded-circle bg-danger flex-shrink-0">
                            <span class="avatar-title">
                                <i class="bi bi-clock text-white"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">{{ $expiringSoonMedicines }}</h5>
                            <p class="text-muted mb-0">Hampir Luput</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Stock Value -->
    <div class="row">
        <div class="col-12">
            <div class="card bg-info bg-opacity-10 border-info">
                <div class="card-body text-center">
                    <h2 class="text-info mb-2">RM {{ number_format($totalStockValue, 2) }}</h2>
                    <p class="text-muted mb-0 fs-5">Jumlah Nilai Inventori Ubat</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock by Category -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-pie-chart text-primary me-2"></i>
                        Inventori Mengikut Kategori
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Kategori</th>
                                    <th>Jumlah Jenis</th>
                                    <th>Total Stok</th>
                                    <th>Nilai (RM)</th>
                                    <th>Peratusan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($medicinesByCategory as $category)
                                    @php
                                        $percentage = $totalStockValue > 0 ? ($category->total_value / $totalStockValue) * 100 : 0;
                                        $categoryLabel = ucfirst($category->category);

                                        $badgeClass = match($category->category) {
                                            'tablet' => 'bg-primary',
                                            'capsule' => 'bg-success',
                                            'syrup' => 'bg-warning',
                                            'injection' => 'bg-danger',
                                            'cream' => 'bg-info',
                                            'drops' => 'bg-secondary',
                                            'spray' => 'bg-dark',
                                            'patch' => 'bg-light text-dark',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <tr>
                                        <td>
                                            <span class="badge {{ $badgeClass }}">{{ $categoryLabel }}</span>
                                        </td>
                                        <td>{{ $category->count }}</td>
                                        <td class="fw-bold">{{ number_format($category->total_stock) }}</td>
                                        <td class="fw-bold text-success">{{ number_format($category->total_value, 2) }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                                    <div class="progress-bar" role="progressbar"
                                                         style="width: {{ $percentage }}%"></div>
                                                </div>
                                                <span class="text-muted small">{{ number_format($percentage, 1) }}%</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Tindakan Pantas</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.medicine.create') }}" class="btn btn-success">
                            <i class="bi bi-plus-circle me-2"></i>Tambah Ubat Baru
                        </a>
                        <a href="{{ route('admin.medicine.low-stock') }}" class="btn btn-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>Lihat Stok Rendah
                        </a>
                        <a href="{{ route('admin.medicine.expiring') }}" class="btn btn-danger">
                            <i class="bi bi-clock me-2"></i>Lihat Hampir Luput
                        </a>
                        <a href="{{ route('admin.medicine.index') }}" class="btn btn-primary">
                            <i class="bi bi-list me-2"></i>Senarai Lengkap
                        </a>
                    </div>
                </div>
            </div>

            <!-- Export Options -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Eksport Laporan</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-success" onclick="window.print()">
                            <i class="bi bi-printer me-2"></i>{{ __('medicine.print') }} {{ __('medicine.inventory_report') }}
                        </button>
                        <button type="button" class="btn btn-info" onclick="exportToExcel()">
                            <i class="bi bi-file-earmark-excel me-2"></i>{{ __('medicine.export') }} Excel
                        </button>
                        <button type="button" class="btn btn-danger" onclick="exportToPDF()">
                            <i class="bi bi-file-earmark-pdf me-2"></i>{{ __('medicine.export') }} PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Stock Medicines -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-graph-up text-success me-2"></i>
                        Top 10 - Stok Tertinggi
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Ranking</th>
                                    <th>Nama Ubat</th>
                                    <th>Stok</th>
                                    <th>Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topStockMedicines as $index => $medicine)
                                    <tr>
                                        <td>
                                            @if($index == 0)
                                                <i class="bi bi-trophy-fill text-warning"></i>
                                            @elseif($index == 1)
                                                <i class="bi bi-award-fill text-secondary"></i>
                                            @elseif($index == 2)
                                                <i class="bi bi-award-fill text-warning"></i>
                                            @else
                                                <span class="text-muted">{{ $index + 1 }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $medicine->name }}</strong>
                                            <br><small class="text-muted">{{ $medicine->medicine_code }}</small>
                                        </td>
                                        <td class="fw-bold">{{ number_format($medicine->stock_quantity) }}</td>
                                        <td class="text-success">RM {{ number_format($medicine->total_value, 0) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-currency-dollar text-success me-2"></i>
                        Top 10 - Nilai Tertinggi
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Ranking</th>
                                    <th>Nama Ubat</th>
                                    <th>Stok</th>
                                    <th>Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topValueMedicines as $index => $medicine)
                                    <tr>
                                        <td>
                                            @if($index == 0)
                                                <i class="bi bi-trophy-fill text-warning"></i>
                                            @elseif($index == 1)
                                                <i class="bi bi-award-fill text-secondary"></i>
                                            @elseif($index == 2)
                                                <i class="bi bi-award-fill text-warning"></i>
                                            @else
                                                <span class="text-muted">{{ $index + 1 }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $medicine->name }}</strong>
                                            <br><small class="text-muted">RM {{ number_format($medicine->unit_price, 2) }}/unit</small>
                                        </td>
                                        <td>{{ number_format($medicine->stock_quantity) }}</td>
                                        <td class="fw-bold text-success">RM {{ number_format($medicine->total_value, 0) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Generated Report Info -->
    <div class="row">
        <div class="col-12">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <small class="text-muted">
                        Laporan dijana pada: <strong>{{ now()->format('d/m/Y H:i:s') }}</strong>
                        | Jumlah data: <strong>{{ $totalMedicines }} jenis ubat</strong>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function exportToExcel() {
    // Simulate export functionality
    alert('Fungsi eksport Excel akan dibangunkan dalam fasa seterusnya.');
}

function exportToPDF() {
    // Simulate export functionality
    alert('Fungsi eksport PDF akan dibangunkan dalam fasa seterusnya.');
}

// Print styles
const printStyles = `
<style>
@media print {
    .btn, .breadcrumb, .page-title-right { display: none !important; }
    .card { border: 1px solid #ddd !important; page-break-inside: avoid; }
    .progress { background: #f0f0f0 !important; }
    .progress-bar { background: #007bff !important; }
}
</style>
`;

document.head.insertAdjacentHTML('beforeend', printStyles);
</script>
@endpush
