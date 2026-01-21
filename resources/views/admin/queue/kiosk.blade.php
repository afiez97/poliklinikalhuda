<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kiosk Giliran - Poliklinik Al-Huda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.0.96/css/materialdesignicons.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1e3a5f 0%, #2d5a87 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .kiosk-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .kiosk-header {
            text-align: center;
            color: white;
            margin-bottom: 40px;
        }

        .kiosk-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .kiosk-header p {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        .queue-type-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            height: 100%;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .queue-type-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }

        .queue-type-card .icon {
            font-size: 4rem;
            margin-bottom: 20px;
        }

        .queue-type-card h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .queue-type-card .waiting-info {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 20px;
        }

        .queue-type-card .btn-get-ticket {
            padding: 15px 40px;
            font-size: 1.2rem;
            border-radius: 50px;
        }

        .icon-registration { color: #28a745; }
        .icon-consultation { color: #007bff; }
        .icon-pharmacy { color: #ffc107; }
        .icon-payment { color: #17a2b8; }
        .icon-lab { color: #6f42c1; }

        /* Modal Styling */
        .ticket-modal .modal-content {
            border-radius: 30px;
            border: none;
        }

        .ticket-modal .modal-body {
            padding: 60px;
            text-align: center;
        }

        .ticket-number {
            font-size: 6rem;
            font-weight: 700;
            color: #1e3a5f;
            letter-spacing: 5px;
        }

        .ticket-info {
            font-size: 1.5rem;
            color: #666;
            margin: 30px 0;
        }

        .print-btn {
            padding: 20px 60px;
            font-size: 1.5rem;
            border-radius: 50px;
        }

        .priority-selector {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
            margin-top: 20px;
        }

        .priority-btn {
            padding: 15px 25px;
            border-radius: 15px;
            border: 2px solid #ddd;
            background: white;
            cursor: pointer;
            transition: all 0.3s;
        }

        .priority-btn:hover, .priority-btn.active {
            border-color: #007bff;
            background: #f0f7ff;
        }

        .priority-btn i {
            font-size: 2rem;
            display: block;
            margin-bottom: 10px;
        }

        @media (max-width: 768px) {
            .kiosk-header h1 { font-size: 1.8rem; }
            .queue-type-card { padding: 20px; }
            .queue-type-card .icon { font-size: 3rem; }
            .ticket-number { font-size: 4rem; }
        }
    </style>
</head>
<body>
    <div class="kiosk-container">
        <div class="kiosk-header">
            <h1><i class="mdi mdi-hospital-building me-3"></i>Poliklinik Al-Huda</h1>
            <p>Sila pilih jenis perkhidmatan yang anda perlukan</p>
        </div>

        <div class="row g-4">
            @forelse($queueTypes as $queueType)
            @php
                $icons = [
                    'R' => ['icon' => 'mdi-clipboard-account', 'class' => 'icon-registration'],
                    'D' => ['icon' => 'mdi-stethoscope', 'class' => 'icon-consultation'],
                    'F' => ['icon' => 'mdi-pill', 'class' => 'icon-pharmacy'],
                    'P' => ['icon' => 'mdi-cash-register', 'class' => 'icon-payment'],
                    'L' => ['icon' => 'mdi-flask', 'class' => 'icon-lab'],
                ];
                $prefix = substr($queueType->code, 0, 1);
                $iconInfo = $icons[$prefix] ?? ['icon' => 'mdi-counter', 'class' => 'icon-registration'];
            @endphp
            <div class="col-md-4">
                <div class="queue-type-card" onclick="selectQueueType({{ $queueType->id }}, '{{ $queueType->name }}', '{{ $queueType->code }}')">
                    <div class="icon {{ $iconInfo['class'] }}">
                        <i class="mdi {{ $iconInfo['icon'] }}"></i>
                    </div>
                    <h3>{{ $queueType->name }}</h3>
                    @if($queueType->name_en)
                    <p class="text-muted mb-2">{{ $queueType->name_en }}</p>
                    @endif
                    <div class="waiting-info">
                        <i class="mdi mdi-account-multiple me-1"></i>
                        {{ $queueType->today_waiting_count }} menunggu
                        <br>
                        <i class="mdi mdi-clock-outline me-1"></i>
                        Anggaran ~{{ ($queueType->today_waiting_count * ($queueType->avg_service_time ?? 5)) }} min
                    </div>
                    <button class="btn btn-primary btn-get-ticket">
                        <i class="mdi mdi-ticket me-2"></i>Ambil Nombor
                    </button>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-warning text-center py-5">
                    <i class="mdi mdi-alert-circle mdi-48px mb-3 d-block"></i>
                    <h4>Tiada perkhidmatan tersedia pada masa ini</h4>
                    <p class="mb-0">Sila hubungi kaunter pendaftaran untuk bantuan.</p>
                </div>
            </div>
            @endforelse
        </div>

        <div class="text-center mt-5">
            <p class="text-white opacity-75">
                <i class="mdi mdi-clock-outline me-2"></i>
                {{ now()->format('d/m/Y H:i') }}
            </p>
        </div>
    </div>

    <!-- Priority Selection Modal -->
    <div class="modal fade" id="priorityModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius: 30px;">
                <div class="modal-body p-5 text-center">
                    <h2 class="mb-4">Pilih Keutamaan</h2>
                    <p class="text-muted mb-4">Adakah anda memerlukan keutamaan khas?</p>

                    <input type="hidden" id="selectedQueueTypeId">
                    <input type="hidden" id="selectedQueueTypeName">

                    <div class="priority-selector">
                        <div class="priority-btn" onclick="selectPriority(6, 'Normal')">
                            <i class="mdi mdi-account text-secondary"></i>
                            <strong>Normal</strong>
                        </div>
                        <div class="priority-btn" onclick="selectPriority(4, 'Warga Emas')">
                            <i class="mdi mdi-human-cane text-info"></i>
                            <strong>Warga Emas</strong>
                            <small class="d-block text-muted">60 tahun ke atas</small>
                        </div>
                        <div class="priority-btn" onclick="selectPriority(3, 'OKU')">
                            <i class="mdi mdi-wheelchair-accessibility text-primary"></i>
                            <strong>OKU</strong>
                        </div>
                        <div class="priority-btn" onclick="selectPriority(5, 'Wanita Mengandung')">
                            <i class="mdi mdi-human-pregnant text-pink"></i>
                            <strong>Mengandung</strong>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button class="btn btn-secondary btn-lg" data-bs-dismiss="modal">Batal</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ticket Result Modal -->
    <div class="modal fade ticket-modal" id="ticketModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-success mb-4">
                        <i class="mdi mdi-check-circle" style="font-size: 5rem;"></i>
                    </div>
                    <h2 class="mb-3">Nombor Giliran Anda</h2>
                    <div class="ticket-number" id="ticketNumber">-</div>
                    <div class="ticket-info">
                        <span id="ticketQueueType"></span><br>
                        <small class="text-muted">Anggaran masa menunggu: <span id="ticketWaitTime">-</span> minit</small>
                    </div>
                    <div class="alert alert-info">
                        <i class="mdi mdi-information me-2"></i>
                        Sila tunggu nombor anda dipanggil di paparan.
                    </div>
                    <button class="btn btn-success print-btn" onclick="printTicket()">
                        <i class="mdi mdi-printer me-2"></i>Cetak Tiket
                    </button>
                    <div class="mt-3">
                        <button class="btn btn-outline-secondary" onclick="closeTicketModal()">Selesai</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const priorityModal = new bootstrap.Modal(document.getElementById('priorityModal'));
        const ticketModal = new bootstrap.Modal(document.getElementById('ticketModal'));

        function selectQueueType(id, name, code) {
            document.getElementById('selectedQueueTypeId').value = id;
            document.getElementById('selectedQueueTypeName').value = name;
            priorityModal.show();
        }

        function selectPriority(level, reason) {
            const queueTypeId = document.getElementById('selectedQueueTypeId').value;
            const queueTypeName = document.getElementById('selectedQueueTypeName').value;

            priorityModal.hide();
            issueTicket(queueTypeId, queueTypeName, level, reason);
        }

        async function issueTicket(queueTypeId, queueTypeName, priorityLevel, priorityReason) {
            try {
                const response = await fetch('{{ route("admin.queue.issueTicket") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        queue_type_id: queueTypeId,
                        priority_level: priorityLevel,
                        priority_reason: priorityReason !== 'Normal' ? priorityReason : null,
                        source: 'kiosk'
                    })
                });

                const data = await response.json();

                if (data.success) {
                    document.getElementById('ticketNumber').textContent = data.ticket.ticket_number;
                    document.getElementById('ticketQueueType').textContent = queueTypeName;
                    document.getElementById('ticketWaitTime').textContent = data.ticket.estimated_wait_time || '~5';
                    ticketModal.show();
                } else {
                    alert('Ralat: ' + data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Maaf, berlaku ralat. Sila cuba lagi.');
            }
        }

        function printTicket() {
            const ticketNumber = document.getElementById('ticketNumber').textContent;
            const queueType = document.getElementById('ticketQueueType').textContent;
            const waitTime = document.getElementById('ticketWaitTime').textContent;
            const now = new Date();

            const printContent = `
                <html>
                <head>
                    <title>Tiket Giliran</title>
                    <style>
                        body { font-family: Arial, sans-serif; text-align: center; padding: 20px; }
                        .ticket-no { font-size: 72pt; font-weight: bold; margin: 20px 0; }
                        .info { font-size: 14pt; margin: 10px 0; }
                        .footer { margin-top: 30px; font-size: 10pt; color: #666; }
                    </style>
                </head>
                <body>
                    <h2>POLIKLINIK AL-HUDA</h2>
                    <hr>
                    <p class="info">${queueType}</p>
                    <div class="ticket-no">${ticketNumber}</div>
                    <p class="info">Anggaran menunggu: ${waitTime} minit</p>
                    <hr>
                    <p class="footer">
                        ${now.toLocaleDateString('ms-MY')} ${now.toLocaleTimeString('ms-MY')}<br>
                        Sila tunggu nombor anda dipanggil.
                    </p>
                </body>
                </html>
            `;

            const printWindow = window.open('', '_blank');
            printWindow.document.write(printContent);
            printWindow.document.close();
            printWindow.print();
            printWindow.close();
        }

        function closeTicketModal() {
            ticketModal.hide();
            location.reload();
        }

        // Auto refresh page every 5 minutes
        setTimeout(() => location.reload(), 300000);
    </script>
</body>
</html>
