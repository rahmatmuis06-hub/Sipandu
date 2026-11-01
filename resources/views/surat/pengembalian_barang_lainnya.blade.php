<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Berita Acara Pengembalian Barang Lainnya - {{ $pengembalian->id }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin-top: 1.5cm;
            margin-bottom: 1cm;
            margin-left: 2cm;
            margin-right: 2cm;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            line-height: 1.2;
            color: #000;
        }

        .text-center { text-align: center; }
        .text-justify { text-align: justify; }
        .font-bold { font-weight: bold; }
        .italic { font-style: italic; }
        
        .mb-2 { margin-bottom: 2px; }
        .mb-5 { margin-bottom: 5px; }
        .mb-10 { margin-bottom: 10px; }
        .mb-15 { margin-bottom: 15px; }

        .kop-surat {
            width: 100%;
            margin-bottom: 15px;
        }
        .kop-surat img {
            width: 100%; 
            height: auto;
            max-height: 2.5cm;
            object-fit: contain;
        }

        .judul-surat {
            font-size: 11pt;
            font-weight: bold;
            margin-bottom: 2px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            vertical-align: top;
            padding: 2px 0;
        }
        .td-label { width: 140px; }
        .td-titikdua { width: 15px; text-align: center; }

        .table-pihak {
            width: 95%;
            margin-left: 5%;
            margin-bottom: 5px;
        }
        
        .table-barang {
            width: 100%;
            margin-bottom: 10px;
        }

        .ttd-table {
            width: 100%;
            text-align: center;
            margin-top: 15px;
            page-break-inside: avoid;
        }
        .ttd-table td {
            width: 50%;
            padding: 2px 5px;
            vertical-align: top;
        }
        .ttd-img-container {
            height: 50px;
            margin: 2px 0;
            display: block;
        }
        .ttd-img-container img {
            max-height: 50px;
            max-width: 100px;
        }
        .ttd-nama {
            font-weight: bold;
        }
    </style>
</head>
<body>

    @php
        $tgl = $tanggalSurat ?? $pengembalian->tanggal_pengembalian_aktual ?? now();
        $hari = \Carbon\Carbon::parse($tgl)->locale('id')->isoFormat('dddd');
        $tanggal_teks = \Carbon\Carbon::parse($tgl)->locale('id')->isoFormat('D');
        $bulan = \Carbon\Carbon::parse($tgl)->locale('id')->isoFormat('MMMM');
        $tahun = \Carbon\Carbon::parse($tgl)->locale('id')->isoFormat('Y');

        $nomor_urut = str_pad($pengembalian->id, 3, '0', STR_PAD_LEFT);
        $tahun_angka = \Carbon\Carbon::parse($tgl)->format('Y');

        $admin = $pengembalian->adminVerifier ?? \App\Models\User::where('role', 'adminpersediaan')->first();
        $kepala = \App\Models\User::where('role', 'kepalabpmp')->first();
    @endphp

    @if(file_exists(storage_path('app/public/kop_surat.png')))
    <div class="kop-surat">
        <img src="{{ storage_path('app/public/kop_surat.png') }}" alt="Kop Surat BPMP Provinsi Gorontalo"> 
    </div>
    @endif

    <div class="text-center mb-15">
        <div class="judul-surat">BERITA ACARA PENGEMBALIAN BARANG LAINNYA</div>
        <div>No: {{ $nomor_urut }}/BLN.01/BPMP/{{ $tahun_angka }}</div>
    </div>

    <div class="text-justify mb-10">
        Pada hari ini <span class="italic font-bold">{{ $hari }}</span> tanggal <span class="italic font-bold">{{ $tanggal_teks }}</span> bulan <span class="italic font-bold">{{ $bulan }}</span> tahun <span class="italic font-bold">{{ $tahun }}</span> bertempat di Balai Penjaminan Mutu Pendidikan (BPMP) Provinsi Gorontalo, yang bertanda tangan di bawah ini :
    </div>

    <table class="table-pihak">
        <tr>
            <td style="width: 20px;">1.</td>
            <td class="td-label">Nama</td>
            <td class="td-titikdua">:</td>
            <td>{{ $admin->name ?? 'Admin Persediaan' }}</td>
        </tr>
        <tr>
            <td></td>
            <td>NIP</td>
            <td class="td-titikdua">:</td>
            <td>{{ $admin->nip ?? '-' }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Jabatan</td>
            <td class="td-titikdua">:</td>
            <td>Admin Pengelola Barang / Persediaan</td>
        </tr>
        <tr>
            <td></td>
            <td>Instansi</td>
            <td class="td-titikdua">:</td>
            <td>Balai Penjaminan Mutu Pendidikan Provinsi Gorontalo</td>
        </tr>
    </table>
    <div class="mb-10" style="margin-left: 5%;">Selanjutnya disebut sebagai <b>PIHAK PERTAMA</b></div>

    <table class="table-pihak">
        <tr>
            <td style="width: 20px;">2.</td>
            <td class="td-label">Nama</td>
            <td class="td-titikdua">:</td>
            <td>{{ $pengembalian->user->name ?? '-' }}</td>
        </tr>
        <tr>
            <td></td>
            <td>NIP</td>
            <td class="td-titikdua">:</td>
            <td>{{ $pengembalian->user->nip ?? '-' }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Jabatan</td>
            <td class="td-titikdua">:</td>
            <td>{{ $pengembalian->user->jabatan ?? '-' }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Instansi</td>
            <td class="td-titikdua">:</td>
            <td>Balai Penjaminan Mutu Pendidikan Provinsi Gorontalo</td>
        </tr>
    </table>
    <div class="mb-10" style="margin-left: 5%;">Selanjutnya disebut sebagai <b>PIHAK KEDUA</b></div>

    <div class="text-justify mb-10">
        <b>PIHAK KEDUA</b> telah menyerahkan kepada <b>PIHAK PERTAMA</b> Peralatan / Barang Lainnya dalam kondisi dan spesifikasi sebagai berikut :
    </div>

    <table class="table-barang">
        <tr>
            <td class="td-label">Kode Barang</td>
            <td class="td-titikdua">:</td>
            <td>{{ $pengembalian->peminjamanBarangLainnya->kode_barang ?? '-' }}</td>
        </tr>
        <tr>
            <td>Nama Barang</td>
            <td class="td-titikdua">:</td>
            <td><b>{{ $pengembalian->peminjamanBarangLainnya->nama_barang ?? '-' }}</b></td>
        </tr>
        <tr>
            <td>Jumlah Dikembalikan</td>
            <td class="td-titikdua">:</td>
            <td>{{ $pengembalian->jumlah_dikembalikan ?? '0' }} {{ $pengembalian->peminjamanBarangLainnya->satuan ?? 'Unit' }}</td>
        </tr>
        <tr>
            <td>Tanggal Kembali Aktual</td>
            <td class="td-titikdua">:</td>
            <td>{{ \Carbon\Carbon::parse($pengembalian->tanggal_pengembalian_aktual)->isoFormat('dddd, D MMMM Y') }}</td>
        </tr>
        <tr>
            <td>Kondisi Saat Ini</td>
            <td class="td-titikdua">:</td>
            <td>{{ ucwords(str_replace(['-', '_'], ' ', $pengembalian->kondisi_barang)) }}</td>
        </tr>
        <tr>
            <td>Catatan</td>
            <td class="td-titikdua">:</td>
            <td>{{ $pengembalian->catatan ?? '-' }}</td>
        </tr>
    </table>
        
    <div class="text-justify mb-15">
        Demikian Berita Acara Pengembalian Barang Lainnya ini dibuat dan ditandatangani dengan sebenar-benarnya untuk digunakan sebagaimana mestinya.
    </div>

    <table class="ttd-table">
        <tr>
            <td>
                <div class="font-bold">PIHAK PERTAMA</div>
                <div class="ttd-img-container"></div>
                <div class="ttd-nama">{{ $admin->name ?? 'Admin Persediaan' }}</div>
                <div class="font-bold">NIP. {{ $admin->nip ?? '.......................................' }}</div>
            </td>
            <td>
                <div class="font-bold">PIHAK KEDUA</div>
                <div class="ttd-img-container"></div>
                <div class="ttd-nama">{{ $pengembalian->user->name ?? '.......................................' }}</div>
                <div class="font-bold">NIP. {{ $pengembalian->user->nip ?? '.......................................' }}</div>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-top: 15px;">
                <div class="font-bold">Mengetahui,</div>
                <div class="font-bold">Kuasa Pengguna Barang / Kepala BPMP</div>
                <div class="ttd-img-container"></div>
                <div class="ttd-nama">{{ $kepala->name ?? 'Rudi Syaifullah, S. SI,M,M.' }}</div>
                <div class="font-bold">NIP. {{ $kepala->nip ?? '197606272003121002' }}</div>
            </td>
        </tr>
    </table>

</body>
</html>
