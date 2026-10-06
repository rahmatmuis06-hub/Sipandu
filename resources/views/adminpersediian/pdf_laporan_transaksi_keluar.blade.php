<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi Keluar Persediaan</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #333;
        }
        @page {
            size: A4 landscape;
            margin: 15mm 20mm;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }
        .header h2 { margin: 0; font-size: 16px; font-weight: bold; text-transform: uppercase; }
        .header p { margin: 5px 0 0; font-size: 12px; }
        
        .info-table {
            width: 100%;
            margin-bottom: 15px;
            font-size: 11px;
        }
        .info-table td {
            padding: 4px 0;
            vertical-align: top;
        }

        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table-data th, .table-data td {
            border: 1px solid #000;
            padding: 8px 4px;
            vertical-align: middle;
        }
        .table-data th {
            background-color: #f3f4f6; /* Abu-abu muda */
            text-align: center;
            font-weight: bold;
            font-size: 10px;
        }
        .table-data td { font-size: 10px; }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        
        .ttd-table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }
        .ttd-table td { border: none; }
    </style>
</head>
<body>

    <!-- KOP SURAT -->
    <div class="header">
        <h2>BALAI PENJAMINAN MUTU PENDIDIKAN (BPMP) PROVINSI GORONTALO</h2>
        <p>Sistem Informasi Manajemen Aset Terpadu (SIPANDU)</p>
    </div>

    <!-- INFO LAPORAN -->
    <table class="info-table">
        <tr>
            <td style="width: 12%;"><strong>Jenis Laporan</strong></td>
            <td style="width: 2%;">:</td>
            <td style="width: 56%;">Data Transaksi Keluar Persediaan (Barang Habis Pakai)</td>
            <td style="width: 30%;" class="text-right"><strong>Tanggal Cetak:</strong> {{ now()->locale('id')->isoFormat('D MMMM YYYY') }}</td>
        </tr>
        <tr>
            <td><strong>Filter Data</strong></td>
            <td>:</td>
            <td colspan="2">
                @if(request('tanggal_input'))
                    Tgl: {{ \Carbon\Carbon::parse(request('tanggal_input'))->format('d/m/Y') }}
                @else
                    Semua Periode
                @endif
                
                @if(request('kode_kategori'))
                    | Kategori: {{ request('kode_kategori') }}
                @endif
            </td>
        </tr>
    </table>

    <!-- TABEL DATA -->
    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 3%;">No</th>
                <th style="width: 10%;">No. Transaksi</th>
                <th style="width: 8%;">Tanggal</th>
                <th style="width: 17%;">Kode Unik Barang</th>
                <th style="width: 12%;">Kategori</th>
                <th style="width: 18%;">Nama Barang</th>
                <th style="width: 8%;">Jml Keluar</th>
                <th style="width: 10%;">Harga</th>
                <th style="width: 12%;">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksi as $item)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="text-center">{{ $item->nomor_transaksi ?? '-' }}</td>
                <td class="text-center">{{ $item->tanggal_input ? \Carbon\Carbon::parse($item->tanggal_input)->format('d/m/Y') : '-' }}</td>
                <td class="text-center">{{ $item->kode_unik_barang }}</td>
                <td class="text-left">{{ $item->kategori ?? '-' }}</td>
                <td class="text-left">{{ $item->nama_barang ?? '-' }}</td>
                <td class="text-center"><b>{{ number_format($item->jumlah_keluar ?? 0, 0, ',', '.') }}</b> {{ $item->satuan ?? ($item->persediaan->satuan ?? 'Unit') }}</td>
                <td class="text-right">Rp {{ number_format($item->harga ?? 0, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($item->total ?? 0, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center" style="padding: 20px;">Tidak ada data transaksi keluar persediaan pada filter/periode ini.</td>
            </tr>
            @endforelse
        </tbody>
        @if($transaksi->count() > 0)
        <tfoot>
            <tr>
                <td colspan="8" class="text-right"><strong>Total Keseluruhan:</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($transaksi->sum('total'), 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td colspan="8" class="text-right"><strong>Total Item Keluar:</strong></td>
                <td class="text-right"><strong>{{ number_format($transaksi->sum('jumlah_keluar'), 0, ',', '.') }} Item</strong></td>
            </tr>
        </tfoot>
        @endif
    </table>

    <!-- TANDA TANGAN -->
    <table class="ttd-table">
        <tr>
            <td style="width: 70%;"></td>
            <td style="width: 30%; text-align: center;">
                Gorontalo, {{ now()->locale('id')->isoFormat('D MMMM YYYY') }}<br>
                Admin Persediaan
                <br><br><br><br>
                <b><u>{{ auth()->user()->name ?? 'Administrator' }}</u></b><br>
                NIP. ........................................
            </td>
        </tr>
    </table>

</body>
</html>
