<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Override;

class PeminjamanBarang extends Model
{
    protected $table = 'peminjaman_barang';
    protected $guarded = [
        'id',
    ];

    
    // ✅ TAMBAH: Field yang boleh diisi mass assignment
    protected $fillable = [
        'user_id',
        'nama_barang',
        'kode_barang',
        'nup',
        'kategori',
        'merek',
        'jumlah',
        'deskripsi_peruntukan',
        'request_date',
        'tanggal_peminjaman',
        'tanggal_pengembalian',
        'komentar',
        'surat_bast_path'
    ];

    protected $casts = [
        'request_date' => 'datetime',
        'tanggal_peminjaman' => 'datetime',
        'tanggal_pengembalian' => 'datetime',
        'diteruskan_ke_kasubag_date' => 'datetime',
        'approved_by_kasubag_date' => 'datetime',
        'jumlah' => 'integer'
    ];

    // ✅ Relasi yang DIKOREKSI (foreign key konsisten)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Admin Aset Tetap yang mereview
    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by_adminasettetap_id');
    }

    // Admin Aset Tetap yang menyetujui
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_adminasettetap_id');
    }

    // Kasubag yang menyetujui
    public function approvedByKasubag(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_kasubag_id');
    }

    // Detail item peminjaman multi-item
    public function items(): HasMany
    {
        return $this->hasMany(DetailPeminjamanBarang::class, 'peminjaman_barang_id');
    }

    // Pengembalian barang
    // ✅ RELASI PENGEMBALIAN (YANG HILANG!)
    public function pengembalianBarang(): HasOne
    {
        return $this->hasOne(PengembalianBarang::class, 'peminjaman_barang_id');
    }

    // ✅ SCOPE UNTUK PENGEMBALIAN
    public function scopeBelumDikembalikan($query)
    {
        return $query->whereDoesntHave('pengembalianBarang')
                     ->orWhereHas('pengembalianBarang', function($q) {
                         $q->where('status', 'diproses');
                     });
    }

    // ✅ SCOPE untuk workflow
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeDalamReview($query)
    {
        return $query->where('status', 'dalam_review');
    }

    public function scopeDisetujuiAdmin($query)
    {
        return $query->where('status', 'disetujui_admin');
    }

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

    public function getIsFinalAttribute(): bool
    {
        return in_array($this->status, ['disetujui', 'ditolak', 'dibatalkan']);
    }

    /**
     * Relasi dengan surat persetujuan
     */
    // public function suratPersetujuan()
    // {
    //     return $this->hasOne(\App\Models\SuratPersetujuan::class, 'request_id')
    //                 ->where('jenis_surat', 'SPB');
    // }
}
