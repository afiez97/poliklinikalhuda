@extends('layouts.admin')
@section('title', 'Statistik Audit')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper">
        <div>
            <h1>Statistik Audit</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span><a href="{{ route('admin.audit.index') }}">Audit</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>Statistik</span>
            </p>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card card-mini">
                <div class="card-body">
                    <h2 class="mb-1">{{ number_format($statistics['total'] ?? 0) }}</h2>
                    <p class="mb-0">Jumlah Log</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card card-mini">
                <div class="card-body">
                    <h2 class="mb-1">{{ number_format($statistics['today'] ?? 0) }}</h2>
                    <p class="mb-0">Log Hari Ini</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card card-mini">
                <div class="card-body">
                    <h2 class="mb-1">{{ number_format($statistics['this_week'] ?? 0) }}</h2>
                    <p class="mb-0">Log Minggu Ini</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card card-mini">
                <div class="card-body">
                    <h2 class="mb-1">{{ number_format($statistics['this_month'] ?? 0) }}</h2>
                    <p class="mb-0">Log Bulan Ini</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Actions Breakdown -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Pecahan Mengikut Tindakan</h5>
                </div>
                <div class="card-body">
                    @if(!empty($statistics['by_action']))
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Tindakan</th>
                                <th class="text-end">Jumlah</th>
                                <th style="width: 40%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $maxAction = max($statistics['by_action']); @endphp
                            @foreach($statistics['by_action'] as $action => $count)
                            <tr>
                                <td>
                                    @php
                                        $actionColors = [
                                            'create' => 'success',
                                            'update' => 'info',
                                            'delete' => 'danger',
                                            'login' => 'primary',
                                            'logout' => 'secondary',
                                            'failed_login' => 'warning',
                                        ];
                                        $color = $actionColors[$action] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $color }}">{{ ucfirst($action) }}</span>
                                </td>
                                <td class="text-end">{{ number_format($count) }}</td>
                                <td>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-{{ $color }}" style="width: {{ ($count / $maxAction) * 100 }}%"></div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <p class="text-muted text-center mb-0">Tiada data</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Top Users -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Pengguna Paling Aktif</h5>
                </div>
                <div class="card-body">
                    @if(!empty($statistics['top_users']))
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Pengguna</th>
                                <th class="text-end">Aktiviti</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($statistics['top_users'] as $userData)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-initial rounded-circle bg-primary me-2" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; font-size: 12px;">
                                            {{ strtoupper(substr($userData['name'] ?? 'S', 0, 2)) }}
                                        </div>
                                        <div>
                                            <strong>{{ $userData['name'] ?? 'Sistem' }}</strong>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end">{{ number_format($userData['count'] ?? 0) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <p class="text-muted text-center mb-0">Tiada data</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('admin.audit.index') }}" class="btn btn-secondary">
            <i class="mdi mdi-arrow-left"></i> Kembali ke Log Audit
        </a>
    </div>
</div>
@endsection
