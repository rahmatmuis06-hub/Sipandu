<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailKendaraan extends Model
{
    protected $table = 'detail_kendaraan';
    
    protected $fillable = [
        'aset_tetap_id',
        'nomor_polisi',
        'no_bpkb',
        'nomor_rangka',
        'nomor_mesin',
    ];

    // Relasi balik (belongsTo) ke model AssetTetap
    public function assetTetap()
    {
        return $this->belongsTo(AssetTetap::class, 'aset_tetap_id');
    }
}