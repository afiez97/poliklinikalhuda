@extends('layouts.admin')

@section('title', 'Baki Cuti - ' . ($staff->user->name ?? 'Kakitangan'))

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between">
        <div>
            <h1>Baki Cuti Kakitangan</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.leave.index') }}">Cuti</a></li>
                    <li class="breadcrumb-item active">Baki Cuti</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card card-default mb-4">
                <div class="card-header">
                    <h2><i class="bi bi-person me-2"></i>Maklumat Kakitangan</h2>
                </div>
                <div class="card-body text-center">
                    <div class="avatar avatar-lg bg-primary text-white rounded-circle mx-auto mb-3" style="width: 80px; height: 80px; line-height: 80px; font-size: 2rem;">
                        {{ substr($staff->user->name ?? 'N', 0, 1) }}
                    </div>
                    <h4 class="mb-1">{{ $staff->user->name ?? '-' }}</h4>
                    <p class="text-muted mb-2">{{ $staff->staff_no }}</p>
                    <span class="badge bg-primary">{{ $staff->department->name ?? '-' }}</span>
                </div>
            </div>

            <!-- Year Selector -->
            <div class="card card-default">
                <div class="card-header">
                    <h2>Tahun</h2>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @for($y = now()->year; $y >= now()->year - 2; $y--)
                        <a href="{{ route('admin.leave.balance', ['staff' => $staff->id, 'year' => $y]) }}"
                           class="btn {{ $year == $y ? 'btn-primary' : 'btn-outline-secondary' }}">
                            {{ $y }}
                        </a>
                        @endfor
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card card-default">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2><i class="bi bi-calendar-check me-2"></i>Baki Cuti {{ $year }}</h2>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editBalanceModal">
                        <i class="bi bi-pencil me-1"></i> Kemaskini
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Jenis Cuti</th>
                                    <th class="text-center">Kelayakan</th>
                                    <th class="text-center">Dibawa</th>
                                    <th class="text-center">Digunakan</th>
                                    <th class="text-center">Baki</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($leaveTypes as $type)
                                @php
                                    $balance = $balances->firstWhere('leave_type_id', $type->id);
                                    $entitled = $balance->entitled_days ?? $type->default_days;
                                    $carried = $balance->carried_forward ?? 0;
                                    $used = $balance->used_days ?? 0;
                                    $remaining = $entitled + $carried - $used;
                                @endphp
                                <tr>
                                    <td>
                                        <span class="badge me-2" style="background-color: {{ $type->color ?? '#6c757d' }}">
                                            {{ $type->name }}
                                        </span>
                                    </td>
                                    <td class="text-center">{{ $entitled }}</td>
                                    <td class="text-center">{{ $carried }}</td>
                                    <td class="text-center text-danger">{{ $used }}</td>
                                    <td class="text-center">
                                        <strong class="{{ $remaining > 0 ? 'text-success' : 'text-danger' }}">
                                            {{ $remaining }}
                                        </strong>
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

<!-- Edit Balance Modal -->
<div class="modal fade" id="editBalanceModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.leave.balance.update', $staff) }}" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="year" value="{{ $year }}">
                <div class="modal-header">
                    <h5 class="modal-title">Kemaskini Baki Cuti - {{ $year }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Jenis Cuti</th>
                                    <th class="text-center">Kelayakan</th>
                                    <th class="text-center">Bawa Dari Tahun Lalu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($leaveTypes as $index => $type)
                                @php
                                    $balance = $balances->firstWhere('leave_type_id', $type->id);
                                @endphp
                                <tr>
                                    <td>
                                        <input type="hidden" name="balances[{{ $index }}][leave_type_id]" value="{{ $type->id }}">
                                        {{ $type->name }}
                                    </td>
                                    <td>
                                        <input type="number" name="balances[{{ $index }}][entitled_days]" class="form-control form-control-sm text-center"
                                               value="{{ $balance->entitled_days ?? $type->default_days }}" min="0" step="0.5">
                                    </td>
                                    <td>
                                        <input type="number" name="balances[{{ $index }}][carried_forward]" class="form-control form-control-sm text-center"
                                               value="{{ $balance->carried_forward ?? 0 }}" min="0" step="0.5"
                                               @if(!$type->is_carry_forward) disabled @endif>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
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
