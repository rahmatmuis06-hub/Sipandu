<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Berita Acara Serah Terima Barang Habis Pakai</title>
<style>
    /* 1. PENGATURAN MARGIN KERTAS PDF (A4 PORTRAIT) */
    @page {
        size: A4 portrait;
        margin-top: 1.2cm;
        margin-bottom: 1.2cm;
        margin-left: 2cm;
        margin-right: 2cm;
    }

    /* 2. BODY & FONT */
    body {
        margin: 0;
        padding: 0;
        font-family: 'Times New Roman', Times, serif;
        font-size: 11pt;
        line-height: 1.35;
        color: #000;
        background-color: transparent;
    }

    /* 3. KOP SURAT */
    .kop-surat {
        margin-bottom: 14px;
        width: 100%;
    }
    .kop-surat img {
        width: 100%; 
        height: auto;
        display: block;
    }

    /* 4. TIPOGRAFI & UMUM */
    .text-center { text-align: center; }
    .text-justify { text-align: justify; }
    .font-bold { font-weight: bold; }
    
    .judul-surat {
        font-size: 12pt;
        font-weight: bold;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .nomor-surat {
        font-size: 11pt;
        font-weight: bold;
        margin-top: 4px;
    }

    /* 5. TABEL IDENTITAS */
    .table-identitas {
        width: 100%;
        border-collapse: collapse;
        margin-top: 6px;
        margin-bottom: 12px;
    }
    .table-identitas td {
        vertical-align: top;
        padding: 2px 0;
        font-size: 11pt;
    }
    .td-label {
        width: 110px;
    }
    .td-colon {
        width: 20px;
        text-align: center;
    }

    /* 6. TABEL BARANG */
    .table-barang {
        width: 100%;
        border-collapse: collapse;
        margin-top: 8px;
        margin-bottom: 14px;
        font-size: 10pt;
    }
    .table-barang th, .table-barang td {
        border: 1px solid #000;
        padding: 5px 6px;
        vertical-align: middle;
    }
    .table-barang th {
        font-weight: bold;
        text-align: center;
        background-color: transparent;
    }

    /* 7. TABEL TANDA TANGAN */
    .ttd-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
        page-break-inside: avoid;
        font-size: 11pt;
    }
    .ttd-table td {
        vertical-align: top;
        padding: 0;
        text-align: center;
    }
    .ttd-space {
        height: 60px;
        margin: 2px 0;
    }
    .ttd-space img {
        max-height: 58px;
        max-width: 120px;
    }
</style>
</head>
<body>

    @php
        // NOMOR SURAT & PENANGGALAN
        $tahun_surat = \Carbon\Carbon::parse($tanggalSurat ?? $permintaan->tanggal_penerimaan ?? $permintaan->created_at ?? now())->format('Y');
        $nomor_urut = !empty($permintaan->nomor_surat) 
            ? $permintaan->nomor_surat 
            : (!empty($permintaan->id) ? str_pad($permintaan->id, 3, '0', STR_PAD_LEFT) : null);

        $tgl = $tanggalSurat ?? $permintaan->tanggal_penerimaan ?? $permintaan->updated_at ?? $permintaan->created_at;
        $tanggalTeks = $tgl ? (\Carbon\Carbon::parse($tgl)->locale('id')->isoFormat('D MMMM Y')) : '............';

        $peminjam = $peminjam ?? $permintaan->user;
        $admin = $admin ?? $permintaan->reviewedBy ?? \App\Models\User::where('role', 'adminpersediaan')->first();
        $kasubag = $kasubag ?? $permintaan->approvedByKasubag ?? \App\Models\User::where('role', 'kasubag')->first();

        $ttdPeminjam = $ttdPeminjam ?? $peminjam?->signature_base64;
        $ttdAdmin = $ttdAdmin ?? $admin?->signature_base64;
        $ttdKasubag = $ttdKasubag ?? $kasubag?->signature_base64;
    @endphp

    {{-- KOP SURAT --}}
    <div class="kop-surat">
        <img src="{{ storage_path('app/public/kop_surat.png') }}" alt="Kop Surat">
    </div>

    {{-- JUDUL DAN NOMOR SURAT --}}
    <div class="text-center" style="margin-top: 12px; margin-bottom: 18px;">
        <div class="judul-surat">BERITA ACARA SERAH TERIMA BARANG HABIS PAKAI</div>
        <div class="nomor-surat">
            Nomor : &nbsp;&nbsp;&nbsp;&nbsp;{{ $nomor_urut ?? '        ' }}&nbsp;&nbsp;&nbsp;&nbsp;/BAST/693228/{{ $tahun_surat }}
        </div>
    </div>

    {{-- PEMBUKA --}}
    <div style="margin-bottom: 8px;">
        Yang bertanda tangan tangan dibawah ini :
    </div>

    {{-- IDENTITAS PEMOHON --}}
    <table class="table-identitas">
        <tr>
            <td class="td-label">Nama</td>
            <td class="td-colon">:</td>
            <td>{{ $peminjam->nama_lengkap ?? $peminjam->name ?? '' }}</td>
        </tr>
        <tr>
            <td class="td-label">NIP</td>
            <td class="td-colon">:</td>
            <td>{{ $peminjam->nip ?? '' }}</td>
        </tr>
        <tr>
            <td class="td-label">Jabatan</td>
            <td class="td-colon">:</td>
            <td>{{ $peminjam->jabatan ?? '' }}</td>
        </tr>
        <tr>
            <td class="td-label">Unit Kerja</td>
            <td class="td-colon">:</td>
            <td>Balai Penjaminan Mutu Pendidikan Provinsi Gorontalo</td>
        </tr>
    </table>

    {{-- PARAGRAF PERNYATAAN --}}
    <div class="text-justify" style="margin-bottom: 10px;">
        Dengan ini telah mengajukan permintaan dan menerima barang habis pakai dengan rincian sebagai berikut :
    </div>

    {{-- TABEL BARANG --}}
    <table class="table-barang">
        <thead>
            <tr>
                <th style="width: 32px;">No.</th>
                <th>Nama Barang</th>
                <th style="width: 85px;">Kode Barang</th>
                <th style="width: 65px;">Satuan</th>
                <th style="width: 70px;">Jumlah Diminta</th>
                <th style="width: 70px;">Jumlah Disetujui</th>
                <th style="width: 100px;">Peruntukan</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($permintaan->items) && $permintaan->items->count() > 0)
                @foreach($permintaan->items as $idx => $it)
                    <tr>
                        <td style="text-align: center;">{{ $idx + 1 }}</td>
                        <td>{{ $it->nama_barang ?? ($it->persediaan->nama_barang ?? '-') }}</td>
                        <td style="text-align: center;">{{ $it->kode_barang ?? ($it->persediaan->kode_barang ?? '-') }}</td>
                        <td style="text-align: center;">{{ $it->satuan ?? ($it->persediaan->satuan ?? 'buah') }}</td>
                        <td style="text-align: center;">{{ $it->jumlah_diminta ?? '0' }}</td>
                        <td style="text-align: center;">
                            {{ $it->jumlah_disetujui !== null ? $it->jumlah_disetujui : (in_array($permintaan->status, ['disetujui', 'selesai']) ? $it->jumlah_diminta : '-') }}
                        </td>
                        <td>{{ $it->peruntukan ?? ($permintaan->tujuan_penggunaan ?? '-') }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td style="text-align: center;">1</td>
                    <td>{{ $permintaan->persediaan->nama_barang ?? $permintaan->nama_barang ?? '-' }}</td>
                    <td style="text-align: center;">{{ $permintaan->kode_barang ?? '-' }}</td>
                    <td style="text-align: center;">{{ $permintaan->persediaan->satuan ?? $permintaan->satuan ?? 'buah' }}</td>
                    <td style="text-align: center;">{{ $permintaan->jumlah_diminta ?? '0' }}</td>
                    <td style="text-align: center;">
                        {{ $permintaan->jumlah_disetujui !== null ? $permintaan->jumlah_disetujui : (in_array($permintaan->status, ['disetujui', 'selesai']) ? $permintaan->jumlah_diminta : '-') }}
                    </td>
                    <td>{{ $permintaan->tujuan_penggunaan ?? '-' }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    {{-- PARAGRAF PENUTUP --}}
    <div class="text-justify" style="margin-top: 12px; margin-bottom: 20px;">
        Demikian Berita Acara ini dibuat dengan benar dan digunakan sebagaimana mestinya.
    </div>

    {{-- TANDA TANGAN --}}
    <table class="ttd-table">
        <tr>
            <td style="width: 50%; text-align: center;">
                <div>Pengelola Persediaan</div>
                <div class="ttd-space">
                    @if(!empty($ttdAdmin))
                        <img src="{{ $ttdAdmin }}" alt="TTD Pengelola Persediaan">
                    @endif
                </div>
                <div>{{ !empty($admin->nama_lengkap) ? $admin->nama_lengkap : (!empty($admin->name) ? $admin->name : '......................') }}</div>
                <div>NIP. {{ $admin->nip ?? '......................' }}</div>
            </td>
            <td style="width: 50%; text-align: center;">
                <div>Gorontalo, {{ $tanggalTeks }}</div>
                <div>Pemohon</div>
                <div class="ttd-space">
                    @if(!empty($ttdPeminjam))
                        <img src="{{ $ttdPeminjam }}" alt="TTD Pemohon">
                    @endif
                </div>
                <div>{{ !empty($peminjam->nama_lengkap) ? $peminjam->nama_lengkap : (!empty($peminjam->name) ? $peminjam->name : '......................') }}</div>
                <div>NIP. {{ $peminjam->nip ?? '......................' }}</div>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center; padding-top: 25px;">
                <div>Mengetahui :</div>
                <div>Kasubbag Umum</div>
                <div class="ttd-space">
                    @if(!empty($ttdKasubag))
                        <img src="{{ $ttdKasubag }}" alt="TTD Kasubbag Umum">
                    @endif
                </div>
                <div>{{ !empty($kasubag->nama_lengkap) ? $kasubag->nama_lengkap : (!empty($kasubag->name) ? $kasubag->name : 'Ardin, S.E,M.E') }}</div>
                <div>NIP. {{ $kasubag->nip ?? '197810242002121001' }}</div>
            </td>
        </tr>
    </table>

</body>
</html>
