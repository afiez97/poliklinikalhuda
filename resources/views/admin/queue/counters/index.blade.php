@extends('layouts.admin')
@section('title', 'Pengurusan Kaunter')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper breadcrumb-contacts">
        <div>
            <h1>Pengurusan Kaunter</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span><a href="{{ route('admin.queue.index') }}">Giliran</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>Kaunter</span>
            </p>
        </div>
        <div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCounterModal">
                <i class="mdi mdi-plus"></i> Tambah Kaunter
            </button>
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
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Kod</th>
                            <th>Nama</th>
                            <th>Jenis Giliran</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                            <th>Dilayan Hari Ini</th>
                            <th class="text-end">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($counters as $counter)
                        <tr>
                            <td><strong>{{ $counter->code }}</strong></td>
                            <td>
                                {{ $counter->name }}
                                @if($counter->name_en)
                                <br><small class="text-muted">{{ $counter->name_en }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $counter->queueType->name }}</span>
                            </td>
                            <td>{{ $counter->location ?? '-' }}</td>
                            <td>
                                @if($counter->is_active)
                                <span class="badge bg-success">Aktif</span>
                                @else
                                <span class="badge bg-secondary">Tidak Aktif</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $counter->today_served_count ?? 0 }}</span>
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editCounterModal{{ $counter->id }}">
                                    <i class="mdi mdi-pencil"></i>
                                </button>
                                <form action="#" method="POST" class="d-inline" onsubmit="return confirm('Padam kaunter ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="mdi mdi-delete"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editCounterModal{{ $counter->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="#" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Kaunter - {{ $counter->code }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Kod Kaunter</label>
                                                <input type="text" name="code" class="form-control" value="{{ $counter->code }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Nama (BM)</label>
                                                <input type="text" name="name" class="form-control" value="{{ $counter->name }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Nama (EN)</label>
                                                <input type="text" name="name_en" class="form-control" value="{{ $counter->name_en }}">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Jenis Giliran</label>
                                                <select name="queue_type_id" class="form-select" required>
                                                    @foreach($queueTypes as $type)
                                                    <option value="{{ $type->id }}" {{ $counter->queue_type_id == $type->id ? 'selected' : '' }}>
                                                        {{ $type->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Lokasi</label>
                                                <input type="text" name="location" class="form-control" value="{{ $counter->location }}">
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" name="is_active" class="form-check-input" value="1" {{ $counter->is_active ? 'checked' : '' }}>
                                                <label class="form-check-label">Aktif</label>
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
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="mdi mdi-information-outline mdi-48px text-muted"></i>
                                <p class="text-muted mb-0">Tiada kaunter didaftarkan</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Counter Modal -->
<div class="modal fade" id="addCounterModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="#" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kaunter Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kod Kaunter <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control" placeholder="cth: K1, BD1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama (BM) <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="cth: Kaunter 1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama (EN)</label>
                        <input type="text" name="name_en" class="form-control" placeholder="cth: Counter 1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jenis Giliran <span class="text-danger">*</span></label>
                        <select name="queue_type_id" class="form-select" required>
                            <option value="">-- Pilih Jenis --</option>
                            @foreach($queueTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lokasi</label>
                        <input type="text" name="location" class="form-control" placeholder="cth: Lobi Utama">
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="is_active" class="form-check-input" value="1" checked>
                        <label class="form-check-label">Aktif</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
