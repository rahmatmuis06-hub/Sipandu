<?php

namespace Database\Seeders;

use App\Models\FasilitasBeranda;
use App\Models\Gedung;
use Illuminate\Database\Seeder;

class GedungFromFasilitasSeeder extends Seeder
{
    public function run(): void
    {
        $fasilitasItems = FasilitasBeranda::orderBy('urutan')->orderBy('id')->get();
        if ($fasilitasItems->isEmpty()) {
            return;
        }

        foreach ($fasilitasItems as $item) {
            $k = $item->konten;
            if (empty($k['name'])) continue;

            $kapasitas = 50;
            if (!empty($k['capacity'])) {
                if (preg_match('/(\d+)\s*-\s*(\d+)/', $k['capacity'], $m)) {
                    $kapasitas = (int)$m[2];
                } elseif (preg_match('/(\d+)/', $k['capacity'], $m)) {
                    $kapasitas = (int)$m[1];
                }
            }

            $luas = '100';
            if (!empty($k['luas'])) {
                $cleaned = trim(str_replace(['m²', 'm2', 'M²', 'M2'], '', $k['luas']));
                if (!empty($cleaned)) {
                    $luas = $cleaned;
                }
            }

            $fotoUrl = null;
            if (!empty($k['images']) && is_array($k['images']) && !empty($k['images'][0])) {
                $fotoUrl = ltrim(preg_replace('#^/?storage/#', '', $k['images'][0]), '/');
            }

            $fasilitasText = null;
            if (!empty($k['features']) && is_array($k['features'])) {
                $fasilitasText = implode(', ', array_filter($k['features']));
            }

            $kategori = $k['category'] ?? 'ruang';

            $tarifMap = [
                'ruang' => 1500000,
                'kelas' => 500000,
                'penginapan' => 500000,
                'ruang_makan' => 300000,
                'outdoor' => 100000,
                'lapangan_Upacara' => 200000,
                'kantor' => 0,
                'sarana_ibadah' => 0,
                'kesehatan' => 0,
                'gedung' => 0,
            ];
            $tarif = $tarifMap[$kategori] ?? 0;

            $gedung = Gedung::where('nama_gedung', $k['name'])->first();
            if ($gedung) {
                $gedung->update([
                    'lokasi' => $k['location'] ?? $gedung->lokasi,
                    'luas_bangunan' => $luas,
                    'kapasitas' => $kapasitas,
                    'kategori' => $kategori,
                    'fasilitas' => $fasilitasText,
                    'foto_url' => $fotoUrl ?? $gedung->foto_url,
                ]);
            } else {
                Gedung::create([
                    'nama_gedung' => $k['name'],
                    'foto_url' => $fotoUrl,
                    'lokasi' => $k['location'] ?? 'BPMP Gorontalo',
                    'luas_bangunan' => $luas,
                    'tarif_sewa' => $tarif,
                    'kapasitas' => $kapasitas,
                    'ketersediaan' => 'Tersedia',
                    'fasilitas' => $fasilitasText,
                    'kategori' => $kategori,
                ]);
            }
        }
    }
}
