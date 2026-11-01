<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PeminjamanBarangLainnya extends Model
{
    use HasFactory;

    protected $table = 'peminjaman_barang_lainnya';

    protected $fillable = [
        'user_id',
        'barang_lainnya_id',
        'nama_barang',
        'kode_barang',
        'jumlah',
        'satuan',
        'tanggal_pinjam',
        'tanggal_kembali',
        'keperluan',
        'status',
        'catatan_admin',
        'approved_by',
    ];

    protected $casts = [
        'tanggal_pinjam'  => 'date',
        'tanggal_kembali' => 'date',
        'jumlah'          => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function barangLainnya(): BelongsTo
    {
        return $this->belongsTo(BarangLainnya::class, 'barang_lainnya_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function pengembalianBarangLainnya()
    {
        return $this->hasOne(PengembalianBarangLainnya::class, 'peminjaman_barang_lainnya_id');
    }
}
