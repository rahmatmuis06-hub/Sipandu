<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Berita Acara Pengembalian Kendaraan - {{ $pengembalian->id }}</title>
    <style>
        /* KOMPRESI EKSTREM UNTUK 1 HALAMAN */
        @page {
            size: A4 portrait;
            margin: 1cm 1.5cm; /* Atas-Bawah 1cm, Kiri-Kanan 1.5cm */
        }
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9pt; /* Ukuran font diperkecil */
            line-height: 1.1; /* Spasi antar baris sangat dirapatkan */
            color: #000;
        }
        .text-center { text-align: center; }
        .text-justify { text-align: justify; }
        .font-bold { font-weight: bold; }
        .italic { font-style: italic; }
        
        .mb-2 { margin-bottom: 2px; }
        .mb-5 { margin-bottom: 3px; }
        .mb-10 { margin-bottom: 5px; }
        .mb-15 { margin-bottom: 10px; }

        .kop-surat { width: 100%; margin-bottom: 10px; }
        .kop-surat img { width: 100%; height: auto; max-height: 2cm; object-fit: contain; }

        .judul-surat { font-size: 10pt; font-weight: normal; margin-bottom: 2px; }

        table { width: 100%; border-collapse: collapse; }
        td { vertical-align: top; padding: 0.5px 0; }
        .td-label { width: 160px; }
        .td-titikdua { width: 15px; text-align: center; }

        .table-pihak { width: 95%; margin-left: 5%; margin-bottom: 2px; }
        .table-barang { width: 100%; margin-bottom: 10px; }

        .ttd-table { width: 100%; text-align: center; margin-top: 15px; page-break-inside: avoid; }
        .ttd-table td { width: 50%; padding: 0px; vertical-align: top; }
        .ttd-img-container { height: 40px; margin: 1px 0; display: block; }
        .ttd-img-container img { max-height: 40px; max-width: 100px; }
        .ttd-nama { font-weight: bold; }
    </style>
</head>
<body>
    @php
        function terbilang($angka) {
            $angka = abs($angka);
            $baca = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
            $terbilang = "";
            if ($angka < 12) { $terbilang = " " . $baca[$angka]; }
            else if ($angka < 20) { $terbilang = terbilang($angka - 10) . " Belas"; }
            else if ($angka < 100) { $terbilang = terbilang($angka / 10) . " Puluh" . terbilang($angka % 10); }
            else if ($angka < 200) { $terbilang = " Seratus" . terbilang($angka - 100); }
            else if ($angka < 1000) { $terbilang = terbilang($angka / 100) . " Ratus" . terbilang($angka % 100); }
            else if ($angka < 2000) { $terbilang = " Seribu" . terbilang($angka - 1000); }
            else if ($angka < 1000000) { $terbilang = terbilang($angka / 1000) . " Ribu" . terbilang($angka % 1000); }
            return trim($terbilang);
        }

        $tgl = $pengembalian->tanggal_pengembalian_aktual ?? now();
        $hari = \Carbon\Carbon::parse($tgl)->locale('id')->isoFormat('dddd');
        $tgl_angka = \Carbon\Carbon::parse($tgl)->format('j');
        $bulan = \Carbon\Carbon::parse($tgl)->locale('id')->isoFormat('MMMM');
        $tahun_angka = \Carbon\Carbon::parse($tgl)->format('Y');

        $nomor_urut = str_pad($pengembalian->id, 3, '0', STR_PAD_LEFT);
    @endphp

    <div class="kop-surat">
        <img src="{{ public_path('storage/kop_surat.png') }}" alt="Kop Surat BPMP"> 
    </div>

    <div class="text-center mb-15">
        <div class="judul-surat">BERITA ACARA PENGEMBALIAN PEMINJAMAN</div>
        <div>No: {{ $nomor_urut }}/LK.01.02/693228/{{ $tahun_angka }}</div>
    </div>

    <div class="text-justify mb-10">
        Pada hari ini <span class="italic font-bold">{{ $hari }}</span> tanggal <span class="italic font-bold">{{ ucwords(terbilang($tgl_angka)) }}</span> bulan <span class="italic font-bold">{{ $bulan }}</span> tahun <span class="italic font-bold">{{ ucwords(terbilang($tahun_angka)) }}</span> yang bertanda tangan dibawah ini :
    </div>

    <table class="table-pihak">
        <tr>
            <td style="width: 20px;">1.</td>
            <td class="td-label">Nama</td>
            <td class="td-titikdua">:</td>
            <td>{{ $admin->name ?? 'Wiwin S. Bokingo, S.H' }}</td>
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
            <td>Balai Penjaminan Mutu Pendidikan Provinsi Gorontalo</td>
        </tr>
        <tr>
            <td></td>
            <td>Instansi</td>
            <td class="td-titikdua">:</td>
            <td>BPMP Provinsi Gorontalo</td>
        </tr>
    </table>
    <div class="mb-5" style="margin-left: 5%;">Selanjutnya disebut sebagai PIHAK PERTAMA</div>

    <table class="table-pihak">
        <tr>
            <td style="width: 20px;">2.</td>
            <td class="td-label">Nama</td>
            <td class="td-titikdua">:</td>
            <td>{{ $pengembalian->user->name ?? 'Rudi Syaifullah, S.Si., M.M' }}</td>
        </tr>
        <tr>
            <td></td>
            <td>NIP</td>
            <td class="td-titikdua">:</td>
            <td>{{ $pengembalian->user->nip ?? '197606272003121002' }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Jabatan</td>
            <td class="td-titikdua">:</td>
            <td>{{ $pengembalian->user->jabatan ?? 'Kepala' }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Instansi</td>
            <td class="td-titikdua">:</td>
            <td>Balai Penjaminan Mutu Pendidikan Provinsi Gorontalo</td>
        </tr>
    </table>
    <div class="mb-10" style="margin-left: 5%;">Selanjutnya disebut sebagai PIHAK KEDUA</div>

    <div class="text-justify mb-10">
        PIHAK KEDUA telah menyerahkan kepada PIHAK PERTAMA Barang Milik Negara (BMN) sesuai spesifikasi sebagai berikut :
    </div>

    <table class="table-barang">
        <tr>
            <td class="td-label">Jenis Kendaraan</td>
            <td class="td-titikdua">:</td>
            <td>Kendaraan Bermotor Roda Empat</td>
        </tr>
        <tr>
            <td>Kode Barang</td>
            <td class="td-titikdua">:</td>
            <td>{{ $pengembalian->peminjamanKendaraan->kode_barang ?? '-' }}</td>
        </tr>
        <tr>
            <td>Nama Barang /Merek/Type</td>
            <td class="td-titikdua">:</td>
            <td>{{ $pengembalian->peminjamanKendaraan->nama_barang ?? '-' }} / {{ $pengembalian->peminjamanKendaraan->merek ?? '-' }}</td>
        </tr>
        <tr>
            <td>NUP</td>
            <td class="td-titikdua">:</td>
            <td>{{ $pengembalian->peminjamanKendaraan->nup ?? '-' }}</td>
        </tr>
        <tr>
            <td>Jumlah</td>
            <td class="td-titikdua">:</td>
            <td>{{ $pengembalian->peminjamanKendaraan->jumlah ?? '1' }} (satu) unit</td>
        </tr>
        <tr>
            <td>Nomor Polisi/BPKB</td>
            <td class="td-titikdua">:</td>
            <td>{{ $pengembalian->peminjamanKendaraan->nomor_polisi_saat_pinjam ?? '-' }} / {{ $pengembalian->peminjamanKendaraan->no_bpkb_saat_pinjam ?? '-' }}</td>
        </tr>
        <tr>
            <td>Nomor Rangka/Mesin</td>
            <td class="td-titikdua">:</td>
            <td>{{ $pengembalian->peminjamanKendaraan->nomor_rangka_saat_pinjam ?? '-' }} / {{ $pengembalian->peminjamanKendaraan->nomor_mesin_saat_pinjam ?? '-' }}</td>
        </tr>
        <tr>
            <td>Kondisi</td>
            <td class="td-titikdua">:</td>
            <td>{{ ucwords(str_replace('-', ' ', $pengembalian->kondisi_kendaraan ?? 'Baik')) }}</td>
        </tr>
    </table>
        
    <div class="text-justify mb-10">
        Demikian berita acara ini ditandatangani dengan sebenar-benarnya dan untuk digunakan sebagaimana mestinya.
    </div>

    <table class="ttd-table">
        <tr>
            <td>
                <div class="font-bold">PIHAK PERTAMA</div>
                <div class="ttd-img-container">
                    @if(!empty($ttdAdmin))
                        <img src="{{ $ttdAdmin }}" alt="TTD Admin">
                    @endif
                </div>
                <div class="ttd-nama">{{ $admin->name ?? 'Wiwin S. Bokingo, S.H' }}</div>
                <div class="font-bold">NIP. {{ $admin->nip ?? '198001122008101002' }}</div>
            </td>
            <td>
                <div class="font-bold">PIHAK KEDUA</div>
                <div class="ttd-img-container">
                    @if(!empty($ttdPeminjam))
                        <img src="{{ $ttdPeminjam }}" alt="TTD Peminjam">
                    @endif
                </div>
                <div class="ttd-nama">{{ $pengembalian->user->name ?? 'Rudi Syaifullah, S.Si., M.M' }}</div>
                <div class="font-bold">NIP. {{ $pengembalian->user->nip ?? '197606272003121002' }}</div>
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
                <div class="ttd-nama">{{ $kepala->name ?? 'Rudi Syaifullah, S.Si., M.M.' }}</div>
                <div class="font-bold">NIP. {{ $kepala->nip ?? '197606272003121002' }}</div>
            </td>
        </tr>
    </table>
</body>
</html>