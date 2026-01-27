<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji - {{ $record->staff->user->name ?? $record->staff->name ?? 'Staf' }} - {{ $record->payrollPeriod->period_name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: #fff;
        }
        .payslip {
            max-width: 800px;
            margin: 20px auto;
            padding: 30px;
            border: 2px solid #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .company-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .company-address {
            font-size: 11px;
            color: #666;
        }
        .payslip-title {
            font-size: 16px;
            font-weight: bold;
            margin-top: 10px;
            text-transform: uppercase;
        }
        .period {
            font-size: 13px;
            margin-top: 5px;
        }
        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .info-box {
            width: 48%;
        }
        .info-box h4 {
            font-size: 12px;
            text-transform: uppercase;
            border-bottom: 1px solid #333;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .info-table {
            width: 100%;
        }
        .info-table td {
            padding: 3px 0;
        }
        .info-table td:first-child {
            width: 40%;
            color: #666;
        }
        .salary-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .salary-box {
            width: 48%;
        }
        .salary-box h4 {
            font-size: 12px;
            text-transform: uppercase;
            padding: 8px;
            margin-bottom: 0;
        }
        .earnings-header {
            background: #28a745;
            color: #fff;
        }
        .deductions-header {
            background: #dc3545;
            color: #fff;
        }
        .salary-table {
            width: 100%;
            border-collapse: collapse;
        }
        .salary-table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .salary-table td:last-child {
            text-align: right;
            width: 35%;
        }
        .salary-table tr:last-child {
            font-weight: bold;
            background: #f5f5f5;
        }
        .total-section {
            background: #333;
            color: #fff;
            padding: 15px;
            text-align: center;
            margin: 20px 0;
        }
        .total-section .label {
            font-size: 14px;
            margin-bottom: 5px;
        }
        .total-section .amount {
            font-size: 24px;
            font-weight: bold;
        }
        .employer-section {
            background: #e3f2fd;
            padding: 15px;
            margin-bottom: 20px;
        }
        .employer-section h4 {
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        .employer-table {
            width: 100%;
        }
        .employer-table td {
            padding: 5px 0;
        }
        .employer-table td:last-child {
            text-align: right;
        }
        .footer {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            width: 45%;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 50px;
            padding-top: 5px;
        }
        .note {
            margin-top: 20px;
            padding: 10px;
            background: #fff3cd;
            font-size: 10px;
            color: #856404;
        }
        @media print {
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
            .payslip {
                border: none;
                margin: 0;
                padding: 20px;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center; padding: 10px; background: #f0f0f0;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 14px; cursor: pointer;">
            Cetak Slip Gaji
        </button>
        <a href="{{ route('admin.payroll.record.show', $record) }}" style="margin-left: 10px; padding: 10px 20px; font-size: 14px; text-decoration: none; color: #333;">
            Kembali
        </a>
    </div>

    <div class="payslip">
        <!-- Header -->
        <div class="header">
            <div class="company-name">POLIKLINIK AL-HUDA</div>
            <div class="company-address">
                Alamat Klinik<br>
                Tel: xxx-xxxxxxx | Email: info@poliklinikalhuda.com
            </div>
            <div class="payslip-title">Slip Gaji / Payslip</div>
            <div class="period">{{ $record->payrollPeriod->period_name }}</div>
        </div>

        <!-- Employee Info -->
        <div class="info-section">
            <div class="info-box">
                <h4>Maklumat Pekerja</h4>
                <table class="info-table">
                    <tr>
                        <td>Nama</td>
                        <td><strong>{{ $record->staff->user->name ?? $record->staff->name ?? '-' }}</strong></td>
                    </tr>
                    <tr>
                        <td>No. Pekerja</td>
                        <td>{{ $record->staff->staff_no ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>No. K/P</td>
                        <td>{{ $record->staff->ic_number ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Jabatan</td>
                        <td>{{ $record->staff->department->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Jawatan</td>
                        <td>{{ $record->staff->position ?? '-' }}</td>
                    </tr>
                </table>
            </div>
            <div class="info-box">
                <h4>Maklumat Caruman</h4>
                <table class="info-table">
                    <tr>
                        <td>No. KWSP</td>
                        <td>{{ $record->staff->epf_number ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>No. PERKESO</td>
                        <td>{{ $record->staff->socso_number ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>No. Cukai</td>
                        <td>{{ $record->staff->tax_number ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Tarikh Bayaran</td>
                        <td>{{ $record->payrollPeriod->payment_date->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td>No. Akaun Bank</td>
                        <td>{{ $record->staff->bank_account ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Salary Details -->
        <div class="salary-section">
            <div class="salary-box">
                <h4 class="earnings-header">Pendapatan / Earnings</h4>
                <table class="salary-table">
                    <tr>
                        <td>Gaji Asas</td>
                        <td>{{ number_format($record->basic_salary, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Elaun</td>
                        <td>{{ number_format($record->allowances, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Bayaran Lebih Masa</td>
                        <td>{{ number_format($record->overtime_pay, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Komisen</td>
                        <td>{{ number_format($record->commission, 2) }}</td>
                    </tr>
                    <tr>
                        <td>JUMLAH KASAR</td>
                        <td>RM {{ number_format($record->gross_salary, 2) }}</td>
                    </tr>
                </table>
            </div>
            <div class="salary-box">
                <h4 class="deductions-header">Potongan / Deductions</h4>
                <table class="salary-table">
                    <tr>
                        <td>KWSP (11%)</td>
                        <td>{{ number_format($record->employee_epf, 2) }}</td>
                    </tr>
                    <tr>
                        <td>PERKESO</td>
                        <td>{{ number_format($record->employee_socso, 2) }}</td>
                    </tr>
                    <tr>
                        <td>SIP / EIS</td>
                        <td>{{ number_format($record->employee_eis, 2) }}</td>
                    </tr>
                    <tr>
                        <td>PCB / Cukai</td>
                        <td>{{ number_format($record->pcb, 2) }}</td>
                    </tr>
                    @if($record->other_deductions > 0)
                    <tr>
                        <td>Potongan Lain</td>
                        <td>{{ number_format($record->other_deductions, 2) }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td>JUMLAH POTONGAN</td>
                        <td>RM {{ number_format($record->total_deductions, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Net Salary -->
        <div class="total-section">
            <div class="label">GAJI BERSIH / NET SALARY</div>
            <div class="amount">RM {{ number_format($record->net_salary, 2) }}</div>
        </div>

        <!-- Employer Contribution -->
        <div class="employer-section">
            <h4>Caruman Majikan (Untuk Makluman)</h4>
            <table class="employer-table">
                <tr>
                    <td>KWSP Majikan (12%)</td>
                    <td>RM {{ number_format($record->employer_epf, 2) }}</td>
                    <td>PERKESO Majikan</td>
                    <td>RM {{ number_format($record->employer_socso, 2) }}</td>
                    <td>SIP Majikan</td>
                    <td>RM {{ number_format($record->employer_eis, 2) }}</td>
                </tr>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="signature-box">
                <div class="signature-line">
                    Disediakan Oleh
                </div>
            </div>
            <div class="signature-box">
                <div class="signature-line">
                    Disahkan Oleh
                </div>
            </div>
        </div>

        <!-- Note -->
        <div class="note">
            <strong>Nota:</strong> Slip gaji ini adalah sulit dan hanya untuk rujukan penerima.
            Sebarang percanggahan sila rujuk kepada Jabatan Sumber Manusia dalam tempoh 7 hari bekerja.
            Dokumen ini dijana secara automatik dan sah tanpa tandatangan.
        </div>
    </div>
</body>
</html>
