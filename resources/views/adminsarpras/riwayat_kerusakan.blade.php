<!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Riwayat Kerusakan</title>@include('partials.operations-style')</head><body><header class="appbar"><span class="brand">SIPANDU</span><small>ADMIN SARPRAS / RIWAYAT KERUSAKAN</small></header><main>
<a href="{{ route('adminsarpras.data-kerusakan') }}">← Data Kerusakan</a>
<h1>{{ $kerusakan->nama_barang }}</h1><p class="muted">Riwayat kondisi dan pemeliharaan barang</p>
<p>{{ $kerusakan->kode_barang }} · NUP {{ $kerusakan->nup ?? '—' }} · {{ $kerusakan->lokasi }}</p>
<div class="stats">
<div class="stat"><span>Kondisi saat ini</span><strong>{{ $kerusakan->kondisi }}</strong></div>
<div class="stat"><span>Catatan perbaikan</span><strong>{{ $kerusakan->riwayatPerbaikan->count() }}</strong></div>
<div class="stat"><span>Total biaya perbaikan</span><strong>Rp {{ number_format($kerusakan->riwayatPerbaikan->sum('biaya'),0,',','.') }}</strong></div>
</div>
@if($errors->any())<section class="error">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</section>@endif
<div class="columns"><section class="panel repair-form"><h2>Catat Perbaikan</h2><p class="muted">Tambahkan tindakan dan hasil pemeriksaan terbaru.</p>
<form method="POST" action="{{ route('adminsarpras.kerusakan.perbaikan.store',$kerusakan) }}">@csrf
<div class="grid">
<div><label for="tanggal">Tanggal perbaikan</label><input id="tanggal" name="tanggal_perbaikan" type="date" value="{{ old('tanggal_perbaikan',today()->format('Y-m-d')) }}" required></div>
<div><label for="status">Hasil perbaikan</label><select id="status" name="status">@foreach(['Proses','Selesai','Tidak Dapat Diperbaiki'] as $status)<option @selected(old('status') === $status)>{{ $status }}</option>@endforeach</select></div>
<div><label for="biaya">Biaya (Rp)</label><input id="biaya" name="biaya" type="number" min="0" step="0.01" value="{{ old('biaya',0) }}"></div>
<div><label for="pelaksana">Teknisi / pelaksana</label><input id="pelaksana" name="pelaksana" maxlength="255" value="{{ old('pelaksana') }}"></div>
</div><label for="tindakan">Tindakan perbaikan</label><textarea id="tindakan" name="tindakan" maxlength="2000" required>{{ old('tindakan') }}</textarea>
<label for="catatan">Catatan</label><textarea id="catatan" name="catatan" maxlength="2000">{{ old('catatan') }}</textarea>
<p class="muted">Pilih Selesai jika kondisi sudah Baik. Catatan ditambahkan ke riwayat tanpa menimpa catatan sebelumnya.</p><button>Simpan Catatan Perbaikan</button></form></section>
<div><div class="section-head"><h2>Riwayat Perbaikan</h2><span class="badge">{{ $kerusakan->riwayatPerbaikan->count() }} catatan</span></div><div class="timeline">
@forelse($kerusakan->riwayatPerbaikan as $item)
<article><span class="badge {{ $item->status==='Selesai' ? 'good' : ($item->status==='Proses' ? 'warn' : 'bad') }}">{{ $item->status }}</span><p><strong>{{ $item->tanggal_perbaikan->translatedFormat('d F Y') }}</strong></p>
<p class="text">{{ $item->tindakan }}</p><p>Pelaksana: {{ $item->pelaksana ?? '—' }} · Biaya: Rp {{ number_format($item->biaya,2,',','.') }}</p>
<p class="text">{{ $item->catatan }}</p><p class="muted">Dicatat oleh {{ $item->dicatatOleh->name ?? '—' }} pada {{ $item->created_at->format('d/m/Y H:i') }}</p></article>
@empty<p class="empty">Belum ada catatan perbaikan.<br>Catatan yang ditambahkan akan muncul di sini.</p>@endforelse</div>
<div class="section-head"><h2>Perubahan Data Kerusakan</h2><span class="badge">{{ $kerusakan->riwayat->count() }} aktivitas</span></div><div class="timeline">
@foreach($kerusakan->riwayat as $item)
<article><strong>{{ $item->aktivitas }}</strong><p class="muted">{{ $item->created_at->format('d/m/Y H:i') }} · {{ $item->pencatat->name ?? 'Sistem' }}</p>
<p>Nama: {{ $item->data['nama_barang'] ?? '—' }} · Kode: {{ $item->data['kode_barang'] ?? '—' }} · NUP: {{ $item->data['nup'] ?? '—' }}</p>
<p>Tanggal laporan: {{ $item->data['tanggal_input'] ?? '—' }} · Kondisi: <strong>{{ $item->data['kondisi'] ?? '—' }}</strong></p>
<p>Lokasi: {{ $item->data['lokasi'] ?? '—' }}</p><p class="text">{{ $item->data['deskripsi'] ?? '—' }}</p>
</article>@endforeach</div></div></div></main></body></html>
