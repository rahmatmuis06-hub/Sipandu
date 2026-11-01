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

    /**
     * Mengambil daftar fasilitas lengkap untuk carousel dan modal di Beranda
     * Memprioritaskan data riil dari tabel Gedung & FotoGedung,
     * serta memperkaya deskripsi / spesifikasi dari FasilitasBeranda jika tersedia.
     */
    public static function getBerandaFacilities(): array
    {
        $facilities = [];
        $handledNames = [];

        // Peta normalisasi kategori agar cocok dengan tombol filter di beranda
        $categoryMap = [
            'ruang_sidang' => 'ruang',
            'aula' => 'ruang',
            'mess' => 'penginapan',
            'asrama' => 'penginapan',
            'ruang_kelas' => 'kelas',
        ];

        // Ambil peta metadata dari FasilitasBeranda (jika ada) untuk melengkapi deskripsi / kontak / operasional
        $fbMeta = [];
        try {
            if (class_exists(FasilitasBeranda::class) && \Illuminate\Support\Facades\Schema::hasTable('fasilitas_beranda')) {
                $fbList = FasilitasBeranda::where('tampil', true)->orderBy('urutan')->orderBy('id')->get();
                foreach ($fbList as $fb) {
                    $k = $fb->konten;
                    if (!empty($k['name'])) {
                        $key = strtolower(trim($k['name']));
                        $fbMeta[$key] = $k;
                    }
                }
            }
        } catch (\Throwable $e) {}

        // Ambil data gedung dari database
        try {
            $query = self::query();
            if (\Illuminate\Support\Facades\Schema::hasTable('foto_gedung')) {
                $query->with('fotos');
            }
            $gedungList = $query->where('ketersediaan', '!=', 'Perlu Perbaikan')
                ->orderBy('id', 'asc')
                ->get();

            foreach ($gedungList as $g) {
                $key = strtolower(trim($g->nama_gedung));
                $handledNames[$key] = true;
                $match = $fbMeta[$key] ?? [];

                // Kategori
                $cat = $g->kategori ?: ($match['category'] ?? 'ruang');
                $normCat = $categoryMap[$cat] ?? $cat;

                // Kapasitas
                $capacityStr = $g->kapasitas ? ($g->kapasitas . ' Orang') : ($match['capacity'] ?? '50 Orang');

                // Luas
                $luasStr = $g->luas_bangunan ? (str_contains($g->luas_bangunan, 'm') ? $g->luas_bangunan : $g->luas_bangunan . ' m²') : ($match['luas'] ?? '-');

                // Fasilitas / Fitur
                $features = [];
                if (!empty($g->fasilitas)) {
                    $features = array_values(array_filter(array_map('trim', preg_split('/[,;\r\n]+/', $g->fasilitas))));
                }
                if (empty($features) && !empty($match['features']) && is_array($match['features'])) {
                    $features = $match['features'];
                }
                if (empty($features)) {
                    $features = ['Pendingin Ruangan (AC)', 'Sound System', 'Koneksi Internet (Wi-Fi)'];
                }

                // Gambar: ambil seluruh foto yang diupload
                $images = [];
                $allFotos = $g->all_fotos;
                if ($allFotos && $allFotos->isNotEmpty()) {
                    foreach ($allFotos as $af) {
                        if (!empty($af['url'])) {
                            $images[] = $af['url'];
                        }
                    }
                }
                if (empty($images) && $g->foto_path) {
                    $images[] = $g->foto_path;
                }
                if (empty($images) && !empty($match['images']) && is_array($match['images'])) {
                    foreach ($match['images'] as $img) {
                        $images[] = str_starts_with($img, 'http') ? $img : asset(ltrim($img, '/'));
                    }
                }
                if (empty($images)) {
                    $images[] = asset('storage/fasilitas/gedung_aula.jpeg');
                }

                $images = array_values(array_unique($images));

                // Deskripsi
                $desc = !empty($match['description'])
                    ? $match['description']
                    : "Fasilitas {$g->nama_gedung} BPMP Provinsi Gorontalo dengan kapasitas {$capacityStr}, siap digunakan untuk berbagai kegiatan kedinasan maupun acara umum.";

                // Operasional & Kontak
                $operasional = !empty($match['operasional']) ? $match['operasional'] : 'Senin–Jumat 07.30–16.00 WITA';
                $kontak = !empty($match['kontak']) ? $match['kontak'] : '(0435) 821-555';

                // Aturan
                $rules = (!empty($match['rules']) && is_array($match['rules']))
                    ? $match['rules']
                    : [
                        'Tamu wajib melapor ke petugas keamanan / resepsionis',
                        'Berpakaian rapi, sopan, dan menjaga etika',
                        'Dilarang merokok di dalam seluruh area gedung',
                        'Menjaga ketertiban, kebersihan, dan keutuhan sarana prasarana',
                        'Konfirmasi jadwal penggunaan kepada pengelola'
                    ];

                // Harga
                $price = $g->tarif_sewa > 0
                    ? ('Rp ' . number_format($g->tarif_sewa, 0, ',', '.') . ' / hari')
                    : ($match['price'] ?? 'Khusus Kedinasan / Hubungi Pengelola');

                $facilities[] = [
                    'id' => $g->id,
                    'name' => $g->nama_gedung,
                    'category' => $normCat,
                    'location' => $g->lokasi ?: ($match['location'] ?? 'BPMP Provinsi Gorontalo'),
                    'capacity' => $capacityStr,
                    'luas' => $luasStr,
                    'operasional' => $operasional,
                    'kontak' => $kontak,
                    'description' => $desc,
                    'features' => $features,
                    'rules' => $rules,
                    'priceOld' => $match['priceOld'] ?? '',
                    'price' => $price,
                    'images' => $images,
                ];
            }
        } catch (\Throwable $e) {
            \Log::warning('Error loading Gedung for Beranda: ' . $e->getMessage());
        }

        // Sertakan sisa item FasilitasBeranda yang belum ada di Gedung (jika ada)
        if (!empty($fbMeta)) {
            foreach ($fbMeta as $key => $fbItem) {
                if (!isset($handledNames[$key])) {
                    if (!empty($fbItem['images']) && is_array($fbItem['images'])) {
                        $fbItem['images'] = array_map(function($img) {
                            return str_starts_with($img, 'http') ? $img : asset(ltrim($img, '/'));
                        }, $fbItem['images']);
                    }
                    $facilities[] = $fbItem;
                }
            }
        }

        // Fallback mutlak jika keduanya kosong
        if (empty($facilities) && class_exists(FasilitasBeranda::class)) {
            try {
                $raw = FasilitasBeranda::where('tampil', true)->orderBy('urutan')->orderBy('id')->get()->pluck('konten')->all();
                if (!empty($raw)) {
                    return $raw;
                }
            } catch (\Throwable $e) {}
        }

        return $facilities;
    }
}