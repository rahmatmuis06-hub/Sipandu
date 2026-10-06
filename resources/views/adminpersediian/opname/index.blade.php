@extends('adminpersediian.opname.layout')
@section('content')
<a href="{{ route('adminpersediaan.data-persediaan') }}">← Data Persediaan</a>
<h1>Stok Opname Bulanan</h1>
<div class="stats">
<div class="stat"><span>Periode tercatat</span><strong>{{ $items->count() }}</strong></div>
<div class="stat"><span>Sedang dikerjakan</span><strong>{{ $items->where('status','draft')->count() }}</strong></div>
<div class="stat"><span>Sudah difinalisasi</span><strong>{{ $items->where('status','final')->count() }}</strong></div>
</div>
<p>Catat hasil hitung fisik per barang dan bandingkan dengan saldo buku. Satu opname untuk setiap bulan.</p>
<form class="box" method="POST" action="{{ route('adminpersediaan.opname.store') }}">@csrf
<h2>Mulai Pemeriksaan Baru</h2>
<label>Bulan <input type="month" name="bulan" value="{{ old('bulan',today()->format('Y-m')) }}" required></label>
<label>Tanggal pemeriksaan <input type="date" name="tanggal" value="{{ old('tanggal',today()->format('Y-m-d')) }}" max="{{ today()->format('Y-m-d') }}" required></label>
<button>Buat Opname</button>
<p class="muted">Opname hari ini mengambil saldo buku saat dibuat. Untuk tanggal lampau, masukkan saldo buku dari arsip. Transaksi dihitung dari awal bulan sampai tanggal pemeriksaan. Hasil opname tidak otomatis mengubah stok master.</p>
</form>
<div class="box"><table><thead><tr><th>Bulan</th><th>Pemeriksaan</th><th>Barang</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
@forelse($items as $op)<tr><td>{{ $op->bulan }}</td><td>{{ $op->tanggal->format('d/m/Y') }}</td><td>{{ $op->details_count }}</td><td>{{ ucfirst($op->status) }}</td><td><a href="{{ route('adminpersediaan.opname.show',$op) }}">Buka</a> · <a href="{{ route('adminpersediaan.opname.report',$op) }}">Laporan keseluruhan</a></td></tr>
@empty<tr><td colspan="5">Belum ada opname.</td></tr>@endforelse
</tbody></table></div>
@endsection
