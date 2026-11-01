<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersediaanBatch extends Model
{
    use HasFactory;

    protected $table = 'persediaan_batches';

    protected $fillable = [
        'persediaan_id',
        'transaksi_masuk_id',
        'tanggal_masuk',
        'harga_satuan',
        'jumlah_awal',
        'jumlah_sisa',
        'sumber',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
        'harga_satuan' => 'decimal:2',
        'jumlah_awal' => 'integer',
        'jumlah_sisa' => 'integer',
    ];

    public function persediaan(): BelongsTo
    {
        return $this->belongsTo(Persediaan::class, 'persediaan_id');
    }

    public function transaksiMasuk(): BelongsTo
    {
        return $this->belongsTo(TransaksiMasukPersediaan::class, 'transaksi_masuk_id');
    }

    public function getSubtotalSisaAttribute(): float
    {
        return (float) ($this->getRawOriginal('harga_satuan') ?? $this->harga_satuan ?? 0) * (int) ($this->jumlah_sisa ?? 0);
    }
}
