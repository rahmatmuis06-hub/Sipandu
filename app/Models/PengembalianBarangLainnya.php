<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengembalianBarangLainnya extends Model
{
    use HasFactory;

    protected $table = 'pengembalian_barang_lainnya';

    protected $fillable = [
        'peminjaman_barang_lainnya_id',
        'user_id',
        'verified_by_admin_id',
        'verified_at',
        'tanggal_pengembalian_aktual',
        'jumlah_dikembalikan',
        'kondisi_barang',
        'catatan',
        'foto_sebelum',
        'foto_sesudah',
        'status_pengembalian',
        'status_verifikasi',
        'komentar_admin',
    ];

    protected $casts = [
        'tanggal_pengembalian_aktual' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function peminjamanBarangLainnya()
    {
        return $this->belongsTo(PeminjamanBarangLainnya::class, 'peminjaman_barang_lainnya_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function adminVerifier()
    {
        return $this->belongsTo(User::class, 'verified_by_admin_id');
    }

    public function scopePending($query)
    {
        return $query->where('status_verifikasi', 'pending');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('kondisi_barang', 'like', "%{$search}%")
            ->orWhereHas('peminjamanBarangLainnya', function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                    ->orWhere('kode_barang', 'like', "%{$search}%");
            })
            ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%"));
    }

    public function scopeKondisi($query, $kondisi)
    {
        return $query->where('status_pengembalian', $kondisi);
    }
}
