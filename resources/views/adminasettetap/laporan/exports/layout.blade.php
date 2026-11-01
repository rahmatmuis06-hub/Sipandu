<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Laporan' }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 10pt; color: #333; margin: 0; padding: 10px; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h2 { margin: 0; font-size: 14pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        .header p { margin: 4px 0 0; font-size: 10pt; }
        .info-periode { margin-bottom: 15px; font-weight: bold; }
        .data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .data-table th, .data-table td { border: 1px solid #64748b; padding: 6px 8px; vertical-align: top; }
        .data-table th { background-color: #f1f5f9; font-weight: bold; text-align: center; font-size: 9pt; text-transform: uppercase; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .section-title { font-size: 12pt; font-weight: bold; margin-bottom: 10px; margin-top: 20px; color: #1e40af; border-bottom: 1px solid #1e40af; padding-bottom: 5px;}
        .footer { margin-top: 40px; width: 100%; }
        .footer td { border: none; text-align: center; width: 50%; }
        .ttd-space { height: 70px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>BALAI PENJAMINAN MUTU PENDIDIKAN (BPMP)</h2>
        <h2>PROVINSI GORONTALO</h2>
        <p>Sistem Informasi Inventaris Barang Terpadu (SIPANDU)</p>
    </div>
    
    <div class="info-periode">
        Judul Laporan : {{ $title ?? '-' }}<br>
        Periode Data &nbsp;: {{ $periode ?? '-' }}
    </div>

    @yield('content')

    @php
        $signer = auth()->user();
        $ttdBase64 = $signer?->signature_base64;
    @endphp
    <table class="footer">
        <tr>
            <td></td>
            <td>
                Gorontalo, {{ \Carbon\Carbon::parse($generated_at ?? now())->locale('id')->translatedFormat('d F Y') }}<br>
                Admin Aset Tetap / Pengelola BMN
                @if($ttdBase64)
                    <div style="height: 70px; margin: 4px 0;">
                        <img src="{{ $ttdBase64 }}" alt="TTD Admin Aset Tetap" style="max-height: 66px; max-width: 180px;">
                    </div>
                @else
                    <div class="ttd-space"></div>
                @endif
                <b><u>{{ $signer->name ?? '.......................................................' }}</u></b><br>
                NIP. {{ !empty($signer?->nip) ? $signer->nip : '........................................' }}
            </td>
        </tr>
    </table>
</body>
</html>