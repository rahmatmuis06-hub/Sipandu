<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Berita Acara Peminjaman BMN - {{ $peminjaman->id }}</title>
    <style>
        /* 1. PENGATURAN HALAMAN */
        @page {
            size: A4 portrait;
            /* Margin diperkecil secara maksimal agar muat 1 halaman */
            margin-top: 1.5cm;
            margin-bottom: 1cm;
            margin-left: 1.5cm;
            margin-right: 1.5cm;
        }

        /* 2. TYPOGRAPHY */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9.5pt; /* Diperkecil sedikit agar hemat ruang vertikal */
            line-height: 1.15; /* Spasi baris dirapatkan */
            color: #000;
        }

        .text-center { text-align: center; }
        .text-justify { text-align: justify; }
        .font-bold { font-weight: bold; }
        .italic { font-style: italic; }
        
        .mb-2 { margin-bottom: 2px; }
        .mb-5 { margin-bottom: 5px; }
        .mb-10 { margin-bottom: 6px; } /* Jarak antar paragraf sangat dirapatkan */
        .mb-15 { margin-bottom: 10px; }

        /* 3. KOP SURAT */
        .kop-surat {
            width: 100%;
            margin-bottom: 10px; /* Jarak kop ke judul dirapatkan */
        }
        .kop-surat img {
            width: 100%; 
            height: auto;
            max-height: 2.5cm; /* Menjaga agar gambar kop tidak terlalu tinggi */
            object-fit: contain;
        }

        .judul-surat {
            font-size: 11pt;
            font-weight: normal;
            margin-bottom: 0px;
        }

        /* 4. TABEL DATA */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            vertical-align: top;
            padding: 0.5px 0; /* Menghilangkan padding agar rapat */
        }
        .td-label { width: 120px; }
        .td-titikdua { width: 15px; text-align: center; }

        /* TABEL PIHAK & BARANG */
        .table-pihak {
            width: 95%;
            margin-left: 5%;
            margin-bottom: 3px;
        }
        
        .table-barang {
            width: 100%;
            margin-bottom: 5px;
        }

        .table-list-barang {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
            margin-bottom: 6px;
            font-size: 8.5pt;
        }
        .table-list-barang th, .table-list-barang td {
            border: 1px solid #333;
            padding: 3px 5px;
            vertical-align: middle;
        }
        .table-list-barang th {
            background-color: #f1f5f9;
            font-weight: bold;
            text-align: center;
        }

        /* 5. LIST KETENTUAN */
        .ketentuan-list {
            margin-top: 3px;
            margin-bottom: 6px;
            padding-left: 20px;
            text-align: justify;
        }
        .ketentuan-list li {
            margin-bottom: 1.5px;
        }

        /* 6. TANDA TANGAN */
        .ttd-table {
            width: 100%;
            text-align: center;
            margin-top: 10px;
            page-break-inside: avoid; /* Memaksa tabel ttd tidak terpotong */
        }
        .ttd-table td {
            width: 50%;
            padding: 0px;
            vertical-align: top;
        }
        .ttd-img-container {
            height: 45px; /* Tinggi maksimal gambar TTD dikurangi */
            margin: 2px 0;
            display: block;
        }
        .ttd-img-container img {
            max-height: 45px;
            max-width: 100px;
        }
        .ttd-nama {
            font-weight: bold;
        }

        /* 7. FOOTER */
        .footer {
            position: fixed;
            bottom: -0.5cm; 
            left: 0;
            right: 0;
            font-size: 7.5pt; 
            color: #555;
            text-align: center;
            border-top: 1px solid #ccc;
            padding-top: 4px;
        }
    </style>
</head>
<body>

    {{-- KOP SURAT --}}
    <div class="kop-surat">
        <img src="{{ public_path('storage/kop_surat.png') }}" alt="Kop Surat BPMP"> 
    </div>

    <!-- JUDUL & NOMOR SURAT -->
    <div class="text-center mb-15">
        <div class="judul-surat">BERITA ACARA PEMINJAMAN BMN</div>
        @php
            $nomor_urut = str_pad($peminjaman->id, 3, '0', STR_PAD_LEFT);
            $tahun_surat = \Carbon\Carbon::parse($tanggalSurat ?? $peminjaman->created_at)->format('Y');
        @endphp
        <div>No: {{ $nomor_urut }}/LK.01.02/693228/{{ $tahun_surat }}</div>
    </div>

    <!-- PEMBUKA -->
    <div class="text-justify mb-10">
        @php
          $tgl = $tanggalSurat ?? $peminjaman->created_at ?? now();
          $hari = \Carbon\Carbon::parse($tgl)->locale('id')->isoFormat('dddd');
          $tanggal_teks = \Carbon\Carbon::parse($tgl)->locale('id')->isoFormat('D');
          $bulan = \Carbon\Carbon::parse($tgl)->locale('id')->isoFormat('MMMM');
          $tahun = \Carbon\Carbon::parse($tgl)->locale('id')->isoFormat('Y');
        @endphp
        Pada hari ini <span class="italic font-bold">{{ $hari }}</span> tanggal <span class="italic font-bold">{{ $tanggal_teks }}</span> bulan <span class="italic font-bold">{{ $bulan }}</span> tahun <span class="italic font-bold">{{ $tahun }}</span> bertempat di Balai Penjaminan Mutu Pendidikan Provinsi Gorontalo, yang bertanda tangan dibawah ini :
    </div>

    <!-- IDENTITAS PIHAK PERTAMA -->
    <table class="table-pihak">
        <tr>
            <td style="width: 20px;">1.</td>
            <td class="td-label">Nama</td>
            <td class="td-titikdua">:</td>
            <td>{{ $admin->name }}</td>
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
            <td>{{ $peminjaman->user->name }}</td>
        </tr>
        <tr>
            <td></td>
            <td>NIP</td>
            <td class="td-titikdua">:</td>
            <td>{{ $peminjaman->user->nip ?? '-' }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Jabatan</td>
            <td class="td-titikdua">:</td>
            <td>{{ $peminjaman->user->jabatan ?? '-' }}</td>
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
        <b>PIHAK PERTAMA</b> telah menyerahkan kepada <b>PIHAK KEDUA</b> Barang Milik Negara (BMN) sesuai spesifikasi sebagai berikut :
    </div>

    <!-- DATA BARANG (TABEL DAFTAR BARANG) -->
    <table class="table-list-barang">
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th>Nama Barang</th>
                <th style="width: 105px;">Kode Barang</th>
                <th style="width: 55px;">NUP</th>
                <th style="width: 90px;">Merek/Type</th>
                <th style="width: 55px;">Jumlah</th>
                <th style="width: 55px;">Kondisi</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($peminjaman->items) && $peminjaman->items->count() > 0)
                @foreach($peminjaman->items as $idx => $it)
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td>{{ $it->nama_barang }}</td>
                        <td class="text-center">{{ $it->kode_barang }}</td>
                        <td class="text-center">{{ $it->nup ?? '-' }}</td>
                        <td class="text-center">{{ $it->merek ?? '-' }}</td>
                        <td class="text-center">{{ $it->jumlah }} Unit</td>
                        <td class="text-center">Baik</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td class="text-center">1</td>
                    <td>{{ $peminjaman->nama_barang }}</td>
                    <td class="text-center">{{ $peminjaman->kode_barang }}</td>
                    <td class="text-center">{{ $peminjaman->nup ?? '-' }}</td>
                    <td class="text-center">{{ $peminjaman->merek ?? '-' }}</td>
                    <td class="text-center">{{ $peminjaman->jumlah }} Unit</td>
                    <td class="text-center">Baik</td>
                </tr>
            @endif
        </tbody>
    </table>

    <table style="width: 100%; margin-bottom: 5px; font-size: 9pt;">
        <tr>
            <td style="width: 75px; font-weight: bold;">Peruntukan</td>
            <td style="width: 15px; text-align: center;">:</td>
            <td>{{ $peminjaman->deskripsi_peruntukan }}</td>
        </tr>
    </table>
        
    <div class="text-justify">
        Dengan ketentuan sebagai berikut :
        <ol class="ketentuan-list">
            <li>Jangka waktu peminjaman {{ \Carbon\Carbon::parse($peminjaman->tanggal_peminjaman)->locale('id')->isoFormat('D MMMM Y') }} sampai dengan {{ \Carbon\Carbon::parse($peminjaman->tanggal_pengembalian)->locale('id')->isoFormat('D MMMM Y') }}.</li>
            <li><b>PIHAK KEDUA</b> wajib menggunakan BMN sesuai dengan tujuan peminjaman dan bertanggung jawab penuh terhadap keamanan dan kelengkapan BMN yang dipinjam.</li>
            <li><b>PIHAK KEDUA</b> dilarang memindahtangankan, meminjamkan kembali, atau menggunakan BMN untuk kegiatan yang bertentangan dengan peraturan perundang-undangan.</li>
            <li>BMN dapat ditarik sewaktu-waktu apabila terjadi penyimpangan, penyalahgunaan atau dibutuhkan untuk kepentingan Lembaga/Kantor.</li>
            <li>Dalam hal terjadi kerusakan atau kehilangan BMN selama masa peminjaman yang disebabkan oleh kelalaian <b>PIHAK KEDUA</b>, maka <b>PIHAK KEDUA</b> bertanggung jawab sesuai dengan ketentuan peraturan perundang-undangan yang berlaku dan wajib menyelesaikan seluruh kewajiban yang timbul akibat kelalaian tersebut.</li>
        </ol>
    </div>

    <div class="text-justify mb-5">
        Demikian berita acara ini ditandatangani dengan sebenar-benarnya dan untuk digunakan sebagaimana mestinya.
    </div>

    <!-- TANDA TANGAN -->
    <table class="ttd-table">
        <tr>
            <td>
                <div class="font-bold">PIHAK PERTAMA</div>
                <div class="ttd-img-container">
                    @if($ttdAdmin)
                        <img src="{{ $ttdAdmin }}" alt="TTD Admin">
                    @endif
                </div>
                <div class="ttd-nama">{{ $admin->name }}</div>
                <div class="font-bold">NIP. {{ $admin->nip ?? '........................' }}</div>
            </td>
            <td>
                <div class="font-bold">PIHAK KEDUA</div>
                <div class="ttd-img-container">
                    @if($ttdPeminjam)
                        <img src="{{ $ttdPeminjam }}" alt="TTD Peminjam">
                    @endif
                </div>
                <div class="ttd-nama">{{ $peminjaman->user->name }}</div>
                <div class="font-bold">NIP. {{ $peminjaman->user->nip ?? '........................' }}</div>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-top: 5px;">
                <div class="font-bold">Mengetahui,</div>
                <div class="font-bold">Kuasa Pengguna Barang</div>
                <div class="ttd-img-container">
                    @if($ttdKepala)
                        <img src="{{ $ttdKepala }}" alt="TTD Kepala">
                    @endif
                </div>
                <div class="ttd-nama">{{ $kepala->name ?? '........................' }}</div>
                <div class="font-bold">NIP. {{ $kepala->nip ?? '........................' }}</div>
            </td>
        </tr>
    </table>

    <div class="footer">
        Dokumen ini digenerate secara otomatis oleh SIPANDU BPMP Provinsi Gorontalo<br>
        Dicetak pada: {{ now()->locale('id')->isoFormat('D MMMM Y, HH:mm') }} WITA
    </div>

</body>
</html>
