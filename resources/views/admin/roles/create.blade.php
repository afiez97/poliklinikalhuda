@extends('layouts.admin')
@section('title', 'Tambah Peranan')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper">
        <div>
            <h1>Tambah Peranan Baru</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span><a href="{{ route('admin.roles.index') }}">Peranan</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>Tambah</span>
            </p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Maklumat Peranan</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.roles.store') }}" method="POST">
                @csrf

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Nama Peranan (Slug) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required placeholder="contoh: doktor">
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Hanya huruf kecil, nombor dan sengkang</small>
                    </div>
                    <div class="col-md-6">
                        <label for="display_name" class="form-label">Nama Paparan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('display_name') is-invalid @enderror" id="display_name" name="display_name" value="{{ old('display_name') }}" required placeholder="contoh: Doktor">
                        @error('display_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label">Penerangan</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="2">{{ old('description') }}</textarea>
                    @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr>

                <h5 class="mb-3">Kebenaran (Permissions)</h5>

                <div class="mb-3">
                    <button type="button" class="btn btn-sm btn-outline-primary" id="selectAll">Pilih Semua</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAll">Nyahpilih Semua</button>
                </div>

                @foreach($permissions as $group => $perms)
                <div class="card mb-3">
                    <div class="card-header py-2 bg-light">
                        <div class="form-check">
                            <input class="form-check-input group-checkbox" type="checkbox" id="group_{{ $group }}" data-group="{{ $group }}">
                            <label class="form-check-label fw-bold text-uppercase" for="group_{{ $group }}">
                                {{ $group }}
                            </label>
                        </div>
                    </div>
                    <div class="card-body py-2">
                        <div class="row">
                            @foreach($perms as $permission)
                            <div class="col-md-3 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input permission-checkbox" type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="perm_{{ $permission->id }}" data-group="{{ $group }}" {{ in_array($permission->name, old('permissions', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="perm_{{ $permission->id }}">
                                        {{ str_replace($group . '.', '', $permission->name) }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach

                <hr>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                        <i class="mdi mdi-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-check"></i> Simpan Peranan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select all button
    document.getElementById('selectAll').addEventListener('click', function() {
        document.querySelectorAll('.permission-checkbox').forEach(function(checkbox) {
            checkbox.checked = true;
        });
        document.querySelectorAll('.group-checkbox').forEach(function(checkbox) {
            checkbox.checked = true;
        });
    });

    // Deselect all button
    document.getElementById('deselectAll').addEventListener('click', function() {
        document.querySelectorAll('.permission-checkbox').forEach(function(checkbox) {
            checkbox.checked = false;
        });
        document.querySelectorAll('.group-checkbox').forEach(function(checkbox) {
            checkbox.checked = false;
        });
    });

    // Group checkbox toggle
    document.querySelectorAll('.group-checkbox').forEach(function(groupCheckbox) {
        groupCheckbox.addEventListener('change', function() {
            const group = this.dataset.group;
            document.querySelectorAll(`.permission-checkbox[data-group="${group}"]`).forEach(function(checkbox) {
                checkbox.checked = groupCheckbox.checked;
            });
        });
    });

    // Update group checkbox when individual permissions change
    document.querySelectorAll('.permission-checkbox').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            const group = this.dataset.group;
            const groupCheckboxes = document.querySelectorAll(`.permission-checkbox[data-group="${group}"]`);
            const allChecked = Array.from(groupCheckboxes).every(cb => cb.checked);
            document.getElementById(`group_${group}`).checked = allChecked;
        });
    });
});
</script>
@endpush
@endsection
