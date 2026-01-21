@extends('layouts.admin')
@section('title', 'Butiran Ubat: ' . $medicine->name)

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper breadcrumb-contacts">
        <div>
            <h1>{{ $medicine->name }}</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span><a href="{{ route('admin.pharmacy.medicines.index') }}">Farmasi</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>{{ $medicine->code }}</span>
            </p>
        </div>
        <div>
            <a href="{{ route('admin.pharmacy.medicines.edit', $medicine) }}" class="btn btn-primary">
                <i class="mdi mdi-pencil me-1"></i> Edit
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <!-- Basic Information -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="mdi mdi-pill me-2"></i>Maklumat Ubat</h5>
                    <div>
                        @if($medicine->is_active)
                        <span class="badge bg-success">Aktif</span>
                        @else
                        <span class="badge bg-secondary">Tidak Aktif</span>
                        @endif
                        @if($medicine->is_controlled)
                        <span class="badge bg-danger">Ubat Terkawal</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="text-muted" width="140">Kod:</td>
                                    <td><strong>{{ $medicine->code }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Barcode:</td>
                                    <td>{{ $medicine->barcode ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Nama:</td>
                                    <td><strong>{{ $medicine->name }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Nama Generik:</td>
                                    <td>{{ $medicine->name_generic ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Kategori:</td>
                                    <td>{{ $medicine->category->name ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="text-muted" width="140">Bentuk Dos:</td>
                                    <td>{{ $medicine->dosage_form_label ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Kekuatan:</td>
                                    <td>{{ $medicine->strength ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Unit:</td>
                                    <td>{{ $medicine->unit }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Pengilang:</td>
                                    <td>{{ $medicine->manufacturer ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Penyimpanan:</td>
                                    <td>{{ $medicine->storage_conditions ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($medicine->dosage_instructions)
                    <hr>
                    <h6 class="mb-2">Arahan Dos</h6>
                    <p class="mb-0">{{ $medicine->dosage_instructions }}</p>
                    @endif

                    @if($medicine->contraindications)
                    <hr>
                    <h6 class="mb-2 text-danger">Kontraindikasi</h6>
                    <p class="mb-0">{{ $medicine->contraindications }}</p>
                    @endif

                    @if($medicine->side_effects)
                    <hr>
                    <h6 class="mb-2 text-warning">Kesan Sampingan</h6>
                    <p class="mb-0">{{ $medicine->side_effects }}</p>
                    @endif
                </div>
            </div>

            <!-- Stock Movements -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="mdi mdi-swap-horizontal me-2"></i>Pergerakan Stok Terkini</h5>
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#adjustStockModal">
                        <i class="mdi mdi-plus me-1"></i> Laras Stok
                    </button>
                </div>
                <div class="card-body">
                    @if($medicine->stockMovements->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Tarikh</th>
                                    <th>Jenis</th>
                                    <th class="text-end">Kuantiti</th>
                                    <th class="text-end">Stok Selepas</th>
                                    <th>Batch</th>
                                    <th>Sebab</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($medicine->stockMovements as $movement)
                                <tr>
                                    <td>{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <span class="badge bg-{{ in_array($movement->movement_type, ['in', 'return']) ? 'success' : 'danger' }}">
                                            {{ $movement->movement_type_label }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        {{ in_array($movement->movement_type, ['in', 'return']) ? '+' : '-' }}{{ $movement->quantity }}
                                    </td>
                                    <td class="text-end">{{ $movement->stock_after }}</td>
                                    <td>{{ $movement->batch_no ?? '-' }}</td>
                                    <td>{{ Str::limit($movement->reason, 30) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-muted text-center mb-0">Tiada pergerakan stok.</p>
                    @endif
                </div>
            </div>

            <!-- Batches -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="mdi mdi-package-variant me-2"></i>Batch Stok</h5>
                </div>
                <div class="card-body">
                    @if($medicine->batches->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>No. Batch</th>
                                    <th>Tarikh Luput</th>
                                    <th class="text-end">Kuantiti Asal</th>
                                    <th class="text-end">Kuantiti Semasa</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($medicine->batches as $batch)
                                <tr class="{{ $batch->isExpired() ? 'table-danger' : ($batch->isExpiringSoon() ? 'table-warning' : '') }}">
                                    <td><strong>{{ $batch->batch_no }}</strong></td>
                                    <td>
                                        {{ $batch->expiry_date->format('d/m/Y') }}
                                        @if($batch->isExpired())
                                        <span class="badge bg-danger">Luput</span>
                                        @elseif($batch->isExpiringSoon())
                                        <span class="badge bg-warning">{{ $batch->expiry_date->diffInDays(now()) }} hari lagi</span>
                                        @endif
                                    </td>
                                    <td class="text-end">{{ $batch->initial_quantity }}</td>
                                    <td class="text-end">{{ $batch->current_quantity }}</td>
                                    <td>
                                        <span class="badge bg-{{ $batch->status === 'active' ? 'success' : ($batch->status === 'low' ? 'warning' : 'secondary') }}">
                                            {{ $batch->status_label }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-muted text-center mb-0">Tiada maklumat batch.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Stock Status -->
            <div class="card mb-4 {{ $medicine->stock_status === 'out_of_stock' ? 'border-danger' : ($medicine->stock_status === 'low' ? 'border-warning' : 'border-success') }}">
                <div class="card-header bg-{{ $medicine->stock_status === 'out_of_stock' ? 'danger' : ($medicine->stock_status === 'low' ? 'warning' : 'success') }} text-{{ $medicine->stock_status === 'low' ? 'dark' : 'white' }}">
                    <h5 class="mb-0"><i class="mdi mdi-package-variant me-2"></i>Status Stok</h5>
                </div>
                <div class="card-body text-center">
                    <h1 class="display-4 mb-0">{{ $medicine->stock_quantity }}</h1>
                    <p class="text-muted mb-3">{{ $medicine->unit }}</p>
                    <span class="badge bg-{{ $medicine->stock_status === 'available' ? 'success' : ($medicine->stock_status === 'low' ? 'warning' : 'danger') }} fs-6">
                        {{ $medicine->stock_status_label }}
                    </span>
                    <hr>
                    <div class="row text-center">
                        <div class="col-6">
                            <small class="text-muted d-block">Paras Pesanan</small>
                            <strong>{{ $medicine->reorder_level }}</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Paras Maksimum</small>
                            <strong>{{ $medicine->max_stock_level }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="mdi mdi-cash me-2"></i>Harga</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Harga Kos:</span>
                        <strong>RM {{ number_format($medicine->cost_price, 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Harga Jualan:</span>
                        <strong class="text-primary fs-5">RM {{ number_format($medicine->selling_price, 2) }}</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Margin:</span>
                        @php
                        $margin = $medicine->cost_price > 0 ? (($medicine->selling_price - $medicine->cost_price) / $medicine->cost_price) * 100 : 0;
                        @endphp
                        <strong class="text-success">{{ number_format($margin, 1) }}%</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Nilai Stok:</span>
                        <strong>RM {{ number_format($medicine->stock_quantity * $medicine->cost_price, 2) }}</strong>
                    </div>
                </div>
            </div>

            <!-- Expiry Status -->
            @if($medicine->expiry_date)
            <div class="card mb-4 {{ $medicine->isExpired() ? 'border-danger' : ($medicine->isExpiringSoon() ? 'border-warning' : '') }}">
                <div class="card-header">
                    <h5 class="mb-0"><i class="mdi mdi-calendar-alert me-2"></i>Tarikh Luput</h5>
                </div>
                <div class="card-body text-center">
                    <h4 class="{{ $medicine->isExpired() ? 'text-danger' : ($medicine->isExpiringSoon() ? 'text-warning' : '') }}">
                        {{ $medicine->expiry_date->format('d/m/Y') }}
                    </h4>
                    @if($medicine->isExpired())
                    <span class="badge bg-danger">TELAH LUPUT</span>
                    @elseif($medicine->isExpiringSoon())
                    <span class="badge bg-warning">{{ $medicine->expiry_date->diffInDays(now()) }} hari lagi</span>
                    @else
                    <span class="badge bg-success">{{ $medicine->expiry_date->diffInDays(now()) }} hari lagi</span>
                    @endif
                </div>
            </div>
            @endif

            <!-- Control Info -->
            @if($medicine->is_controlled)
            <div class="card mb-4 border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="mdi mdi-shield-alert me-2"></i>Ubat Terkawal</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Jadual Racun:</strong> {{ $medicine->poison_schedule ?? 'Tidak Dinyatakan' }}</p>
                    <small class="text-muted">
                        Ubat ini tertakluk kepada Akta Racun 1952. Semua transaksi mesti direkodkan dalam Daftar Racun.
                    </small>
                </div>
            </div>
            @endif

            <!-- Actions -->
            <div class="card">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.pharmacy.medicines.edit', $medicine) }}" class="btn btn-primary">
                            <i class="mdi mdi-pencil me-2"></i>Edit Ubat
                        </a>
                        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#adjustStockModal">
                            <i class="mdi mdi-package-variant me-2"></i>Laras Stok
                        </button>
                        <a href="{{ route('admin.pharmacy.medicines.index') }}" class="btn btn-outline-secondary">
                            <i class="mdi mdi-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stock Adjustment Modal -->
<div class="modal fade" id="adjustStockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.pharmacy.medicines.adjustStock', $medicine) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Laras Stok: {{ $medicine->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Stok semasa: <strong>{{ $medicine->stock_quantity }} {{ $medicine->unit }}</strong></p>

                    <div class="mb-3">
                        <label class="form-label">Jenis Pergerakan</label>
                        <select name="movement_type" class="form-select" required>
                            <option value="in">Masuk (+)</option>
                            <option value="out">Keluar (-)</option>
                            <option value="adjustment">Pelarasan</option>
                            <option value="expired">Luput</option>
                            <option value="damaged">Rosak</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kuantiti</label>
                        <input type="number" name="quantity" class="form-control" min="1" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">No. Batch (Pilihan)</label>
                        <input type="text" name="batch_no" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Sebab</label>
                        <textarea name="reason" class="form-control" rows="2" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
