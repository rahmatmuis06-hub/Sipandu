<?php
// app/Models/PengembalianKendaraan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengembalianKendaraan extends Model
{
    use HasFactory;

    protected $table = 'pengembalian_kendaraan'; // ✅ NAMA TABLE EXPLICIT

    protected $fillable = [
        'peminjaman_kendaraan_id',
        'tanggal_pengembalian_aktual',
        'kondisi_kendaraan',
        'catatan',
        'foto_sebelum',
        'foto_sesudah',
        'status_pengembalian',
        'biaya_denda',
        'user_id',
        'verified_by_admin_id',
        'komentar_admin',
        'status_verifikasi',
        'verified_at'
    ];

    protected $casts = [
        'tanggal_pengembalian_aktual' => 'datetime',
        'verified_at' => 'datetime',
        'biaya_denda' => 'decimal:2',
    ];

    public function peminjamanKendaraan(): BelongsTo
    {
        return $this->belongsTo(PeminjamanKendaraan::class, 'peminjaman_kendaraan_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by_admin_id');
    }
}
