<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Helvetica','Arial',sans-serif;font-size:11px;color:#1e293b;line-height:1.5}
        .header{text-align:center;border-bottom:3px solid #7c3aed;padding-bottom:12px;margin-bottom:16px}
        .header h1{font-size:18px;color:#7c3aed;margin-bottom:2px}
        .header h2{font-size:13px;color:#334155;margin-bottom:4px}
        .header p{font-size:10px;color:#64748b}
        .section{margin-bottom:18px;page-break-inside:avoid}
        .section-title{font-size:13px;font-weight:bold;color:#7c3aed;border-bottom:2px solid #ddd6fe;padding-bottom:4px;margin-bottom:8px}
        .sub-title{font-size:11px;font-weight:bold;color:#334155;margin:8px 0 4px}
        table{width:100%;border-collapse:collapse;margin-bottom:10px}
        th{background:#7c3aed;color:#fff;padding:5px 8px;font-size:9px;text-align:left;text-transform:uppercase}
        td{padding:4px 8px;border-bottom:1px solid #e2e8f0;font-size:9px}
        tr:nth-child(even) td{background:#f8fafc}
        .stats-table td{padding:6px 10px;font-size:10px}
        .stats-table td strong{color:#334155}
        .page-break{page-break-before:always}
        .footer{margin-top:20px;padding-top:10px;border-top:1px solid #e2e8f0;font-size:9px;color:#94a3b8;text-align:center}
        .summary-grid{width:100%;margin-bottom:12px}
        .summary-grid td{padding:8px;text-align:center;border:1px solid #e2e8f0;background:#f8fafc}
        .summary-grid .val{font-size:16px;font-weight:bold;color:#7c3aed}
        .summary-grid .lbl{font-size:8px;color:#64748b;text-transform:uppercase}
    </style>
</head>
<body>
    <div class="header">
        <h1>BPMP PROVINSI GORONTALO</h1>
        <h2>{{ $title }}</h2>
        <p>{{ $subtitle }} | Periode: {{ $periode }} | Dicetak oleh: {{ $generated_by }} | {{ $generated_at->format('d/m/Y H:i') }}</p>
    </div>

    {{-- ══════════ RINGKASAN UTAMA ══════════ --}}
    <div class="section">
        <div class="section-title">📊 Ringkasan Eksekutif</div>
        <table class="summary-grid">
            <tr>
                <td><div class="val">{{ number_format($persediaan_stats['total_item']) }}</div><div class="lbl">Item Persediaan</div></td>
                <td><div class="val">{{ number_format($aset_stats['total_aset']) }}</div><div class="lbl">Aset Tetap</div></td>
                <td><div class="val">{{ number_format($sarpras_stats['total_gedung']) }}</div><div class="lbl">Gedung</div></td>
                
            </tr>
        </table>
    </div>

    {{-- ══════════ PERSEDIAAN ══════════ --}}
    <div class="section">
        <div class="section-title">📦 Laporan Admin Persediaan</div>
        <table class="stats-table">
            <tr><td><strong>Transaksi Masuk:</strong></td><td>{{ $persediaan_stats['transaksi_masuk'] }}</td><td><strong>Nilai Masuk:</strong></td><td>Rp {{ number_format($persediaan_stats['nilai_masuk'],0,',','.') }}</td></tr>
            <tr><td><strong>Transaksi Keluar:</strong></td><td>{{ $persediaan_stats['transaksi_keluar'] }}</td><td><strong>Nilai Keluar:</strong></td><td>Rp {{ number_format($persediaan_stats['nilai_keluar'],0,',','.') }}</td></tr>
            <tr><td><strong>Permintaan Persediaan:</strong></td><td>{{ $persediaan_stats['permintaan'] }}</td><td></td><td></td></tr>
        </table>
        <div class="sub-title">Daftar Persediaan</div>
        <table>
            <thead><tr><th>No</th><th>Kode Unik Barang</th><th>Nama Barang</th><th>Kategori</th><th>Jumlah</th></tr></thead>
            <tbody>
            @foreach($persediaan_list as $i => $item)
                <tr><td>{{ $i+1 }}</td><td>{{ $item->kode_unik_barang }}</td><td>{{ $item->nama_barang }}</td><td>{{ $item->kategori }}</td><td>{{ $item->getRawOriginal('jumlah') }}</td></tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="page-break"></div>

    {{-- ══════════ ASET TETAP ══════════ --}}
    <div class="section">
        <div class="section-title">🏢 Laporan Admin Aset Tetap</div>
        <table class="stats-table">
            <tr><td><strong>Total Aset:</strong></td><td>{{ number_format($aset_stats['total_aset']) }}</td><td><strong>Nilai Total:</strong></td><td>Rp {{ number_format($aset_stats['total_nilai'],0,',','.') }}</td></tr>
            <tr><td><strong>Transaksi Masuk:</strong></td><td>{{ $aset_stats['transaksi_masuk'] }}</td><td><strong>Transaksi Keluar:</strong></td><td>{{ $aset_stats['transaksi_keluar'] }}</td></tr>
            <tr><td><strong>Mutasi:</strong></td><td>{{ $aset_stats['mutasi'] }}</td><td><strong>Peminjaman Aktif:</strong></td><td>{{ $aset_stats['peminjaman_aktif'] }}</td></tr>
        </table>
        <div class="sub-title">Daftar Aset Tetap</div>
        <table>
            <thead><tr><th>No</th><th>Kode</th><th>Nama</th><th>Kategori</th><th>Kondisi</th><th>Lokasi</th><th>Nilai</th></tr></thead>
            <tbody>
            @foreach($aset_list as $i => $item)
                <tr><td>{{ $i+1 }}</td><td>{{ $item->kode_barang }}</td><td>{{ $item->nama_barang }}</td><td>{{ $item->kategori }}</td><td>{{ $item->kondisi }}</td><td>{{ $item->lokasi }}</td><td>Rp {{ number_format($item->nilai_perolehan ?? 0,0,',','.') }}</td></tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="page-break"></div>

    {{-- ══════════ SARPRAS ══════════ --}}
    <div class="section">
        <div class="section-title">🏗️ Laporan Admin Sarpras</div>
        <table class="stats-table">
            <tr><td><strong>Total Gedung:</strong></td><td>{{ $sarpras_stats['total_gedung'] }}</td><td><strong>Tersedia:</strong></td><td>{{ $sarpras_stats['gedung_tersedia'] }}</td></tr>
            <tr><td><strong>Kerusakan:</strong></td><td>{{ $sarpras_stats['kerusakan'] }}</td><td><strong>Peminjaman Gedung:</strong></td><td>{{ $sarpras_stats['peminjaman_gedung'] }}</td></tr>
        </table>
        <div class="sub-title">Daftar Gedung</div>
        <table>
            <thead><tr><th>No</th><th>Nama Gedung</th><th>Lokasi</th><th>Kapasitas</th><th>Ketersediaan</th></tr></thead>
            <tbody>
            @foreach($gedung_list as $i => $item)
                <tr><td>{{ $i+1 }}</td><td>{{ $item->nama_gedung }}</td><td>{{ $item->lokasi }}</td><td>{{ $item->kapasitas }}</td><td>{{ $item->ketersediaan }}</td></tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        Dokumen ini dicetak secara otomatis oleh Sistem SIPANDU &mdash; BPMP Provinsi Gorontalo &mdash; {{ $generated_at->format('d/m/Y H:i') }}
    </div>
</body>
</html>
