<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BarangLainnyaUnit extends Model
{
    use HasFactory;

    protected $table = 'barang_lainnya_units';

    protected $fillable = [
        'barang_lainnya_id',
        'kode_unit',
        'status',
        'peminjaman_aktif_id',
        'keterangan',
    ];

    public function barangLainnya(): BelongsTo
    {
        return $this->belongsTo(BarangLainnya::class, 'barang_lainnya_id');
    }

    public function peminjamanAktif(): BelongsTo
    {
        return $this->belongsTo(PeminjamanBarangLainnya::class, 'peminjaman_aktif_id');
    }
}
