@extends('adminasettetap.laporan.exports.layout')

@section('content')
<div class="section-title">A. Peminjaman Barang</div>
<table class="data-table">
    <thead>
        <tr>
            <th style="width: 5%;">No</th>
            <th style="width: 15%;">Tgl Pinjam</th>
            <th style="width: 25%;">Peminjam</th>
            <th style="width: 35%;">Barang yang Dipinjam</th>
            <th style="width: 20%;">Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($peminjaman_barang as $index => $item)
        <tr>
            <td class="text-center">{{ $index + 1 }}</td>
            <td class="text-center">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td>
            <td>{{ $item->user->name ?? '-' }}</td>
            <td>{{ $item->nama_barang ?? '-' }}</td>
            <td class="text-center">{{ strtoupper(str_replace('_', ' ', $item->status)) }}</td>
        </tr>
        @empty
        <tr><td colspan="5" class="text-center">Tidak ada data peminjaman barang.</td></tr>
        @endforelse
    </tbody>
</table>

<div class="section-title">B. Peminjaman Kendaraan</div>
<table class="data-table">
    <thead>
        <tr>
            <!-- Penyesuaian lebar kolom agar muat untuk detail -->
            <th style="width: 5%;">No</th>
            <th style="width: 15%;">Tgl Pinjam</th>
            <th style="width: 20%;">Peminjam</th>
            <th style="width: 45%;">Kendaraan & Spesifikasi</th>
            <th style="width: 15%;">Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($peminjaman_kendaraan as $index => $item)
        <tr>
            <td class="text-center">{{ $index + 1 }}</td>
            <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal_peminjaman)->format('d/m/Y') }}</td>
            <td>
                {{ $item->user->name ?? '-' }}<br>
                <span style="font-size: 0.85em; color: #555;">NIP: {{ $item->user->nip ?? '-' }}</span>
            </td>
            <td>
                <!-- Nama dan Merek Kendaraan -->
                <strong>{{ $item->nama_barang ?? '-' }} {{ $item->merek ? ' - ' . $item->merek : '' }}</strong>
                
                <!-- Detail Kendaraan dengan font lebih kecil -->
                <div style="font-size: 0.85em; color: #444; margin-top: 4px; line-height: 1.4;">
                    <strong>Nopol:</strong> {{ $item->nomor_polisi_saat_pinjam ?? '-' }} | 
                    <strong>BPKB:</strong> {{ $item->no_bpkb_saat_pinjam ?? '-' }}<br>
                    <strong>Rangka:</strong> {{ $item->nomor_rangka_saat_pinjam ?? '-' }}<br>
                    <strong>Mesin:</strong> {{ $item->nomor_mesin_saat_pinjam ?? '-' }}
                </div>
            </td>
            <td class="text-center">
                {{ strtoupper(str_replace('_', ' ', $item->status)) }}
            </td>
        </tr>
        @empty
        <tr><td colspan="5" class="text-center">Tidak ada data peminjaman kendaraan.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection