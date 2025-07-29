@extends('layouts.admin')

@section('title', 'Detail Ubat - ' . $medicine->name)

@section('content')
<div class="container-fluid">

    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Detail Ubat</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.medicine.index') }}">Inventori Ubat</a></li>
                        <li class="breadcrumb-item active">{{ $medicine->name }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Medicine Information -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-capsule text-primary me-2"></i>
                        {{ $medicine->name }}
                    </h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.medicine.edit', $medicine) }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <button type="button" class="btn btn-outline-success btn-sm"
                                data-bs-toggle="modal" data-bs-target="#updateStockModal">
                            <i class="bi bi-plus-minus"></i> Update Stok
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Kod Ubat:</th>
                                    <td><code class="fs-6">{{ $medicine->medicine_code }}</code></td>
                                </tr>
                                <tr>
                                    <th>Nama:</th>
                                    <td class="fw-bold">{{ $medicine->name }}</td>
                                </tr>
                                <tr>
                                    <th>Kategori:</th>
                                    <td><span class="badge bg-secondary">{{ $medicine->category_label }}</span></td>
                                </tr>
                                <tr>
                                    <th>Kekuatan:</th>
                                    <td>{{ $medicine->strength ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Pengeluar:</th>
                                    <td>{{ $medicine->manufacturer ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Nombor Batch:</th>
                                    <td>{{ $medicine->batch_number ?: '-' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Status:</th>
                                    <td>{!! $medicine->status_badge !!}</td>
                                </tr>
                                <tr>
                                    <th>Harga Unit:</th>
                                    <td class="fw-bold text-success">RM {{ number_format($medicine->unit_price, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Stok Semasa:</th>
                                    <td>
                                        <span class="fw-bold fs-5">{{ $medicine->stock_quantity }}</span>
                                        {!! $medicine->stock_status !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Stok Minimum:</th>
                                    <td>{{ $medicine->minimum_stock }}</td>
                                </tr>
                                <tr>
                                    <th>Tarikh Luput:</th>
                                    <td>
                                        @if($medicine->expiry_date)
                                            {{ $medicine->expiry_date->format('d/m/Y') }}
                                            <br>{!! $medicine->expiry_status !!}
                                        @else
                                            <span class="text-muted">Tiada tarikh luput</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Nilai Total:</th>
                                    <td class="fw-bold text-primary">RM {{ number_format($medicine->total_value, 2) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($medicine->description)
                        <div class="mt-4">
                            <h6>Keterangan:</h6>
                            <p class="text-muted">{{ $medicine->description }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Stock Summary -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Ringkasan Stok</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-12 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="mb-1">{{ $medicine->stock_quantity }}</h4>
                                <p class="mb-0 text-muted">Unit Tersedia</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <h6 class="mb-1">{{ $medicine->minimum_stock }}</h6>
                                <small class="text-muted">Min Stock</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <h6 class="mb-1">RM {{ number_format($medicine->total_value, 0) }}</h6>
                                <small class="text-muted">Nilai Total</small>
                            </div>
                        </div>
                    </div>

                    @if($medicine->isLowStock())
                        <div class="alert alert-warning mt-3 mb-0">
                            <i class="bi bi-exclamation-triangle"></i>
                            <strong>Alert Stok Rendah!</strong><br>
                            Stok telah mencapai paras minimum.
                        </div>
                    @endif

                    @if($medicine->isExpiringSoon())
                        <div class="alert alert-danger mt-3 mb-0">
                            <i class="bi bi-clock"></i>
                            <strong>Hampir Luput!</strong><br>
                            Ubat akan luput dalam {{ Carbon\Carbon::now()->diffInDays($medicine->expiry_date) }} hari.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Updates -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Maklumat Tambahan</h6>
                </div>
                <div class="card-body">
                    <small class="text-muted">
                        <strong>Dicipta:</strong><br>
                        {{ $medicine->created_at->format('d/m/Y H:i') }}
                    </small>
                    <hr>
                    <small class="text-muted">
                        <strong>Kemaskini Terakhir:</strong><br>
                        {{ $medicine->updated_at->format('d/m/Y H:i') }}
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('admin.medicine.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali ke Senarai
                </a>
                <a href="{{ route('admin.medicine.edit', $medicine) }}" class="btn btn-primary">
                    <i class="bi bi-pencil"></i> Edit Ubat
                </a>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="bi bi-trash"></i> Padam
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Update Stock Modal -->
<div class="modal fade" id="updateStockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.medicine.update-stock', $medicine) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title">Update Stok - {{ $medicine->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Stok Semasa: <span class="fw-bold">{{ $medicine->stock_quantity }} unit</span></label>
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
                        <label for="reason" class="form-label">Sebab</label>
                        <input type="text" class="form-control" id="reason" name="reason"
                               placeholder="Sebab penambahan/pengurangan stok...">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Stok</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Padam Ubat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i>
                    <strong>Amaran!</strong> Tindakan ini tidak boleh dibatalkan.
                </div>
                <p>Adakah anda pasti ingin memadam ubat <strong>{{ $medicine->name }}</strong>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.medicine.destroy', $medicine) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Ya, Padam</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
