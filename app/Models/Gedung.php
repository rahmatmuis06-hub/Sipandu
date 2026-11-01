<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{
    Peminjaman,
};

class Gedung extends Model
{
    use HasFactory;

    protected $table = 'gedung';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'nama_gedung',
        'foto_url',
        'lokasi',
        'luas_bangunan',
        'tarif_sewa',
        'kapasitas',
        'ketersediaan',
        'fasilitas',
        'kategori'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'tarif_sewa' => 'integer',
        'kapasitas' => 'integer',
    ];

    // Helper untuk dropdown options
    public static function kategoriOptions()
    {
        $default = [
            'kantor' => 'Kantor',
            'ruang' => 'Ruang Pertemuan',
            'kelas' => 'Ruang Kelas',
            'penginapan' => 'Asrama / Mess',
            'ruang_makan' => 'Ruang Makan',
            'gedung' => 'Gedung Arsip',
            'outdoor' => 'Fasilitas Olahraga',
            'lapangan_Upacara' => 'Lapangan Upacara',
            'sarana_ibadah' => 'Sarana Ibadah',
            'kesehatan' => 'Kesehatan',
            // Opsi kompatibilitas lama
            'ruang_sidang' => 'Ruang Sidang',
            'mess' => 'Mess',
            'asrama' => 'Asrama',
            'aula' => 'Aula',
            'ruang_kelas' => 'Ruang Kelas',
        ];

        try {
            $existing = self::whereNotNull('kategori')
                ->where('kategori', '!=', '')
                ->distinct()
                ->pluck('kategori')
                ->all();

            foreach ($existing as $cat) {
                if (!isset($default[$cat])) {
                    $default[$cat] = ucwords(str_replace(['_', '-'], ' ', $cat));
                }
            }
        } catch (\Throwable $e) {
            // Abaikan jika koneksi/tabel belum siap
        }

        return $default;
    }
    public static function ketersediaanOptions()
    {
        return [
            'Tersedia' => 'Tersedia',
            'Sedang Dipakai' => 'Sedang Dipakai',
            'Renovasi' => 'Renovasi',
            'Perlu Perbaikan' => 'Perlu Perbaikan',
        ];
    }

    /**
     * ✅ ICON MAPPING untuk setiap kategori
     */
    public function getIconAttribute()
    {
        return match($this->kategori) {
            'ruang_sidang' => 'door-open',
            'mess' => 'bed',
            'asrama', 'penginapan' => 'home',
            'ruang_makan' => 'utensils',
            'aula', 'ruang' => 'university',
            'ruang_kelas', 'kelas' => 'chalkboard-teacher',
            'kantor' => 'briefcase',
            'outdoor' => 'futbol',
            'lapangan_Upacara' => 'flag',
            'sarana_ibadah' => 'mosque',
            'kesehatan' => 'hospital',
            'gedung' => 'archive',
            default => 'building'
        };
    }

    public function getFotoPathAttribute()
    {
        if (!$this->foto_url) {
            return null;
        }
        if (str_starts_with($this->foto_url, 'http://') || str_starts_with($this->foto_url, 'https://')) {
            return $this->foto_url;
        }
        $clean = ltrim(preg_replace('#^/?storage/#', '', $this->foto_url), '/');
        return asset('storage/' . $clean);
    }

    /**
     * ✅ Status class untuk fasilitas view
     */
    public function getStatusClassAttribute()
    {
        return match($this->ketersediaan) {
            'Tersedia' => 'available',
            default => 'booked'
        };
    }

    public function peminjaman()
    {
        return $this->hasMany(PeminjamanGedung::class, 'gedung_id');
    }

    /**
     * Relasi ke banyak foto gedung
     */
    public function fotos()
    {
        return $this->hasMany(FotoGedung::class, 'gedung_id')->orderBy('urutan', 'asc')->orderBy('id', 'asc');
    }

    /**
     * Ambil seluruh foto gedung (baik dari relasi foto_gedung maupun foto_url)
     */
    public function getAllFotosAttribute()
    {
        $list = collect();

        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('foto_gedung')) {
                $fotoRecords = $this->relationLoaded('fotos') ? $this->fotos : $this->fotos()->get();
                if ($fotoRecords && $fotoRecords->count() > 0) {
                    foreach ($fotoRecords as $f) {
                        $list->push([
                            'id' => $f->id,
                            'url' => $f->foto_url,
                            'raw_path' => $f->foto_path,
                        ]);
                    }
                }
            }
        } catch (\Throwable $e) {
            // Fallback jika tabel foto_gedung belum siap
        }

        // Jika relasi kosong namun ada foto_url lama
        if ($list->isEmpty() && $this->foto_url) {
            $list->push([
                'id' => null,
                'url' => $this->foto_path,
                'raw_path' => $this->foto_url,
            ]);
        }

        return $list;
    }


    public function getTarifSewaFormatAttribute()
    {
        return 'Rp ' . number_format($this->tarif_sewa, 0, ',', '.');
    }

    public function getKetersediaanBadgeAttribute()
    {
        $badgeClass = match($this->ketersediaan) {
            'Tersedia' => 'badge bg-success',
            'Sedang Dipakai' => 'badge bg-warning',
            'Renovasi' => 'badge bg-danger',
            'Perlu Perbaikan' => 'badge bg-secondary',
            default => 'badge bg-dark'
        };

        return '<span class="' . $badgeClass . '">' . $this->ketersediaan . '</span>';
    }

    // Scoped queries
    public function scopeTersedia($query)
    {
        return $query->where('ketersediaan', 'Tersedia');
    }

    public function scopeKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

}