@extends('adminpersediian.opname.layout')
@section('content')
<a href="{{ route('adminpersediaan.opname.index') }}">← Stok Opname Bulanan</a>
<h1>Opname {{ $op->bulan }} — {{ ucfirst($op->status) }}</h1>
<div class="stats">
<div class="stat"><span>Jenis barang</span><strong>{{ $rows->count() }}</strong></div>
<div class="stat"><span>Sudah dihitung</span><strong>{{ $rows->filter(fn($d)=>$d->stok_fisik!==null && $d->stok_buku!==null)->count() }} / {{ $rows->count() }}</strong></div>
<div class="stat"><span>Barang dengan selisih</span><strong>{{ $rows->filter(fn($d)=>$d->selisih!==null && $d->selisih!=0)->count() }}</strong></div>
</div>
<p>Pemeriksaan {{ $op->tanggal->format('d/m/Y') }}. Selisih = stok fisik − saldo buku. Kolom kosong berarti belum dihitung; isi 0 jika barang habis.</p>
<a class="button" href="{{ route('adminpersediaan.opname.report',$op) }}">Laporan seluruh barang</a>
<a class="button" href="{{ route('adminpersediaan.opname.report',[$op,'pdf'=>1]) }}">Unduh PDF keseluruhan</a>
@if($op->status==='draft')
<p class="muted">Simpan setiap barang sebelum finalisasi. Saldo buku dan transaksi merupakan catatan saat opname dibuat; periksa kesesuaiannya dengan tanggal hitung.</p>
@endif
<div class="box"><table><thead><tr><th>Barang / satuan</th><th>Masuk</th><th>Keluar</th><th>Saldo buku</th><th>Stok fisik</th><th>Selisih</th><th>Catatan</th><th>Aksi</th></tr></thead><tbody>
@foreach($rows as $d)
<tr><td>{{ $d->kode }}<br><strong>{{ $d->nama }}</strong><br>{{ $d->satuan }}</td><td>{{ $d->masuk }}</td><td>{{ $d->keluar }}</td>
@if($op->status==='draft')
<td><input form="barang-{{ $d->id }}" aria-label="Saldo buku {{ $d->nama }}" name="stok_buku" type="number" min="0" max="2147483647" required value="{{ $d->stok_buku }}"></td>
<td><input form="barang-{{ $d->id }}" aria-label="Stok fisik {{ $d->nama }}" name="stok_fisik" type="number" min="0" max="2147483647" value="{{ $d->stok_fisik }}"></td>
@else<td>{{ $d->stok_buku }}</td><td>{{ $d->stok_fisik }}</td>@endif
<td><span class="badge {{ $d->selisih===null ? 'warn' : ($d->selisih==0 ? 'good' : 'bad') }}">{{ $d->selisih ?? 'Belum dihitung' }}</span></td>
<td>@if($op->status==='draft')<textarea form="barang-{{ $d->id }}" aria-label="Catatan {{ $d->nama }}" name="catatan" maxlength="2000">{{ $d->catatan }}</textarea>@else{{ $d->catatan }}@endif</td>
<td>@if($op->status==='draft')<form id="barang-{{ $d->id }}" method="POST" action="{{ route('adminpersediaan.opname.update',$op) }}">@csrf @method('PUT')<input type="hidden" name="detail_id" value="{{ $d->id }}"><button>Simpan</button></form>@endif
<a href="{{ route('adminpersediaan.opname.report',[$op,'barang'=>$d->persediaan_id]) }}">Laporan barang</a><br><a href="{{ route('adminpersediaan.opname.report',[$op,'barang'=>$d->persediaan_id,'pdf'=>1]) }}">PDF barang</a></td></tr>
@endforeach
</tbody></table></div>
@if($op->status==='draft')
<form method="POST" action="{{ route('adminpersediaan.opname.finalize',$op) }}" onsubmit="return confirm('Finalisasi akan mengunci semua hasil opname. Lanjutkan?')">@csrf<button>Finalisasi Opname</button></form>
@endif
@endsection
