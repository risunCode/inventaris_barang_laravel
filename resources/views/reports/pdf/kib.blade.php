<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }} - {{ $commodity->item_code }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .header h1 {
            font-size: 18pt;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .header h2 {
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 10pt;
            color: #666;
        }
        .kib-info {
            margin-bottom: 20px;
            text-align: center;
        }
        .kib-info h3 {
            font-size: 14pt;
            margin-bottom: 10px;
            background-color: #f0f0f0;
            padding: 8px;
            border: 1px solid #333;
        }
        table.info {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table.info td {
            padding: 8px 12px;
            border: 1px solid #333;
            vertical-align: top;
        }
        table.info .label {
            font-weight: bold;
            background-color: #f8f9fa;
            width: 180px;
        }
        table.info .value {
            background-color: #ffffff;
        }
        .image-section {
            margin: 20px 0;
            text-align: center;
        }
        .image-placeholder {
            width: 250px;
            height: 200px;
            border: 2px solid #333;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f5f5f5;
            font-style: italic;
            color: #666;
        }
        .maintenance-history {
            margin-top: 25px;
        }
        .maintenance-history h4 {
            font-size: 12pt;
            margin-bottom: 10px;
            background-color: #e9ecef;
            padding: 6px 12px;
            border: 1px solid #333;
        }
        table.maintenance {
            width: 100%;
            border-collapse: collapse;
            font-size: 10pt;
        }
        table.maintenance th,
        table.maintenance td {
            border: 1px solid #333;
            padding: 6px 8px;
            text-align: left;
        }
        table.maintenance th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .signature-area {
            margin-top: 40px;
            page-break-inside: avoid;
        }
        .signature-box {
            width: 45%;
            display: inline-block;
            text-align: center;
            vertical-align: top;
        }
        .signature-line {
            border-bottom: 1px solid #333;
            margin-top: 60px;
            margin-bottom: 5px;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9pt;
            font-weight: bold;
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
        .qr-code {
            float: right;
            margin: 0 0 10px 10px;
            border: 1px solid #333;
            padding: 10px;
            background-color: #fff;
        }
        .specifications {
            margin: 15px 0;
        }
        .specifications pre {
            background-color: #f8f9fa;
            padding: 10px;
            border: 1px solid #dee2e6;
            font-size: 9pt;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('app.name') }}</h1>
        <h2>{{ $title }}</h2>
        <p>Dicetak pada: {{ $date }}</p>
    </div>

    <div class="kib-info">
        <h3>KARTU INVENTARIS BARANG</h3>
        <!-- QR Code Placeholder -->
        <div class="qr-code">
            <div style="width: 80px; height: 80px; border: 1px solid #ccc; display: flex; align-items: center; justify-content: center; font-size: 8pt; text-align: center;">
                QR CODE<br>{{ $commodity->item_code }}
            </div>
        </div>
    </div>

    <!-- Identitas Barang -->
    <table class="info">
        <tr>
            <td class="label">Kode Barang</td>
            <td class="value"><strong>{{ $commodity->item_code }}</strong></td>
        </tr>
        <tr>
            <td class="label">Nama Barang</td>
            <td class="value"><strong>{{ $commodity->name }}</strong></td>
        </tr>
        <tr>
            <td class="label">Kategori</td>
            <td class="value">{{ $commodity->category->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Merk/Brand</td>
            <td class="value">{{ $commodity->brand ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Model/Tipe</td>
            <td class="value">{{ $commodity->model ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Serial Number</td>
            <td class="value">{{ $commodity->serial_number ?? '-' }}</td>
        </tr>
    </table>

    <!-- Perolehan & Lokasi -->
    <table class="info">
        <tr>
            <td class="label">Cara Perolehan</td>
            <td class="value">{{ $commodity->acquisition_type_label }}</td>
        </tr>
        <tr>
            <td class="label">Sumber Perolehan</td>
            <td class="value">{{ $commodity->acquisition_source ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tahun Perolehan</td>
            <td class="value">{{ $commodity->purchase_year ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Harga Perolehan</td>
            <td class="value"><strong>{{ $commodity->formatted_price }}</strong></td>
        </tr>
        <tr>
            <td class="label">Jumlah</td>
            <td class="value">{{ $commodity->quantity }} unit</td>
        </tr>
        <tr>
            <td class="label">Kondisi</td>
            <td class="value">
                @if($commodity->condition === 'baik')
                    <span class="badge badge-success">{{ $commodity->condition_label }}</span>
                @elseif($commodity->condition === 'rusak_ringan')
                    <span class="badge badge-warning">{{ $commodity->condition_label }}</span>
                @else
                    <span class="badge badge-danger">{{ $commodity->condition_label }}</span>
                @endif
            </td>
        </tr>
    </table>

    <!-- Lokasi & Penanggung Jawab -->
    <table class="info">
        <tr>
            <td class="label">Lokasi</td>
            <td class="value">{{ $commodity->location->name ?? '-' }}</td>
        </tr>
        @if($commodity->location && ($commodity->location->building || $commodity->location->floor || $commodity->location->room))
        <tr>
            <td class="label">Detail Lokasi</td>
            <td class="value">
                @if($commodity->location->building){{ $commodity->location->building }}@endif
                @if($commodity->location->floor) Lt.{{ $commodity->location->floor }}@endif
                @if($commodity->location->room) {{ $commodity->location->room }}@endif
            </td>
        </tr>
        @endif
        <tr>
            <td class="label">Penanggung Jawab</td>
            <td class="value">{{ $commodity->responsible_person ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Dibuat oleh</td>
            <td class="value">{{ $commodity->creator->name ?? '-' }} ({{ $commodity->created_at->format('d/m/Y') }})</td>
        </tr>
        @if($commodity->updater)
        <tr>
            <td class="label">Terakhir diubah</td>
            <td class="value">{{ $commodity->updater->name }} ({{ $commodity->updated_at->format('d/m/Y') }})</td>
        </tr>
        @endif
    </table>

    <!-- Spesifikasi -->
    @if($commodity->specifications)
    <div class="specifications">
        <h4 style="font-size: 12pt; margin-bottom: 8px; background-color: #e9ecef; padding: 6px 12px; border: 1px solid #333;">Spesifikasi Teknis</h4>
        <pre>{{ $commodity->specifications }}</pre>
    </div>
    @endif

    <!-- Catatan -->
    @if($commodity->notes)
    <div class="specifications">
        <h4 style="font-size: 12pt; margin-bottom: 8px; background-color: #e9ecef; padding: 6px 12px; border: 1px solid #333;">Catatan</h4>
        <pre>{{ $commodity->notes }}</pre>
    </div>
    @endif

    <!-- Foto Barang -->
    <div class="image-section">
        <h4 style="font-size: 12pt; margin-bottom: 10px;">Foto Barang</h4>
        <div class="image-placeholder">
            @if($commodity->images && $commodity->images->count() > 0)
                Foto tersedia ({{ $commodity->images->count() }} gambar)
            @else
                Tidak ada foto
            @endif
        </div>
    </div>

    <!-- Riwayat Maintenance -->
    @if($commodity->maintenances && $commodity->maintenances->count() > 0)
    <div class="maintenance-history">
        <h4>Riwayat Pemeliharaan</h4>
        <table class="maintenance">
            <thead>
                <tr>
                    <th width="80">Tanggal</th>
                    <th>Jenis Pemeliharaan</th>
                    <th width="100">Teknisi</th>
                    <th width="80" class="text-right">Biaya</th>
                </tr>
            </thead>
            <tbody>
                @foreach($commodity->maintenances->sortByDesc('maintenance_date') as $maintenance)
                <tr>
                    <td>{{ $maintenance->maintenance_date->format('d/m/Y') }}</td>
                    <td>{{ $maintenance->description }}</td>
                    <td>{{ $maintenance->performed_by }}</td>
                    <td class="text-right">{{ number_format($maintenance->cost, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-right">Total Biaya Maintenance</th>
                    <th class="text-right">{{ number_format($commodity->maintenances->sum('cost'), 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
    @endif

    <!-- Area Tanda Tangan -->
    <div class="signature-area">
        <table width="100%">
            <tr>
                <td width="50%" class="signature-box">
                    <p><strong>Pengelola Barang</strong></p>
                    <div class="signature-line"></div>
                    <p>(_______________________)</p>
                    <p>NIP. ___________________</p>
                </td>
                <td width="50%" class="signature-box">
                    <p>{{ $date }}</p>
                    <p><strong>Penanggung Jawab</strong></p>
                    <div class="signature-line"></div>
                    <p>(_______________________)</p>
                    <p>NIP. ___________________</p>
                </td>
            </tr>
        </table>
    </div>

    <!-- Footer Info -->
    <div style="margin-top: 30px; font-size: 9pt; color: #666; text-align: center; border-top: 1px solid #ddd; padding-top: 10px;">
        <p>Kartu Inventaris Barang - {{ config('app.name') }}</p>
        <p>Dokumen ini dicetak secara otomatis pada {{ now()->format('d F Y H:i:s') }} WIB</p>
    </div>
</body>
</html>
