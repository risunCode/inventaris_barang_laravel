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
        .month-header {
            background-color: #e9ecef !important;
            font-weight: bold;
            font-size: 10pt;
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
                <td width="150"><strong>Total Maintenance</strong></td>
                <td>: {{ $logs->count() }} kegiatan</td>
            </tr>
            <tr>
                <td><strong>Total Biaya</strong></td>
                <td>: Rp {{ number_format($totalCost, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>Rata-rata Biaya</strong></td>
                <td>: Rp {{ $logs->count() > 0 ? number_format($totalCost / $logs->count(), 0, ',', '.') : '0' }}</td>
            </tr>
            <tr>
                <td><strong>Periode</strong></td>
                <td>: 
                    @if($logs->count() > 0)
                        {{ $logs->min('maintenance_date')->format('d/m/Y') }} - {{ $logs->max('maintenance_date')->format('d/m/Y') }}
                    @else
                        -
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th width="30" class="text-center">No</th>
                <th width="80">Kode Barang</th>
                <th>Nama Barang</th>
                <th width="80">Tanggal</th>
                <th width="120">Jenis Maintenance</th>
                <th width="80">Teknisi</th>
                <th width="80" class="text-right">Biaya (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $index => $log)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $log->commodity->item_code ?? '-' }}</td>
                <td>{{ $log->commodity->name ?? '-' }}</td>
                <td>{{ $log->maintenance_date->format('d/m/Y') }}</td>
                <td>{{ $log->description }}</td>
                <td>{{ $log->performed_by }}</td>
                <td class="text-right">{{ number_format($log->cost, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center" style="font-style: italic; color: #666;">Tidak ada data maintenance</td>
            </tr>
            @endforelse
        </tbody>
        @if($logs->count() > 0)
        <tfoot>
            <tr>
                <th colspan="6" class="text-right">TOTAL BIAYA</th>
                <th class="text-right">{{ number_format($totalCost, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
        @endif
    </table>

    @if($logs->count() > 0)
    <div class="summary">
        <h3>Ringkasan Maintenance per Bulan</h3>
        <table>
            @php
                $monthlyData = $logs->groupBy(function($log) {
                    return $log->maintenance_date->format('Y-m');
                })->map(function($monthLogs) {
                    return [
                        'count' => $monthLogs->count(),
                        'cost' => $monthLogs->sum('cost'),
                        'month_name' => $monthLogs->first()->maintenance_date->translatedFormat('F Y')
                    ];
                })->sortKeys();
            @endphp
            
            @foreach($monthlyData as $monthKey => $data)
            <tr>
                <td class="label" width="120">{{ $data['month_name'] }}</td>
                <td width="80">: {{ $data['count'] }} kegiatan</td>
                <td class="text-right">Rp {{ number_format($data['cost'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr style="border-top: 2px solid #333;">
                <td class="label"><strong>TOTAL KESELURUHAN</strong></td>
                <td><strong>: {{ $logs->count() }} kegiatan</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($totalCost, 0, ',', '.') }}</strong></td>
            </tr>
        </table>
        
        <h4 style="margin-top: 15px;">Jenis Maintenance Terbanyak</h4>
        <table style="margin-top: 5px;">
            @php
                $maintenanceTypes = $logs->groupBy('description')->map(function($typeLogs) {
                    return [
                        'count' => $typeLogs->count(),
                        'cost' => $typeLogs->sum('cost')
                    ];
                })->sortByDesc('count')->take(5);
            @endphp
            
            @foreach($maintenanceTypes as $type => $data)
            <tr>
                <td class="label" width="200">{{ $type }}</td>
                <td width="80">: {{ $data['count'] }} kali</td>
                <td class="text-right">Rp {{ number_format($data['cost'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </table>
    </div>
    @endif

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
