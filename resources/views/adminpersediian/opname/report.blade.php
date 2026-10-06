<!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><title>Laporan Stok Opname {{ $op->bulan }}</title><style>@page{margin:20px}body{font:11px DejaVu Sans,Arial;color:#111}table{border-collapse:collapse;width:100%}th,td{border:1px solid #aaa;padding:6px}th{background:#edf2fa}tr{page-break-inside:avoid}td.num{text-align:right}.actions{margin:18px 0}@media print{.actions{display:none}}</style></head><body>
<div class="actions"><a href="{{ route('adminpersediaan.opname.show',$op) }}">Kembali</a> · <a href="{{ request()->fullUrlWithQuery(['pdf'=>1]) }}">Unduh PDF</a></div>
<h2>Laporan Stok Opname Persediaan — {{ $op->bulan }}</h2>
<p>Tanggal pemeriksaan: {{ $op->tanggal->format('d/m/Y') }} · Status: {{ strtoupper($op->status) }} · {{ $rows->count() }} jenis barang</p>
<p>Masuk/keluar: awal bulan sampai tanggal pemeriksaan. Selisih = fisik − buku. Nilai menggunakan harga satuan yang dicatat saat opname dibuat.</p>
<table><thead><tr><th>Kode / Nama</th><th>Satuan</th><th>Masuk</th><th>Keluar</th><th>Saldo buku</th><th>Fisik</th><th>Selisih</th><th>Harga (Rp)</th><th>Nilai buku (Rp)</th><th>Nilai fisik (Rp)</th><th>Nilai selisih (Rp)</th><th>Catatan</th></tr></thead><tbody>
@foreach($rows as $d)<tr><td>{{ $d->kode }}<br>{{ $d->nama }}</td><td>{{ $d->satuan }}</td><td>{{ $d->masuk }}</td><td>{{ $d->keluar }}</td><td>{{ $d->stok_buku ?? '—' }}</td><td>{{ $d->stok_fisik ?? '—' }}</td><td>{{ $d->selisih ?? '—' }}</td><td class="num">{{ number_format($d->harga,2,',','.') }}</td>
<td class="num">{{ $d->stok_buku===null ? '—' : number_format($d->stok_buku*$d->harga,2,',','.') }}</td>
<td class="num">{{ $d->stok_fisik===null ? '—' : number_format($d->stok_fisik*$d->harga,2,',','.') }}</td>
<td class="num">{{ $d->selisih===null ? '—' : number_format($d->selisih*$d->harga,2,',','.') }}</td><td>{{ $d->catatan }}</td></tr>@endforeach
</tbody></table>
@if($rows->every(fn($d)=>$d->stok_buku!==null && $d->stok_fisik!==null))
<p>Total nilai buku: Rp {{ number_format($rows->sum(fn($d)=>$d->stok_buku*$d->harga),2,',','.') }}<br>Total nilai fisik: Rp {{ number_format($rows->sum(fn($d)=>$d->stok_fisik*$d->harga),2,',','.') }}<br>Total nilai selisih: Rp {{ number_format($rows->sum(fn($d)=>$d->selisih*$d->harga),2,',','.') }}</p>
@else<p>Hasil belum lengkap. Total nilai belum ditampilkan karena masih ada barang yang belum dihitung.</p>@endif
<p>Jumlah barang tidak dijumlahkan lintas satuan. Dokumen ini mencatat pemeriksaan dan tidak otomatis melakukan penyesuaian stok.</p>
<div style="page-break-inside:avoid; margin-top:24px;">
<p style="text-align:right;">Gorontalo, {{ $op->tanggal->locale('id')->translatedFormat('d F Y') }}</p>
<table style="border:0; text-align:center;">
<tr>
<td style="border:0; width:50%; text-align:center; vertical-align:top;">
Mengetahui,<br><strong>Kepala Subbagian Umum</strong>
<div style="height:85px; padding-top:8px;">
@if($ttdKasubag)
<img src="{{ $ttdKasubag }}" alt="Tanda tangan Kasubag" style="max-height:75px; max-width:180px;">
@endif
</div>
<strong style="text-decoration:underline;">{{ $kasubag->nama_lengkap ?? $kasubag->name ?? '................................' }}</strong><br>
NIP. {{ $kasubag->nip ?? '................................' }}
</td>
<td style="border:0; width:50%; text-align:center; vertical-align:top;">
Pelaksana Stok Opname,<br><strong>Admin Persediaan</strong>
<div style="height:85px; padding-top:8px;">
@if($ttdPelaksana)
<img src="{{ $ttdPelaksana }}" alt="Tanda tangan Admin Persediaan" style="max-height:75px; max-width:180px;">
@endif
</div>
<strong style="text-decoration:underline;">{{ $pelaksana->nama_lengkap ?? $pelaksana->name ?? '................................' }}</strong><br>
NIP. {{ $pelaksana->nip ?? '................................' }}
</td>
</tr></table>
</div>
@if(!$ttdKasubag || !$ttdPelaksana)
<p class="actions">Tanda tangan yang belum tersedia dapat diunggah melalui Pengaturan Akun masing-masing petugas.</p>
@endif
</body></html>
