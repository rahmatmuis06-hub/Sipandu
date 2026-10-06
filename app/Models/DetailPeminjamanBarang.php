<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPeminjamanBarang extends Model
{
    protected $table = 'detail_peminjaman_barang';

    protected $fillable = [
        'peminjaman_barang_id',
        'aset_tetap_id',
        'kode_barang',
        'nup',
        'nama_barang',
        'merek',
        'kategori',
        'jumlah',
        'kondisi',
    ];

    protected $casts = [
        'jumlah' => 'integer',
    ];

    public function peminjamanBarang()
    {
        return $this->belongsTo(PeminjamanBarang::class, 'peminjaman_barang_id');
    }

    public function asetTetap()
    {
        return $this->belongsTo(AssetTetap::class, 'aset_tetap_id');
    }
}
