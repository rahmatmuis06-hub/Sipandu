@extends('adminasettetap.laporan.exports.layout')

@section('content')
<table class="data-table">
    <thead>
        <tr>
            <th style="width: 4%;">No</th>
            <th style="width: 10%;">Tgl Keluar</th>
            <th style="width: 15%;">Kode Barang</th>
            <th style="width: 6%;">NUP</th>
            <th style="width: 22%;">Nama Barang</th>
            <th style="width: 12%;">Merek</th>
            <th style="width: 13%;">Nilai Perolehan</th>
            <th style="width: 10%;">Lokasi</th>
            <th style="width: 8%;">No SK / Ket</th>
        </tr>
    </thead>
    <tbody>
        @forelse($transaksi as $index => $item)
        <tr>
            <td class="text-center">{{ $index + 1 }}</td>
            <td class="text-center">{{ $item->tanggal_input ? \Carbon\Carbon::parse($item->tanggal_input)->format('d/m/Y') : '-' }}</td>
            <td class="text-center">{{ $item->kode_barang ?? '-' }}</td>
            <td class="text-center">{{ $item->nup ?? '-' }}</td>
            <td>{{ $item->nama_barang ?? '-' }}</td>
            <td>{{ $item->merek ?? '-' }}</td>
            <td class="text-right">Rp {{ number_format($item->nilai_perolehan ?? 0, 0, ',', '.') }}</td>
            <td>{{ $item->lokasi ?? '-' }}</td>
            <td>{{ $item->nomor_sk ?? ($item->keterangan ?? '-') }}</td>
        </tr>
        @empty
        <tr><td colspan="9" class="text-center">Tidak ada data transaksi keluar aset tetap pada periode ini.</td></tr>
        @endforelse
    </tbody>
    @if(isset($transaksi) && count($transaksi) > 0)
    <tfoot>
        <tr>
            <td colspan="6" class="text-right"><strong>Total Nilai Perolehan:</strong></td>
            <td class="text-right"><strong>Rp {{ number_format($transaksi->sum('nilai_perolehan'), 0, ',', '.') }}</strong></td>
            <td colspan="2"></td>
        </tr>
    </tfoot>
    @endif
</table>
@endsection