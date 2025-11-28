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
            margin-bottom: 15px;
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
        .condition-header {
            font-weight: bold;
            font-size: 10pt;
        }
        .condition-baik {
            background-color: #d4edda !important;
            color: #155724;
        }
        .condition-rusak-ringan {
            background-color: #fff3cd !important;
            color: #856404;
        }
        .condition-rusak-berat {
            background-color: #f8d7da !important;
            color: #721c24;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8pt;
        }
        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }
        .badge-warning {
            background-color: #fff3cd;
            color: #856404;
        }
        .badge-danger {
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
                <td width="120"><strong>Kondisi Baik</strong></td>
                <td>: {{ $conditionStats['baik'] ?? 0 }} item</td>
            </tr>
            <tr>
                <td><strong>Rusak Ringan</strong></td>
                <td>: {{ $conditionStats['rusak_ringan'] ?? 0 }} item</td>
            </tr>
            <tr>
                <td><strong>Rusak Berat</strong></td>
                <td>: {{ $conditionStats['rusak_berat'] ?? 0 }} item</td>
            </tr>
            <tr style="border-top: 1px solid #ddd;">
                <td><strong>Total Barang</strong></td>
                <td><strong>: {{ array_sum($conditionStats) }} item</strong></td>
            </tr>
        </table>
    </div>

    @php 
        $conditionLabels = [
            'baik' => 'Kondisi Baik',
            'rusak_ringan' => 'Rusak Ringan', 
            'rusak_berat' => 'Rusak Berat'
        ];
        $conditionClasses = [
            'baik' => 'condition-baik',
            'rusak_ringan' => 'condition-rusak-ringan',
            'rusak_berat' => 'condition-rusak-berat'
        ];
    @endphp

    @foreach(['baik', 'rusak_ringan', 'rusak_berat'] as $condition)
        @if(isset($commodities[$condition]) && $commodities[$condition]->count() > 0)
        <table class="data">
            <thead>
                <tr class="condition-header {{ $conditionClasses[$condition] }}">
                    <th colspan="5">{{ $conditionLabels[$condition] }} ({{ $commodities[$condition]->count() }} item)</th>
                </tr>
                <tr>
                    <th width="30" class="text-center">No</th>
                    <th width="80">Kode Barang</th>
                    <th>Nama Barang</th>
                    <th width="100">Lokasi</th>
                    <th width="90" class="text-right">Nilai (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($commodities[$condition] as $index => $commodity)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $commodity->item_code }}</td>
                    <td>{{ $commodity->name }}</td>
                    <td>{{ $commodity->location->name ?? '-' }}</td>
                    <td class="text-right">{{ number_format($commodity->purchase_price, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" class="text-right">Subtotal {{ $conditionLabels[$condition] }}</th>
                    <th class="text-right">{{ number_format($commodities[$condition]->sum('purchase_price'), 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>
        @endif
    @endforeach

    <div class="summary">
        <h3>Ringkasan Kondisi Barang</h3>
        <table>
            <tr>
                <td class="label" width="150">Baik</td>
                <td>: {{ $conditionStats['baik'] ?? 0 }} item</td>
                <td class="text-right">
                    Rp {{ number_format(isset($commodities['baik']) ? $commodities['baik']->sum('purchase_price') : 0, 0, ',', '.') }}
                </td>
            </tr>
            <tr>
                <td class="label">Rusak Ringan</td>
                <td>: {{ $conditionStats['rusak_ringan'] ?? 0 }} item</td>
                <td class="text-right">
                    Rp {{ number_format(isset($commodities['rusak_ringan']) ? $commodities['rusak_ringan']->sum('purchase_price') : 0, 0, ',', '.') }}
                </td>
            </tr>
            <tr>
                <td class="label">Rusak Berat</td>
                <td>: {{ $conditionStats['rusak_berat'] ?? 0 }} item</td>
                <td class="text-right">
                    Rp {{ number_format(isset($commodities['rusak_berat']) ? $commodities['rusak_berat']->sum('purchase_price') : 0, 0, ',', '.') }}
                </td>
            </tr>
            <tr style="border-top: 2px solid #333;">
                <td class="label"><strong>TOTAL KESELURUHAN</strong></td>
                <td><strong>: {{ array_sum($conditionStats) }} item</strong></td>
                <td class="text-right">
                    <strong>Rp {{ number_format(
                        (isset($commodities['baik']) ? $commodities['baik']->sum('purchase_price') : 0) +
                        (isset($commodities['rusak_ringan']) ? $commodities['rusak_ringan']->sum('purchase_price') : 0) +
                        (isset($commodities['rusak_berat']) ? $commodities['rusak_berat']->sum('purchase_price') : 0),
                        0, ',', '.'
                    ) }}</strong>
                </td>
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
