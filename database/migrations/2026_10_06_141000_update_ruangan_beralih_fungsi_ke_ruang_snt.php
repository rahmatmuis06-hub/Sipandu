<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Update data Aset Tetap yang berlokasi di ruangan yang dialihfungsikan menjadi Ruang SNT
        if (Schema::hasTable('aset_tetap')) {
            DB::table('aset_tetap')
                ->where('lokasi', 'like', '%laboratorium%')
                ->orWhere('lokasi', 'like', '%lab %')
                ->update(['lokasi' => 'Ruang SNT']);
        }

        // 2. Tambah / update master Gedung/Ruangan untuk Ruang SNT
        if (Schema::hasTable('gedung')) {
            $existingGedung = DB::table('gedung')
                ->where('nama_gedung', 'like', '%Ruang SNT%')
                ->first();

            if (!$existingGedung) {
                // Cek apakah ada eks Lab yang ingin dialihfungsikan
                $eksLab = DB::table('gedung')
                    ->where('nama_gedung', 'like', '%Laboratorium%')
                    ->first();

                if ($eksLab) {
                    DB::table('gedung')->where('id', $eksLab->id)->update([
                        'nama_gedung'   => 'Ruang SNT BPMP Gorontalo',
                        'lokasi'        => 'Gedung SNT Lt. 2 BPMP Gorontalo',
                        'kategori'      => 'ruang',
                        'kapasitas'     => 40,
                        'fasilitas'     => 'AC, Smart TV/Proyektor, Sound System, Meja Rapat, Kursi Kerja, WiFi',
                        'ketersediaan'  => 'Tersedia',
                        'updated_at'    => now(),
                    ]);
                } else {
                    DB::table('gedung')->insert([
                        'nama_gedung'   => 'Ruang SNT BPMP Gorontalo',
                        'lokasi'        => 'Gedung SNT BPMP Gorontalo',
                        'luas_bangunan' => '65',
                        'tarif_sewa'    => 500000,
                        'kapasitas'     => 40,
                        'ketersediaan'  => 'Tersedia',
                        'fasilitas'     => 'AC, Smart TV/Proyektor, Sound System, Meja Rapat, Kursi Kerja, WiFi',
                        'kategori'      => 'ruang',
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ]);
                }
            }
        }

        // 3. Tambah / perbarui Unit Kerja dengan Lokasi Ruang SNT jika ada tabel unit_kerjas
        if (Schema::hasTable('unit_kerjas')) {
            $existingUnit = DB::table('unit_kerjas')
                ->where('nama_unit', 'like', '%SNT%')
                ->orWhere('lokasi', 'like', '%Ruang SNT%')
                ->first();

            if (!$existingUnit) {
                DB::table('unit_kerjas')->insert([
                    'nama_unit'  => 'Tim Kerja SNT (Standar & Tata Kelola)',
                    'lokasi'     => 'Ruang SNT BPMP Gorontalo',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // 4. Update data Fasilitas Beranda jika ada
        if (Schema::hasTable('fasilitas_beranda')) {
            $fasilitas = DB::table('fasilitas_beranda')->get();
            foreach ($fasilitas as $f) {
                $konten = json_decode($f->konten, true);
                if (is_array($konten) && isset($konten['name']) && stripos($konten['name'], 'laboratorium') !== false) {
                    $konten['name'] = 'Ruang SNT BPMP Gorontalo';
                    $konten['description'] = 'Ruangan SNT yang representatif dan nyaman, dilengkapi sarana multimedia, proyektor, serta AC untuk rapat koordinasi dan kegiatan penjaminan mutu.';
                    $konten['location'] = 'Gedung SNT BPMP Gorontalo';
                    DB::table('fasilitas_beranda')->where('id', $f->id)->update([
                        'konten' => json_encode($konten),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No hard rollback required
    }
};
