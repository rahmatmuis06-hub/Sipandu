<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPermintaanPersediaan extends Model
{
    protected $table = 'detail_permintaan_persediaan';

    protected $fillable = [
        'permintaan_persediaan_id',
        'persediaan_id',
        'kode_barang',
        'nama_barang',
        'satuan',
        'jumlah_diminta',
        'jumlah_disetujui',
    ];

    protected $casts = [
        'jumlah_diminta' => 'integer',
        'jumlah_disetujui' => 'integer',
    ];

    public function permintaanPersediaan()
    {
        return $this->belongsTo(PermintaanPersediaan::class, 'permintaan_persediaan_id');
    }

    public function persediaan()
    {
        return $this->belongsTo(Persediaan::class, 'persediaan_id');
    }
}
