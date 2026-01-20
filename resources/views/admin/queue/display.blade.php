<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paparan Giliran - Poliklinik Al-Huda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        .header {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 20px 40px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .header h1 {
            color: #fff;
            font-size: 2rem;
            margin: 0;
        }

        .header .time {
            color: #4cc9f0;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .main-content {
            padding: 40px;
        }

        .now-serving {
            background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
            border-radius: 20px;
            padding: 40px;
            margin-bottom: 40px;
            box-shadow: 0 10px 40px rgba(67, 97, 238, 0.3);
        }

        .now-serving h2 {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.5rem;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .serving-item {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            animation: pulse 2s infinite;
        }

        .serving-item.calling {
            background: rgba(255, 193, 7, 0.3);
            animation: blink 1s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); }
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .queue-number {
            font-size: 4rem;
            font-weight: 700;
            color: #fff;
            min-width: 200px;
            text-align: center;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .queue-info {
            flex: 1;
            padding-left: 30px;
            border-left: 2px solid rgba(255, 255, 255, 0.2);
            margin-left: 30px;
        }

        .queue-info .patient-name {
            color: #fff;
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .queue-info .counter {
            color: #4cc9f0;
            font-size: 1.2rem;
        }

        .waiting-section {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            padding: 30px;
        }

        .waiting-section h2 {
            color: #fff;
            font-size: 1.3rem;
            margin-bottom: 25px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .waiting-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
        }

        .waiting-item {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .waiting-item:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-5px);
        }

        .waiting-item .number {
            font-size: 2rem;
            font-weight: 700;
            color: #fff;
        }

        .waiting-item .counter-name {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
        }

        .priority-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-top: 8px;
        }

        .priority-emergency { background: #dc3545; color: #fff; }
        .priority-urgent { background: #fd7e14; color: #fff; }
        .priority-pregnant { background: #e91e8e; color: #fff; }
        .priority-disabled { background: #6f42c1; color: #fff; }
        .priority-elderly { background: #0d6efd; color: #fff; }

        .no-queue {
            text-align: center;
            padding: 60px;
            color: rgba(255, 255, 255, 0.5);
        }

        .no-queue i {
            font-size: 5rem;
            margin-bottom: 20px;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.3);
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer .logo {
            color: rgba(255, 255, 255, 0.7);
            font-size: 1.1rem;
        }

        .footer .marquee {
            color: #4cc9f0;
            font-size: 1rem;
        }

        .counter-stats {
            display: flex;
            gap: 30px;
            margin-top: 10px;
        }

        .counter-stat {
            background: rgba(255, 255, 255, 0.1);
            padding: 10px 20px;
            border-radius: 10px;
            text-align: center;
        }

        .counter-stat .value {
            color: #4cc9f0;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .counter-stat .label {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.8rem;
        }
    </style>
</head>
<body>
    <div class="header d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="mdi mdi-hospital-building me-2"></i>Poliklinik Al-Huda</h1>
            <div class="counter-stats">
                @foreach($counters as $counter)
                <div class="counter-stat">
                    <div class="value">{{ $counter->code }}</div>
                    <div class="label">{{ $counter->name }}</div>
                </div>
                @endforeach
            </div>
        </div>
        <div class="time" id="currentTime"></div>
    </div>

    <div class="main-content">
        <!-- Now Serving -->
        <div class="now-serving">
            <h2><i class="mdi mdi-bullhorn me-2"></i>Sedang Dipanggil / Dilayan</h2>
            @forelse($currentlyServing as $entry)
            <div class="serving-item {{ $entry->status === 'calling' ? 'calling' : '' }}">
                <div class="queue-number">{{ $entry->queue_number }}</div>
                <div class="queue-info">
                    <div class="patient-name">{{ $entry->patientVisit?->patient?->name ?? 'Pesakit' }}</div>
                    <div class="counter">
                        <i class="mdi mdi-arrow-right-bold me-1"></i>
                        Sila ke {{ $entry->queueCounter?->name ?? 'Kaunter' }} ({{ $entry->called_counter ?? $entry->queueCounter?->code }})
                    </div>
                </div>
            </div>
            @empty
            <div class="no-queue">
                <i class="mdi mdi-account-clock"></i>
                <p>Tiada panggilan pada masa ini</p>
            </div>
            @endforelse
        </div>

        <!-- Waiting List -->
        <div class="waiting-section">
            <h2><i class="mdi mdi-account-group me-2"></i>Senarai Menunggu</h2>
            @if($waiting->isNotEmpty())
            <div class="waiting-list">
                @foreach($waiting as $entry)
                <div class="waiting-item">
                    <div class="number">{{ $entry->queue_number }}</div>
                    <div class="counter-name">{{ $entry->queueCounter?->name }}</div>
                    @if($entry->priority !== 'normal')
                    <div class="priority-badge priority-{{ $entry->priority }}">
                        {{ $entry->priority_label }}
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            @else
            <div class="no-queue">
                <i class="mdi mdi-check-circle"></i>
                <p>Tiada giliran dalam barisan</p>
            </div>
            @endif
        </div>
    </div>

    <div class="footer">
        <div class="logo">
            <i class="mdi mdi-hospital-marker me-1"></i>
            Poliklinik Al-Huda - Sistem Pengurusan Giliran
        </div>
        <div class="marquee">
            <i class="mdi mdi-information me-1"></i>
            Sila pastikan dokumen lengkap sebelum mendaftar. Terima kasih.
        </div>
    </div>

    <script>
        // Update time
        function updateTime() {
            const now = new Date();
            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };
            document.getElementById('currentTime').textContent = now.toLocaleDateString('ms-MY', options);
        }

        updateTime();
        setInterval(updateTime, 1000);

        // Auto-refresh every 10 seconds
        setTimeout(function() {
            window.location.reload();
        }, 10000);
    </script>
</body>
</html>
