<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .header h1 {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 10pt;
            color: #666;
        }
        .meta {
            margin-bottom: 15px;
            font-size: 9pt;
        }
        .meta table {
            width: 100%;
        }
        .meta td {
            padding: 2px 0;
        }
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.data th,
        table.data td {
            border: 1px solid #333;
            padding: 6px 8px;
            text-align: left;
        }
        table.data th {
            background-color: #f0f0f0;
            font-weight: bold;
            font-size: 9pt;
        }
        table.data td {
            font-size: 9pt;
        }
        table.data tr:nth-child(even) {
            background-color: #fafafa;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8pt;
        }
        .badge-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .badge-approved {
            background-color: #cce5ff;
            color: #004085;
        }
        .badge-completed {
            background-color: #d4edda;
            color: #155724;
        }
        .badge-rejected {
            background-color: #f8d7da;
            color: #721c24;
        }
        .summary {
            margin-top: 20px;
            padding: 10px;
            background-color: #f5f5f5;
            border: 1px solid #ddd;
        }
        .summary table {
            width: 100%;
        }
        .summary td {
            padding: 3px 0;
        }
        .summary .label {
            font-weight: bold;
        }
        .signature-area {
            margin-top: 50px;
        }
        .signature-line {
            border-bottom: 1px solid #333;
            margin-top: 60px;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('app.name') }}</h1>
        <h1>{{ $title }}</h1>
        <p>Dicetak pada: {{ $date }}</p>
    </div>

    <div class="meta">
        <table>
            <tr>
                <td width="120"><strong>Total Transfer</strong></td>
                <td>: {{ $transfers->count() }} transaksi</td>
            </tr>
            <tr>
                <td><strong>Status Pending</strong></td>
                <td>: {{ $transfers->where('status', 'pending')->count() }} transaksi</td>
            </tr>
            <tr>
                <td><strong>Status Approved</strong></td>
                <td>: {{ $transfers->where('status', 'approved')->count() }} transaksi</td>
            </tr>
            <tr>
                <td><strong>Status Completed</strong></td>
                <td>: {{ $transfers->where('status', 'completed')->count() }} transaksi</td>
            </tr>
            <tr>
                <td><strong>Status Rejected</strong></td>
                <td>: {{ $transfers->where('status', 'rejected')->count() }} transaksi</td>
            </tr>
        </table>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th width="30" class="text-center">No</th>
                <th width="80">Kode Barang</th>
                <th>Nama Barang</th>
                <th width="90">Lokasi Asal</th>
                <th width="90">Lokasi Tujuan</th>
                <th width="70">Pemohon</th>
                <th width="60" class="text-center">Status</th>
                <th width="80">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transfers as $index => $transfer)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $transfer->commodity->item_code ?? '-' }}</td>
                <td>{{ $transfer->commodity->name ?? '-' }}</td>
                <td>{{ $transfer->fromLocation->name ?? '-' }}</td>
                <td>{{ $transfer->toLocation->name ?? '-' }}</td>
                <td>{{ $transfer->requester->name ?? '-' }}</td>
                <td class="text-center">
                    @if($transfer->status === 'pending')
                        <span class="badge badge-pending">Pending</span>
                    @elseif($transfer->status === 'approved')
                        <span class="badge badge-approved">Approved</span>
                    @elseif($transfer->status === 'completed')
                        <span class="badge badge-completed">Selesai</span>
                    @else
                        <span class="badge badge-rejected">Ditolak</span>
                    @endif
                </td>
                <td>{{ $transfer->created_at->format('d/m/Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center" style="font-style: italic; color: #666;">Tidak ada data transfer</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <h3>Ringkasan Transfer Barang</h3>
        <table>
            <tr>
                <td class="label" width="120">Pending</td>
                <td>: {{ $transfers->where('status', 'pending')->count() }} transaksi</td>
            </tr>
            <tr>
                <td class="label">Approved</td>
                <td>: {{ $transfers->where('status', 'approved')->count() }} transaksi</td>
            </tr>
            <tr>
                <td class="label">Completed</td>
                <td>: {{ $transfers->where('status', 'completed')->count() }} transaksi</td>
            </tr>
            <tr>
                <td class="label">Rejected</td>
                <td>: {{ $transfers->where('status', 'rejected')->count() }} transaksi</td>
            </tr>
            <tr style="border-top: 2px solid #333;">
                <td class="label"><strong>TOTAL</strong></td>
                <td><strong>: {{ $transfers->count() }} transaksi</strong></td>
            </tr>
        </table>
    </div>

    <div class="signature-area">
        <table width="100%">
            <tr>
                <td width="50%"></td>
                <td width="50%" class="text-center">
                    <p>{{ $date }}</p>
                    <p><strong>Penanggung Jawab</strong></p>
                    <div class="signature-line"></div>
                    <p>(_______________________)</p>
                    <p>NIP. ___________________</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
