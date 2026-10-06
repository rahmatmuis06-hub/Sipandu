<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Helvetica','Arial',sans-serif;font-size:11px;color:#1e293b;line-height:1.5}
        .header{text-align:center;border-bottom:3px solid #ea580c;padding-bottom:12px;margin-bottom:16px}
        .header h1{font-size:16px;color:#ea580c;margin-bottom:2px}
        .header h2{font-size:13px;color:#334155;margin-bottom:4px}
        .header p{font-size:10px;color:#64748b}
        .section{margin-bottom:18px}
        .section-title{font-size:13px;font-weight:bold;color:#ea580c;border-bottom:2px solid #fed7aa;padding-bottom:4px;margin-bottom:8px}
        table{width:100%;border-collapse:collapse;margin-bottom:12px}
        th{background:#ea580c;color:#fff;padding:6px 8px;font-size:10px;text-align:left;text-transform:uppercase}
        td{padding:5px 8px;border-bottom:1px solid #e2e8f0;font-size:10px}
        tr:nth-child(even) td{background:#f8fafc}
        .footer{margin-top:20px;padding-top:10px;border-top:1px solid #e2e8f0;font-size:9px;color:#94a3b8;text-align:center}
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .badge { padding: 2px 4px; font-weight: bold; text-transform: uppercase; font-size: 9px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>BPMP PROVINSI GORONTALO</h1>
        <h2>{{ $title }}</h2>
        <p>Periode: {{ $periode }} | Dicetak oleh: {{ $generated_by }} | Tanggal: {{ $generated_at->format('d/m/Y H:i') }}</p>
    </div>

    <div class="section">
        <div class="section-title">Ringkasan Statistik Persediaan</div>
        <table>
            <tr>
                <td><strong>Total Item Acuan:</strong></td><td>{{ number_format($stats['total_item'] ?? 0) }} Jenis Barang</td>
                <td><strong>Nilai Pasokan Masuk:</strong></td><td>Rp {{ number_format($stats['nilai_masuk'] ?? 0,0,',','.') }}</td>
            </tr>
            <tr>
                <td><strong>Transaksi Masuk Periode Ini:</strong></td><td>{{ $stats['total_masuk'] ?? 0 }} Trx</td>
                <td><strong>Nilai Distribusi Keluar:</strong></td><td>Rp {{ number_format($stats['nilai_keluar'] ?? 0,0,',','.') }}</td>
            </tr>
            <tr>
                <td><strong>Transaksi Keluar Periode Ini:</strong></td><td>{{ $stats['total_keluar'] ?? 0 }} Trx</td>
                <td></td><td></td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">1. Data Stok Master Persediaan (Barang Habis Pakai)</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 4%;" class="text-center">No</th>
                    <th style="width: 16%;">Kode Unik Barang</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th style="width: 10%;" class="text-center">Sisa Stok</th>
                    <th style="width: 15%;" class="text-right">Harga Satuan</th>
                    <th style="width: 15%;" class="text-right">Total Nilai</th>
                </tr>
            </thead>
            <tbody>
            @forelse($persediaan as $i => $item)
                <tr>
                    <td class="text-center">{{ $i+1 }}</td>
                    <td>{{ $item->kode_unik_barang }}</td>
                    <td><strong>{{ $item->nama_barang }}</strong></td>
                    <td>{{ $item->kategori }}</td>
                    <td class="text-center">{{ $item->jumlah }}</td>
                    <td class="text-right">Rp {{ number_format($item->getRawOriginal('harga_satuan') ?? $item->harga_satuan ?? 0,0,',','.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->harga_total ?? 0,0,',','.') }}</td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center">Tidak ada data master persediaan</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">2. Log Transaksi Masuk / Restock Logistik</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 4%;" class="text-center">No</th>
                    <th style="width: 12%;" class="text-center">Tanggal</th>
                    <th style="width: 16%;">Kode Unik Barang</th>
                    <th>Nama Barang</th>
                    <th style="width: 10%;" class="text-center">Vol Masuk</th>
                    <th style="width: 15%;" class="text-right">Harga Satuan</th>
                    <th style="width: 15%;" class="text-right">Total Anggaran</th>
                </tr>
            </thead>
            <tbody>
            @forelse($transaksi_masuk as $i => $item)
                <tr>
                    <td class="text-center">{{ $i+1 }}</td>
                    <td class="text-center">{{ $item->tanggal_input ? \Carbon\Carbon::parse($item->tanggal_input)->format('d/m/Y') : '-' }}</td>
                    <td>{{ $item->kode_unik_barang }}</td>
                    <td>{{ $item->nama_barang }}</td>
                    <td class="text-center">{{ $item->jumlah_masuk }}</td>
                    <td class="text-right">Rp {{ number_format($item->harga_satuan ?? 0,0,',','.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->total ?? 0,0,',','.') }}</td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center">Tidak ada rekaman transaksi masuk pada periode ini</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">3. Log Transaksi Keluar / Distribusi Mandiri</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 4%;" class="text-center">No</th>
                    <th style="width: 12%;" class="text-center">Tanggal</th>
                    <th style="width: 16%;">Kode Unik Barang</th>
                    <th>Nama Barang</th>
                    <th style="width: 10%;" class="text-center">Vol Keluar</th>
                    <th style="width: 15%;" class="text-right">Total Nilai</th>
                    <th>Keterangan Keperluan</th>
                </tr>
            </thead>
            <tbody>
            @forelse($transaksi_keluar as $i => $item)
                <tr>
                    <td class="text-center">{{ $i+1 }}</td>
                    <td class="text-center">{{ $item->tanggal_input ? \Carbon\Carbon::parse($item->tanggal_input)->format('d/m/Y') : '-' }}</td>
                    <td>{{ $item->kode_unik_barang }}</td>
                    <td>{{ $item->nama_barang }}</td>
                    <td class="text-center">{{ $item->jumlah_keluar }}</td>
                    <td class="text-right">Rp {{ number_format($item->total ?? 0,0,',','.') }}</td>
                    <td>{{ $item->keterangan ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center">Tidak ada rekaman transaksi keluar pada periode ini</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">4. Dokumen Riwayat Permintaan Barang Habis Pakai (Pegawai)</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 4%;" class="text-center">No</th>
                    <th style="width: 12%;" class="text-center">Tanggal</th>
                    <th style="width: 18%;">Nama Pemohon</th>
                    <th>Barang Habis Pakai</th>
                    <th style="width: 8%;" class="text-center">Minta</th>
                    <th style="width: 8%;" class="text-center">Setuju</th>
                    <th style="width: 12%;" class="text-center">Status</th>
                    <th style="width: 18%;">Tujuan Penggunaan</th>
                </tr>
            </thead>
            <tbody>
            @forelse($permintaan as $i => $item)
                <tr>
                    <td class="text-center">{{ $i+1 }}</td>
                    <td class="text-center">{{ $item->tanggal_permintaan ? \Carbon\Carbon::parse($item->tanggal_permintaan)->format('d/m/Y') : ($item->created_at ? $item->created_at->format('d/m/Y') : '-') }}</td>
                    <td>{{ $item->user->name ?? $item->nama_lengkap ?? '-' }}</td>
                    <td>
                        {{ $item->persediaan->nama_barang ?? $item->nama_barang }}
                        <br><span style="font-size: 8px; color: #64748b;">Kode: {{ $item->persediaan->kode_unik_barang ?? (($item->kode_kategori ?? '').'-'.($item->kode_barang ?? '')) }}</span>
                    </td>
                    <td class="text-center">{{ $item->jumlah_diminta }}</td>
                    <td class="text-center"><strong>{{ $item->jumlah_disetujui ?? 0 }}</strong></td>
                    <td class="text-center">
                        <span class="badge">
                            {{ str_replace('_', ' ', $item->status) }}
                        </span>
                    </td>
                    <td>{{ $item->tujuan_penggunaan ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center">Tidak ada data dokumen pengajuan permintaan</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">
        Dokumen ini dicetak secara otomatis oleh Sistem SIPANDU &mdash; BPMP Provinsi Gorontalo &mdash; {{ $generated_at->format('d/m/Y H:i') }}
    </div>
</body>
</html>
