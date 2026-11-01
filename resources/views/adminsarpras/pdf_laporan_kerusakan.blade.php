<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kerusakan Sarpras</title>
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
                <td>Data Kerusakan Barang / Fasilitas (Sarpras)</td>
                <td style="text-align: right;"><b>Tanggal Cetak:</b> {{ now()->locale('id')->isoFormat('D MMMM YYYY') }}</td>
            </tr>
            <tr>
                <td><b>Filter Data</b></td>
                <td>:</td>
                <td>
                    @if(request('bulan') && request('tahun'))
                        Bulan {{ request('bulan') }} Tahun {{ request('tahun') }}
                    @else
                        Semua Periode Waktu
                    @endif
                    
                    @if(request('kondisi'))
                        | Kondisi: {{ request('kondisi') }}
                    @endif
                </td>
                <td></td>
            </tr>
        </table>
    </div>

    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 3%;">No</th>
                <th style="width: 12%;">Tanggal Input</th>
                <th style="width: 20%;">Nama Barang</th>
                <th style="width: 15%;">Kode Barang / NUP</th>
                <th style="width: 15%;">Lokasi</th>
                <th style="width: 10%;">Kondisi</th>
                <th style="width: 25%;">Deskripsi / Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kerusakans as $item)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal_input)->format('d/m/Y') }}</td>
                <td><b>{{ $item->nama_barang }}</b></td>
                <td>
                    {{ $item->kode_barang }}<br>
                    <span style="font-size: 10px; color: #666;">NUP: {{ $item->nup ?? '-' }}</span>
                </td>
                <td>{{ $item->lokasi }}</td>
                <td class="text-center">
                    @if($item->kondisi == 'Baik')
                        Baik
                    @elseif($item->kondisi == 'Rusak Ringan')
                        Rusak Ringan
                    @else
                        Rusak Berat
                    @endif
                </td>
                <td>{{ $item->deskripsi ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada data kerusakan pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
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