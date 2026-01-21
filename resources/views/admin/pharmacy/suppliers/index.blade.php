@extends('layouts.admin')

@section('title', 'Senarai Pembekal - Farmasi')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between">
        <div>
            <h1>Senarai Pembekal</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.pharmacy.medicines.index') }}">Farmasi</a></li>
                    <li class="breadcrumb-item active">Pembekal</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.pharmacy.suppliers.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Tambah Pembekal
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card card-default">
                <div class="card-header">
                    <h2>Carian & Tapis</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.pharmacy.suppliers.index') }}" method="GET">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Carian</label>
                                <input type="text" class="form-control" name="search"
                                       value="{{ $filters['search'] ?? '' }}"
                                       placeholder="Nama, telefon, email...">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Status</label>
                                <select name="is_active" class="form-select">
                                    <option value="">Semua Status</option>
                                    <option value="1" {{ ($filters['is_active'] ?? '') === '1' ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ ($filters['is_active'] ?? '') === '0' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="bi bi-search me-1"></i> Cari
                                </button>
                                <a href="{{ route('admin.pharmacy.suppliers.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-lg me-1"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card card-default">
                <div class="card-body">
                    @if($suppliers->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-building text-muted" style="font-size: 4rem;"></i>
                            <h4 class="mt-3 text-muted">Tiada Pembekal Dijumpai</h4>
                            <p class="text-muted">Sila tambah pembekal baru atau ubah kriteria carian.</p>
                            <a href="{{ route('admin.pharmacy.suppliers.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-lg me-1"></i> Tambah Pembekal
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Kod</th>
                                        <th>Nama Pembekal</th>
                                        <th>Hubungi</th>
                                        <th>Lokasi</th>
                                        <th>Terma Bayaran</th>
                                        <th>Status</th>
                                        <th class="text-end">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($suppliers as $supplier)
                                    <tr>
                                        <td>
                                            <code>{{ $supplier->code }}</code>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.pharmacy.suppliers.show', $supplier) }}" class="fw-semibold text-dark">
                                                {{ $supplier->name }}
                                            </a>
                                            @if($supplier->contact_person)
                                            <br><small class="text-muted">PIC: {{ $supplier->contact_person }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($supplier->phone)
                                            <div><i class="bi bi-telephone text-muted me-1"></i>{{ $supplier->phone }}</div>
                                            @endif
                                            @if($supplier->email)
                                            <div><i class="bi bi-envelope text-muted me-1"></i>{{ $supplier->email }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            @if($supplier->city || $supplier->state)
                                            {{ $supplier->city }}{{ $supplier->city && $supplier->state ? ', ' : '' }}{{ $supplier->state }}
                                            @else
                                            <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($supplier->payment_terms)
                                            {{ $supplier->payment_terms }} hari
                                            @else
                                            <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($supplier->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                            @else
                                            <span class="badge bg-secondary">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group">
                                                <a href="{{ route('admin.pharmacy.suppliers.show', $supplier) }}"
                                                   class="btn btn-sm btn-outline-info" title="Lihat">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.pharmacy.suppliers.edit', $supplier) }}"
                                                   class="btn btn-sm btn-outline-primary" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                        title="Padam"
                                                        onclick="confirmDelete({{ $supplier->id }}, '{{ $supplier->name }}')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                            <form id="delete-form-{{ $supplier->id }}"
                                                  action="{{ route('admin.pharmacy.suppliers.destroy', $supplier) }}"
                                                  method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div class="text-muted">
                                Menunjukkan {{ $suppliers->firstItem() ?? 0 }} - {{ $suppliers->lastItem() ?? 0 }}
                                daripada {{ $suppliers->total() }} pembekal
                            </div>
                            {{ $suppliers->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(id, name) {
    if (confirm('Adakah anda pasti mahu memadam pembekal "' + name + '"?')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endpush
@endsection
