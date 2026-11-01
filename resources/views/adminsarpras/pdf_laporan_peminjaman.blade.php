<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Peminjaman Gedung</title>
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
            padding: 6px;
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
                <td>Laporan Peminjaman Gedung & Fasilitas</td>
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
                <th style="width: 3%;">No</th>
                <th style="width: 10%;">ID Peminjaman</th>
                <th style="width: 17%;">Nama Peminjam / Instansi</th>
                <th style="width: 15%;">Gedung / Fasilitas</th>
                <th style="width: 15%;">Tgl Pinjam</th>
                <th style="width: 15%;">Tujuan</th>
                <th style="width: 10%;">Status</th>
                <th style="width: 15%;">Total Bayar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peminjaman as $item)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="text-center">PG-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}</td>
                <td>
                    <b>{{ $item->nama_lengkap }}</b><br>
                    <span style="font-size: 10px;">{{ $item->instansi_lembaga }}</span>
                </td>
                <td>{{ $item->gedung->nama_gedung ?? $item->nama_fasilitas ?? '—' }}</td>
                <td class="text-center">
                    {{ $item->tanggal_pinjam->format('d/m/Y') }} <br>
                    <span style="font-size: 10px;">({{ $item->jam_mulai }} - {{ $item->jam_selesai }})</span>
                </td>
                <td>{{ $item->tujuan_penggunaan }}</td>
                <td class="text-center">{{ ucfirst(str_replace('_', ' ', $item->status)) }}</td>
                <td class="text-right">Rp {{ number_format($item->total_pembayaran, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Tidak ada data peminjaman.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="7" class="text-right">Total Pendapatan Laporan Ini (Status Disetujui/Selesai):</th>
                <th class="text-right">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    @php
        $signer = auth()->user();
        $ttdBase64 = $signer?->signature_base64;
    @endphp
    <table class="footer-table">
        <tr>
            <td></td>
            <td></td>
            <td>
                Gorontalo, {{ now()->locale('id')->isoFormat('D MMMM YYYY') }}<br>
                Admin Sarana Prasarana
                @if($ttdBase64)
                    <div style="height: 70px; margin: 4px 0;">
                        <img src="{{ $ttdBase64 }}" alt="TTD Admin Sarpras" style="max-height: 66px; max-width: 180px;">
                    </div>
                @else
                    <div class="ttd-space"></div>
                @endif
                <b><u>{{ $signer->name ?? 'Administrator' }}</u></b><br>
                NIP. {{ !empty($signer?->nip) ? $signer->nip : '.....................................' }}
            </td>
        </tr>
    </table>

</body>
</html>