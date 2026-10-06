@extends('adminsarpras.layout_fasilitas_beranda')
@section('content')
<a href="{{ route('adminsarpras.data-gedung') }}">← Data Gedung</a>
<h1>Fasilitas Beranda</h1><p class="muted">Kelola informasi dan foto kartu fasilitas yang dilihat pengunjung.</p>
@if(session('success'))<p class="notice">{{ session('success') }}</p>@endif
<a href="{{ route('home') }}" target="_blank" rel="noopener">Lihat Beranda ↗</a>
<div class="grid">
@foreach($items as $item)
<div class="card">
<img src="{{ $item->konten['images'][0] ?? '' }}" alt="{{ $item->konten['name'] }}" style="width:100%;height:180px">
<h2>{{ $item->konten['name'] }}</h2>
<p>{{ $item->konten['location'] }}</p>
<p class="muted">Urutan {{ $item->urutan }} · {{ $item->tampil ? 'Ditampilkan' : 'Disembunyikan' }}</p>
<a class="button" href="{{ route('adminsarpras.fasilitas-beranda.edit', $item) }}">Edit Fasilitas</a>
</div>
@endforeach
</div>
@endsection
