<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Permintaan Persediaan</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h2 { margin: 0; font-size: 16px; font-weight: bold; text-transform: uppercase; }
        .header p { margin: 5px 0 0; font-size: 12px; }
        
        .info { margin-bottom: 15px; }
        .info table { width: 100%; font-size: 11px; }
        .info td { padding: 2px 0; border: none; }

        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table-data th, .table-data td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
            vertical-align: top;
        }
        .table-data th {
            background-color: #f0f0f0;
            text-align: center;
            font-weight: bold;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        
        .status-badge {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
        }

        .footer {
            width: 100%;
            margin-top: 30px;
        }
        .footer-table { width: 100%; border: none; }
        .footer-table td { text-align: center; border: none; width: 33%; }
        .ttd-space { height: 70px; }
    </style>
</head>
<body>

    <div class="header">
        <h2>BALAI PENJAMINAN MUTU PENDIDIKAN (BPMP) PROVINSI GORONTALO</h2>
        <p>Sistem Informasi Manajemen Aset Terpadu (SIMASET)</p>
    </div>

    <div class="info">
        <table>
            <tr>
                <td style="width: 120px;"><b>Jenis Laporan</b></td>
                <td style="width: 10px;">:</td>
                <td>Data Transaksi Permintaan Persediaan (Barang Habis Pakai)</td>
                <td style="text-align: right;"><b>Tanggal Cetak:</b> {{ now()->locale('id')->isoFormat('D MMMM YYYY') }}</td>
            </tr>
            <tr>
                <td><b>Filter Data</b></td>
                <td>:</td>
                <td>{{ request('status') ? ucfirst(str_replace('_', ' ', request('status'))) : 'Semua Status' }}</td>
                <td></td>
            </tr>
        </table>
    </div>

    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 12%;">ID Request</th>
                <th style="width: 16%;">Nama Pemohon</th>
                <th style="width: 20%;">Barang Diminta</th>
                <th style="width: 8%;">Jumlah</th>
                <th style="width: 12%;">Tgl Permintaan</th>
                <th style="width: 16%;">Tujuan Penggunaan</th>
                <th style="width: 12%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($permintaan as $item)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="text-center">REQ-{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</td>
                <td>
                    <b>{{ $item->user->name ?? $item->nama_lengkap }}</b>
                </td>
                <td>
                    {{ $item->persediaan->nama_barang ?? $item->nama_barang }}<br>
                    <span style="font-size: 9px; color: #555;">Kode: {{ $item->persediaan->kode_unik_barang ?? (($item->kode_kategori ?? '').'-'.($item->kode_barang ?? '')) }}</span>
                </td>
                <td class="text-center"><b>{{ $item->jumlah_diminta }}</b> Unit</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal_permintaan)->format('d/m/Y') }}</td>
                <td>{{ $item->tujuan_penggunaan }}</td>
                <td class="text-center status-badge">
                    @php 
                        $statusText = str_replace('_', ' ', $item->status);
                    @endphp
                    {{ $statusText }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Tidak ada data permintaan persediaan.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7" class="text-right"><b>Total Transaksi Disetujui:</b></td>
                <td class="text-center"><b>{{ $totalTransaksiDisetujui }}</b> Trx</td>
            </tr>
            <tr>
                <td colspan="7" class="text-right"><b>Total Item Disetujui:</b></td>
                <td class="text-center"><b>{{ $totalItemDisetujui }}</b> Unit</td>
            </tr>
        </tfoot>
    </table>

    <table class="footer-table">
        <tr>
            <td></td>
            <td></td>
            <td>
                Gorontalo, {{ now()->locale('id')->isoFormat('D MMMM YYYY') }}<br>
                Admin Persediaan
                <div class="ttd-space"></div>
                <b><u>{{ auth()->user()->name ?? 'Administrator' }}</u></b><br>
                NIP. .....................................
            </td>
        </tr>
    </table>

</body>
</html>
