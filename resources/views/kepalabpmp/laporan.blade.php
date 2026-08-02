<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPANDU - Laporan Kepala BPMP</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root{--primary:#4338ca;--secondary:#2563eb;--accent:#0ea5e9;--success:#22c55e;--warning:#f59e0b;--danger:#ef4444;--bg:#eef2ff;--card-bg:#fff;--text-primary:#0f172a;--text-secondary:#475569;--border:#e2e8f0;--radius:20px;--shadow:0 16px 50px rgba(15,23,42,.08)}
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--bg);color:var(--text-primary);display:flex;min-height:100vh}
        .main{margin-left:260px;flex:1;padding:28px 32px 40px}
        .topbar{display:flex;align-items:center;justify-content:space-between;gap:16px;padding-bottom:16px;margin-bottom:22px}
        .breadcrumb{display:inline-flex;align-items:center;gap:8px;font-size:13px;color:var(--text-secondary)}
        .breadcrumb span{font-weight:700;color:var(--secondary)}
        .topbar-title{font-family:'Space Grotesk',sans-serif;font-size:24px;font-weight:800}
        .topbar-right{display:flex;align-items:center;gap:14px}
        .topbar-pill{display:inline-flex;align-items:center;gap:8px;background:#fff;border:1px solid var(--border);border-radius:999px;padding:10px 14px;font-size:13px;color:var(--text-secondary)}
        .topbar-pill i{color:var(--secondary)}
        .report-banner{background:linear-gradient(135deg,#4338ca 0%,#0ea5e9 100%);border-radius:32px;padding:32px;color:#fff;margin-bottom:30px;position:relative;overflow:hidden;box-shadow:0 16px 50px rgba(15,23,42,.15)}
        .report-banner::before{content:'';position:absolute;top:-60px;right:-80px;width:220px;height:220px;border-radius:50%;background:rgba(255,255,255,.12)}
        .report-banner h1{font-family:'Space Grotesk',sans-serif;font-size:28px;font-weight:800;margin-bottom:10px}
        .report-banner p{color:rgba(255,255,255,.88);max-width:640px;line-height:1.75;margin-bottom:18px;font-size:14px}
        .report-meta{display:flex;flex-wrap:wrap;gap:18px;font-size:13px;color:rgba(255,255,255,.92)}
        .filter-bar{background:#fff;border-radius:20px;border:1px solid var(--border);padding:20px 24px;margin-bottom:24px;display:flex;align-items:center;gap:14px;flex-wrap:wrap;box-shadow:var(--shadow)}
        .filter-bar label{font-size:13px;font-weight:600;color:var(--text-secondary)}
        .filter-bar input[type=date]{padding:8px 14px;border:1px solid var(--border);border-radius:10px;font-size:13px;font-family:inherit}
        .filter-bar .btn-filter{padding:10px 20px;background:var(--primary);color:#fff;border:none;border-radius:10px;font-size:13px;font-weight:600;cursor:pointer;transition:.2s}
        .filter-bar .btn-filter:hover{background:#3730a3}
        .section-title{font-family:'Space Grotesk',sans-serif;font-size:20px;font-weight:800;margin:28px 0 16px;display:flex;align-items:center;gap:10px;color:var(--text-primary)}
        .section-title i{color:var(--primary)}
        .summary-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:18px;margin-bottom:24px}
        .summary-box{background:#fff;border-radius:20px;border:1px solid var(--border);box-shadow:0 8px 24px rgba(15,23,42,.06);padding:20px;display:flex;align-items:center;gap:14px;transition:transform .2s,box-shadow .2s}
        .summary-box:hover{transform:translateY(-2px);box-shadow:0 14px 36px rgba(15,23,42,.1)}
        .summary-box .marker{width:44px;height:44px;border-radius:14px;display:grid;place-items:center;color:#fff;font-size:17px;flex-shrink:0}
        .summary-info{display:flex;flex-direction:column;gap:4px}
        .summary-value{font-family:'Space Grotesk',sans-serif;font-size:24px;font-weight:800}
        .summary-label{font-size:11px;color:var(--text-secondary);text-transform:uppercase;letter-spacing:.8px}
        .chart-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:20px;margin-bottom:24px}
        .chart-card{background:#fff;border-radius:20px;border:1px solid var(--border);box-shadow:0 8px 24px rgba(15,23,42,.06);padding:22px}
        .chart-title{font-family:'Space Grotesk',sans-serif;font-size:16px;font-weight:700;display:flex;align-items:center;gap:8px;margin-bottom:14px}
        .chart-title i{color:var(--secondary)}
        .data-card{background:#fff;border-radius:20px;border:1px solid var(--border);box-shadow:0 8px 24px rgba(15,23,42,.06);overflow:hidden;margin-bottom:24px}
        .data-card-header{display:flex;align-items:center;justify-content:space-between;padding:20px 24px;border-bottom:1px solid var(--border)}
        .data-card-header h2{font-family:'Space Grotesk',sans-serif;font-size:16px;font-weight:700;display:flex;align-items:center;gap:8px}
        .data-card-header h2 i{color:var(--primary)}
        .data-table{width:100%;border-collapse:collapse}
        .data-table th,.data-table td{padding:12px 18px;text-align:left;font-size:12px}
        .data-table th{text-transform:uppercase;letter-spacing:.7px;font-weight:700;color:var(--text-secondary);background:#f8fafc;border-bottom:1px solid var(--border)}
        .data-table td{color:var(--text-primary);border-bottom:1px solid var(--border)}
        .data-table tr:hover td{background:#f8fafc}
        .status-badge{display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:999px;font-size:10px;font-weight:700;text-transform:uppercase}
        .badge-success{background:rgba(34,197,94,.12);color:#16a34a}
        .badge-warning{background:rgba(245,158,11,.14);color:#d97706}
        .badge-danger{background:rgba(239,68,68,.12);color:#dc2626}
        .badge-info{background:rgba(14,165,233,.12);color:#0284c7}
        .download-section{background:#fff;border-radius:20px;border:1px solid var(--border);padding:24px;margin-bottom:24px;box-shadow:var(--shadow)}
        .download-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:14px}
        .download-btn{display:flex;align-items:center;gap:12px;padding:16px 20px;border-radius:16px;border:1px solid var(--border);background:#f8fafc;text-decoration:none;color:var(--text-primary);transition:.2s;cursor:pointer}
        .download-btn:hover{transform:translateY(-2px);box-shadow:0 12px 30px rgba(15,23,42,.1);border-color:var(--primary)}
        .download-btn .dl-icon{width:42px;height:42px;border-radius:12px;display:grid;place-items:center;color:#fff;font-size:16px;flex-shrink:0}
        .download-btn .dl-info{display:flex;flex-direction:column;gap:2px}
        .download-btn .dl-title{font-size:13px;font-weight:700}
        .download-btn .dl-desc{font-size:11px;color:var(--text-secondary)}
        @media(max-width:1120px){.chart-grid{grid-template-columns:1fr}.main{margin-left:0;padding:20px}}
        @media(max-width:768px){.summary-grid{grid-template-columns:1fr}.topbar{flex-direction:column;align-items:flex-start}.download-grid{grid-template-columns:1fr}}
    </style>
</head>
<body>
    @include('partials.sidebar')

    <div class="main">
        <div class="topbar">
            <div>
                <div class="breadcrumb"><i class="fas fa-home"></i> <span>Laporan</span></div>
                <div class="topbar-title">Laporan Kepala BPMP</div>
            </div>
            <div class="topbar-right">
                <div class="topbar-pill"><i class="fas fa-calendar-day"></i>{{ date('d M Y') }}</div>
            </div>
        </div>

        <section class="report-banner">
            <h1><i class="fas fa-chart-bar"></i> Pusat Laporan SIPANDU</h1>
            <p>Laporan komprehensif seluruh data yang dikelola oleh Admin Persediaan, Admin Aset Tetap, dan Admin Sarpras dalam satu tampilan terpadu.</p>
            <div class="report-meta">
                <div><strong>Periode:</strong> {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</div>
                <div><strong>Pembuat:</strong> {{ auth()->user()->name }}</div>
                <div><strong>Dicetak:</strong> {{ now()->format('d M Y H:i') }}</div>
            </div>
        </section>

        {{-- FILTER PERIODE --}}
        <form class="filter-bar" method="GET" action="{{ route('kepalabpmp.laporan') }}">
            <label>Dari:</label>
            <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}">
            <label>Sampai:</label>
            <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}">
            <button type="submit" class="btn-filter"><i class="fas fa-filter"></i> Filter</button>
        </form>

        {{-- ══════════ DOWNLOAD SECTION ══════════ --}}
        <div class="download-section">
            <div class="section-title" style="margin-top:0"><i class="fas fa-download"></i> Download Laporan PDF</div>
            <div class="download-grid">
                <a href="{{ route('kepalabpmp.laporan.download-aset-tetap', ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}" class="download-btn">
                    <div class="dl-icon" style="background:linear-gradient(135deg,#f97316,#ea580c)"><i class="fas fa-warehouse"></i></div>
                    <div class="dl-info"><span class="dl-title">Laporan Aset Tetap</span><span class="dl-desc">Data aset,transaksi, mutasi, peminjaman & Pengembalian</span></div>
                </a>
                <a href="{{ route('kepalabpmp.laporan.download-persediaan', ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}" class="download-btn">
                    <div class="dl-icon" style="background:linear-gradient(135deg,#22c55e,#16a34a)"><i class="fas fa-boxes"></i></div>
                    <div class="dl-info"><span class="dl-title">Laporan Persediaan</span><span class="dl-desc">Data persediaan, transaksi & Permintaan Persediaan</span></div>
                </a>
                <a href="{{ route('kepalabpmp.laporan.download-sarpras', ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}" class="download-btn">
                    <div class="dl-icon" style="background:linear-gradient(135deg,#0ea5e9,#0284c7)"><i class="fas fa-building"></i></div>
                    <div class="dl-info"><span class="dl-title">Laporan Sarpras</span><span class="dl-desc">Gedung, kerusakan & peminjaman</span></div>
                </a>
                {{-- <a href="{{ route('kepalabpmp.laporan.download-lengkap', ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}" class="download-btn">
                    <div class="dl-icon" style="background:linear-gradient(135deg,#8b5cf6,#7c3aed)"><i class="fas fa-file-pdf"></i></div>
                    <div class="dl-info"><span class="dl-title">Laporan Lengkap</span><span class="dl-desc">Semua data dalam 1 dokumen</span></div>
                </a> --}}
            </div>
        </div>

       {{-- ══════════ 1. ADMIN ASET TETAP ══════════ --}}
        <div class="section-title"><i class="fas fa-warehouse"></i> Ringkasan Aset Tetap</div>
        <div class="summary-grid">
            <div class="summary-box">
                <div class="marker" style="background:linear-gradient(135deg,#f97316,#ea580c)"><i class="fas fa-database"></i></div>
                <div class="summary-info"><div class="summary-value">{{ number_format($asetTetap['total_aset']) }}</div><div class="summary-label">Total Aset</div></div>
            </div>
            <div class="summary-box">
                <div class="marker" style="background:linear-gradient(135deg,#14b8a6,#0d9488)"><i class="fas fa-money-bill-wave"></i></div>
                <div class="summary-info"><div class="summary-value" style="font-size:16px;">Rp {{ number_format($asetTetap['total_nilai'], 0, ',', '.') }}</div><div class="summary-label">Nilai Aset</div></div>
            </div>
            <div class="summary-box">
                <div class="marker" style="background:linear-gradient(135deg,#3b82f6,#2563eb)"><i class="fas fa-exchange-alt"></i></div>
                <div class="summary-info"><div class="summary-value">{{ $asetTetap['mutasi'] }}</div><div class="summary-label">Total Mutasi Lokasi</div></div>
            </div>
            <div class="summary-box">
                <div class="marker" style="background:linear-gradient(135deg,#ec4899,#db2777)"><i class="fas fa-handshake"></i></div>
                <div class="summary-info"><div class="summary-value">{{ $asetTetap['peminjaman_barang_aktif'] + $asetTetap['peminjaman_kendaraan_aktif'] }}</div><div class="summary-label">Peminjaman Aktif</div></div>
            </div>
        </div>

        <div class="tables-grid">
            <div class="data-card">
                <div class="data-card-header"><h2><i class="fas fa-arrow-down text-success"></i> Transaksi Masuk Aset (Terbaru)</h2></div>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead><tr><th>Tanggal</th><th>Nama Barang</th><th>Kategori</th><th>Nilai</th></tr></thead>
                        <tbody>
                        @forelse($asetTetap['recent_masuk'] as $item)
                            <tr>
                                <td>{{ $item->tanggal_perolehan ? \Carbon\Carbon::parse($item->tanggal_perolehan)->format('d/m/y') : '-' }}</td>
                                <td><b>{{ $item->nama_barang }}</b></td>
                                <td>{{ $item->kategori ?? '-' }}</td>
                                <td>Rp {{ number_format($item->nilai_perolehan ?? 0, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted" style="padding:20px;">Belum ada penambahan aset</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="data-card">
                <div class="data-card-header"><h2><i class="fas fa-arrow-up text-danger"></i> Transaksi Keluar Aset (Terbaru)</h2></div>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead><tr><th>Tanggal</th><th>Nama Barang</th><th>Kategori</th><th>Keterangan</th></tr></thead>
                        <tbody>
                        @forelse($asetTetap['recent_keluar'] as $item)
                            <tr>
                                <td>{{ $item->tanggal_input ? \Carbon\Carbon::parse($item->tanggal_input)->format('d/m/y') : '-' }}</td>
                                <td><b>{{ $item->nama_barang }}</b></td>
                                <td>{{ $item->kategori ?? '-' }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($item->keterangan, 30) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted" style="padding:20px;">Belum ada pengeluaran aset</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="tables-grid">
            <div class="data-card mb-4">
                <div class="data-card-header"><h2><i class="fas fa-exchange-alt text-warning"></i> Mutasi Lokasi Aset (Terbaru)</h2></div>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead><tr><th>Tanggal</th><th>Nama Barang</th><th>Dari</th><th>Ke</th></tr></thead>
                        <tbody>
                        @forelse($asetTetap['recent_mutasi'] ?? [] as $item)
                            <tr>
                                <td>{{ $item->tanggal_mutasi ? \Carbon\Carbon::parse($item->tanggal_mutasi)->format('d/m/y') : '-' }}</td>
                                <td><b>{{ $item->barang->nama_barang ?? '-' }}</b></td>
                                <td>{{ $item->lokasi_asal ?? '-' }}</td>
                                <td>{{ $item->lokasi_tujuan ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted" style="padding:20px;">Belum ada data mutasi</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="data-card mb-4">
                <div class="data-card-header"><h2><i class="fas fa-handshake text-info"></i> Peminjaman Aset Tetap (Terbaru)</h2></div>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead><tr><th>Tgl Pinjam</th><th>Peminjam</th><th>Nama Barang</th><th>Status</th></tr></thead>
                        <tbody>
                        @forelse($asetTetap['recent_peminjaman'] ?? [] as $item)
                            <tr>
                                <td>{{ $item->created_at ? $item->created_at->format('d/m/y') : '-' }}</td>
                                <td>{{ $item->user->name ?? $item->nama_lengkap ?? '-' }}</td>
                                <td><b>{{ $item->barang->nama_barang ?? $item->nama_barang ?? '-' }}</b></td>
                                <td>
                                    @php
                                        $statusClass = in_array(strtolower($item->status), ['disetujui', 'di setujui', 'dipinjam']) ? 'badge-success' : (strtolower($item->status) == 'dikembalikan' ? 'badge-info' : 'badge-warning');
                                    @endphp
                                    <span class="status-badge {{ $statusClass }}">{{ ucfirst(str_replace('_',' ',$item->status)) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted" style="padding:20px;">Belum ada data peminjaman</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ══════════ 2. ADMIN PERSEDIAAN ══════════ --}}
        <div class="section-title"><i class="fas fa-boxes"></i> Ringkasan Barang Persediaan (Habis Pakai)</div>
        <div class="summary-grid">
            <div class="summary-box">
                <div class="marker" style="background:linear-gradient(135deg,#22c55e,#16a34a)"><i class="fas fa-cubes"></i></div>
                <div class="summary-info"><div class="summary-value">{{ number_format($persediaan['total_item']) }}</div><div class="summary-label">Total Jenis Item</div></div>
            </div>
            <div class="summary-box">
                <div class="marker" style="background:linear-gradient(135deg,#0ea5e9,#0284c7)"><i class="fas fa-dolly-flatbed"></i></div>
                <div class="summary-info"><div class="summary-value">{{ $persediaan['transaksi_masuk'] }} / {{ $persediaan['transaksi_keluar'] }}</div><div class="summary-label">Trx Masuk / Keluar</div></div>
            </div>
            <div class="summary-box">
                <div class="marker" style="background:linear-gradient(135deg,#f97316,#ea580c)"><i class="fas fa-clipboard-list"></i></div>
                <div class="summary-info"><div class="summary-value">{{ $persediaan['permintaan_total'] }}</div><div class="summary-label">Total Permintaan</div></div>
            </div>
            <div class="summary-box">
                <div class="marker" style="background:linear-gradient(135deg,#8b5cf6,#7c3aed)"><i class="fas fa-check-double"></i></div>
                <div class="summary-info"><div class="summary-value text-success">{{ $persediaan['permintaan_disetujui'] }}</div><div class="summary-label">Permintaan Disetujui</div></div>
            </div>
        </div>

        <div class="tables-grid">
            <div class="data-card mb-4">
                <div class="data-card-header"><h2><i class="fas fa-arrow-down text-success"></i> Restock Persediaan (Terbaru)</h2></div>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead><tr><th>Tanggal</th><th>Kode</th><th>Nama Barang</th><th>Vol</th><th>Total Nilai</th></tr></thead>
                        <tbody>
                        @forelse($persediaan['recent_masuk'] as $item)
                            <tr>
                                <td>{{ $item->tanggal_input ? \Carbon\Carbon::parse($item->tanggal_input)->format('d/m/y') : '-' }}</td>
                                <td>{{ $item->kode_barang }}</td>
                                <td><b>{{ $item->nama_barang }}</b></td>
                                <td><span class="badge-info status-badge">{{ $item->jumlah_masuk }}</span></td>
                                <td>Rp {{ number_format($item->getRawOriginal('total') ?? $item->total ?? 0, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted" style="padding:20px;">Belum ada data restock</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="data-card mb-4">
                <div class="data-card-header"><h2><i class="fas fa-arrow-up text-danger"></i> Distribusi Persediaan (Terbaru)</h2></div>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead><tr><th>Tanggal</th><th>Kode</th><th>Nama Barang</th><th>Vol</th><th>Total Nilai</th></tr></thead>
                        <tbody>
                        @forelse($persediaan['recent_keluar'] as $item)
                            <tr>
                                <td>{{ $item->tanggal_input ? \Carbon\Carbon::parse($item->tanggal_input)->format('d/m/y') : '-' }}</td>
                                <td>{{ $item->kode_barang }}</td>
                                <td><b>{{ $item->nama_barang }}</b></td>
                                <td><span class="badge-danger status-badge">{{ $item->jumlah_keluar }}</span></td>
                                <td>Rp {{ number_format($item->getRawOriginal('total') ?? $item->total ?? 0, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted" style="padding:20px;">Belum ada pengeluaran logistik</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="data-card mb-4">
            <div class="data-card-header"><h2><i class="fas fa-file-invoice text-primary"></i> Permintaan Persediaan Pegawai (Terbaru)</h2></div>
            <div class="table-responsive">
                <table class="data-table">
                    <thead><tr><th>Tanggal</th><th>ID Request</th><th>Pemohon</th><th>Barang Diminta</th><th>Jml Minta</th><th>Disetujui</th><th>Status</th></tr></thead>
                    <tbody>
                    @forelse($persediaan['recent_permintaan'] ?? [] as $item)
                        <tr>
                            <td>{{ $item->tanggal_permintaan ? \Carbon\Carbon::parse($item->tanggal_permintaan)->format('d/m/y') : ($item->created_at ? $item->created_at->format('d/m/y') : '-') }}</td>
                            <td class="font-weight-bold text-blue">REQ-{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $item->user->name ?? $item->nama_lengkap ?? '-' }}</td>
                            <td><b>{{ $item->persediaan->nama_barang ?? $item->nama_barang ?? '-' }}</b></td>
                            <td class="text-center">{{ $item->jumlah_diminta }}</td>
                            <td class="text-center font-weight-bold text-success">{{ $item->jumlah_disetujui ?? 0 }}</td>
                            <td>
                                @php
                                    $badgeClass = match($item->status) {
                                        'di setujui','disetujui','disetujui_kasubag' => 'badge-success',
                                        'pending','dalam_review' => 'badge-warning',
                                        'di tolak','ditolak','ditolak_kasubag' => 'badge-danger',
                                        default => 'badge-info'
                                    };
                                @endphp
                                <span class="status-badge {{ $badgeClass }}">{{ ucfirst(str_replace('_',' ',$item->status)) }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted" style="padding:20px;">Belum ada dokumen permintaan persediaan</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ══════════ 3. ADMIN SARPRAS ══════════ --}}
        <div class="section-title"><i class="fas fa-building"></i> Ringkasan Sarana & Prasarana</div>
        <div class="summary-grid">
            <div class="summary-box">
                <div class="marker" style="background:linear-gradient(135deg,#0ea5e9,#0284c7)"><i class="fas fa-city"></i></div>
                <div class="summary-info"><div class="summary-value">{{ $sarpras['total_gedung'] }}</div><div class="summary-label">Total Gedung</div></div>
            </div>
            <div class="summary-box">
                <div class="marker" style="background:linear-gradient(135deg,#22c55e,#16a34a)"><i class="fas fa-check-circle"></i></div>
                <div class="summary-info"><div class="summary-value">{{ $sarpras['gedung_tersedia'] }}</div><div class="summary-label">Gedung Tersedia</div></div>
            </div>
            <div class="summary-box">
                <div class="marker" style="background:linear-gradient(135deg,#f59e0b,#d97706)"><i class="fas fa-door-open"></i></div>
                <div class="summary-info"><div class="summary-value">{{ $sarpras['peminjaman_gedung'] }}</div><div class="summary-label">Peminjaman Gedung</div></div>
            </div>
            <div class="summary-box">
                <div class="marker" style="background:linear-gradient(135deg,#ef4444,#dc2626)"><i class="fas fa-tools"></i></div>
                <div class="summary-info"><div class="summary-value">{{ $sarpras['kerusakan_periode'] }}</div><div class="summary-label">Kerusakan Dilaporkan</div></div>
            </div>
        </div>

        <div class="tables-grid">
            <div class="data-card mb-4">
                <div class="data-card-header"><h2><i class="fas fa-door-open text-primary"></i> Peminjaman Gedung (Terbaru)</h2></div>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead><tr><th>Peminjam</th><th>Gedung</th><th>Tgl Pinjam</th><th>Status</th></tr></thead>
                        <tbody>
                        @forelse($sarpras['recent_peminjaman'] as $item)
                            <tr>
                                <td><b>{{ $item->nama_lengkap }}</b></td>
                                <td>{{ $item->gedung->nama_gedung ?? '-' }}</td>
                                <td>{{ $item->tanggal_pinjam ? \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d/m/y') : '-' }}</td>
                                <td>
                                    @php
                                        $badgeClass = match(strtolower($item->status)) {
                                            'di setujui','disetujui_kasubag' => 'badge-success',
                                            'pending','dalam_review' => 'badge-warning',
                                            'di tolak','ditolak' => 'badge-danger',
                                            default => 'badge-info'
                                        };
                                    @endphp
                                    <span class="status-badge {{ $badgeClass }}">{{ ucfirst(str_replace('_',' ',$item->status)) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted" style="padding:20px;">Belum ada peminjaman gedung</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="data-card mb-4">
                <div class="data-card-header"><h2><i class="fas fa-tools text-danger"></i> Laporan Kerusakan (Terbaru)</h2></div>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead><tr><th>Tanggal Lapor</th><th>Gedung</th><th>Ruangan</th><th>Tingkat Kondisi</th></tr></thead>
                        <tbody>
                        @forelse($sarpras['recent_kerusakan'] as $item)
                            <tr>
                                <td>{{ $item->created_at ? $item->created_at->format('d/m/y') : '-' }}</td>
                                <td><b>{{ $item->gedung->nama_gedung ?? '-' }}</b></td>
                                <td>{{ $item->ruangan ?? '-' }}</td>
                                <td>
                                    <span class="status-badge {{ strtolower($item->kondisi) == 'rusak berat' ? 'badge-danger' : 'badge-warning' }}">
                                        {{ $item->kondisi }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted" style="padding:20px;">Belum ada laporan kerusakan fasilitas</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="data-card mb-4">
            <div class="data-card-header"><h2><i class="fas fa-city text-success"></i> Data Master Sarana / Gedung</h2></div>
            <div class="table-responsive">
                <table class="data-table">
                    <thead><tr><th>No</th><th>Nama Gedung</th><th>Status Ketersediaan Saat Ini</th></tr></thead>
                    <tbody>
                    @forelse($sarpras['gedung_list'] ?? [] as $i => $item)
                        <tr>
                            <td style="width: 5%;">{{ $i+1 }}</td>
                            <td><b>{{ $item->nama_gedung ?? '-' }}</b></td>
                            <td>
                                @php
                                    $ketersediaan = strtolower($item->ketersediaan ?? '');
                                    $availClass = $ketersediaan == 'tersedia' ? 'badge-success' : ($ketersediaan == 'renovasi' ? 'badge-danger' : 'badge-warning');
                                @endphp
                                <span class="status-badge {{ $availClass }}">{{ $item->ketersediaan ?? 'Tidak Diketahui' }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-muted" style="padding:20px;">Data infrastruktur gedung belum tersedia</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ══════════ GRAFIK VISUALISASI ══════════ --}}
        <div class="section-title"><i class="fas fa-chart-pie"></i> Visualisasi Analitik Tren Tahunan</div>
        <div class="chart-grid">
            <div class="chart-card">
                <div class="chart-title"><i class="fas fa-chart-line text-success"></i> Tren Transaksi Persediaan</div>
                <div style="position: relative; height: 300px; width: 100%;">
                    <canvas id="chartPersediaan"></canvas>
                </div>
            </div>
            <div class="chart-card">
                <div class="chart-title"><i class="fas fa-chart-bar text-primary"></i> Tren Transaksi Aset Tetap</div>
                <div style="position: relative; height: 300px; width: 100%;">
                    <canvas id="chartAset"></canvas>
                </div>
            </div>
        </div>
    </div>
   

    <script>
        const labels = @json($charts['labels']);
        
        // Konfigurasi Warna Global agar Senada dengan Tema SIPANDU
        const colorMasuk = '#4338ca';       // Indigo (Menandakan Transaksi Masuk/Penambahan)
        const colorMasukBg = 'rgba(67, 56, 202, 0.12)';
        const colorKeluar = '#f97316';      // Orange (Menandakan Transaksi Keluar/Pengurangan)
        const colorKeluarBg = 'rgba(249, 115, 22, 0.12)';
        
        // Opsi Grafis Standar yang Sama untuk Kedua Chart
        const globalChartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: { family: "'Plus Jakarta Sans', sans-serif", size: 12, weight: 500 },
                        padding: 15,
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    backgroundColor: '#0f172a',
                    titleFont: { family: "'Plus Jakarta Sans', sans-serif", size: 12, weight: 700 },
                    bodyFont: { family: "'Plus Jakarta Sans', sans-serif", size: 12 },
                    padding: 10,
                    cornerRadius: 8,
                    displayColors: true
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(15, 23, 42, 0.05)', borderDash: [5, 5] },
                    ticks: {
                        font: { family: "'Plus Jakarta Sans', sans-serif", size: 11 },
                        color: '#64748b'
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: {
                        font: { family: "'Plus Jakarta Sans', sans-serif", size: 11 },
                        color: '#64748b'
                    }
                }
            }
        };

        // 1. Chart Tren Transaksi Persediaan (Line Chart yang Dipercantik)
        new Chart(document.getElementById('chartPersediaan'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Barang Masuk',
                        data: @json($charts['persediaan_masuk']),
                        borderColor: colorMasuk,
                        backgroundColor: colorMasukBg,
                        tension: 0.35,
                        fill: true,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        borderWidth: 2.5
                    },
                    {
                        label: 'Barang Keluar',
                        data: @json($charts['persediaan_keluar']),
                        borderColor: colorKeluar,
                        backgroundColor: colorKeluarBg,
                        tension: 0.35,
                        fill: true,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        borderWidth: 2.5
                    }
                ]
            },
            options: globalChartOptions
        });
        
        // 2. Chart Tren Transaksi Aset Tetap (Diubah menjadi Line Chart agar senada dengan Persediaan)
        new Chart(document.getElementById('chartAset'), {
            type: 'line', // Mengubah tipe dari 'bar' menjadi 'line'
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Aset Masuk',
                        data: @json($charts['aset_masuk']),
                        borderColor: colorMasuk,
                        backgroundColor: colorMasukBg,
                        tension: 0.35,
                        fill: true,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        borderWidth: 2.5
                    },
                    {
                        label: 'Aset Keluar',
                        data: @json($charts['aset_keluar']),
                        borderColor: colorKeluar,
                        backgroundColor: colorKeluarBg,
                        tension: 0.35,
                        fill: true,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        borderWidth: 2.5
                    }
                ]
            },
            options: globalChartOptions
        });
    </script>
</body>
</html>
