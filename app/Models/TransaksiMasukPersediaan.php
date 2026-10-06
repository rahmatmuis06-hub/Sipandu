<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransaksiMasukPersediaan extends Model
{
    use HasFactory;

    protected $table = 'transaksi_masuk_persediaan';
    
    protected $fillable = [
        'tanggal_input',
        'kode_kategori',
        'kategori',
        'kode_barang',
        'nama_barang',
        'jumlah_masuk',
        'satuan',
        'harga_satuan',
        'total',
        'user_id',
        'created_by'
    ];

    protected $casts = [
        'tanggal_input' => 'date:Y-m-d',
        'harga_satuan' => 'decimal:2',
        'total' => 'decimal:2',
        'jumlah_masuk' => 'integer',
    ];

    // Relasi User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    // Mutator: Auto hitung total saat harga_satuan atau jumlah_masuk diubah
    public function setHargaSatuanAttribute($value)
    {
        $this->attributes['harga_satuan'] = $value;
        if ($this->jumlah_masuk) {
            $this->attributes['total'] = $value * $this->jumlah_masuk;
        }
    }

    public function setJumlahMasukAttribute($value)
    {
        $this->attributes['jumlah_masuk'] = $value;
        if ($this->harga_satuan) {
            $this->attributes['total'] = $value * $this->harga_satuan;
        }
    }

   

    public function getHargaSatuanFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->harga_satuan ?? 0, 0, ',', '.');
    }

    public function getTotalFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->total ?? 0, 0, ',', '.');
    }

    public function getKodeUnikBarangAttribute(): string
    {
        return trim((string) $this->kode_kategori).'-'.trim((string) $this->kode_barang);
    }

    public function getTanggalInputFormatAttribute(): string
    {
        return $this->tanggal_input ? $this->tanggal_input->format('d/m/Y') : '-';
    }

    // Scope untuk query
    public function scopeFilterTanggal($query, $tanggal)
    {
        return $query->whereDate('tanggal_input', $tanggal);
    }

    public function scopeFilterKategori($query, $kategori)
    {
        return $query->where('kode_kategori', 'like', "%{$kategori}%")
                    ->orWhere('kategori', 'like', "%{$kategori}%");
    }

    public function scopeFilterBarang($query, $barang)
    {
        return $query->where('kode_barang', 'like', "%{$barang}%")
                    ->orWhere('nama_barang', 'like', "%{$barang}%");
    }
}
