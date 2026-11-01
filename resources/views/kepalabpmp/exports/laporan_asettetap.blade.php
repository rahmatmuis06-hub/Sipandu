<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - BPMP Provinsi Gorontalo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* CSS Reset & Dasar */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #1e293b;
            background-color: #f1f5f9;
            line-height: 1.5;
        }

        /* ══════════ TOP ACTION BAR (HANYA DI LAYAR) ══════════ */
        .top-action-bar {
            position: sticky;
            top: 0;
            z-index: 9999;
            background: linear-gradient(135deg, #1e293b, #0f172a);
            color: #fff;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        }
        .top-action-bar .bar-title {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            font-weight: 700;
        }
        .top-action-bar .bar-title i {
            color: #ea580c;
            font-size: 18px;
        }
        .top-action-bar .bar-badge {
            font-size: 11px;
            font-weight: 600;
            background: rgba(234, 88, 12, 0.2);
            color: #fdba74;
            border: 1px solid rgba(234, 88, 12, 0.4);
            padding: 3px 10px;
            border-radius: 99px;
        }
        .top-action-bar .bar-buttons {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            font-family: inherit;
        }
        .btn-print {
            background: linear-gradient(135deg, #ea580c, #c2410c);
            color: #fff;
            box-shadow: 0 2px 8px rgba(234, 88, 12, 0.4);
        }
        .btn-print:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(234, 88, 12, 0.6);
        }
        .btn-excel {
            background: #10b981;
            color: #fff;
        }
        .btn-excel:hover {
            background: #059669;
        }
        .btn-close-tab {
            background: #475569;
            color: #fff;
        }
        .btn-close-tab:hover {
            background: #334155;
        }

        /* ══════════ KERTAS CETAK (SHEET) ══════════ */
        .paper-container {
            max-width: 1150px;
            margin: 24px auto 40px auto;
            background: #fff;
            padding: 32px 40px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border-radius: 8px;
        }

        /* Kop Dokumen */
        .header {
            text-align: center;
            border-bottom: 3px double #ea580c;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }
        .header h1 {
            font-size: 17px;
            color: #ea580c;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }
        .header h2 {
            font-size: 13.5px;
            color: #1e293b;
            font-weight: 800;
            margin-bottom: 4px;
        }
        .header p {
            font-size: 10.5px;
            color: #64748b;
        }

        /* Bagian & Tabel */
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 12.5px;
            font-weight: 800;
            color: #ea580c;
            border-bottom: 2px solid #fed7aa;
            padding-bottom: 4px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .sub-title {
            font-size: 11px;
            font-weight: 700;
            color: #334155;
            margin: 8px 0 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 10.5px;
        }
        th {
            background: #ea580c;
            color: #ffffff;
            padding: 7px 8px;
            font-size: 10px;
            text-align: left;
            text-transform: uppercase;
            font-weight: 700;
            border: 1px solid #ea580c;
        }
        td {
            padding: 6px 8px;
            border: 1px solid #e2e8f0;
            font-size: 10px;
            color: #1e293b;
        }
        tr:nth-child(even) td {
            background: #f8fafc;
        }
        tfoot tr td {
            background: #f1f5f9 !important;
            border-top: 2px solid #cbd5e1;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 9px;
            background: #e2e8f0;
            color: #334155;
            border-radius: 4px;
        }

        /* Area Tanda Tangan */
        .signature-section {
            margin-top: 32px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }
        .signature-box {
            width: 45%;
            text-align: center;
            font-size: 11px;
        }
        .signature-title {
            margin-bottom: 60px;
            font-weight: 600;
            color: #475569;
        }
        .signature-name {
            font-weight: 800;
            text-decoration: underline;
            color: #0f172a;
        }
        .signature-nip {
            font-size: 10px;
            color: #64748b;
        }

        /* Footer */
        .footer {
            margin-top: 24px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
            font-size: 9px;
            color: #94a3b8;
            text-align: center;
        }

        /* ══════════ PENGATURAN MEDIA CETAK (PRINT & SAVE AS PDF) ══════════ */
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: #fff !important;
                color: #000 !important;
                padding: 0 !important;
            }
            .paper-container {
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                border-radius: 0 !important;
            }
            @page {
                size: A4 landscape;
                margin: 10mm 12mm;
            }
            table {
                page-break-inside: auto;
            }
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
            thead {
                display: table-header-group;
            }
            tfoot {
                display: table-footer-group;
            }
            th {
                background: #ea580c !important;
                color: #ffffff !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            tr:nth-child(even) td {
                background: #f8fafc !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .signature-section {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>

    {{-- ══════════ FLOATING ACTION BAR (HANYA DITAMPILKAN DI LAYAR BROWSER) ══════════ --}}
    <div class="top-action-bar no-print">
        <div class="bar-title">
            <i class="fas fa-file-invoice"></i>
            <span>{{ $title }}</span>
            <span class="bar-badge"><i class="fas fa-check-circle"></i> Siap Disimpan Sebagai PDF</span>
        </div>
        <div class="bar-buttons">
            <button onclick="window.print()" class="btn-action btn-print">
                <i class="fas fa-print"></i> Cetak / Simpan PDF
            </button>
            <a href="{{ route('kepalabpmp.laporan.download-aset-tetap-excel', request()->all()) }}" class="btn-action btn-excel">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
            <button onclick="window.close()" class="btn-action btn-close-tab">
                <i class="fas fa-times"></i> Tutup
            </button>
        </div>
    </div>

    {{-- ══════════ KERTAS DOKUMEN CETAK ══════════ --}}
    <div class="paper-container">

        {{-- KOP SURAT RESMI --}}
        <div class="header">
            <p style="font-size: 11px; font-weight: 700; color: #334155; margin-bottom: 2px;">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</p>
            <h1>BPMP PROVINSI GORONTALO</h1>
            <h2>{{ strtoupper($title) }}</h2>
            <p>Periode: <strong>{{ $periode }}</strong> &nbsp;|&nbsp; Dicetak oleh: <strong>{{ $generated_by }}</strong> &nbsp;|&nbsp; Tanggal Cetak: {{ $generated_at->format('d/m/Y H:i') }}</p>
        </div>

        {{-- RINGKASAN STATISTIK --}}
        <div class="section">
            <div class="section-title"><i class="fas fa-chart-pie"></i> Ringkasan Statistik Aset Tetap</div>
            <table>
                <tr>
                    <td style="width: 25%;"><strong>Total Master Aset:</strong></td>
                    <td style="width: 25%;"><strong>{{ number_format($stats['total_aset'] ?? 0) }}</strong> Unit</td>
                    <td style="width: 25%;"><strong>Total Nilai Aset:</strong></td>
                    <td style="width: 25%;"><strong>Rp {{ number_format($stats['total_nilai'] ?? 0, 0, ',', '.') }}</strong></td>
                </tr>
                <tr>
                    <td><strong>Transaksi Masuk Periode Ini:</strong></td>
                    <td>{{ $stats['total_masuk'] ?? 0 }} Transaksi</td>
                    <td><strong>Transaksi Keluar Periode Ini:</strong></td>
                    <td>{{ $stats['total_keluar'] ?? 0 }} Transaksi</td>
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
            <div class="section-title"><i class="fas fa-layer-group"></i> 1. Rekapitulasi Data Master Aset Tetap (Berdasarkan Kategori)</div>
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
                    <tr style="font-weight:bold;">
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
            <div class="section-title"><i class="fas fa-plus-circle"></i> 2. Daftar Pendaftaran / Penambahan Aset Tetap Baru (Periode Ini)</div>
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
                    <tr><td colspan="8" class="text-center" style="padding: 12px; color: #64748b;">Tidak ada pendaftaran aset tetap baru pada periode ini</td></tr>
                @endforelse
                </tbody>
                @if($aset->isNotEmpty())
                <tfoot>
                    <tr style="font-weight:bold;">
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
            <div class="section-title"><i class="fas fa-arrow-down"></i> 3. Log Transaksi Masuk Aset Tetap (Pengadaan / Perolehan Periode Ini)</div>
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
                    <tr><td colspan="6" class="text-center" style="padding: 12px; color: #64748b;">Tidak ada rekaman transaksi masuk pada periode ini</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- 4. TRANSAKSI KELUAR ASET TETAP --}}
        <div class="section">
            <div class="section-title"><i class="fas fa-arrow-up"></i> 4. Log Transaksi Keluar Aset Tetap (Penghapusan / Pemindahtanganan Periode Ini)</div>
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
                    <tr><td colspan="6" class="text-center" style="padding: 12px; color: #64748b;">Tidak ada rekaman transaksi keluar pada periode ini</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- 5. MUTASI LOKASI ASET TETAP --}}
        <div class="section">
            <div class="section-title"><i class="fas fa-exchange-alt"></i> 5. Log Mutasi Lokasi Aset Tetap (Periode Ini)</div>
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
                    <tr><td colspan="6" class="text-center" style="padding: 12px; color: #64748b;">Tidak ada riwayat mutasi aset pada periode ini</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- 6. PEMINJAMAN ASET TETAP --}}
        <div class="section">
            <div class="section-title"><i class="fas fa-handshake"></i> 6. Log Peminjaman Aset Tetap (Barang & Kendaraan Periode Ini)</div>
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
                    <tr><td colspan="6" class="text-center" style="padding: 10px; color: #64748b;">Tidak ada data peminjaman barang pada periode ini</td></tr>
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
                    <tr><td colspan="6" class="text-center" style="padding: 10px; color: #64748b;">Tidak ada data peminjaman kendaraan pada periode ini</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- 7. PENGEMBALIAN ASET TETAP --}}
        <div class="section">
            <div class="section-title"><i class="fas fa-undo"></i> 7. Log Pengembalian Aset Tetap (Barang & Kendaraan Periode Ini)</div>
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
                    <tr><td colspan="6" class="text-center" style="padding: 10px; color: #64748b;">Tidak ada data pengembalian barang pada periode ini</td></tr>
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
                    <tr><td colspan="6" class="text-center" style="padding: 10px; color: #64748b;">Tidak ada data pengembalian kendaraan pada periode ini</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- LEMBAR TANDA TANGAN / PENGESAHAN --}}
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-title">
                    Pengelola Barang Milik Negara (BMN),<br>
                    BPMP Provinsi Gorontalo
                </div>
                <div class="signature-name">( Tim Pengelola Aset Tetap )</div>
                <div class="signature-nip">NIP. .....................................................</div>
            </div>
            <div class="signature-box">
                <div class="signature-title">
                    Gorontalo, {{ $generated_at->translatedFormat('d F Y') }}<br>
                    Mengetahui,<br>
                    <strong>Kepala BPMP Provinsi Gorontalo</strong>
                </div>
                <div class="signature-name">{{ $generated_by }}</div>
                <div class="signature-nip">NIP. .....................................................</div>
            </div>
        </div>

        {{-- FOOTER RESMI --}}
        <div class="footer">
            Dokumen Resmi &mdash; Dicetak melalui Sistem Informasi Terpadu (SIPANDU) BPMP Provinsi Gorontalo &mdash; {{ $generated_at->format('d/m/Y H:i') }}
        </div>
    </div>

    {{-- SCRIPT OTOMATIS PEMICU CETAK / SIMPAN SEBAGAI PDF --}}
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            // Berikan jeda sejenak agar CSS & font selesai di-render sempurna
            setTimeout(function() {
                window.print();
            }, 600);
        });
    </script>
</body>
</html>
