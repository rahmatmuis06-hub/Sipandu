<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangLainnya extends Model
{
    use HasFactory;

    protected $table = 'barang_lainnya';

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori',
        'satuan',
        'jumlah',
        'harga_satuan',
        'harga_total',
        'tanggal_masuk',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
        'harga_satuan'  => 'decimal:2',
        'harga_total'   => 'decimal:2',
        'jumlah'        => 'integer',
    ];
}
