@extends('layouts.admin')
@section('title', 'Laporan Giliran')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper breadcrumb-contacts">
        <div>
            <h1>Laporan Giliran</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span><a href="{{ route('admin.queue.index') }}">Giliran</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>Laporan</span>
            </p>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.queue.reports') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Tarikh</label>
                    <input type="date" name="date" class="form-control" value="{{ $date->format('Y-m-d') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jenis Giliran</label>
                    <select name="queue_type_id" class="form-select">
                        <option value="">Semua Jenis</option>
                        @foreach($queueTypes as $type)
                        <option value="{{ $type->id }}" {{ request('queue_type_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="mdi mdi-filter"></i> Tapis
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Daily Stats Summary -->
    <div class="row mb-4">
        @php
            $totals = [
                'total' => $dailyStats->sum('total_tickets'),
                'served' => $dailyStats->sum('served_tickets'),
                'no_show' => $dailyStats->sum('no_show_tickets'),
                'cancelled' => $dailyStats->sum('cancelled_tickets'),
            ];
        @endphp
        <div class="col-md-3">
            <div class="card card-mini bg-primary text-white">
                <div class="card-body text-center">
                    <h2 class="mb-0">{{ $totals['total'] }}</h2>
                    <small>Jumlah Tiket</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-mini bg-success text-white">
                <div class="card-body text-center">
                    <h2 class="mb-0">{{ $totals['served'] }}</h2>
                    <small>Selesai Dilayan</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-mini bg-warning text-white">
                <div class="card-body text-center">
                    <h2 class="mb-0">{{ $totals['no_show'] }}</h2>
                    <small>Tidak Hadir</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-mini bg-danger text-white">
                <div class="card-body text-center">
                    <h2 class="mb-0">{{ $totals['cancelled'] }}</h2>
                    <small>Dibatalkan</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Daily Stats by Queue Type -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-chart-bar me-2"></i>
                        Statistik Mengikut Jenis ({{ $date->format('d/m/Y') }})
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Jenis</th>
                                    <th class="text-center">Tiket</th>
                                    <th class="text-center">Selesai</th>
                                    <th class="text-center">Purata Tunggu</th>
                                    <th class="text-center">Kadar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dailyStats as $stat)
                                <tr>
                                    <td>
                                        <strong>{{ $stat->queueType->code }}</strong>
                                        <br><small class="text-muted">{{ $stat->queueType->name }}</small>
                                    </td>
                                    <td class="text-center">{{ $stat->total_tickets }}</td>
                                    <td class="text-center text-success">{{ $stat->served_tickets }}</td>
                                    <td class="text-center">{{ $stat->formatted_avg_wait_time }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $stat->completion_rate >= 80 ? 'success' : ($stat->completion_rate >= 50 ? 'warning' : 'danger') }}">
                                            {{ $stat->completion_rate }}%
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        Tiada data untuk tarikh ini
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hourly Stats Chart -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-clock-outline me-2"></i>
                        Statistik Per Jam ({{ $date->format('d/m/Y') }})
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 400px;">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="sticky-top bg-light">
                                <tr>
                                    <th>Jam</th>
                                    <th class="text-center">Dikeluarkan</th>
                                    <th class="text-center">Dilayan</th>
                                    <th class="text-center">Purata Tunggu</th>
                                    <th class="text-center">Kaunter Aktif</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($hourlyStats as $stat)
                                <tr>
                                    <td><strong>{{ $stat->hour_range }}</strong></td>
                                    <td class="text-center">{{ $stat->tickets_issued }}</td>
                                    <td class="text-center text-success">{{ $stat->tickets_served }}</td>
                                    <td class="text-center">{{ $stat->formatted_wait_time }}</td>
                                    <td class="text-center">{{ $stat->active_counters }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        Tiada data untuk tarikh ini
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Insights -->
    @if($dailyStats->isNotEmpty())
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="mdi mdi-lightbulb-outline me-2"></i>
                Analisis Prestasi
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                @php
                    $avgWaitTime = $dailyStats->avg('avg_wait_time');
                    $maxWaitTime = $dailyStats->max('max_wait_time');
                    $overallCompletion = $totals['total'] > 0 ? round(($totals['served'] / $totals['total']) * 100, 1) : 0;
                    $noShowRate = $totals['total'] > 0 ? round(($totals['no_show'] / $totals['total']) * 100, 1) : 0;
                @endphp
                <div class="col-md-6">
                    <h6 class="text-muted">Ringkasan</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="mdi mdi-clock-outline text-info me-2"></i>
                            Purata masa menunggu: <strong>{{ round($avgWaitTime) }} minit</strong>
                        </li>
                        <li class="mb-2">
                            <i class="mdi mdi-clock-alert text-warning me-2"></i>
                            Masa menunggu maksimum: <strong>{{ $maxWaitTime ?? 0 }} minit</strong>
                        </li>
                        <li class="mb-2">
                            <i class="mdi mdi-check-circle text-success me-2"></i>
                            Kadar penyelesaian: <strong>{{ $overallCompletion }}%</strong>
                        </li>
                        <li class="mb-2">
                            <i class="mdi mdi-account-off text-danger me-2"></i>
                            Kadar tidak hadir: <strong>{{ $noShowRate }}%</strong>
                        </li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted">Cadangan</h6>
                    <ul class="list-unstyled">
                        @if($avgWaitTime > 30)
                        <li class="mb-2 text-warning">
                            <i class="mdi mdi-alert me-2"></i>
                            Purata masa menunggu tinggi. Pertimbangkan untuk menambah kaunter.
                        </li>
                        @endif
                        @if($noShowRate > 10)
                        <li class="mb-2 text-warning">
                            <i class="mdi mdi-alert me-2"></i>
                            Kadar tidak hadir tinggi. Pertimbangkan notifikasi SMS reminder.
                        </li>
                        @endif
                        @if($overallCompletion < 80)
                        <li class="mb-2 text-warning">
                            <i class="mdi mdi-alert me-2"></i>
                            Kadar penyelesaian rendah. Semak operasi kaunter.
                        </li>
                        @endif
                        @if($avgWaitTime <= 30 && $noShowRate <= 10 && $overallCompletion >= 80)
                        <li class="mb-2 text-success">
                            <i class="mdi mdi-check-circle me-2"></i>
                            Prestasi giliran dalam keadaan baik.
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
