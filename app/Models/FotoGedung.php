<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FotoGedung extends Model
{
    use HasFactory;

    protected $table = 'foto_gedung';

    protected $fillable = [
        'gedung_id',
        'foto_path',
        'urutan',
    ];

    public function gedung()
    {
        return $this->belongsTo(Gedung::class, 'gedung_id');
    }

    /**
     * URL foto lengkap yang bisa diakses publik
     */
    public function getFotoUrlAttribute()
    {
        if (!$this->foto_path) {
            return null;
        }
        if (str_starts_with($this->foto_path, 'http://') || str_starts_with($this->foto_path, 'https://')) {
            return $this->foto_path;
        }
        $clean = ltrim(preg_replace('#^/?storage/#', '', $this->foto_path), '/');
        return asset('storage/' . $clean);
    }
}
