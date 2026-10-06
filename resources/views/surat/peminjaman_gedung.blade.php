<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Perjanjian Peminjaman Gedung - {{ $peminjaman->id }}</title>
    <style>
        /* 1. PENGATURAN HALAMAN */
        @page {
            size: A4 portrait;
            margin-top: 3cm;
            margin-bottom: 2cm;
            margin-left: 2.5cm;
            margin-right: 2.5cm;
        }

        /* 2. TYPOGRAPHY */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt; 
            line-height: 1.4;
            color: #000;
        }

        .text-center { text-align: center; }
        .text-justify { text-align: justify; }
        .font-bold { font-weight: bold; }
        .italic { font-style: italic; }
        .underline { text-decoration: underline; }
        
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
        }

        .judul-surat {
            font-size: 12pt;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 2px;
            text-transform: uppercase;
        }

        /* 4. TABEL DATA */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        td {
            vertical-align: top;
            padding: 3px 0;
        }
        .td-label { width: 150px; }
        .td-titikdua { width: 15px; text-align: center; }

        /* TABEL RINCIAN BIAYA */
        .tabel-rincian {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            margin-bottom: 15px;
        }
        .tabel-rincian th, .tabel-rincian td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }
        .tabel-rincian th {
            background-color: #f0f0f0;
            text-align: center;
        }

        /* 5. TANDA TANGAN */
        .ttd-table {
            width: 100%;
            text-align: center;
            margin-top: 20px;
            page-break-inside: avoid; 
        }
        .ttd-table td {
            width: 50%;
            padding: 5px;
            vertical-align: top;
        }
        .ttd-img-container {
            height: 70px;
            margin: 5px 0;
            display: block;
        }
        .ttd-img-container img {
            max-height: 70px;
            max-width: 120px;
        }
        .ttd-nama {
            font-weight: bold;
            text-decoration: underline;
        }

        /* 6. FOOTER */
        .footer {
            position: fixed;
            bottom: -1cm; 
            left: 0;
            right: 0;
            font-size: 8pt; 
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

    <div class="text-center mb-15">
        <div class="judul-surat">SURAT PERJANJIAN SEWA / PEMINJAMAN FASILITAS</div>
        @php
            $nomor_urut = str_pad($peminjaman->id, 3, '0', STR_PAD_LEFT);
            $tahun_surat = \Carbon\Carbon::parse($tanggalSurat ?? $peminjaman->tanggal_approval ?? $peminjaman->created_at)->format('Y');
        @endphp
        <div>Nomor: {{ $nomor_urut }}/SP.PP/BPMP/{{ $tahun_surat }}</div>
    </div>

    @php
        // Menggunakan tanggal disetujuinya dokumen, atau tanggal dibuat jika belum disetujui
        $tgl = $tanggalSurat ?? $peminjaman->tanggal_approval ?? $peminjaman->created_at ?? now();
        $hari = \Carbon\Carbon::parse($tgl)->locale('id')->translatedFormat('l');
        $tanggal_teks = \Carbon\Carbon::parse($tgl)->locale('id')->translatedFormat('j');
        $bulan = \Carbon\Carbon::parse($tgl)->locale('id')->translatedFormat('F');
        $tahun = \Carbon\Carbon::parse($tgl)->locale('id')->translatedFormat('Y');
    @endphp
    <div class="text-justify mb-10">
        Pada hari ini <span class="font-bold italic">{{ $hari }}</span> tanggal <span class="font-bold italic">{{ $tanggal_teks }}</span> bulan <span class="font-bold italic">{{ $bulan }}</span> tahun <span class="font-bold italic">{{ $tahun }}</span>, kami yang bertanda tangan di bawah ini:
    </div>

    <table>
        <tr>
            <td class="td-label">1. Nama</td>
            <td class="td-titikdua">:</td>
            <td class="font-bold">{{ $admin->name ?? 'Admin Sarpras / Kasubag TU' }}</td>
        </tr>
        <tr>
            <td style="padding-left: 13px;">NIP</td>
            <td class="td-titikdua">:</td>
            <td>{{ $admin->nip ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding-left: 13px;">Jabatan</td>
            <td class="td-titikdua">:</td>
            <td>{{ $admin->jabatan ?? 'Pengelola Barang Milik Negara (BMN)' }}</td>
        </tr>
        <tr>
            <td colspan="3" class="text-justify" style="padding-top: 5px;">
                Dalam hal ini bertindak untuk dan atas nama <b>Balai Penjaminan Mutu Pendidikan (BPMP) Provinsi Gorontalo</b>, yang selanjutnya disebut sebagai <b>PIHAK PERTAMA</b>.
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td class="td-label">2. Nama</td>
            <td class="td-titikdua">:</td>
            <td class="font-bold">{{ $peminjaman->nama_lengkap }}</td>
        </tr>
        <tr>
            <td style="padding-left: 13px;">NIP / NIK</td>
            <td class="td-titikdua">:</td>
            <td>{{ $peminjaman->nip_nik ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding-left: 13px;">Instansi/Lembaga</td>
            <td class="td-titikdua">:</td>
            <td>{{ $peminjaman->instansi_lembaga ?? '-' }}</td>
        </tr>
        <tr>
            <td colspan="3" class="text-justify" style="padding-top: 5px;">
                Dalam hal ini bertindak untuk dan atas nama pribadi / instansi yang bersangkutan, yang selanjutnya disebut sebagai <b>PIHAK KEDUA</b>.
            </td>
        </tr>
    </table>

    <div class="text-justify mb-10">
        PIHAK PERTAMA dan PIHAK KEDUA sepakat untuk mengadakan perjanjian sewa / peminjaman fasilitas Gedung milik BPMP Provinsi Gorontalo dengan rincian dan ketentuan sebagai berikut:
    </div>

    <table class="tabel-rincian">
        <tr>
            <th colspan="2">RINCIAN PEMINJAMAN FASILITAS</th>
        </tr>
        <tr>
            <td style="width: 35%;"><b>Nama Fasilitas / Gedung</b></td>
            <td style="width: 65%;">{{ $peminjaman->nama_fasilitas ?? $peminjaman->fasilitas }}</td>
        </tr>
        <tr>
            <td><b>Tujuan Penggunaan</b></td>
            <td>{{ $peminjaman->tujuan_penggunaan }}</td>
        </tr>
        <tr>
            <td><b>Jadwal Penggunaan</b></td>
            <td>
                {{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->translatedFormat('d M Y') }} s.d {{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->translatedFormat('d M Y') }}
                <br>
                <i>(Pukul: {{ \Carbon\Carbon::parse($peminjaman->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($peminjaman->jam_selesai)->format('H:i') }} WITA)</i>
            </td>
        </tr>
        <tr>
            <td><b>Jumlah Peserta</b></td>
            <td>{{ $peminjaman->jumlah_peserta ?? 0 }} Orang</td>
        </tr>
        <tr>
            <td><b>Total Biaya Sewa / PNBP</b></td>
            <td><b>Rp {{ number_format($peminjaman->total_pembayaran, 0, ',', '.') }}</b> 
                <br><i>(Status: {{ $peminjaman->status_pembayaran === 'lunas' ? 'LUNAS' : 'BELUM LUNAS' }})</i>
            </td>
        </tr>
    </table>
        
    <div class="text-justify mb-10">
        <b>KESEPAKATAN DAN TATA TERTIB:</b>
        <ol style="margin-top: 5px; padding-left: 20px;">
            <li class="mb-5">PIHAK KEDUA wajib melunasi pembayaran sewa fasilitas (E-Billing / PNBP) sebelum fasilitas digunakan.</li>
            <li class="mb-5">PIHAK KEDUA dilarang mengalihkan hak penggunaan fasilitas kepada pihak lain tanpa persetujuan tertulis dari PIHAK PERTAMA.</li>
            <li class="mb-5">PIHAK KEDUA bertanggung jawab penuh untuk menjaga kebersihan, ketertiban, dan keamanan fasilitas yang disewa beserta seluruh barang/alat yang ada di dalamnya.</li>
            <li>Apabila terjadi kerusakan atau kehilangan atas fasilitas/barang milik BPMP akibat kelalaian PIHAK KEDUA, maka PIHAK KEDUA wajib mengganti kerugian tersebut.</li>
        </ol>
    </div>

    <div class="text-justify mb-10" style="text-indent: 40px;">
        Demikian Surat Perjanjian Sewa/Peminjaman Fasilitas ini dibuat dan disepakati oleh kedua belah pihak dalam keadaan sadar dan tanpa paksaan dari pihak mana pun untuk dapat dipergunakan sebagaimana mestinya.
    </div>

    <div style="text-align: right; margin-right: 15px;">
        Gorontalo, {{ $tanggal_teks }} {{ $bulan }} {{ $tahun }}
    </div>

    <table class="ttd-table">
        <tr>
            <td>
                <div><b>PIHAK PERTAMA</b><br>Pengelola BMN BPMP</div>
                <div class="ttd-img-container">
                    @if(isset($ttdAdmin) && $ttdAdmin)
                        <img src="{{ $ttdAdmin }}" alt="TTD Admin">
                    @endif
                </div>
                <div class="ttd-nama">{{ $admin->name ?? 'Admin Sarpras' }}</div>
                <div>NIP. {{ $admin->nip ?? '........................' }}</div>
            </td>
            <td>
                <div><b>PIHAK KEDUA</b><br>Penyewa / Peminjam</div>
                <div class="ttd-img-container">
                    @if(isset($ttdPeminjam) && $ttdPeminjam)
                        <img src="{{ $ttdPeminjam }}" alt="TTD Peminjam">
                    @endif
                </div>
                <div class="ttd-nama">{{ $peminjaman->nama_lengkap }}</div>
                <div>NIP/NIK. {{ $peminjaman->nip_nik ?? '........................' }}</div>
            </td>
        </tr>
        
        <tr>
            <td colspan="2" style="padding-top: 15px;">
                <div>Mengetahui,</div>
                <div class="font-bold">Kepala BPMP Provinsi Gorontalo</div>
                <div class="ttd-img-container">
                    @if(isset($ttdKepala) && $ttdKepala)
                        <img src="{{ $ttdKepala }}" alt="TTD Kepala">
                    @endif
                </div>
                <div class="ttd-nama">{{ $kepala->name ?? '........................' }}</div>
                <div>NIP. {{ $kepala->nip ?? '........................' }}</div>
            </td>
        </tr>
    </table>

    <div class="footer">
        Dokumen ini diterbitkan secara otomatis oleh Sistem Informasi Manajemen Aset Terpadu (SIMASET) BPMP Gorontalo<br>
        Dicetak pada: {{ now()->translatedFormat('d F Y, H:i') }} WITA
    </div>

</body>
</html>
