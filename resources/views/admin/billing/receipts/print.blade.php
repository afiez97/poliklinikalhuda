<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resit {{ $receipt->receipt_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            width: 80mm;
            padding: 5mm;
            background: white;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }
        .clinic-name {
            font-size: 16px;
            font-weight: bold;
        }
        .clinic-info {
            font-size: 10px;
            color: #333;
        }
        .receipt-title {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            margin: 10px 0;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
        }
        .info-row .label {
            color: #666;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }
        .items {
            margin: 10px 0;
        }
        .item {
            margin: 5px 0;
        }
        .item-name {
            display: block;
        }
        .item-detail {
            display: flex;
            justify-content: space-between;
            padding-left: 10px;
            font-size: 11px;
        }
        .totals {
            margin: 10px 0;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
        }
        .total-row.grand {
            font-size: 14px;
            font-weight: bold;
            border-top: 1px solid #000;
            padding-top: 5px;
            margin-top: 5px;
        }
        .payment-info {
            background: #f5f5f5;
            padding: 10px;
            margin: 10px 0;
        }
        .footer {
            text-align: center;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px dashed #000;
            font-size: 10px;
        }
        .footer p {
            margin: 3px 0;
        }
        .qr-code {
            text-align: center;
            margin: 10px 0;
        }
        @media print {
            body {
                width: 80mm;
            }
            @page {
                size: 80mm auto;
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="clinic-name">POLIKLINIK AL-HUDA</div>
        <div class="clinic-info">
            No. 123, Jalan Contoh<br>
            43000 Kajang, Selangor<br>
            Tel: 03-1234 5678<br>
            SSM: 123456-A
        </div>
    </div>

    <div class="receipt-title">*** RESIT RASMI ***</div>

    <div class="info-section">
        <div class="info-row">
            <span class="label">No. Resit:</span>
            <span>{{ $receipt->receipt_number }}</span>
        </div>
        <div class="info-row">
            <span class="label">Tarikh:</span>
            <span>{{ $receipt->created_at->format('d/m/Y H:i') }}</span>
        </div>
        <div class="info-row">
            <span class="label">No. Invois:</span>
            <span>{{ $invoice->invoice_number }}</span>
        </div>
    </div>

    <div class="divider"></div>

    <div class="info-section">
        <div class="info-row">
            <span class="label">Pesakit:</span>
            <span></span>
        </div>
        <div style="padding-left: 10px;">
            {{ $invoice->patient->name }}<br>
            MRN: {{ $invoice->patient->mrn }}
        </div>
    </div>

    <div class="divider"></div>

    <div class="items">
        @foreach($invoice->items as $item)
        <div class="item">
            <span class="item-name">{{ $item->item_name }}</span>
            <div class="item-detail">
                <span>{{ $item->quantity }} x RM{{ number_format($item->unit_price, 2) }}</span>
                <span>RM{{ number_format($item->line_total, 2) }}</span>
            </div>
        </div>
        @endforeach
    </div>

    <div class="divider"></div>

    <div class="totals">
        <div class="total-row">
            <span>Jumlah Kecil:</span>
            <span>RM{{ number_format($invoice->subtotal, 2) }}</span>
        </div>
        @if($invoice->discount_amount > 0)
        <div class="total-row">
            <span>Diskaun:</span>
            <span>-RM{{ number_format($invoice->discount_amount, 2) }}</span>
        </div>
        @endif
        @if($invoice->tax_amount > 0)
        <div class="total-row">
            <span>SST ({{ $invoice->tax_rate }}%):</span>
            <span>RM{{ number_format($invoice->tax_amount, 2) }}</span>
        </div>
        @endif
        @if($invoice->rounding_amount != 0)
        <div class="total-row">
            <span>Pembundaran:</span>
            <span>RM{{ number_format($invoice->rounding_amount, 2) }}</span>
        </div>
        @endif
        <div class="total-row grand">
            <span>JUMLAH:</span>
            <span>RM{{ number_format($invoice->grand_total, 2) }}</span>
        </div>
    </div>

    <div class="payment-info">
        <div class="total-row">
            <span>Kaedah Bayaran:</span>
            <span>
                @php
                    $methodLabel = match($payment->payment_method) {
                        'cash' => 'TUNAI',
                        'card' => 'KAD',
                        'qr' => 'QR PAY',
                        'ewallet' => 'E-WALLET',
                        'transfer' => 'PINDAHAN',
                        'panel' => 'PANEL',
                        default => strtoupper($payment->payment_method)
                    };
                @endphp
                {{ $methodLabel }}
            </span>
        </div>
        <div class="total-row">
            <span>Jumlah Dibayar:</span>
            <span>RM{{ number_format($payment->amount, 2) }}</span>
        </div>
        @if($payment->payment_method === 'card' && $payment->card_last_four)
        <div class="total-row">
            <span>Kad:</span>
            <span>****{{ $payment->card_last_four }}</span>
        </div>
        @endif
        @if($payment->reference_number)
        <div class="total-row">
            <span>Rujukan:</span>
            <span>{{ $payment->reference_number }}</span>
        </div>
        @endif
    </div>

    @if($invoice->balance > 0)
    <div style="text-align: center; padding: 10px; background: #ffeeee; margin: 10px 0;">
        <strong>BAKI BELUM DIBAYAR:</strong><br>
        <span style="font-size: 16px; color: red;">RM{{ number_format($invoice->balance, 2) }}</span>
    </div>
    @else
    <div style="text-align: center; padding: 10px; background: #eeffee; margin: 10px 0;">
        <strong style="color: green;">*** DIBAYAR PENUH ***</strong>
    </div>
    @endif

    <div class="footer">
        <p>Terima kasih atas kunjungan anda.</p>
        <p>Semoga cepat sembuh!</p>
        <p style="margin-top: 10px;">--------------------------------</p>
        <p>Resit ini adalah bukti pembayaran sah.</p>
        <p>Sila simpan untuk rujukan.</p>
        <p style="margin-top: 10px; font-size: 9px;">
            Dicetak: {{ now()->format('d/m/Y H:i:s') }}
        </p>
    </div>
</body>
</html>
