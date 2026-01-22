<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Dispensing - {{ $record->dispensing_no }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: #fff;
        }

        .container {
            max-width: 80mm;
            margin: 0 auto;
            padding: 10px;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #000;
        }

        .header h1 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 10px;
            color: #666;
        }

        .section {
            margin-bottom: 12px;
        }

        .section-title {
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
            margin-bottom: 5px;
            padding-bottom: 3px;
            border-bottom: 1px solid #ddd;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }

        .info-label {
            color: #666;
        }

        .info-value {
            font-weight: 500;
            text-align: right;
        }

        .patient-name {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            margin: 10px 0;
        }

        .medicine-list {
            width: 100%;
            border-collapse: collapse;
        }

        .medicine-list th,
        .medicine-list td {
            padding: 5px 3px;
            text-align: left;
            vertical-align: top;
        }

        .medicine-list th {
            border-bottom: 1px solid #000;
            font-size: 10px;
            text-transform: uppercase;
        }

        .medicine-list td {
            border-bottom: 1px dashed #ccc;
        }

        .medicine-name {
            font-weight: bold;
        }

        .medicine-dosage {
            font-size: 10px;
            color: #666;
            margin-top: 2px;
        }

        .qty {
            text-align: center;
            font-weight: bold;
        }

        .price {
            text-align: right;
        }

        .total-section {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #000;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 3px 0;
        }

        .total-row.grand-total {
            font-size: 14px;
            font-weight: bold;
            border-top: 1px solid #000;
            margin-top: 5px;
            padding-top: 8px;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            color: #666;
            padding-top: 10px;
            border-top: 1px dashed #000;
        }

        .footer p {
            margin-bottom: 3px;
        }

        .pharmacist-signature {
            margin-top: 20px;
            text-align: center;
        }

        .signature-line {
            width: 60%;
            border-bottom: 1px solid #000;
            margin: 30px auto 5px;
        }

        .barcode {
            text-align: center;
            margin: 15px 0;
            font-family: 'Libre Barcode 39', monospace;
            font-size: 32px;
        }

        .qr-placeholder {
            text-align: center;
            margin: 10px 0;
        }

        .warning-box {
            background: #fff3cd;
            border: 1px solid #ffc107;
            padding: 8px;
            margin: 10px 0;
            font-size: 10px;
        }

        .warning-box strong {
            display: block;
            margin-bottom: 3px;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .no-print {
                display: none;
            }

            .container {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>POLIKLINIK AL-HUDA</h1>
            <p>No. 123, Jalan Contoh, Taman Contoh</p>
            <p>12345 Kuala Lumpur</p>
            <p>Tel: 03-1234 5678</p>
        </div>

        <!-- Dispensing Info -->
        <div class="section">
            <div class="info-row">
                <span class="info-label">No. Slip:</span>
                <span class="info-value">{{ $record->dispensing_no }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tarikh:</span>
                <span class="info-value">{{ $record->dispensed_at?->format('d/m/Y H:i') ?? now()->format('d/m/Y H:i') }}</span>
            </div>
            @if($record->prescription)
            <div class="info-row">
                <span class="info-label">No. Preskripsi:</span>
                <span class="info-value">{{ $record->prescription->prescription_no }}</span>
            </div>
            @endif
        </div>

        <!-- Patient Info -->
        <div class="section">
            <div class="section-title">Maklumat Pesakit</div>
            <div class="patient-name">{{ $record->patient?->name ?? '-' }}</div>
            <div class="info-row">
                <span class="info-label">No. MRN:</span>
                <span class="info-value">{{ $record->patient?->mrn ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Umur/Jantina:</span>
                <span class="info-value">
                    {{ $record->patient?->age ?? '-' }} tahun /
                    {{ $record->patient?->gender == 'male' ? 'L' : ($record->patient?->gender == 'female' ? 'P' : '-') }}
                </span>
            </div>
        </div>

        @if($record->prescription?->doctor)
        <div class="section">
            <div class="info-row">
                <span class="info-label">Doktor:</span>
                <span class="info-value">{{ $record->prescription->doctor->user?->name ?? '-' }}</span>
            </div>
        </div>
        @endif

        <!-- Medicine List -->
        <div class="section">
            <div class="section-title">Senarai Ubat</div>
            <table class="medicine-list">
                <thead>
                    <tr>
                        <th>Ubat</th>
                        <th class="qty">Qty</th>
                        <th class="price">Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($record->items as $item)
                    <tr>
                        <td>
                            <div class="medicine-name">{{ $item->medicine?->name ?? '-' }}</div>
                            @if($item->medicine?->strength)
                            <div class="medicine-dosage">{{ $item->medicine->strength }}</div>
                            @endif
                            @if($item->dosage_instructions)
                            <div class="medicine-dosage">{{ $item->dosage_instructions }}</div>
                            @endif
                        </td>
                        <td class="qty">{{ $item->quantity_dispensed }}</td>
                        <td class="price">{{ number_format($item->total_price, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Total -->
        <div class="total-section">
            <div class="total-row">
                <span>Jumlah Item:</span>
                <span>{{ $record->items->count() }}</span>
            </div>
            <div class="total-row grand-total">
                <span>JUMLAH:</span>
                <span>RM {{ number_format($record->total_amount, 2) }}</span>
            </div>
        </div>

        <!-- Warnings if any -->
        @if($record->patient?->allergies)
        <div class="warning-box">
            <strong>AMARAN ALAHAN:</strong>
            {{ $record->patient->allergies }}
        </div>
        @endif

        <!-- Pharmacist Signature -->
        <div class="pharmacist-signature">
            <div class="signature-line"></div>
            <p>{{ $record->dispensedBy?->name ?? '-' }}</p>
            <p style="font-size: 9px; color: #666;">Pharmacist / Ahli Farmasi</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>ARAHAN PENTING:</strong></p>
            <p>Ambil ubat mengikut arahan yang diberikan.</p>
            <p>Simpan ubat di tempat yang sejuk dan kering.</p>
            <p>Jauhkan dari kanak-kanak.</p>
            <p style="margin-top: 10px;">Terima kasih kerana memilih Poliklinik Al-Huda</p>
            <p style="margin-top: 5px; font-size: 9px;">Dicetak: {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>

    <!-- Print Button (not shown when printing) -->
    <div class="no-print" style="text-align: center; margin: 20px; padding: 20px;">
        <button onclick="window.print()" style="padding: 10px 30px; font-size: 14px; cursor: pointer;">
            Cetak Slip
        </button>
        <button onclick="window.close()" style="padding: 10px 30px; font-size: 14px; cursor: pointer; margin-left: 10px;">
            Tutup
        </button>
    </div>

    <script>
        // Auto print on page load (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
