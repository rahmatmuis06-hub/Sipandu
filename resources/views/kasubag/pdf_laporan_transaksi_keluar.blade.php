<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi Keluar - Kasubag</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            color: #111;
            line-height: 1.3;
        }
        @page {
            size: A4 landscape;
            margin: 12mm 15mm;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 12px;
        }
        .header h2 {
            margin: 0;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header h3 {
            margin: 3px 0 0;
            font-size: 12px;
            font-weight: bold;
        }
        .header p {
            margin: 3px 0 0;
            font-size: 10px;
            color: #333;
        }
        
        .meta-table {
            width: 100%;
            margin-bottom: 12px;
            font-size: 10px;
        }
        .meta-table td {
            padding: 2px 0;
            vertical-align: top;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .data-table th, .data-table td {
            border: 1px solid #333;
            padding: 6px 4px;
            vertical-align: middle;
        }
        .data-table th {
            background-color: #f1f5f9;
            text-align: center;
            font-weight: bold;
            font-size: 9.5px;
            text-transform: uppercase;
        }
        .data-table td {
            font-size: 9px;
        }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        
        .summary-box {
            margin-bottom: 15px;
            width: 100%;
            border-collapse: collapse;
        }
        .summary-box td {
            border: 1px solid #94a3b8;
            background: #f8fafc;
            padding: 6px 10px;
            font-size: 10px;
        }

        .ttd-table {
            width: 100%;
            margin-top: 25px;
            border-collapse: collapse;
            page-break-inside: avoid;
        }
        .ttd-table td {
            border: none;
            vertical-align: top;
        }
    </style>
</head>
<body>

    <!-- KOP SURAT -->
    <div class="header">
        <h2>BALAI PENJAMINAN MUTU PENDIDIKAN (BPMP) PROVINSI GORONTALO</h2>
        <h3>LAPORAN MONITORING TRANSAKSI KELUAR ({{ strtoupper($sumber === 'aset_tetap' ? 'ASET TETAP / BMN' : 'PERSEDIAAN') }})</h3>
        <p>Sistem Informasi Manajemen Terpadu (SIPANDU) - Format: {{ $mode === 'keseluruhan' ? 'Rekapitulasi Keseluruhan' : 'Rincian Per Detail Barang' }}</p>
    </div>

    <!-- META INFO -->
    <table class="meta-table">
        <tr>
            <td style="width: 15%;"><strong>Sumber Data</strong></td>
            <td style="width: 2%;">:</td>
            <td style="width: 48%;">{{ $sumber === 'aset_tetap' ? 'Aset Tetap / BMN' : 'Barang Persediaan (Habis Pakai)' }}</td>
            <td style="width: 15%;"><strong>Tanggal Cetak</strong></td>
            <td style="width: 2%;">:</td>
            <td style="width: 18%;" class="text-right">{{ now()->locale('id')->isoFormat('D MMMM YYYY') }}</td>
        </tr>
        <tr>
            <td><strong>Mode Laporan</strong></td>
            <td>:</td>
            <td>{{ $mode === 'keseluruhan' ? 'Rekapitulasi Keseluruhan / Kategori' : 'Rincian Per Detail Barang' }}</td>
            <td><strong>Dicetak Oleh</strong></td>
            <td>:</td>
            <td class="text-right">{{ $kasubagUser->name ?? 'Kasubag Umum' }}</td>
        </tr>
        <tr>
            <td><strong>Filter Periode</strong></td>
            <td>:</td>
            <td colspan="4">
                @if($startDate && $endDate)
                    {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
                @elseif($startDate)
                    Mulai {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}
                @elseif($endDate)
                    Hingga {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
                @else
                    Semua Periode / Riwayat
                @endif
                @if($kategori)
                    | {{ $sumber === 'aset_tetap' ? 'Merek' : 'Kategori' }}: {{ $kategori }}
                @endif
                @if($search)
                    | Kata Kunci: "{{ $search }}"
                @endif
            </td>
        </tr>
    </table>

    <!-- RINGKASAN DATA -->
    <table class="summary-box">
        <tr>
            <td style="width: 33%;"><strong>Total Transaksi Keluar:</strong> {{ number_format($totalTransaksi, 0, ',', '.') }} Kali</td>
            <td style="width: 33%;"><strong>Total Volume / Kuantitas:</strong> {{ number_format($totalUnit, 0, ',', '.') }} Unit</td>
            <td style="width: 34%;"><strong>Total Nilai Pengeluaran:</strong> Rp {{ number_format($totalNilai, 0, ',', '.') }}</td>
        </tr>
    </table>

    @if($mode === 'keseluruhan')
        <!-- ============================================== -->
        <!-- TABEL REKAPITULASI KESELURUHAN PER KATEGORI    -->
        <!-- ============================================== -->
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 4%;">No</th>
                    <th>Nama {{ $sumber === 'aset_tetap' ? 'Merek / Golongan' : 'Kategori Barang' }}</th>
                    @if($sumber === 'persediaan')
                        <th style="width: 15%;">Kode Kategori</th>
                    @endif
                    <th style="width: 15%;">Frekuensi Transaksi</th>
                    <th style="width: 15%;">Total Unit Keluar</th>
                    <th style="width: 20%;">Total Nilai (Rp)</th>
                    <th style="width: 12%;">Kontribusi Nilai</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rekapKategori as $item)
                    @php
                        $kontribusi = $totalNilai > 0 ? ($item->total_nominal / $totalNilai) * 100 : 0;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-left font-bold">{{ $item->nama_kategori }}</td>
                        @if($sumber === 'persediaan')
                            <td class="text-center">{{ $item->kode_kategori ?? '-' }}</td>
                        @endif
                        <td class="text-center">{{ number_format($item->frekuensi, 0, ',', '.') }} Kali</td>
                        <td class="text-center font-bold">{{ number_format($item->total_unit, 0, ',', '.') }} Unit</td>
                        <td class="text-right font-bold">Rp {{ number_format($item->total_nominal, 0, ',', '.') }}</td>
                        <td class="text-center">{{ number_format($kontribusi, 1) }}%</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $sumber === 'persediaan' ? 7 : 6 }}" class="text-center" style="padding: 20px;">
                            Tidak ada data rekapitulasi pada filter yang dipilih.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            @if(count($rekapKategori) > 0)
                <tfoot>
                    <tr style="background:#f1f5f9; font-weight:bold;">
                        <td colspan="{{ $sumber === 'persediaan' ? 3 : 2 }}" class="text-right">TOTAL KESELURUHAN:</td>
                        <td class="text-center">{{ number_format($rekapKategori->sum('frekuensi'), 0, ',', '.') }} Kali</td>
                        <td class="text-center">{{ number_format($rekapKategori->sum('total_unit'), 0, ',', '.') }} Unit</td>
                        <td class="text-right">Rp {{ number_format($rekapKategori->sum('total_nominal'), 0, ',', '.') }}</td>
                        <td class="text-center">100.0%</td>
                    </tr>
                </tfoot>
            @endif
        </table>

    @else
        <!-- ============================================== -->
        <!-- TABEL RINCIAN PER DETAIL BARANG                -->
        <!-- ============================================== -->
        <table class="data-table">
            <thead>
                @if($sumber === 'aset_tetap')
                    <tr>
                        <th style="width: 3%;">No</th>
                        <th style="width: 9%;">Tanggal</th>
                        <th style="width: 14%;">Kode Barang</th>
                        <th style="width: 8%;">NUP</th>
                        <th style="width: 22%;">Nama Barang</th>
                        <th style="width: 12%;">Merek</th>
                        <th style="width: 12%;">Lokasi</th>
                        <th style="width: 10%;">No. SK</th>
                        <th style="width: 10%;">Nilai (Rp)</th>
                    </tr>
                @else
                    <tr>
                        <th style="width: 3%;">No</th>
                        <th style="width: 8%;">Tanggal</th>
                        <th style="width: 14%;">Kode Barang</th>
                        <th style="width: 22%;">Nama Barang</th>
                        <th style="width: 14%;">Kategori</th>
                        <th style="width: 8%;">Jml Keluar</th>
                        <th style="width: 10%;">Harga Satuan</th>
                        <th style="width: 11%;">Total Nilai</th>
                        <th style="width: 10%;">Keterangan</th>
                    </tr>
                @endif
            </thead>
            <tbody>
                @forelse($detailTransaksi as $item)
                    @if($sumber === 'aset_tetap')
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center">{{ $item->tanggal_input ? \Carbon\Carbon::parse($item->tanggal_input)->format('d/m/Y') : '-' }}</td>
                            <td class="text-center">{{ $item->kode_barang ?? '-' }}</td>
                            <td class="text-center font-bold">{{ $item->nup ?? '-' }}</td>
                            <td class="text-left font-bold">{{ $item->nama_barang ?? '-' }}</td>
                            <td class="text-left">{{ $item->merek ?? '-' }}</td>
                            <td class="text-left">{{ $item->lokasi ?? '-' }}</td>
                            <td class="text-center">{{ $item->nomor_sk ?? '-' }}</td>
                            <td class="text-right font-bold">Rp {{ number_format($item->nilai_perolehan ?? 0, 0, ',', '.') }}</td>
                        </tr>
                    @else
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center">{{ $item->tanggal_input ? \Carbon\Carbon::parse($item->tanggal_input)->format('d/m/Y') : '-' }}</td>
                            <td class="text-center">{{ $item->kode_unik_barang ?? $item->kode_barang ?? '-' }}</td>
                            <td class="text-left font-bold">{{ $item->nama_barang ?? '-' }}</td>
                            <td class="text-left">{{ $item->kategori ?? '-' }}</td>
                            <td class="text-center font-bold">{{ number_format($item->jumlah_keluar ?? 0, 0, ',', '.') }} {{ $item->satuan ?? 'Unit' }}</td>
                            <td class="text-right">Rp {{ number_format($item->harga ?? 0, 0, ',', '.') }}</td>
                            <td class="text-right font-bold">Rp {{ number_format($item->total ?? 0, 0, ',', '.') }}</td>
                            <td class="text-left">{{ $item->keterangan ?? '-' }}</td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="9" class="text-center" style="padding: 20px;">
                            Tidak ada rincian data transaksi keluar pada filter yang dipilih.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            @if(count($detailTransaksi) > 0)
                <tfoot>
                    <tr style="background:#f1f5f9; font-weight:bold;">
                        <td colspan="{{ $sumber === 'aset_tetap' ? 8 : 7 }}" class="text-right">TOTAL KESELURUHAN:</td>
                        <td class="text-right">
                            Rp {{ number_format($sumber === 'aset_tetap' ? $detailTransaksi->sum('nilai_perolehan') : $detailTransaksi->sum('total'), 0, ',', '.') }}
                        </td>
                        @if($sumber !== 'aset_tetap')
                            <td></td>
                        @endif
                    </tr>
                </tfoot>
            @endif
        </table>
    @endif

    <!-- TANDA TANGAN -->
    <table class="ttd-table">
        <tr>
            <td style="width: 65%;"></td>
            <td style="width: 35%; text-align: center;">
                Gorontalo, {{ now()->locale('id')->isoFormat('D MMMM YYYY') }}<br>
                Kasubag Tata Usaha BPMP Gorontalo
                <br><br><br><br>
                <b><u>{{ $kasubagUser->name ?? 'Kasubag Umum' }}</u></b><br>
                NIP. {{ $kasubagUser->nip ?? '........................................' }}
            </td>
        </tr>
    </table>

</body>
</html>
