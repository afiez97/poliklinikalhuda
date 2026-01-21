<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Paparan Giliran - Poliklinik Al-Huda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            overflow: hidden;
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
            height: calc(100vh - 180px);
            display: flex;
            gap: 40px;
        }

        .now-serving-section {
            flex: 1;
        }

        .now-serving {
            background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
            border-radius: 20px;
            padding: 40px;
            height: 100%;
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
            padding: 30px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            transition: all 0.3s;
        }

        .serving-item.calling {
            background: rgba(255, 193, 7, 0.3);
            animation: blink 1s infinite;
        }

        .serving-item.new-call {
            animation: highlight 0.5s ease-out;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        @keyframes highlight {
            0% { transform: scale(1.05); background: rgba(76, 201, 240, 0.5); }
            100% { transform: scale(1); }
        }

        .queue-number {
            font-size: 5rem;
            font-weight: 700;
            color: #fff;
            min-width: 250px;
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
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .queue-info .counter {
            color: #4cc9f0;
            font-size: 1.5rem;
        }

        .queue-info .priority {
            margin-top: 10px;
        }

        .waiting-section {
            width: 400px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            padding: 30px;
            overflow-y: auto;
        }

        .waiting-section h2 {
            color: #fff;
            font-size: 1.3rem;
            margin-bottom: 25px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .waiting-item {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 15px 20px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
        }

        .waiting-item:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .waiting-item .number {
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff;
        }

        .waiting-item .queue-type {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.85rem;
        }

        .priority-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .priority-1 { background: #dc3545; color: #fff; }
        .priority-2 { background: #6f42c1; color: #fff; }
        .priority-3 { background: #0d6efd; color: #fff; }
        .priority-4 { background: #17a2b8; color: #fff; }
        .priority-5 { background: #e91e8e; color: #fff; }
        .priority-6 { background: transparent; }

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
            flex: 1;
            overflow: hidden;
            margin-left: 50px;
        }

        .footer .marquee-text {
            color: #4cc9f0;
            font-size: 1rem;
            white-space: nowrap;
            animation: marquee 30s linear infinite;
        }

        @keyframes marquee {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }

        .last-completed {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .last-completed h3 {
            color: rgba(255, 255, 255, 0.6);
            font-size: 1rem;
            margin-bottom: 15px;
        }

        .completed-item {
            display: inline-block;
            background: rgba(40, 167, 69, 0.2);
            color: #28a745;
            padding: 8px 15px;
            border-radius: 8px;
            margin-right: 10px;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .sound-toggle {
            position: fixed;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: #fff;
            padding: 10px 15px;
            border-radius: 10px;
            cursor: pointer;
            z-index: 100;
        }

        .sound-toggle:hover {
            background: rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body>
    <button class="sound-toggle" onclick="toggleSound()">
        <i class="mdi mdi-volume-high" id="soundIcon"></i>
    </button>

    <div class="header d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="mdi mdi-hospital-building me-2"></i>Poliklinik Al-Huda</h1>
        </div>
        <div class="time" id="currentTime"></div>
    </div>

    <div class="main-content">
        <!-- Now Serving Section -->
        <div class="now-serving-section">
            <div class="now-serving">
                <h2><i class="mdi mdi-bullhorn me-2"></i>Sedang Dipanggil</h2>
                <div id="servingList">
                    @forelse($currentlyServing as $ticket)
                    <div class="serving-item {{ $ticket->status === 'called' ? 'calling' : '' }}" data-ticket-id="{{ $ticket->id }}">
                        <div class="queue-number">{{ $ticket->ticket_number }}</div>
                        <div class="queue-info">
                            <div class="patient-name">{{ $ticket->patient?->name ?? 'Pesakit' }}</div>
                            <div class="counter">
                                <i class="mdi mdi-arrow-right-bold me-1"></i>
                                Sila ke {{ $ticket->currentCounter?->name ?? 'Kaunter' }}
                            </div>
                            @if($ticket->priority_level < 6)
                            <div class="priority">
                                <span class="priority-badge priority-{{ $ticket->priority_level }}">
                                    {{ $ticket->priority_label }}
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="no-queue" id="noServing">
                        <i class="mdi mdi-account-clock"></i>
                        <p>Tiada panggilan pada masa ini</p>
                    </div>
                    @endforelse
                </div>

                @if($lastCompleted->isNotEmpty())
                <div class="last-completed">
                    <h3><i class="mdi mdi-check-circle me-2"></i>Baru Selesai</h3>
                    @foreach($lastCompleted->take(5) as $ticket)
                    <span class="completed-item">{{ $ticket->ticket_number }}</span>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        <!-- Waiting Section -->
        <div class="waiting-section">
            <h2><i class="mdi mdi-account-group me-2"></i>Seterusnya</h2>
            <div id="waitingList">
                @php
                    $waitingTickets = \App\Models\QueueTicket::today()
                        ->waiting()
                        ->when(!empty($queueTypes), fn($q) => $q->whereIn('queue_type_id', $queueTypes->pluck('id')))
                        ->orderedByPriority()
                        ->with(['queueType'])
                        ->limit(15)
                        ->get();
                @endphp
                @forelse($waitingTickets as $ticket)
                <div class="waiting-item">
                    <div>
                        <div class="number">{{ $ticket->ticket_number }}</div>
                        <div class="queue-type">{{ $ticket->queueType->name }}</div>
                    </div>
                    @if($ticket->priority_level < 6)
                    <span class="priority-badge priority-{{ $ticket->priority_level }}">
                        {{ $ticket->priority_label }}
                    </span>
                    @endif
                </div>
                @empty
                <div class="no-queue">
                    <i class="mdi mdi-check-circle"></i>
                    <p>Tiada giliran menunggu</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="footer">
        <div class="logo">
            <i class="mdi mdi-hospital-marker me-1"></i>
            Sistem Pengurusan Giliran
        </div>
        <div class="marquee">
            <div class="marquee-text">
                <i class="mdi mdi-information me-2"></i>
                Selamat datang ke Poliklinik Al-Huda. Sila pastikan dokumen lengkap sebelum mendaftar. Terima kasih atas kesabaran anda.
                <i class="mdi mdi-phone me-4"></i> 03-1234 5678
            </div>
        </div>
    </div>

    <script>
        let soundEnabled = true;
        let lastCalledTickets = @json($currentlyServing->pluck('id')->toArray());

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

        // Toggle sound
        function toggleSound() {
            soundEnabled = !soundEnabled;
            const icon = document.getElementById('soundIcon');
            icon.className = soundEnabled ? 'mdi mdi-volume-high' : 'mdi mdi-volume-off';
        }

        // Text-to-Speech announcement
        function announce(ticketNumber, counterName) {
            if (!soundEnabled) return;

            // Play chime first
            playChime();

            setTimeout(() => {
                const text = `Nombor ${ticketNumber.split('').join(' ')}, sila ke ${counterName}`;
                const utterance = new SpeechSynthesisUtterance(text);
                utterance.lang = 'ms-MY';
                utterance.rate = 0.8;
                utterance.pitch = 1;
                utterance.volume = 1;

                // Try to use a Malay voice if available
                const voices = speechSynthesis.getVoices();
                const malayVoice = voices.find(v => v.lang.includes('ms') || v.lang.includes('id'));
                if (malayVoice) {
                    utterance.voice = malayVoice;
                }

                speechSynthesis.speak(utterance);
            }, 500);
        }

        // Play chime sound
        function playChime() {
            if (!soundEnabled) return;

            try {
                const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                const frequencies = [523.25, 659.25, 783.99]; // C5, E5, G5

                frequencies.forEach((freq, i) => {
                    setTimeout(() => {
                        const oscillator = audioContext.createOscillator();
                        const gainNode = audioContext.createGain();

                        oscillator.connect(gainNode);
                        gainNode.connect(audioContext.destination);

                        oscillator.frequency.value = freq;
                        oscillator.type = 'sine';

                        gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
                        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);

                        oscillator.start(audioContext.currentTime);
                        oscillator.stop(audioContext.currentTime + 0.5);
                    }, i * 150);
                });
            } catch (e) {
                console.log('Audio not supported');
            }
        }

        // Fetch and update display
        async function refreshDisplay() {
            try {
                const params = new URLSearchParams(window.location.search);
                const response = await fetch(`{{ route('admin.queue.displayData') }}?${params}`);
                const data = await response.json();

                // Check for new calls
                const currentIds = data.currently_serving.map(t => t.id);
                const newCalls = data.currently_serving.filter(t =>
                    !lastCalledTickets.includes(t.id) && t.status === 'called'
                );

                // Announce new calls
                newCalls.forEach(ticket => {
                    announce(ticket.ticket_number, ticket.current_counter?.name || 'Kaunter');
                });

                lastCalledTickets = currentIds;

                // Update display would go here (or just reload for simplicity)
                if (newCalls.length > 0) {
                    setTimeout(() => location.reload(), 3000);
                }
            } catch (error) {
                console.error('Error refreshing display:', error);
            }
        }

        // Load voices when available
        speechSynthesis.onvoiceschanged = () => {
            speechSynthesis.getVoices();
        };

        // Refresh every 5 seconds
        setInterval(refreshDisplay, 5000);

        // Full page reload every 2 minutes to ensure freshness
        setTimeout(() => location.reload(), 120000);
    </script>
</body>
</html>
