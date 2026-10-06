<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Persediaan extends Model
{
    use HasFactory;

    protected $table = 'persediaan';
    
    protected $fillable = [
        'kode_kategori',
        'kategori',
        'kode_barang',
        'kode_unik_barang',
        'nama_barang',
        'satuan',
        'tanggal_masuk',
        'harga_satuan',
        'harga_total',
        'jumlah'
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
        'harga_satuan' => 'decimal:2',
        'harga_total' => 'decimal:2',
        'jumlah' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(function (Persediaan $persediaan) {
            try {
                if (\Illuminate\Support\Facades\Schema::hasColumn('persediaan', 'kode_unik_barang')) {
                    if (empty($persediaan->attributes['kode_unik_barang'])) {
                        $persediaan->attributes['kode_unik_barang'] = trim((string) $persediaan->kode_kategori)
                            .'-'.trim((string) $persediaan->kode_barang);
                    }
                } else {
                    unset($persediaan->kode_unik_barang);
                }
            } catch (\Throwable $e) {
                unset($persediaan->kode_unik_barang);
            }
        });
    }

    public function getKodeUnikBarangAttribute(): string
    {
        if (!empty($this->attributes['kode_unik_barang'])) {
            return (string) $this->attributes['kode_unik_barang'];
        }
        return trim((string) $this->kode_kategori) . '-' . trim((string) $this->kode_barang);
    }

    /**
     * Accessor Virtual baru agar tidak merusak nilai asli database.
     * Dipanggil di Blade Tabel menggunakan: $item->harga_satuan_format
     */
    protected function hargaSatuanFormat(): Attribute
    {
        return Attribute::make(
            get: fn () => 'Rp ' . number_format($this->harga_satuan, 0, ',', '.'),
        );
    }

    /**
     * Accessor Virtual baru untuk harga total.
     * Dipanggil di Blade Tabel menggunakan: $item->harga_total_format
     */
    protected function hargaTotalFormat(): Attribute
    {
        return Attribute::make(
            get: fn () => 'Rp ' . number_format($this->harga_satuan * $this->jumlah, 0, ',', '.'),
        );
    }

    // Scope untuk filter kategori
    public function scopeKategori($query, $kodeKategori)
    {
        return $query->where('kode_kategori', $kodeKategori);
    }
}
