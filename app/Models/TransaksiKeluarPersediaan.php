<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransaksiKeluarPersediaan extends Model
{
    use HasFactory;

    protected $table = 'transaksi_keluar_persediaan';
    
    protected $fillable = [
        'tanggal_input',     // ✅ Sesuai "Tanggal Input"
        'kode_kategori',     // ✅ Sesuai "Kota Kategori"
        'kategori',          // ✅ Sesuai "Kategori"
        'kode_barang',       // ✅ Sesuai "Kode Barang"
        'nama_barang',       // ✅ Sesuai "Nama Barang"
        'jumlah_keluar',     // ✅ Sesuai "Jumlah Keluar"
        'harga',             // ✅ Sesuai "Harga"
        'total',             // ✅ Sesuai "Total"
        'satuan',
        'user_id',
    ];

    protected $casts = [
        'tanggal_input' => 'date',
        'harga' => 'decimal:2',
        'total' => 'decimal:2',
        'jumlah_keluar' => 'integer',
    ];

    // Relasi User (opsional)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ✅ MUTATOR: Auto hitung TOTAL = Harga × Jumlah Keluar
    protected static function boot()
    {
        parent::boot();

        // Auto calculate total saat saving
        static::saving(function ($model) {
            $model->total = ($model->harga ?? 0) * ($model->jumlah_keluar ?? 0);
        });
    }

    // ✅ ACCESSOR: Format Rupiah untuk tampilan tabel
    public function getHargaFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->harga ?? 0, 0, ',', '.');
    }

    public function getTotalFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->total ?? 0, 0, ',', '.');
    }

    public function getNoAttribute(): string
    {
        return $this->getKey();
    }

    // ✅ SCOPE untuk filter tabel
    public function scopeFilterTanggal($query, $tanggal)
    {
        return $query->whereDate('tanggal_input', $tanggal);
    }

    public function scopeFilterKodeKategori($query, $kodeKategori)
    {
        return $query->where('kode_kategori', 'like', "%{$kodeKategori}%");
    }

    public function scopeFilterKategori($query, $kategori)
    {
        return $query->where('kategori', 'like', "%{$kategori}%");
    }

    /**
     * Relasi ke tabel master persediaan
     */
    public function persediaan()
    {
        // Ganti 'kode_barang' dengan nama kolom yang benar-benar Anda 
        // gunakan di database untuk menghubungkan transaksi dengan master barang.
        
        // Opsi 1: Jika relasinya menggunakan kode_barang
        // return $this->belongsTo(Persediaan::class, 'kode_barang', 'kode_barang');
        
        // Opsi 2: Jika relasinya menggunakan ID (uncomment baris di bawah dan hapus Opsi 1 jika ini yang benar)
        return $this->belongsTo(Persediaan::class, 'id_barang', 'id');
    }
}