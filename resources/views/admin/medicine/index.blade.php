@extends('layouts.admin')

@section('title', 'Senarai Ubat - Inventori')

@push('styles')
<!-- DataTables CSS -->
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid">

    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0"><i class="bi bi-capsule me-2"></i>{{ __('medicine.medicine_inventory') }}</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Inventori Ubat</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card bg-primary bg-opacity-10 border-primary">
                <div class="card-body text-center">
                    <h4 class="text-primary">{{ $medicines->count() }}</h4>
                    <p class="mb-0">Total Ubat</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning bg-opacity-10 border-warning">
                <div class="card-body text-center">
                    <h4 class="text-warning">{{ $medicines->filter(function($m) { return $m->isLowStock(); })->count() }}</h4>
                    <p class="mb-0">Stok Rendah</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger bg-opacity-10 border-danger">
                <div class="card-body text-center">
                    <h4 class="text-danger">{{ $medicines->filter(function($m) { return $m->isExpiringSoon(); })->count() }}</h4>
                    <p class="mb-0">Hampir Luput</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success bg-opacity-10 border-success">
                <div class="card-body text-center">
                    <h4 class="text-success">RM {{ number_format($medicines->sum('total_value'), 0) }}</h4>
                    <p class="mb-0">Nilai Total</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Medicine List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><i class="bi bi-list-ul me-2"></i>{{ __('medicine.medicine_list') }}</h5>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('admin.medicine.create') }}" class="btn btn-success btn-sm">
                            <i class="bi bi-plus-circle me-1"></i> {{ __('medicine.add_new_medicine') }}
                        </a>
                        <a href="{{ route('admin.medicine.low-stock') }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-exclamation-triangle me-1"></i> {{ __('medicine.low_stock') }}
                        </a>
                        <a href="{{ route('admin.medicine.expiring') }}" class="btn btn-danger btn-sm">
                            <i class="bi bi-clock me-1"></i> {{ __('medicine.expiring_soon') }}
                        </a>
                        <a href="{{ route('admin.medicine.stock-report') }}" class="btn btn-info btn-sm">
                            <i class="bi bi-bar-chart me-1"></i> {{ __('medicine.stock_report') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="medicinesTable" class="table table-striped table-hover w-100">
                            <thead class="table-dark">
                                <tr>
                                    <th>Kod Ubat</th>
                                    <th>Nama Ubat</th>
                                    <th>Kategori</th>
                                    <th>Kekuatan</th>
                                    <th>Stok</th>
                                    <th>Status Stok</th>
                                    <th>Harga Unit</th>
                                    <th>Nilai Total</th>
                                    <th>Status</th>
                                    <th>Luput</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($medicines as $medicine)
                                    <tr>
                                        <td>
                                            <code class="small">{{ $medicine->medicine_code }}</code>
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ $medicine->name }}</strong>
                                                @if($medicine->manufacturer)
                                                    <br><small class="text-muted">{{ $medicine->manufacturer }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $medicine->category_label }}</span>
                                        </td>
                                        <td>{{ $medicine->strength ?: '-' }}</td>
                                        <td class="text-center">
                                            <span class="fw-bold">{{ $medicine->stock_quantity }}</span>
                                            @if($medicine->isLowStock())
                                                <br><small class="text-warning">Min: {{ $medicine->minimum_stock }}</small>
                                            @endif
                                        </td>
                                        <td>{!! $medicine->stock_status !!}</td>
                                        <td class="text-end">RM {{ number_format($medicine->unit_price, 2) }}</td>
                                        <td class="text-end fw-bold text-success">RM {{ number_format($medicine->total_value, 2) }}</td>
                                        <td>{!! $medicine->status_badge !!}</td>
                                        <td>
                                            @if($medicine->expiry_date)
                                                <div>
                                                    {{ $medicine->expiry_date->format('d/m/Y') }}
                                                    <br>{!! $medicine->expiry_status !!}
                                                </div>
                                            @else
                                                <span class="text-muted">Tiada</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.medicine.show', $medicine) }}"
                                                   class="btn btn-sm btn-info" title="{{ __('medicine.view') }}">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.medicine.edit', $medicine) }}"
                                                   class="btn btn-sm btn-primary" title="{{ __('medicine.edit') }}">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-success"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#updateStockModal"
                                                        data-medicine-id="{{ $medicine->id }}"
                                                        data-medicine-name="{{ $medicine->name }}"
                                                        title="{{ __('medicine.update_stock') }}">
                                                    <i class="bi bi-plus-minus"></i>
                                                </button>
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
    </div>
</div>

<!-- Update Stock Modal -->
<div class="modal fade" id="updateStockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="updateStockForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title">Update Stok Ubat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Ubat: <span id="medicineName" class="fw-bold"></span></label>
                    </div>
                    <div class="mb-3">
                        <label for="action" class="form-label">Aksi</label>
                        <select class="form-select" id="action" name="action" required>
                            <option value="">Pilih aksi...</option>
                            <option value="add">Tambah Stok</option>
                            <option value="subtract">Kurangkan Stok</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Kuantiti</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="reason" class="form-label">Sebab (Optional)</label>
                        <input type="text" class="form-control" id="reason" name="reason"
                               placeholder="Sebab penambahan/pengurangan stok...">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('medicine.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('medicine.update_stock') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#medicinesTable').DataTable({
        responsive: true,
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
        language: {
            "lengthMenu": "Papar _MENU_ entri",
            "zeroRecords": "Tiada rekod dijumpai",
            "info": "Memaparkan _START_ hingga _END_ daripada _TOTAL_ entri",
            "infoEmpty": "Memaparkan 0 hingga 0 daripada 0 entri",
            "infoFiltered": "(ditapis daripada _MAX_ jumlah entri)",
            "search": "Cari:",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Seterusnya",
                "previous": "Sebelumnya"
            },
            "processing": "Memproses...",
            "loadingRecords": "Memuatkan...",
            "emptyTable": "Tiada data tersedia dalam jadual"
        },
        dom: '<"row"<"col-md-6"l><"col-md-6"f>>t<"row"<"col-md-6"i><"col-md-6"p>>',
        columnDefs: [
            {
                targets: [4, 6, 7], // Stock, Unit Price, Total Value columns
                className: 'text-center'
            },
            {
                targets: [10], // Action column
                orderable: false,
                searchable: false,
                className: 'text-center'
            }
        ],
        order: [[1, 'asc']], // Default sort by medicine name
        buttons: [
            {
                extend: 'excel',
                text: '<i class="bi bi-file-earmark-excel"></i> Excel',
                className: 'btn btn-success btn-sm',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 6, 7, 8, 9] // Exclude action column
                }
            },
            {
                extend: 'pdf',
                text: '<i class="bi bi-file-earmark-pdf"></i> PDF',
                className: 'btn btn-danger btn-sm',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 6, 7, 8, 9] // Exclude action column
                }
            },
            {
                extend: 'print',
                text: '<i class="bi bi-printer"></i> Cetak',
                className: 'btn btn-secondary btn-sm',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 6, 7, 8, 9] // Exclude action column
                }
            }
        ]
    });

    // Add custom search filters
    $('#categoryFilter').on('change', function() {
        var category = $(this).val();
        if (category) {
            table.column(2).search(category).draw();
        } else {
            table.column(2).search('').draw();
        }
    });

    $('#statusFilter').on('change', function() {
        var status = $(this).val();
        if (status) {
            table.column(8).search(status).draw();
        } else {
            table.column(8).search('').draw();
        }
    });

    $('#stockFilter').on('change', function() {
        if ($(this).is(':checked')) {
            table.column(5).search('Stok Rendah|Habis', true, false).draw();
        } else {
            table.column(5).search('').draw();
        }
    });

    // Update Stock Modal functionality
    const updateStockModal = document.getElementById('updateStockModal');
    const updateStockForm = document.getElementById('updateStockForm');
    const medicineName = document.getElementById('medicineName');

    updateStockModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const medicineId = button.getAttribute('data-medicine-id');
        const medicineName_ = button.getAttribute('data-medicine-name');

        medicineName.textContent = medicineName_;
        updateStockForm.action = `/admin/medicine/${medicineId}/update-stock`;
    });

    // Custom search for stock status
    $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex) {
            var stockFilter = $('#stockFilter').is(':checked');
            if (!stockFilter) {
                return true;
            }

            var stockStatus = data[5]; // Stock status column
            return stockStatus.includes('Rendah') || stockStatus.includes('Habis');
        }
    );
});

// Add some custom filters above the table
$(document).ready(function() {
    // Add filter controls above the table
    var filterHtml = `
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="card bg-light">
                    <div class="card-body">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label for="categoryFilter" class="form-label small">Filter Kategori</label>
                                <select class="form-select form-select-sm" id="categoryFilter">
                                    <option value="">Semua Kategori</option>
                                    <option value="Tablet">Tablet</option>
                                    <option value="Kapsul">Kapsul</option>
                                    <option value="Sirap">Sirap</option>
                                    <option value="Suntikan">Suntikan</option>
                                    <option value="Krim">Krim</option>
                                    <option value="Titisan">Titisan</option>
                                    <option value="Semburan">Semburan</option>
                                    <option value="Tampalan">Tampalan</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="statusFilter" class="form-label small">Filter Status</label>
                                <select class="form-select form-select-sm" id="statusFilter">
                                    <option value="">Semua Status</option>
                                    <option value="Aktif">Aktif</option>
                                    <option value="Tidak Aktif">Tidak Aktif</option>
                                    <option value="Luput">Luput</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="stockFilter">
                                    <label class="form-check-label small" for="stockFilter">
                                        Stok Rendah/Habis Sahaja
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="resetFilters()">
                                    <i class="bi bi-arrow-clockwise"></i> Reset Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;

    $('#medicinesTable').closest('.card').before(filterHtml);
});

function resetFilters() {
    $('#categoryFilter').val('');
    $('#statusFilter').val('');
    $('#stockFilter').prop('checked', false);
    $('#medicinesTable').DataTable().search('').columns().search('').draw();
}
</script>
@endpush
