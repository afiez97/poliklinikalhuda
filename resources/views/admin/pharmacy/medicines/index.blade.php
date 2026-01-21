@extends('layouts.admin')
@section('title', 'Senarai Ubat')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper breadcrumb-contacts">
        <div>
            <h1>Senarai Ubat</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>Farmasi</span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>Ubat</span>
            </p>
        </div>
        <div>
            <a href="{{ route('admin.pharmacy.medicines.create') }}" class="btn btn-primary">
                <i class="mdi mdi-plus me-1"></i> Tambah Ubat
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ number_format($statistics['total_medicines']) }}</h4>
                            <small>Jumlah Ubat</small>
                        </div>
                        <div class="align-self-center">
                            <i class="mdi mdi-pill fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ number_format($statistics['low_stock_count']) }}</h4>
                            <small>Stok Rendah</small>
                        </div>
                        <div class="align-self-center">
                            <i class="mdi mdi-alert fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ number_format($statistics['expiring_soon_count']) }}</h4>
                            <small>Hampir Luput</small>
                        </div>
                        <div class="align-self-center">
                            <i class="mdi mdi-calendar-alert fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">RM {{ number_format($statistics['total_stock_value'], 2) }}</h4>
                            <small>Nilai Stok</small>
                        </div>
                        <div class="align-self-center">
                            <i class="mdi mdi-cash fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.pharmacy.medicines.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama, kod, barcode..." value="{{ $filters['search'] ?? '' }}">
                    </div>
                    <div class="col-md-2">
                        <select name="category_id" class="form-select">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ ($filters['category_id'] ?? '') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="dosage_form" class="form-select">
                            <option value="">Semua Bentuk</option>
                            @foreach($dosageForms as $key => $label)
                            <option value="{{ $key }}" {{ ($filters['dosage_form'] ?? '') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="stock_status" class="form-select">
                            <option value="">Semua Status Stok</option>
                            <option value="available" {{ ($filters['stock_status'] ?? '') == 'available' ? 'selected' : '' }}>Tersedia</option>
                            <option value="low" {{ ($filters['stock_status'] ?? '') == 'low' ? 'selected' : '' }}>Stok Rendah</option>
                            <option value="out_of_stock" {{ ($filters['stock_status'] ?? '') == 'out_of_stock' ? 'selected' : '' }}>Habis Stok</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-magnify me-1"></i> Cari
                        </button>
                        <a href="{{ route('admin.pharmacy.medicines.index') }}" class="btn btn-outline-secondary">
                            <i class="mdi mdi-refresh me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Medicines Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Kod</th>
                            <th>Nama Ubat</th>
                            <th>Kategori</th>
                            <th>Bentuk/Dos</th>
                            <th class="text-end">Harga</th>
                            <th class="text-center">Stok</th>
                            <th>Status</th>
                            <th class="text-end">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($medicines as $medicine)
                        <tr>
                            <td>
                                <a href="{{ route('admin.pharmacy.medicines.show', $medicine) }}" class="fw-bold text-primary">
                                    {{ $medicine->code }}
                                </a>
                                @if($medicine->is_controlled)
                                <span class="badge bg-danger ms-1" title="Ubat Terkawal">
                                    <i class="mdi mdi-shield-alert"></i>
                                </span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $medicine->name }}</strong>
                                @if($medicine->name_generic)
                                <br><small class="text-muted">{{ $medicine->name_generic }}</small>
                                @endif
                            </td>
                            <td>{{ $medicine->category->name ?? '-' }}</td>
                            <td>
                                {{ $medicine->dosage_form_label ?? '-' }}
                                @if($medicine->strength)
                                <br><small class="text-muted">{{ $medicine->strength }}</small>
                                @endif
                            </td>
                            <td class="text-end">
                                <strong>RM {{ number_format($medicine->selling_price, 2) }}</strong>
                                <br><small class="text-muted">Kos: RM {{ number_format($medicine->cost_price, 2) }}</small>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-{{ $medicine->stock_status === 'available' ? 'success' : ($medicine->stock_status === 'low' ? 'warning' : 'danger') }}">
                                    {{ $medicine->stock_quantity }} {{ $medicine->unit }}
                                </span>
                            </td>
                            <td>
                                @if($medicine->is_active)
                                <span class="badge bg-success">Aktif</span>
                                @else
                                <span class="badge bg-secondary">Tidak Aktif</span>
                                @endif
                                @if($medicine->isExpired())
                                <span class="badge bg-danger">Luput</span>
                                @elseif($medicine->isExpiringSoon())
                                <span class="badge bg-warning">Hampir Luput</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        Tindakan
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.pharmacy.medicines.show', $medicine) }}">
                                                <i class="mdi mdi-eye me-2"></i> Lihat
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.pharmacy.medicines.edit', $medicine) }}">
                                                <i class="mdi mdi-pencil me-2"></i> Edit
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#adjustStockModal{{ $medicine->id }}">
                                                <i class="mdi mdi-package-variant me-2"></i> Laras Stok
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('admin.pharmacy.medicines.destroy', $medicine) }}" method="POST" onsubmit="return confirm('Padam ubat ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="mdi mdi-delete me-2"></i> Padam
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="mdi mdi-pill-off fs-1 text-muted"></i>
                                <p class="text-muted mb-0">Tiada ubat dijumpai.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $medicines->links() }}
        </div>
    </div>
</div>

<!-- Stock Adjustment Modals -->
@foreach($medicines as $medicine)
<div class="modal fade" id="adjustStockModal{{ $medicine->id }}" tabindex="-1">
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
@endforeach
@endsection
