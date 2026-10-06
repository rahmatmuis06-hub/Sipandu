<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengembalianBarang extends Model
{
    use HasFactory;

    protected $table = 'pengembalian_barang';

    protected $fillable = [
        'peminjaman_barang_id',
        'user_id',
        'verified_by_adminAsetTetap_id',
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
    ];

    // ✅ RELASI SESUAI STRUKTUR ANDA
    public function peminjamanBarang()
    {
        return $this->belongsTo(PeminjamanBarang::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function adminVerifier()
    {
        return $this->belongsTo(User::class, 'verified_by_adminAsetTetap_id');
    }

    // ✅ SCOPE UNTUK FILTER & SEARCH
    public function scopePending($query)
    {
        return $query->where('status_verifikasi', 'pending');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('kondisi_barang', 'like', "%{$search}%")
                    ->orWhereHas('peminjamanBarang', function($q) use ($search) {
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