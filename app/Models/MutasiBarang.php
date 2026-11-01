<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MutasiBarang extends Model
{
    use HasFactory;

    protected $table = 'mutasi_barang';
    
    protected $fillable = [
        'aset_tetap_id',
        'user_id',
        'kode_barang',
        'nup',
        'nama_barang',
        'lokasi_awal',
        'lokasi_akhir',
        'kondisi',
        'tanggal_mutasi',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_mutasi' => 'date',
    ];

    // Relasi
    public function asetTetap(): BelongsTo
    {
        return $this->belongsTo(AssetTetap::class, 'aset_tetap_id');
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(AssetTetap::class, 'aset_tetap_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeByLokasi($query, $lokasiAwal, $lokasiAkhir)
    {
        return $query->where('lokasi_awal', $lokasiAwal)
                     ->where('lokasi_akhir', $lokasiAkhir);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('kode_barang', 'like', "%{$search}%")
                     ->orWhere('nama_barang', 'like', "%{$search}%")
                     ->orWhere('lokasi_awal', 'like', "%{$search}%")
                     ->orWhere('lokasi_akhir', 'like', "%{$search}%");
    }

    // Accessors
    public function getNoMutasiAttribute(): string
    {
        return 'MT-' . str_pad($this->id, 3, '0', STR_PAD_LEFT);
    }

    public function getLokasiPerubahanAttribute(): string
    {
        return "{$this->lokasi_awal} → {$this->lokasi_akhir}";
    }

    public function getTanggalInputAttribute(): string
    {
        return $this->created_at?->format('d/m/Y');
    }

    public function getTanggalMutasiFormattedAttribute(): string
    {
        return $this->tanggal_mutasi?->format('d/m/Y');
    }
}