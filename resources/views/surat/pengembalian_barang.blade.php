<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Berita Acara Pengembalian Peminjaman - {{ $pengembalian->id }}</title>
    <style>
        /* 1. PENGATURAN HALAMAN */
        @page {
            size: A4 portrait;
            /* Margin diperkecil secara maksimal agar muat 1 halaman */
            margin-top: 1.5cm;
            margin-bottom: 1cm;
            margin-left: 2cm;
            margin-right: 2cm;
        }

        /* 2. TYPOGRAPHY */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt; /* Ukuran font dioptimalkan agar hemat ruang */
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

        /* 3. KOP SURAT */
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
            font-weight: normal;
            margin-bottom: 2px;
        }

        /* 4. TABEL DATA */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            vertical-align: top;
            padding: 1px 0;
        }
        .td-label { width: 120px; }
        .td-titikdua { width: 15px; text-align: center; }

        /* TABEL PIHAK & BARANG */
        .table-pihak {
            width: 95%;
            margin-left: 5%;
            margin-bottom: 5px;
        }
        
        .table-barang {
            width: 100%;
            margin-bottom: 10px;
        }

        /* 6. TANDA TANGAN */
        .ttd-table {
            width: 100%;
            text-align: center;
            margin-top: 15px;
            page-break-inside: avoid; /* Memaksa tabel ttd tidak terpotong ke halaman baru */
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
        // LOGIKA PENANGGALAN OTOMATIS
        $tgl = $tanggalSurat ?? $pengembalian->tanggal_pengembalian_aktual ?? now();
        $hari = \Carbon\Carbon::parse($tgl)->locale('id')->isoFormat('dddd');
        $tanggal_teks = \Carbon\Carbon::parse($tgl)->locale('id')->isoFormat('D');
        $bulan = \Carbon\Carbon::parse($tgl)->locale('id')->isoFormat('MMMM');
        $tahun = \Carbon\Carbon::parse($tgl)->locale('id')->isoFormat('Y');

        // FORMAT NOMOR SURAT AUTOINCREMENT 3 DIGIT
        $nomor_urut = str_pad($pengembalian->id, 3, '0', STR_PAD_LEFT);
        $tahun_angka = \Carbon\Carbon::parse($tgl)->format('Y');
    @endphp

    {{-- KOP SURAT --}}
    <div class="kop-surat">
        <img src="{{ storage_path('app/public/kop_surat.png') }}" alt="Kop Surat BPMP Provinsi Gorontalo"> 
    </div>

    <!-- JUDUL & NOMOR SURAT -->
    <div class="text-center mb-15">
        <div class="judul-surat">BERITA ACARA PENGEMBALIAN PEMINJAMAN</div>
        <div>No: {{ $nomor_urut }}/LK.01.02/893228/{{ $tahun_angka }}</div>
    </div>

    <!-- PEMBUKA -->
    <div class="text-justify mb-10">
        Pada hari ini <span class="italic font-bold">{{ $hari }}</span> tanggal <span class="italic font-bold">{{ $tanggal_teks }}</span> bulan <span class="italic font-bold">{{ $bulan }}</span> tahun <span class="italic font-bold">{{ $tahun }}</span> bertempat di Balai Penjaminan Mutu Pendidikan Provinsi Gorontalo, yang bertanda tangan dibawah ini :
    </div>

    <!-- IDENTITAS PIHAK PERTAMA -->
    <table class="table-pihak">
        <tr>
            <td style="width: 20px;">1.</td>
            <td class="td-label">Nama</td>
            <td class="td-titikdua">:</td>
            <td>{{ $admin->name ?? 'Wiwin Suriadi Bokingo' }}</td>
        </tr>
        <tr>
            <td></td>
            <td>NIP</td>
            <td class="td-titikdua">:</td>
            <td>{{ $admin->nip ?? '198001122008101002' }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Jabatan</td>
            <td class="td-titikdua">:</td>
            <td>Pengadministrasi Barang / Admin Aset Tetap</td>
        </tr>
        <tr>
            <td></td>
            <td>Instansi</td>
            <td class="td-titikdua">:</td>
            <td>Balai Penjaminan Mutu Pendidikan Provinsi Gorontalo</td>
        </tr>
    </table>
    <div class="mb-10" style="margin-left: 5%;">Selanjutnya disebut sebagai <b>PIHAK PERTAMA</b></div>

    <!-- IDENTITAS PIHAK KEDUA -->
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

    <!-- TRANSISI BARANG -->
    <div class="text-justify mb-10">
        <b>PIHAK KEDUA</b> telah menyerahkan kepada <b>PIHAK PERTAMA</b> Barang Milik Negara (BMN) sesuai spesifikasi sebagai berikut :
    </div>

    <!-- DATA BARANG -->
    <table class="table-barang">
        <tr>
            <td class="td-label">Kode Barang</td>
            <td class="td-titikdua">:</td>
            <td>{{ $pengembalian->peminjamanBarang->kode_barang ?? '-' }}</td>
        </tr>
        <tr>
            <td>Nama Barang</td>
            <td class="td-titikdua">:</td>
            <td>{{ $pengembalian->peminjamanBarang->nama_barang ?? '-' }}</td>
        </tr>
        <tr>
            <td>Merek/Type</td>
            <td class="td-titikdua">:</td>
            <td>{{ $pengembalian->peminjamanBarang->merek ?? '-' }}</td>
        </tr>
        <tr>
            <td>NUP</td>
            <td class="td-titikdua">:</td>
            <td>{{ $pengembalian->peminjamanBarang->nup ?? '-' }}</td>
        </tr>
        <tr>
            <td>Jumlah</td>
            <td class="td-titikdua">:</td>
            <td>{{ $pengembalian->jumlah_dikembalikan ?? '0' }} Unit</td>
        </tr>
        <tr>
            <td>Kondisi</td>
            <td class="td-titikdua">:</td>
            <td>{{ ucwords(str_replace('-', ' ', $pengembalian->kondisi_barang)) }}</td>
        </tr>
    </table>
        
    <div class="text-justify mb-15">
        Demikian berita acara ini ditandatangani dengan sebenar-benarnya dan untuk digunakan sebagaimana mestinya.
    </div>

    <!-- TANDA TANGAN -->
    <table class="ttd-table">
        <tr>
            <td>
                <div class="font-bold">PIHAK PERTAMA</div>
                <div class="ttd-img-container">
                    @if(!empty($ttdAdmin))
                        <img src="{{ $ttdAdmin }}" alt="TTD Admin">
                    @endif
                </div>
                <div class="ttd-nama">{{ $admin->name ?? 'Wiwin Suriadi Bokingo' }}</div>
                <div class="font-bold">NIP. {{ $admin->nip ?? '198001122008101002' }}</div>
            </td>
            <td>
                <div class="font-bold">PIHAK KEDUA</div>
                <div class="ttd-img-container">
                    @if(!empty($ttdPeminjam))
                        <img src="{{ $ttdPeminjam }}" alt="TTD Peminjam">
                    @endif
                </div>
                <div class="ttd-nama">{{ $pengembalian->user->name ?? '.......................................' }}</div>
                <div class="font-bold">NIP. {{ $pengembalian->user->nip ?? '.......................................' }}</div>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-top: 15px;">
                <div class="font-bold">Mengetahui,</div>
                <div class="font-bold">Kuasa Pengguna Barang</div>
                <div class="ttd-img-container">
                    @if(!empty($ttdKepala))
                        <img src="{{ $ttdKepala }}" alt="TTD Kepala">
                    @endif
                </div>
                <div class="ttd-nama">{{ $kepala->name ?? 'Rudi Syaifullah, S. SI,M,M.' }}</div>
                <div class="font-bold">NIP. {{ $kepala->nip ?? '197606272003121002' }}</div>
            </td>
        </tr>
    </table>

</body>
</html>
