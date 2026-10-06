<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Surat Permintaan Persediaan</title>
<style>
    /* 1. PENGATURAN MARGIN KERTAS PDF */
    @page {
        size: A4 portrait;
        margin-top: 2cm;
        margin-bottom: 2cm;
        margin-left: 2cm;
        margin-right: 2cm;
    }

    /* 2. RESET BODY & PEMADATAN TYPOGRAPHY */
    body {
        margin: 0;
        padding: 0;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 9.5pt;
        line-height: 1.2;
        color: #000;
        background-color: transparent;
    }

    /* 3. KOP SURAT */
    .kop-surat {
        margin-bottom: 8px;
        width: 100%;
    }
    .kop-surat img {
        width: 100%; 
        height: auto;
        display: block;
    }

    /* 4. TIPOGRAFI & SPACING */
    .text-center { text-align: center; }
    .text-justify { text-align: justify; }
    .font-bold { font-weight: bold; }
    
    .mb-5 { margin-bottom: 4px; }
    .mb-10 { margin-bottom: 8px; }

    .judul-surat {
        font-size: 10.5pt;
        margin-bottom: 2px;
        text-decoration: underline;
        font-weight: bold;
    }

    /* 5. TABEL IDENTITAS & BARANG */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 6px;
    }
    td {
        vertical-align: top;
        padding: 1px 0;
    }
    .td-label { width: 110px; }
    .td-titikdua { width: 15px; text-align: center; }

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

    /* 6. TABEL TANDA TANGAN */
    .ttd-table {
        width: 100%;
        text-align: center;
        margin-top: 10px;
        page-break-inside: avoid; 
    }
    .ttd-table td {
        width: 50%;
        padding: 0;
        vertical-align: bottom;
    }
    .ttd-img-container {
        height: 45px;
        margin: 2px 0;
    }
    .ttd-img-container img {
        max-height: 45px;
        max-width: 100px;
    }
    .ttd-nama {
        font-weight: bold;
        text-decoration: underline;
        margin-bottom: 1px;
    }

    /* 7. FOOTER */
    .footer {
        position: fixed;
        bottom: -1.2cm; 
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
        <img src="{{ storage_path('app/public/kop_surat.png') }}" alt="Kop Surat BPMP Provinsi Gorontalo"> 
    </div>

    <!-- JUDUL -->
    <div class="text-center mb-10">
        <div class="judul-surat">SURAT PERMINTAAN PERSEDIAAN</div>
        @php
            // Format ID jadi 3 digit (contoh: 1 -> 001, 25 -> 025)
            $nomor_urut = str_pad($permintaan->id, 3, '0', STR_PAD_LEFT);
            
            // Ambil tahun dari tanggal permintaan dibuat
            $tahun_surat = \Carbon\Carbon::parse($tanggalSurat ?? $permintaan->tanggal_penerimaan ?? $permintaan->created_at)->format('Y');
        @endphp
        <div>No: {{ $nomor_urut }}/BA.PP/693228/2026{{ date('Y') }}</div>
    </div>

    <!-- PEMBUKA -->
    <div class="text-justify mb-5">
        Yang bertanda tangan di bawah ini:
    </div>

    <!-- IDENTITAS PEMOHON (Ditarik dari relasi $permintaan->user) -->
    <table>
        <tr>
            <td class="td-label">Nama</td>
            <td class="td-titikdua">:</td>
            <td>{{ $peminjam->nama_lengkap ?? $peminjam->name ?? '-' }}</td>
        </tr>
        <tr>
            <td>NIP</td>
            <td class="td-titikdua">:</td>
            <td>{{ $peminjam->nip ?? '-' }}</td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td class="td-titikdua">:</td>
            <td>{{ $peminjam->jabatan ?? '-' }}</td>
        </tr>
        <tr>
            <td>Unit Kerja</td>
            <td class="td-titikdua">:</td>
            <td>Balai Penjaminan Mutu Pendidikan Provinsi Gorontalo</td>
        </tr>
    </table>

    <!-- PARAGRAF TRANSISI -->
    <div class="text-justify mb-5">
        Dengan ini mengajukan permintaan persediaan dengan rincian sebagai berikut:
    </div>

    <!-- DATA BARANG (TABEL DAFTAR BARANG) -->
    <table class="table-list-barang">
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th>Nama Barang</th>
                <th style="width: 110px;">Kode Barang</th>
                <th style="width: 70px;">Satuan</th>
                <th style="width: 70px;">Jumlah Diminta</th>
                <th style="width: 70px;">Jumlah Disetujui</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($permintaan->items) && $permintaan->items->count() > 0)
                @foreach($permintaan->items as $idx => $it)
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td>{{ $it->nama_barang }}</td>
                        <td class="text-center">{{ $it->kode_barang }}</td>
                        <td class="text-center">{{ $it->satuan ?? ($it->persediaan->satuan ?? 'Unit') }}</td>
                        <td class="text-center">{{ $it->jumlah_diminta }}</td>
                        <td class="text-center">{{ $permintaan->status === 'pending' ? '-' : ($permintaan->jumlah_disetujui !== null ? $it->jumlah_diminta : '-') }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td class="text-center">1</td>
                    <td>{{ $permintaan->persediaan->nama_barang ?? $permintaan->nama_barang ?? '-' }}</td>
                    <td class="text-center">{{ $permintaan->kode_barang ?? '-' }}</td>
                    <td class="text-center">{{ $permintaan->persediaan->satuan ?? $permintaan->satuan ?? 'Unit' }}</td>
                    <td class="text-center">{{ $permintaan->jumlah_diminta ?? '0' }}</td>
                    <td class="text-center">{{ $permintaan->status === 'pending' ? '-' : ($permintaan->jumlah_disetujui ?? $permintaan->jumlah_diminta ?? '-') }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <table style="width: 100%; margin-bottom: 5px; font-size: 9pt;">
        <tr>
            <td style="width: 75px; font-weight: bold;">Peruntukan</td>
            <td style="width: 15px; text-align: center;">:</td>
            <td>{{ $permintaan->tujuan_penggunaan ?? '-' }}</td>
        </tr>
    </table>
        
    <div class="text-justify mb-5">
        Persediaan tersebut diperlukan untuk keperluan operasional dan akan digunakan sesuai dengan peruntukannya.
    </div>

    <div class="text-justify mb-10">
        Demikian permintaan ini kami sampaikan, atas perhatian dan persetujuan Bapak/Ibu, kami ucapkan terima kasih.
    </div>

    <!-- TANGGAL SURAT -->
    @php
        // Tanggal berita acara mengikuti tanggal penerimaan yang dipilih saat persetujuan.
        // Data lama tetap memakai tanggal persetujuan/perubahan terakhir.
        $tgl = $tanggalSurat ?? $permintaan->tanggal_penerimaan ?? $permintaan->updated_at ?? $permintaan->created_at ?? now();
        $tanggal = \Carbon\Carbon::parse($tgl)->format('j');
        $bulan = \Carbon\Carbon::parse($tgl)->locale('id')->translatedFormat('F');
        $tahun = \Carbon\Carbon::parse($tgl)->format('Y');
    @endphp
    <div style="text-align: right; margin-bottom: 5px; margin-right: 15px;">
        Gorontalo, {{ $tanggal }} {{ $bulan }} {{ $tahun }}
    </div>

    <!-- TANDA TANGAN -->
    <table class="ttd-table">
        <tr>
            <td>
                <div>Pengadministrasi BMN,</div>
                <div class="ttd-img-container">
                    @if(!empty($ttdAdmin))
                        <img src="{{ $ttdAdmin }}" alt="TTD Admin">
                    @endif
                </div>
                <div class="ttd-nama">{{ $admin->nama_lengkap ?? $admin->name ?? 'Admin Persediaan' }}</div>
                <div>NIP. {{ $admin->nip ?? '-' }}</div>
            </td>
            <td>
                <div>Pemohon,</div>
                <div class="ttd-img-container">
                    @if(!empty($ttdPeminjam))
                        <img src="{{ $ttdPeminjam }}" alt="TTD Pemohon">
                    @endif
                </div>
                <div class="ttd-nama">{{ $peminjam->nama_lengkap ?? $peminjam->name ?? '-' }}</div>
                <div>NIP. {{ $peminjam->nip ?? '-' }}</div>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-top: 15px;">
                <div>Mengetahui,</div>
                <div class="font-bold">Kapala Sub Bagian Umum</div>
                <div class="ttd-img-container">
                    @if(!empty($ttdKasubag))
                        <img src="{{ $ttdKasubag }}" alt="TTD Kasubag">
                    @endif
                </div>
                <div class="ttd-nama">{{ $kasubag->nama_lengkap ?? $kasubag->name ?? 'Kasubag Umum' }}</div>
                <div>NIP. {{ $kasubag->nip ?? '-' }}</div>
            </td>
        </tr>
    </table>

    <!-- FOOTER -->
    <div class="footer">
        Dokumen ini digenerate secara otomatis oleh Sistem Inventaris BPMP Provinsi Gorontalo<br>
        Dicetak pada: {{ $tanggalCetak ?? now()->translatedFormat('d F Y, H:i') }} WITA
    </div>

</body>
</html>
