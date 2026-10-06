<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kerusakan extends Model
{
    use HasFactory;

    protected $table = 'kerusakan';

    protected $fillable = [
        'tanggal_input', 'nama_barang', 'kode_barang', 
        'nup', 'kondisi', 'foto', 'lokasi', 'deskripsi'
    ];

    protected $casts = ['tanggal_input' => 'date'];

    public function riwayat() {
        return $this->hasMany(RiwayatKerusakan::class)->latest('id');
    }

    protected static function booted(): void {
        static::created(function ($item) {
            $item->riwayat()->create(['data'=>$item->getAttributes(), 'aktivitas'=>'Data kerusakan ditambahkan', 'user_id'=>auth()->id()]);
        });
        static::updated(function ($item) {
            if ($item->wasChanged($item->getFillable())) {
                $item->riwayat()->create(['data'=>$item->getAttributes(), 'aktivitas'=>'Data kerusakan diperbarui', 'user_id'=>auth()->id()]);
            }
        });
    }

    public function riwayatPerbaikan(): HasMany
    {
        return $this->hasMany(PerbaikanKerusakan::class)->latest('tanggal_perbaikan')->latest('id');
    }

    public function scopeFilterKondisi($query, $kondisi)
    {
        return $kondisi ? $query->where('kondisi', $kondisi) : $query;
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('nama_barang', 'like', "%{$search}%")
              ->orWhere('kode_barang', 'like', "%{$search}%")
              ->orWhere('nup', 'like', "%{$search}%")
              ->orWhere('lokasi', 'like', "%{$search}%");
        });
    }
}
