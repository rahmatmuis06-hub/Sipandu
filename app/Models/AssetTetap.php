<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetTetap extends Model
{
    protected $table = 'aset_tetap';
    protected $fillable = [
        'tanggal_input',
        'kode_barang',
        'nup',
        'nama_barang',
        'merek',
        'kategori',
        'tanggal_perolehan',
        'nilai_perolehan',
        'kondisi',
        'lokasi',
        'jumlah',
        'status'

    ];

    protected $casts = [
        'tanggal_input' => 'date',
        'tanggal_perolehan' => 'date',
        'nilai_perolehan' => 'decimal:2',
        'jumlah' => 'integer'
    ];


    public function transaksiMasuk()
    {
        return $this->hasMany(TransaksiMasukAssetTetap::class, 'aset_tetap_id');
    }

    public function transaksiKeluar()
    {
        return $this->hasMany(TransaksiKeluarAssetTetap::class, 'aset_tetap_id');
    }

    public function peminjamanKendaraan()
    {
        return $this->hasMany(PeminjamanKendaraan::class, 'aset_tetap_id');
    }
    public function pengembalianKendaraan()
    {
        return $this->hasMany(PengembalianKendaraan::class, 'aset_tetap_id');
    }

    public function peminjamanBarang()
    {
        return $this->hasMany(PeminjamanBarang::class, 'aset_tetap_id');
    }

    public function pengembalianBarang()
    {
        return $this->hasMany(PengembalianBarang::class, 'aset_tetap_id');
    }


    public function mutasiBarang()
    {
        return $this->hasMany(MutasiBarang::class, 'aset_tetap_id');
    }

    public function pengaduan()
    {
        return $this->hasMany(Pengaduan::class, 'aset_tetap_id');
    }

    public function surveyKepuasan()
    {     
        return $this->hasMany(SurveyKepuasan::class, 'aset_tetap_id');
    }

    // Tambahkan fungsi ini di dalam class AssetTetap
    public function detailKendaraan()
    {
        return $this->hasOne(DetailKendaraan::class, 'aset_tetap_id');
    }

    protected static function boot()
    {
        parent::boot();

        // Event listener saat aset tetap akan dihapus
        static::deleting(function ($aset) {
            // Hapus detail kendaraan jika ada
            if ($aset->detailKendaraan) {
                $aset->detailKendaraan->delete();
            }
        });
    }


}
