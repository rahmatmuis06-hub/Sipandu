<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PermintaanPersediaan extends Model
{
    use HasFactory;

    protected $table = 'permintaan_persediaan';
    
    protected $fillable = [
        'nama_lengkap',
        'kode_barang',
        'nama_barang',
        'persediaan_id',
        'user_id',
        'jumlah_diminta',
        'jumlah_disetujui',
        'satuan',
        'tanggal_permintaan',
        'tanggal_penerimaan',
        'tanggal_dibutuhkan',
        'tujuan_penggunaan',
        'surat_bast_path',
        
       // Workflow
        'reviewed_by_adminpersediaan_id',
        'approved_by_kasubag_id',
        'status', 
        
    ];

    protected $casts = [
        'tanggal_permintaan' => 'date',
        'tanggal_penerimaan' => 'date',
        'tanggal_dibutuhkan' => 'date',
        'jumlah_diminta' => 'integer',
    ];

    // Relasi
    public function persediaan(): BelongsTo
    {
        return $this->belongsTo(Persediaan::class, 'persediaan_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by_adminpersediaan_id');
    }

    public function approvedByKasubag(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_kasubag_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(DetailPermintaanPersediaan::class, 'permintaan_persediaan_id');
    }

    // Scopes Workflow
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeDalamReview($query)
    {
        return $query->where('status', 'dalam_review');
    }

    public function scopeDisetujuiKasubag($query)
    {
        return $query->where('status', 'disetujui_kasubag');
    }

    // Status badge helper
    public function getStatusBadgeAttribute(): array
    {
        return match($this->status) {
            'pending' => ['text' => 'Pending', 'color' => 'warning', 'icon' => 'fa-clock'],
            'dalam_review', 'diteruskan_kasubag' => ['text' => 'Diteruskan ke Kasubag', 'color' => 'info', 'icon' => 'fa-paper-plane'],
            'disetujui_kasubag' => ['text' => 'Disetujui Kasubag', 'color' => 'success', 'icon' => 'fa-check-circle'],
            'disetujui', 'disetujui_admin', 'selesai' => ['text' => 'Disetujui', 'color' => 'success', 'icon' => 'fa-thumbs-up'],
            'ditolak', 'rejected' => ['text' => 'Ditolak', 'color' => 'danger', 'icon' => 'fa-times-circle'],
            'dibatalkan', 'cancelled' => ['text' => 'Dibatalkan', 'color' => 'secondary', 'icon' => 'fa-ban'],
            default => ['text' => ucfirst($this->status ?? 'Unknown'), 'color' => 'secondary', 'icon' => 'fa-question-circle']
        };
    }

    // Check apakah sudah final
    public function getIsFinalAttribute(): bool
    {
        return in_array($this->status, ['disetujui', 'ditolak']);
    }
}
