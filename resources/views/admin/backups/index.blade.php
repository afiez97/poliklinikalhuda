@extends('layouts.admin')
@section('title', 'Pengurusan Backup')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper breadcrumb-contacts">
        <div>
            <h1>Pengurusan Backup</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>Backup</span>
            </p>
        </div>
        <div>
            @can('create', App\Models\Backup::class)
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createBackupModal">
                <i class="mdi mdi-plus"></i> Cipta Backup
            </button>
            @endcan
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card card-mini">
                <div class="card-body">
                    <h2 class="mb-1">{{ $statistics['total'] }}</h2>
                    <p class="mb-0">Jumlah Backup</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card card-mini">
                <div class="card-body">
                    <h2 class="mb-1 text-success">{{ $statistics['completed'] }}</h2>
                    <p class="mb-0">Berjaya</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card card-mini">
                <div class="card-body">
                    <h2 class="mb-1 text-danger">{{ $statistics['failed'] }}</h2>
                    <p class="mb-0">Gagal</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card card-mini">
                <div class="card-body">
                    <h2 class="mb-1">{{ $statistics['last_backup'] ? $statistics['last_backup']->diffForHumans() : '-' }}</h2>
                    <p class="mb-0">Backup Terakhir</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Backups Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nama Fail</th>
                            <th>Jenis</th>
                            <th>Saiz</th>
                            <th>Status</th>
                            <th>Dicipta Oleh</th>
                            <th>Tarikh</th>
                            <th class="text-end">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($backups as $backup)
                        <tr>
                            <td>
                                <i class="mdi mdi-archive me-1 text-muted"></i>
                                {{ $backup->filename }}
                            </td>
                            <td>
                                @switch($backup->type)
                                    @case('full')
                                        <span class="badge bg-primary">Penuh</span>
                                        @break
                                    @case('database')
                                        <span class="badge bg-info">Pangkalan Data</span>
                                        @break
                                    @case('files')
                                        <span class="badge bg-secondary">Fail</span>
                                        @break
                                @endswitch
                            </td>
                            <td>{{ $backup->formatted_size }}</td>
                            <td>
                                @switch($backup->status)
                                    @case('completed')
                                        <span class="badge bg-success">Selesai</span>
                                        @break
                                    @case('in_progress')
                                        <span class="badge bg-warning">Dalam Proses</span>
                                        @break
                                    @case('failed')
                                        <span class="badge bg-danger">Gagal</span>
                                        @break
                                    @case('pending')
                                        <span class="badge bg-secondary">Menunggu</span>
                                        @break
                                @endswitch
                            </td>
                            <td>{{ $backup->createdBy?->name ?? 'Sistem' }}</td>
                            <td>
                                {{ $backup->created_at->format('d/m/Y H:i') }}
                                @if($backup->completed_at)
                                <br><small class="text-muted">Selesai: {{ $backup->completed_at->format('H:i:s') }}</small>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="mdi mdi-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        @if($backup->status === 'completed')
                                        @can('download', $backup)
                                        <li><a class="dropdown-item" href="{{ route('admin.backups.download', $backup) }}"><i class="mdi mdi-download me-2"></i>Muat Turun</a></li>
                                        @endcan
                                        @can('restore', $backup)
                                        <li>
                                            <form action="{{ route('admin.backups.restore', $backup) }}" method="POST" onsubmit="return confirm('AMARAN: Ini akan memulihkan sistem dari backup ini. Adakah anda pasti?')">
                                                @csrf
                                                <button type="submit" class="dropdown-item"><i class="mdi mdi-restore me-2"></i>Pulihkan</button>
                                            </form>
                                        </li>
                                        @endcan
                                        @endif
                                        @can('delete', $backup)
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('admin.backups.destroy', $backup) }}" method="POST" onsubmit="return confirm('Adakah anda pasti ingin memadam backup ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger"><i class="mdi mdi-delete me-2"></i>Padam</button>
                                            </form>
                                        </li>
                                        @endcan
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="mdi mdi-archive-off mdi-48px text-muted"></i>
                                <p class="text-muted mb-0">Tiada backup dijumpai</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-3">
                {{ $backups->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Create Backup Modal -->
<div class="modal fade" id="createBackupModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.backups.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Cipta Backup Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Jenis Backup <span class="text-danger">*</span></label>
                        <select name="type" class="form-select" required>
                            <option value="full">Backup Penuh (Pangkalan Data + Fail)</option>
                            <option value="database">Pangkalan Data Sahaja</option>
                            <option value="files">Fail Sahaja</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Penerangan</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Catatan untuk backup ini..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-archive"></i> Cipta Backup
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
