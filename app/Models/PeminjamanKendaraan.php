<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PeminjamanKendaraan extends Model
{
    protected $table = 'peminjaman_kendaraan';
    
    protected $guarded = ['id'];
    
    protected $fillable = [
        'user_id', 'nama_barang', 'kode_barang', 'nup', 'merek',
        'jumlah', 'deskripsi_peruntukan', 'request_date',
        'tanggal_peminjaman', 'tanggal_pengembalian', 'komentar',
        'status', 'surat_bast_path','nomor_polisi_saat_pinjam','no_bpkb_saat_pinjam','nomor_rangka_saat_pinjam','nomor_mesin_saat_pinjam',
    ];

    protected $casts = [
        'request_date' => 'datetime',
        'tanggal_peminjaman' => 'datetime',
        'tanggal_pengembalian' => 'datetime',
        'jumlah' => 'integer'
    ];

    // Relasi
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    
    public function reviewedBy(): BelongsTo 
    { 
        return $this->belongsTo(User::class, 'reviewed_by_adminasettetap_id'); 
    }
    
    public function approvedBy(): BelongsTo 
    { 
        return $this->belongsTo(User::class, 'approved_by_adminasettetap_id'); 
    }
    
    public function approvedByKasubag(): BelongsTo 
    { 
        return $this->belongsTo(User::class, 'approved_by_kasubag_id'); 
    }
    
    public function pengembalianKendaraan(): HasOne 
    { 
        return $this->hasOne(PengembalianKendaraan::class); 
    }

    // Scopes
    public function scopePending($query) { return $query->where('status', 'pending'); }
    public function scopeDalamReview($query) { return $query->where('status', 'dalam_review'); }
    public function scopeBisaDiedit($query) { 
        return $query->whereIn('status', ['pending', 'dalam_review']); 
    }
    public function scopeByUser($query, $userId) { 
        return $query->where('user_id', $userId); 
    }

    // Status helpers
    public function getStatusBadgeAttribute(): array
    {
        return match($this->status) {
            'pending' => ['text' => 'Pending', 'color' => 'warning', 'icon' => 'fa-clock'],
            'dalam_review' => ['text' => 'Dalam Review', 'color' => 'info', 'icon' => 'fa-eye'],
            'disetujui_kasubag' => ['text' => 'Disetujui Kasubag', 'color' => 'success', 'icon' => 'fa-check-circle'],
            'disetujui' => ['text' => 'Disetujui', 'color' => 'success', 'icon' => 'fa-thumbs-up'],
            'ditolak' => ['text' => 'Ditolak', 'color' => 'danger', 'icon' => 'fa-times-circle'],
            'dibatalkan' => ['text' => 'Dibatalkan', 'color' => 'secondary', 'icon' => 'fa-ban'],
            default => ['text' => 'Unknown', 'color' => 'secondary']
        };
    }

    public function getStatusBadgeClassAttribute()
    {
        return match(strtolower($this->status)) {
            'pending', 'dalam_review' => 'status-pending',
            'disetujui_admin', 'disetujui', 'dikembalikan' => 'status-diterima',
            'ditolak' => 'status-ditolak',
            default => 'status-pending'
        };
    }

    // Check if current user can edit
    public function canEditBy(Auth $user)
    {
        return in_array($this->status, ['pending', 'dalam_review']);
    }
}