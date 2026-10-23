<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kuitansi SPP - {{ $payment->payment_code }}</title>
    <style>
        /* Page setup for A4 */
        @page {
            size: A4 portrait;
            margin: 15mm;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #1c1917;
            font-size: 13px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        .receipt-box {
            width: 100%;
            background: #ffffff;
        }

        /* Header Banner */
        .header-banner {
            background-color: #597928;
            color: #ffffff;
            text-align: center;
            padding: 14px;
            font-weight: bold;
            font-size: 16px;
            letter-spacing: 1px;
            margin-bottom: 20px;
        }

        /* School Info */
        .school-info {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 1px solid #e7e5e4;
            padding-bottom: 15px;
        }

        .school-name {
            font-size: 16px;
            font-weight: bold;
            color: #1c1917;
            margin: 5px 0;
        }

        .school-address {
            font-size: 11px;
            color: #78716c;
            max-width: 400px;
            margin: 0 auto;
        }

        /* Meta Table (Transaction Code & Status) */
        .meta-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .meta-table td {
            padding: 8px 12px;
            background-color: #fafaf9;
            border: 1px solid #e7e5e4;
        }

        /* Information Grid Table */
        .info-table {
            width: 100%;
            margin-bottom: 25px;
            border-collapse: collapse;
            background-color: #fcf9f5;
        }

        .info-table td {
            padding: 8px 12px;
            border: 1px solid #e7e5e4;
            vertical-align: top;
        }

        .info-label {
            font-size: 10px;
            text-transform: uppercase;
            font-weight: bold;
            color: #78716c;
            display: block;
            margin-bottom: 2px;
        }

        .info-value {
            font-size: 13px;
            font-weight: bold;
            color: #1c1917;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .items-table th {
            background-color: #e2ebd7;
            color: #1c1917;
            font-size: 11px;
            text-transform: uppercase;
            text-align: left;
            padding: 8px 10px;
            border: 1px solid #d6d3d1;
        }

        .items-table td {
            padding: 8px 10px;
            border: 1px solid #d6d3d1;
            font-size: 12px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .total-row {
            background-color: #f5f5f4;
            font-weight: bold;
        }

        /* Signature Section */
        .signature-table {
            width: 100%;
            margin-top: 40px;
            border-collapse: collapse;
        }

        .signature-table td {
            vertical-align: top;
            width: 50%;
        }

        .footer-note {
            font-size: 10px;
            color: #78716c;
        }

        .signature-space {
            height: 60px;
        }

        .signature-name {
            font-weight: bold;
            border-bottom: 1px solid #a8a29e;
            display: inline-block;
            padding-bottom: 2px;
            padding-left: 20px;
            padding-right: 20px;
        }
    </style>
</head>

<body>

    <div class="receipt-box">
        {{-- Header Banner --}}
        <div class="header-banner">
            BUKTI PEMBAYARAN SPP SEKOLAH
        </div>

        {{-- School Info --}}
        <div class="school-info">
            <h2 class="school-name">{{ $school->school_name ?? 'Your School Name' }}</h2>
            <p class="school-address">{{ $school->address ?? 'Alamat sekolah belum diatur.' }}</p>
        </div>

        {{-- Meta Details Table --}}
        <table class="meta-table">
            <tr>
                <td>
                    <span class="info-label">Status Pembayaran</span>
                    <span style="color: #047857; font-weight: bold; font-size: 11px;">LUNAS (PAID)</span>
                </td>
                <td class="text-right">
                    <span class="info-label">Kode Transaksi</span>
                    <span style="font-family: monospace; font-weight: bold;">{{ $payment->payment_code }}</span>
                </td>
            </tr>
        </table>

        {{-- Student Information Grid --}}
        <table class="info-table">
            <tr>
                <td width="50%">
                    <span class="info-label">Nama Siswa</span>
                    <span class="info-value">{{ $payment->bill->student->fullname }}</span>
                </td>
                <td width="50%">
                    <span class="info-label">Nomor Induk Siswa (NIS)</span>
                    <span class="info-value"
                        style="font-family: monospace;">{{ $payment->bill->student->nis ?? '-' }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="info-label">Periode Tagihan</span>
                    <span class="info-value">{{ $payment->bill->billing_period }}</span>
                </td>
                <td>
                    <span class="info-label">Tanggal Pembayaran</span>
                    <span class="info-value">{{ $formattedDate }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="info-label">Metode Pembayaran</span>
                    <span class="info-value">{{ $payment->paymentMethod->name }}</span>
                </td>
                <td>
                    <span class="info-label">Catatan</span>
                    <span class="info-value"
                        style="font-style: italic; font-weight: normal;">{{ $payment->notes ?? '-' }}</span>
                </td>
            </tr>
        </table>

        {{-- Items Table --}}
        <table class="items-table">
            <thead>
                <tr>
                    <th class="text-center" width="10%">No</th>
                    <th width="65%">Rincian / Keterangan</th>
                    <th class="text-right" width="25%">Nominal</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">1</td>
                    <td>Pembayaran Iuran SPP Periode {{ $payment->bill->billing_period }}</td>
                    <td class="text-right">{{ rupiah($payment->bill->amount) }}</td>
                </tr>
                <tr class="total-row">
                    <td colspan="2" class="text-right">Total Pembayaran:</td>
                    <td class="text-right" style="color: #597928;">{{ rupiah($payment->bill->amount) }}</td>
                </tr>
            </tbody>
        </table>

        {{-- Signature Section --}}
        <table class="signature-table">
            <tr>
                <td>
                    <div class="footer-note">
                        <p>Dokumen ini dicetak secara otomatis melalui sistem.</p>
                        <p>Sah dan tidak memerlukan stempel basah apabila diverifikasi.</p>
                    </div>
                </td>
                <td class="text-center">
                    <p style="margin-bottom: 5px; color: #57534e;">Petugas / Bendahara Sekolah</p>
                    <div class="signature-space"></div>
                    <span class="signature-name">( ______________________ )</span>
                </td>
            </tr>
        </table>
    </div>

</body>

</html>
