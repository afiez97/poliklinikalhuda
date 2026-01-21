@extends('layouts.admin')
@section('title', 'Jenis Giliran')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper breadcrumb-contacts">
        <div>
            <h1>Jenis Giliran</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span><a href="{{ route('admin.queue.index') }}">Giliran</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>Jenis Giliran</span>
            </p>
        </div>
        <div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTypeModal">
                <i class="mdi mdi-plus"></i> Tambah Jenis
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        @forelse($queueTypes as $type)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <span class="badge bg-primary me-2">{{ $type->code }}</span>
                        {{ $type->name }}
                    </h5>
                    @if($type->is_active)
                    <span class="badge bg-success">Aktif</span>
                    @else
                    <span class="badge bg-secondary">Tidak Aktif</span>
                    @endif
                </div>
                <div class="card-body">
                    @if($type->name_en)
                    <p class="text-muted mb-3">{{ $type->name_en }}</p>
                    @endif

                    <div class="row text-center mb-3">
                        <div class="col-4">
                            <h4 class="mb-0 text-warning">{{ $type->today_tickets ?? 0 }}</h4>
                            <small class="text-muted">Hari Ini</small>
                        </div>
                        <div class="col-4">
                            <h4 class="mb-0 text-info">{{ $type->counters_count ?? 0 }}</h4>
                            <small class="text-muted">Kaunter</small>
                        </div>
                        <div class="col-4">
                            <h4 class="mb-0 text-success">{{ $type->avg_service_time ?? 5 }}m</h4>
                            <small class="text-muted">Purata</small>
                        </div>
                    </div>

                    <ul class="list-unstyled small text-muted">
                        <li><i class="mdi mdi-clock-outline me-1"></i> Operasi: {{ $type->operating_start ?? '08:00' }} - {{ $type->operating_end ?? '17:00' }}</li>
                        <li><i class="mdi mdi-account-multiple me-1"></i> Max Giliran: {{ $type->max_queue_size ?? 'Tiada had' }}</li>
                        @if($type->autoTransferQueue)
                        <li><i class="mdi mdi-arrow-right-bold me-1"></i> Auto-transfer ke: {{ $type->autoTransferQueue->name }}</li>
                        @endif
                    </ul>
                </div>
                <div class="card-footer bg-transparent">
                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editTypeModal{{ $type->id }}">
                        <i class="mdi mdi-pencil"></i> Edit
                    </button>
                    <a href="{{ route('admin.queue.counters') }}?type={{ $type->id }}" class="btn btn-sm btn-outline-info">
                        <i class="mdi mdi-counter"></i> Kaunter
                    </a>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editTypeModal{{ $type->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="#" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Jenis Giliran - {{ $type->code }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Kod</label>
                                    <input type="text" name="code" class="form-control" value="{{ $type->code }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nama (BM)</label>
                                    <input type="text" name="name" class="form-control" value="{{ $type->name }}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nama (EN)</label>
                                    <input type="text" name="name_en" class="form-control" value="{{ $type->name_en }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nama (ZH)</label>
                                    <input type="text" name="name_zh" class="form-control" value="{{ $type->name_zh }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Purata Masa Perkhidmatan (min)</label>
                                    <input type="number" name="avg_service_time" class="form-control" value="{{ $type->avg_service_time }}" min="1">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Max Giliran Harian</label>
                                    <input type="number" name="max_queue_size" class="form-control" value="{{ $type->max_queue_size }}" min="1">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Nisbah Keutamaan</label>
                                    <input type="number" name="priority_ratio" class="form-control" value="{{ $type->priority_ratio }}" min="1" max="10">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Masa Mula</label>
                                    <input type="time" name="operating_start" class="form-control" value="{{ $type->operating_start }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Masa Tamat</label>
                                    <input type="time" name="operating_end" class="form-control" value="{{ $type->operating_end }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Auto-Transfer Ke</label>
                                    <select name="auto_transfer_to" class="form-select">
                                        <option value="">-- Tiada --</option>
                                        @foreach($queueTypes as $targetType)
                                        @if($targetType->id !== $type->id)
                                        <option value="{{ $targetType->id }}" {{ $type->auto_transfer_to == $targetType->id ? 'selected' : '' }}>
                                            {{ $targetType->name }}
                                        </option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" name="is_active" class="form-check-input" value="1" {{ $type->is_active ? 'checked' : '' }}>
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
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="mdi mdi-information-outline mdi-72px text-muted"></i>
                    <h4 class="text-muted">Tiada jenis giliran</h4>
                    <p class="text-muted">Sila tambah jenis giliran baru.</p>
                </div>
            </div>
        </div>
        @endforelse
    </div>
</div>

<!-- Add Type Modal -->
<div class="modal fade" id="addTypeModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="#" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Jenis Giliran Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kod <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control" placeholder="cth: R, D1, F" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama (BM) <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="cth: Pendaftaran" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama (EN)</label>
                            <input type="text" name="name_en" class="form-control" placeholder="cth: Registration">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama (ZH)</label>
                            <input type="text" name="name_zh" class="form-control" placeholder="cth: 登记">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Purata Masa (min)</label>
                            <input type="number" name="avg_service_time" class="form-control" value="5" min="1">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Max Giliran</label>
                            <input type="number" name="max_queue_size" class="form-control" value="200" min="1">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Nisbah Keutamaan</label>
                            <input type="number" name="priority_ratio" class="form-control" value="3" min="1" max="10">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Masa Mula</label>
                            <input type="time" name="operating_start" class="form-control" value="08:00">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Masa Tamat</label>
                            <input type="time" name="operating_end" class="form-control" value="17:00">
                        </div>
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
