<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Statistik Konten</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #10b981;
            padding-bottom: 20px;
        }
        
        .school-name {
            font-size: 24px;
            font-weight: bold;
            color: #059669;
            margin-bottom: 5px;
        }
        
        .report-title {
            font-size: 18px;
            color: #374151;
            margin-bottom: 10px;
        }
        
        .report-period {
            font-size: 14px;
            color: #6b7280;
        }
        
        .section {
            margin-bottom: 25px;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 15px;
            padding: 8px 12px;
            background-color: #f3f4f6;
            border-left: 4px solid #10b981;
        }
        
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .stats-row {
            display: table-row;
        }
        
        .stats-cell {
            display: table-cell;
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            background-color: #f9fafb;
        }
        
        .stats-cell.header {
            background-color: #10b981;
            color: white;
            font-weight: bold;
        }
        
        .stats-cell.data {
            background-color: white;
        }
        
        .highlight {
            background-color: #d1fae5;
            font-weight: bold;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .table th,
        .table td {
            padding: 8px 12px;
            text-align: left;
            border: 1px solid #d1d5db;
        }
        
        .table th {
            background-color: #10b981;
            color: white;
            font-weight: bold;
        }
        
        .table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        .summary-box {
            background-color: #f0fdf4;
            border: 1px solid #22c55e;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .summary-title {
            font-weight: bold;
            color: #15803d;
            margin-bottom: 10px;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #d1d5db;
            text-align: center;
            color: #6b7280;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="school-name">{{ $schoolProfile->school_name ?? 'Sekolah' }}</div>
        <div class="report-title">Laporan Statistik Konten Website</div>
        <div class="report-period">
            Periode: {{ \Carbon\Carbon::parse($startDate)->format('d F Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}
        </div>
        <div style="font-size: 10px; color: #9ca3af; margin-top: 5px;">
            Generated on {{ $generatedAt->format('d F Y H:i:s') }}
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="section">
        <div class="section-title">Ringkasan Statistik Konten</div>
        <div class="summary-box">
            <div class="summary-title">Total Konten: {{ number_format($data['news']['total'] + $data['galleries']['total']) }}</div>
            <div>Berita: {{ number_format($data['news']['total']) }} | Galeri: {{ number_format($data['galleries']['total']) }}</div>
            <div>Dipublikasikan: {{ number_format($data['news']['published'] + $data['galleries']['published']) }} | Draft: {{ number_format($data['news']['draft'] + $data['galleries']['draft']) }}</div>
        </div>
    </div>

    <!-- News Statistics -->
    <div class="section">
        <div class="section-title">Statistik Berita</div>
        <div class="stats-grid">
            <div class="stats-row">
                <div class="stats-cell header">Total Berita</div>
                <div class="stats-cell header">Dipublikasikan</div>
                <div class="stats-cell header">Draft</div>
                <div class="stats-cell header">Persentase Publikasi</div>
            </div>
            <div class="stats-row">
                <div class="stats-cell data highlight">{{ number_format($data['news']['total']) }}</div>
                <div class="stats-cell data">{{ number_format($data['news']['published']) }}</div>
                <div class="stats-cell data">{{ number_format($data['news']['draft']) }}</div>
                <div class="stats-cell data">
                    {{ $data['news']['total'] > 0 ? number_format(($data['news']['published'] / $data['news']['total']) * 100, 2) : 0 }}%
                </div>
            </div>
        </div>
    </div>

    <!-- Gallery Statistics -->
    <div class="section">
        <div class="section-title">Statistik Galeri</div>
        <div class="stats-grid">
            <div class="stats-row">
                <div class="stats-cell header">Total Galeri</div>
                <div class="stats-cell header">Dipublikasikan</div>
                <div class="stats-cell header">Draft</div>
                <div class="stats-cell header">Persentase Publikasi</div>
            </div>
            <div class="stats-row">
                <div class="stats-cell data highlight">{{ number_format($data['galleries']['total']) }}</div>
                <div class="stats-cell data">{{ number_format($data['galleries']['published']) }}</div>
                <div class="stats-cell data">{{ number_format($data['galleries']['draft']) }}</div>
                <div class="stats-cell data">
                    {{ $data['galleries']['total'] > 0 ? number_format(($data['galleries']['published'] / $data['galleries']['total']) * 100, 2) : 0 }}%
                </div>
            </div>
        </div>
    </div>

    <!-- News by Category -->
    @if($data['news']['by_category']->count() > 0)
    <div class="section">
        <div class="section-title">Berita per Kategori</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Kategori</th>
                    <th>Jumlah Berita</th>
                    <th>Persentase</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['news']['by_category'] as $category)
                <tr>
                    <td>{{ $category->newsCategory->nama ?? 'Tidak ada kategori' }}</td>
                    <td>{{ number_format($category->total) }}</td>
                    <td>{{ $data['news']['total'] > 0 ? number_format(($category->total / $data['news']['total']) * 100, 2) : 0 }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Galleries by Category -->
    @if($data['galleries']['by_category']->count() > 0)
    <div class="section">
        <div class="section-title">Galeri per Kategori</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Kategori</th>
                    <th>Jumlah Galeri</th>
                    <th>Persentase</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['galleries']['by_category'] as $category)
                <tr>
                    <td>{{ $category->kategori->nama ?? 'Tidak ada kategori' }}</td>
                    <td>{{ number_format($category->total) }}</td>
                    <td>{{ $data['galleries']['total'] > 0 ? number_format(($category->total / $data['galleries']['total']) * 100, 2) : 0 }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Admin Activity for Content -->
    @if($data['admin_activity']->count() > 0)
    <div class="section">
        <div class="section-title">Aktivitas Admin pada Konten</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Admin</th>
                    <th>Aksi</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['admin_activity'] as $activity)
                <tr>
                    <td>{{ $activity->name }}</td>
                    <td>{{ ucfirst($activity->action) }}</td>
                    <td>{{ number_format($activity->total) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Laporan ini dihasilkan secara otomatis oleh sistem Galeri Sekolah</p>
        <p>{{ $schoolProfile->school_name ?? 'Sekolah' }} - {{ $generatedAt->format('d F Y H:i:s') }}</p>
    </div>
</body>
</html>
