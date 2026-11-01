<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; color: #1e293b; line-height: 1.5; padding: 10px; }
        .header { text-align: center; border-bottom: 3px solid #ea580c; padding-bottom: 12px; margin-bottom: 16px; }
        .header h1 { font-size: 16px; color: #ea580c; margin-bottom: 2px; }
        .header h2 { font-size: 13px; color: #334155; margin-bottom: 4px; }
        .header p { font-size: 10px; color: #64748b; }
        .section { margin-bottom: 18px; }
        .section-title { font-size: 13px; font-weight: bold; color: #ea580c; border-bottom: 2px solid #fed7aa; padding-bottom: 4px; margin-bottom: 8px; }
        .sub-title { font-size: 11px; font-weight: bold; color: #334155; margin: 8px 0 4px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        th { background: #ea580c; color: #fff; padding: 6px 8px; font-size: 10px; text-align: left; text-transform: uppercase; }
        td { padding: 5px 8px; border-bottom: 1px solid #e2e8f0; font-size: 10px; }
        tr:nth-child(even) td { background: #f8fafc; }
        .footer { margin-top: 20px; padding-top: 10px; border-top: 1px solid #e2e8f0; font-size: 9px; color: #94a3b8; text-align: center; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .badge { padding: 2px 4px; font-weight: bold; text-transform: uppercase; font-size: 9px; background: #f1f5f9; border-radius: 3px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>BPMP PROVINSI GORONTALO</h1>
        <h2>{{ $title }}</h2>
        <p>Periode: {{ $periode }} | Dicetak oleh: {{ $generated_by }} | Tanggal: {{ $generated_at->format('d/m/Y H:i') }}</p>
    </div>

    {{-- RINGKASAN STATISTIK --}}
    <div class="section">
        <div class="section-title">Ringkasan Statistik Aset Tetap</div>
        <table>
            <tr>
                <td style="width: 25%;"><strong>Total Master Aset:</strong></td>
                <td style="width: 25%;">{{ number_format($stats['total_aset'] ?? 0) }} Unit</td>
                <td style="width: 25%;"><strong>Total Nilai Aset:</strong></td>
                <td style="width: 25%;">Rp {{ number_format($stats['total_nilai'] ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>Transaksi Masuk Periode Ini:</strong></td>
                <td>{{ $stats['total_masuk'] ?? 0 }} Trx</td>
                <td><strong>Transaksi Keluar Periode Ini:</strong></td>
                <td>{{ $stats['total_keluar'] ?? 0 }} Trx</td>
            </tr>
            <tr>
                <td><strong>Total Mutasi Lokasi Periode Ini:</strong></td>
                <td>{{ $stats['total_mutasi'] ?? 0 }} Mutasi</td>
                <td></td>
                <td></td>
            </tr>
        </table>
    </div>

    {{-- 1. REKAPITULASI KATEGORI MASTER ASET TETAP --}}
    @if(isset($kategori_summary) && $kategori_summary->isNotEmpty())
    <div class="section">
        <div class="section-title">1. Rekapitulasi Data Master Aset Tetap (Berdasarkan Kategori)</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;" class="text-center">No</th>
                    <th>Kategori Aset Tetap</th>
                    <th style="width: 15%;" class="text-center">Jumlah Unit</th>
                    <th style="width: 25%;" class="text-right">Total Nilai Perolehan</th>
                    <th style="width: 15%;" class="text-center">Proporsi Nilai</th>
                </tr>
            </thead>
            <tbody>
            @php
                $grandTotalNilai = $stats['total_nilai'] ?? $kategori_summary->sum('total_nilai');
                $grandTotalUnit = $kategori_summary->sum('total_unit');
            @endphp
            @foreach($kategori_summary as $i => $row)
                @php
                    $persen = ($grandTotalNilai > 0) ? round((($row->total_nilai ?? 0) / $grandTotalNilai) * 100, 1) : 0;
                @endphp
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td><strong>{{ strtoupper($row->kategori ?: 'LAIN-LAIN / BELUM TERKATEGORI') }}</strong></td>
                    <td class="text-center">{{ number_format($row->total_unit ?? 0) }} Unit</td>
                    <td class="text-right">Rp {{ number_format($row->total_nilai ?? 0, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $persen }}%</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
                <tr style="background:#f8fafc; font-weight:bold;">
                    <td colspan="2" class="text-right"><strong>TOTAL KESELURUHAN MASTER:</strong></td>
                    <td class="text-center"><strong>{{ number_format($grandTotalUnit) }} Unit</strong></td>
                    <td class="text-right"><strong>Rp {{ number_format($grandTotalNilai, 0, ',', '.') }}</strong></td>
                    <td class="text-center"><strong>100%</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
    @endif

    {{-- 2. DATA ASET TETAP BARU / PENAMBAHAN PERIODE INI --}}
    <div class="section">
        <div class="section-title">2. Daftar Pendaftaran / Penambahan Aset Tetap Baru (Periode Ini)</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 4%;" class="text-center">No</th>
                    <th style="width: 14%;">Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th style="width: 10%;" class="text-center">Kondisi</th>
                    <th>Lokasi</th>
                    <th style="width: 8%;" class="text-center">Jumlah</th>
                    <th style="width: 15%;" class="text-right">Nilai Perolehan</th>
                </tr>
            </thead>
            <tbody>
            @forelse($aset as $i => $item)
                <tr>
                    <td class="text-center">{{ $i+1 }}</td>
                    <td>{{ $item->kode_barang }}</td>
                    <td><strong>{{ $item->nama_barang }}</strong></td>
                    <td>{{ $item->kategori ?? '-' }}</td>
                    <td class="text-center">{{ $item->kondisi ?? '-' }}</td>
                    <td>{{ $item->lokasi ?? '-' }}</td>
                    <td class="text-center">{{ $item->jumlah ?? 1 }}</td>
                    <td class="text-right">Rp {{ number_format($item->nilai_perolehan ?? 0, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center">Tidak ada pendaftaran aset tetap baru pada periode ini</td></tr>
            @endforelse
            </tbody>
            @if($aset->isNotEmpty())
            <tfoot>
                <tr style="background:#f8fafc; font-weight:bold;">
                    <td colspan="6" class="text-right"><strong>SUBTOTAL PENAMBAHAN PERIODE:</strong></td>
                    <td class="text-center"><strong>{{ number_format($aset->sum('jumlah')) }}</strong></td>
                    <td class="text-right"><strong>Rp {{ number_format($aset->sum('nilai_perolehan'), 0, ',', '.') }}</strong></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>

    {{-- 3. TRANSAKSI MASUK ASET TETAP --}}
    <div class="section">
        <div class="section-title">3. Log Transaksi Masuk Aset Tetap (Pengadaan / Perolehan Periode Ini)</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 4%;" class="text-center">No</th>
                    <th style="width: 12%;" class="text-center">Tanggal</th>
                    <th>Nama Barang</th>
                    <th style="width: 10%;" class="text-center">Kondisi</th>
                    <th>Kategori</th>
                    <th style="width: 15%;" class="text-right">Nilai Perolehan</th>
                </tr>
            </thead>
            <tbody>
            @forelse($transaksi_masuk as $i => $item)
                <tr>
                    <td class="text-center">{{ $i+1 }}</td>
                    <td class="text-center">{{ $item->tanggal_perolehan ? \Carbon\Carbon::parse($item->tanggal_perolehan)->format('d/m/Y') : '-' }}</td>
                    <td><strong>{{ $item->nama_barang }}</strong></td>
                    <td class="text-center">{{ $item->kondisi ?? '-' }}</td>
                    <td>{{ $item->kategori ?? '-' }}</td>
                    <td class="text-right">Rp {{ number_format($item->nilai_perolehan ?? 0, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">Tidak ada rekaman transaksi masuk pada periode ini</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- 4. TRANSAKSI KELUAR ASET TETAP --}}
    <div class="section">
        <div class="section-title">4. Log Transaksi Keluar Aset Tetap (Penghapusan / Pemindahtanganan Periode Ini)</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 4%;" class="text-center">No</th>
                    <th style="width: 12%;" class="text-center">Tanggal</th>
                    <th>Nama Barang</th>
                    <th style="width: 10%;" class="text-center">Kondisi</th>
                    <th>Kategori</th>
                    <th>Keterangan Keperluan</th>
                </tr>
            </thead>
            <tbody>
            @forelse($transaksi_keluar as $i => $item)
                <tr>
                    <td class="text-center">{{ $i+1 }}</td>
                    <td class="text-center">{{ $item->tanggal_input ? \Carbon\Carbon::parse($item->tanggal_input)->format('d/m/Y') : '-' }}</td>
                    <td><strong>{{ $item->nama_barang }}</strong></td>
                    <td class="text-center">{{ $item->kondisi ?? '-' }}</td>
                    <td>{{ $item->kategori ?? '-' }}</td>
                    <td>{{ $item->keterangan ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">Tidak ada rekaman transaksi keluar pada periode ini</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- 5. MUTASI LOKASI ASET TETAP --}}
    <div class="section">
        <div class="section-title">5. Log Mutasi Lokasi Aset Tetap (Periode Ini)</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 4%;" class="text-center">No</th>
                    <th style="width: 12%;" class="text-center">Tanggal</th>
                    <th>Nama Barang</th>
                    <th>Lokasi Asal</th>
                    <th>Lokasi Tujuan</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
            @forelse($mutasi as $i => $item)
                <tr>
                    <td class="text-center">{{ $i+1 }}</td>
                    <td class="text-center">{{ $item->tanggal_mutasi ? \Carbon\Carbon::parse($item->tanggal_mutasi)->format('d/m/Y') : '-' }}</td>
                    <td><strong>{{ $item->nama_barang ?? $item->asetTetap?->nama_barang ?? '-' }}</strong></td>
                    <td>{{ $item->lokasi_awal ?? $item->lokasi_asal ?? '-' }}</td>
                    <td>{{ $item->lokasi_akhir ?? $item->lokasi_tujuan ?? '-' }}</td>
                    <td>{{ $item->keterangan ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">Tidak ada riwayat mutasi aset pada periode ini</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- 6. PEMINJAMAN ASET TETAP --}}
    <div class="section">
        <div class="section-title">6. Log Peminjaman Aset Tetap (Barang & Kendaraan Periode Ini)</div>
        <div class="sub-title">A. Peminjaman Barang Inventaris</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 4%;" class="text-center">No</th>
                    <th style="width: 12%;" class="text-center">Tgl Pinjam</th>
                    <th>Peminjam</th>
                    <th>Nama Barang</th>
                    <th style="width: 8%;" class="text-center">Jumlah</th>
                    <th style="width: 14%;" class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
            @forelse($peminjaman_barang as $i => $item)
                <tr>
                    <td class="text-center">{{ $i+1 }}</td>
                    <td class="text-center">{{ $item->tanggal_peminjaman ? \Carbon\Carbon::parse($item->tanggal_peminjaman)->format('d/m/Y') : ($item->created_at ? $item->created_at->format('d/m/Y') : '-') }}</td>
                    <td>{{ $item->user?->name ?? '-' }}</td>
                    <td><strong>{{ $item->nama_barang ?? '-' }}</strong></td>
                    <td class="text-center">{{ $item->jumlah ?? 1 }}</td>
                    <td class="text-center"><span class="badge">{{ ucfirst(str_replace('_', ' ', $item->status ?? '-')) }}</span></td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">Tidak ada data peminjaman barang pada periode ini</td></tr>
            @endforelse
            </tbody>
        </table>

        <div class="sub-title">B. Peminjaman Kendaraan Dinas / Operasional</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 4%;" class="text-center">No</th>
                    <th style="width: 12%;" class="text-center">Tgl Pinjam</th>
                    <th>Peminjam</th>
                    <th>Kendaraan</th>
                    <th>Tujuan Penggunaan</th>
                    <th style="width: 14%;" class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
            @forelse($peminjaman_kendaraan as $i => $item)
                <tr>
                    <td class="text-center">{{ $i+1 }}</td>
                    <td class="text-center">{{ $item->tanggal_peminjaman ? \Carbon\Carbon::parse($item->tanggal_peminjaman)->format('d/m/Y') : ($item->created_at ? $item->created_at->format('d/m/Y') : '-') }}</td>
                    <td>{{ $item->user?->name ?? '-' }}</td>
                    <td><strong>{{ $item->nama_kendaraan ?? $item->nama_barang ?? '-' }}</strong></td>
                    <td>{{ $item->tujuan ?? '-' }}</td>
                    <td class="text-center"><span class="badge">{{ ucfirst(str_replace('_', ' ', $item->status ?? '-')) }}</span></td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">Tidak ada data peminjaman kendaraan pada periode ini</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- 7. PENGEMBALIAN ASET TETAP --}}
    <div class="section">
        <div class="section-title">7. Log Pengembalian Aset Tetap (Barang & Kendaraan Periode Ini)</div>
        <div class="sub-title">A. Pengembalian Barang Inventaris</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 4%;" class="text-center">No</th>
                    <th style="width: 12%;" class="text-center">Tgl Kembali</th>
                    <th>Peminjam</th>
                    <th>Nama Barang</th>
                    <th style="width: 12%;" class="text-center">Kondisi</th>
                    <th style="width: 14%;" class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
            @forelse($pengembalian_barang ?? [] as $i => $item)
                <tr>
                    <td class="text-center">{{ $i+1 }}</td>
                    <td class="text-center">{{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') : '-' }}</td>
                    <td>{{ $item->peminjamanBarang?->user?->name ?? '-' }}</td>
                    <td><strong>{{ $item->peminjamanBarang?->nama_barang ?? '-' }}</strong></td>
                    <td class="text-center">{{ $item->kondisi_barang ?? '-' }}</td>
                    <td class="text-center"><span class="badge">{{ ucfirst(str_replace('_', ' ', $item->status_pengembalian ?? '-')) }}</span></td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">Tidak ada data pengembalian barang pada periode ini</td></tr>
            @endforelse
            </tbody>
        </table>

        <div class="sub-title">B. Pengembalian Kendaraan Dinas / Operasional</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 4%;" class="text-center">No</th>
                    <th style="width: 12%;" class="text-center">Tgl Kembali</th>
                    <th>Peminjam</th>
                    <th>Kendaraan</th>
                    <th style="width: 12%;" class="text-center">Kondisi</th>
                    <th style="width: 14%;" class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
            @forelse($pengembalian_kendaraan ?? [] as $i => $item)
                <tr>
                    <td class="text-center">{{ $i+1 }}</td>
                    <td class="text-center">{{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') : '-' }}</td>
                    <td>{{ $item->peminjamanKendaraan?->user?->name ?? '-' }}</td>
                    <td><strong>{{ $item->peminjamanKendaraan?->nama_kendaraan ?? $item->peminjamanKendaraan?->nama_barang ?? '-' }}</strong></td>
                    <td class="text-center">{{ $item->kondisi_kendaraan ?? '-' }}</td>
                    <td class="text-center"><span class="badge">{{ ucfirst(str_replace('_', ' ', $item->status_pengembalian ?? '-')) }}</span></td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">Tidak ada data pengembalian kendaraan pada periode ini</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">
        Dokumen ini dicetak secara otomatis oleh Sistem SIPANDU &mdash; BPMP Provinsi Gorontalo &mdash; {{ $generated_at->format('d/m/Y H:i') }}
    </div>
</body>
</html>
