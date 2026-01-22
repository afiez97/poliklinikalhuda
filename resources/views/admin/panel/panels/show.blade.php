@extends('layouts.admin')

@section('title', $panel->panel_name)

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between">
        <div>
            <h1>{{ $panel->panel_name }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.panel.panels.index') }}">Panel</a></li>
                    <li class="breadcrumb-item active">{{ $panel->panel_code }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.panel.panels.edit', $panel) }}" class="btn btn-primary">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
            <a href="{{ route('admin.panel.panels.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Maklumat Panel -->
            <div class="card card-default">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2><i class="bi bi-building me-2"></i>Maklumat Panel</h2>
                    @php
                        $statusColors = [
                            'active' => 'success',
                            'inactive' => 'secondary',
                            'suspended' => 'danger',
                        ];
                    @endphp
                    <span class="badge bg-{{ $statusColors[$panel->status] ?? 'secondary' }} fs-6">
                        {{ $panel->status_name }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td class="text-muted" width="40%">Kod:</td>
                                    <td><code>{{ $panel->panel_code }}</code></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Jenis:</td>
                                    <td>
                                        @php
                                            $typeColors = [
                                                'corporate' => 'info',
                                                'insurance' => 'primary',
                                                'government' => 'success',
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $typeColors[$panel->panel_type] ?? 'secondary' }}">
                                            {{ $panel->type_name }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Pegawai:</td>
                                    <td>{{ $panel->contact_person ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Telefon:</td>
                                    <td>{{ $panel->phone ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Email:</td>
                                    <td>{{ $panel->email ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td class="text-muted" width="40%">Terma Bayaran:</td>
                                    <td>{{ $panel->payment_terms_days }} hari</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">SLA Kelulusan:</td>
                                    <td>{{ $panel->sla_approval_days }} hari</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">SLA Bayaran:</td>
                                    <td>{{ $panel->sla_payment_days }} hari</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Alamat:</td>
                                    <td>{{ $panel->full_address ?: '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    @if($panel->notes)
                    <hr>
                    <div>
                        <strong>Nota:</strong>
                        <p class="mb-0">{{ $panel->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Pakej -->
            <div class="card card-default mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2><i class="bi bi-box me-2"></i>Pakej Liputan</h2>
                    <a href="{{ route('admin.panel.packages.create', $panel) }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Pakej
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Kod</th>
                                    <th>Nama Pakej</th>
                                    <th>Had Tahunan</th>
                                    <th>Had Lawatan</th>
                                    <th>Co-Pay</th>
                                    <th>Status</th>
                                    <th width="80"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($panel->packages as $package)
                                <tr>
                                    <td><code>{{ $package->package_code }}</code></td>
                                    <td>
                                        {{ $package->package_name }}
                                        @if($package->is_default)
                                        <span class="badge bg-primary ms-1">Default</span>
                                        @endif
                                    </td>
                                    <td>{{ $package->annual_limit ? 'RM ' . number_format($package->annual_limit, 2) : '-' }}</td>
                                    <td>{{ $package->per_visit_limit ? 'RM ' . number_format($package->per_visit_limit, 2) : '-' }}</td>
                                    <td>{{ $package->co_payment_percentage }}%</td>
                                    <td>
                                        <span class="badge bg-{{ $package->is_active ? 'success' : 'secondary' }}">
                                            {{ $package->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.panel.packages.edit', [$panel, $package]) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-3 text-muted">Tiada pakej dikonfigurasi.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pengecualian -->
            <div class="card card-default mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2><i class="bi bi-slash-circle me-2"></i>Pengecualian</h2>
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addExclusionModal">
                        <i class="bi bi-plus-lg me-1"></i> Tambah
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Jenis</th>
                                    <th>Kod</th>
                                    <th>Nama</th>
                                    <th>Sebab</th>
                                    <th width="50"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($panel->exclusions as $exclusion)
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary">{{ $exclusion->type_name }}</span>
                                    </td>
                                    <td><code>{{ $exclusion->exclusion_code ?? '-' }}</code></td>
                                    <td>{{ $exclusion->exclusion_name }}</td>
                                    <td>{{ $exclusion->reason ?? '-' }}</td>
                                    <td>
                                        <form action="{{ route('admin.panel.exclusions.destroy', [$panel, $exclusion]) }}" method="POST"
                                              onsubmit="return confirm('Padam pengecualian ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3 text-muted">Tiada pengecualian dikonfigurasi.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Kontrak -->
            <div class="card card-default">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2><i class="bi bi-file-earmark-text me-2"></i>Kontrak</h2>
                    <a href="{{ route('admin.panel.contracts.create', $panel) }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-plus-lg"></i>
                    </a>
                </div>
                <div class="card-body">
                    @if($panel->activeContract)
                    @php $contract = $panel->activeContract; @endphp
                    <div class="mb-2">
                        <strong>{{ $contract->contract_number ?? 'Kontrak Aktif' }}</strong>
                    </div>
                    <table class="table table-borderless table-sm mb-0">
                        <tr>
                            <td class="text-muted">Mula:</td>
                            <td>{{ $contract->effective_date->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tamat:</td>
                            <td>
                                {{ $contract->expiry_date->format('d/m/Y') }}
                                @if($contract->daysUntilExpiry() <= 30)
                                <span class="badge bg-warning text-dark ms-1">{{ $contract->daysUntilExpiry() }} hari lagi</span>
                                @endif
                            </td>
                        </tr>
                        @if($contract->annual_cap)
                        <tr>
                            <td class="text-muted">Had Tahunan:</td>
                            <td>RM {{ number_format($contract->annual_cap, 2) }}</td>
                        </tr>
                        @endif
                    </table>
                    @else
                    <p class="text-muted mb-0">Tiada kontrak aktif.</p>
                    @endif
                </div>
            </div>

            <!-- Statistik -->
            <div class="card card-default mt-4">
                <div class="card-header">
                    <h2><i class="bi bi-graph-up me-2"></i>Statistik Bulan Ini</h2>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm mb-0">
                        <tr>
                            <td class="text-muted">Jumlah Tuntutan:</td>
                            <td class="text-end fw-semibold">{{ $revenue['total_claims'] ?? 0 }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Dituntut:</td>
                            <td class="text-end">RM {{ number_format($revenue['total_claimed'] ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Diluluskan:</td>
                            <td class="text-end text-success">RM {{ number_format($revenue['total_approved'] ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Dibayar:</td>
                            <td class="text-end text-primary">RM {{ number_format($revenue['total_paid'] ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tertunggak:</td>
                            <td class="text-end text-warning">RM {{ number_format($revenue['total_outstanding'] ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Kadar Tolak:</td>
                            <td class="text-end">{{ $revenue['rejection_rate'] ?? 0 }}%</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Pekerja Terbaru -->
            <div class="card card-default mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2><i class="bi bi-people me-2"></i>Pekerja Terbaru</h2>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($panel->employees as $employee)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-semibold">{{ $employee->name }}</div>
                                <small class="text-muted">{{ $employee->employee_id }}</small>
                            </div>
                            <span class="badge bg-{{ $employee->status == 'active' ? 'success' : 'secondary' }}">
                                {{ $employee->status_name }}
                            </span>
                        </li>
                        @empty
                        <li class="list-group-item text-center text-muted">Tiada pekerja berdaftar.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Pengecualian -->
<div class="modal fade" id="addExclusionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.panel.exclusions.store', $panel) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Pengecualian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Jenis <span class="text-danger">*</span></label>
                        <select name="exclusion_type" class="form-select" required>
                            @foreach(\App\Models\PanelExclusion::TYPES as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kod</label>
                        <input type="text" name="exclusion_code" class="form-control" placeholder="cth: PROC001">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama <span class="text-danger">*</span></label>
                        <input type="text" name="exclusion_name" class="form-control" required placeholder="cth: Prosedur Kosmetik">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sebab Pengecualian</label>
                        <textarea name="reason" class="form-control" rows="2"></textarea>
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
