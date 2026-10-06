@extends('adminsarpras.layout_fasilitas_beranda')
@section('content')
<a href="{{ route('adminsarpras.fasilitas-beranda.index') }}">← Fasilitas Beranda</a>
<h1>Edit {{ $item->konten['name'] }}</h1>
@if($errors->any())<div class="error">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif
<form class="card" method="POST" enctype="multipart/form-data" action="{{ route('adminsarpras.fasilitas-beranda.update', $item) }}">
@csrf @method('PUT')
<div class="grid">
@foreach(['name'=>'Nama fasilitas','location'=>'Lokasi','capacity'=>'Kapasitas (contoh: 100 Orang)','luas'=>'Luas (contoh: 500 m²)','operasional'=>'Jam operasional','kontak'=>'Nomor telepon'] as $field=>$label)
<div><label for="{{ $field }}">{{ $label }}</label><input id="{{ $field }}" name="{{ $field }}" value="{{ old($field, $item->konten[$field] ?? '') }}" {{ in_array($field,['name','location']) ? 'required' : '' }} maxlength="255"></div>
@endforeach
<div><label for="category">Kategori</label><select id="category" name="category">@foreach($kategori as $key=>$label)<option value="{{ $key }}" @selected(old('category',$item->konten['category']) === $key)>{{ $label }}</option>@endforeach</select></div>
<div><label for="urutan">Urutan tampil</label><input id="urutan" name="urutan" type="number" min="0" max="10000" required value="{{ old('urutan',$item->urutan) }}"></div>
<div><label for="tampil">Tampilan di beranda</label><select id="tampil" name="tampil"><option value="1" @selected(old('tampil',$item->tampil)==1)>Ditampilkan</option><option value="0" @selected(old('tampil',$item->tampil)==0)>Disembunyikan</option></select></div>
</div>
<label for="description">Deskripsi</label><textarea id="description" name="description">{{ old('description',$item->konten['description'] ?? '') }}</textarea>
@foreach(['features'=>'Fasilitas','rules'=>'Aturan penggunaan'] as $field=>$label)
<label for="{{ $field }}">{{ $label }} (satu per baris)</label><textarea id="{{ $field }}" name="{{ $field }}">{{ old($field,implode("\n",$item->konten[$field] ?? [])) }}</textarea>
@endforeach
<h2>Foto</h2><p class="muted">Foto pertama menjadi sampul kartu. Centang foto yang ingin dilepas; unggahan baru ditambahkan setelah foto yang tersisa.</p>
<div class="photos">
@foreach($item->konten['images'] ?? [] as $i=>$image)
<div><img src="{{ $image }}" alt="Foto {{ $i+1 }}" width="180" height="130"><label><input type="checkbox" name="hapus_foto[]" value="{{ $i }}"> Lepas foto {{ $i+1 }}</label></div>
@endforeach
</div>
<label for="foto">Unggah foto baru (maksimal 10 foto, masing-masing 5 MB)</label><input type="file" id="foto" name="foto[]" accept="image/jpeg,image/png,image/webp" multiple>
<p class="muted">Untuk mengganti semua foto, centang semua foto lama lalu pilih foto baru.</p>
<button type="submit">Simpan Perubahan</button>
</form>
@endsection
