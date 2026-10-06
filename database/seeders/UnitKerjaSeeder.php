<?php

namespace Database\Seeders;

use App\Models\UnitKerja;
use Illuminate\Database\Seeder;

class UnitKerjaSeeder extends Seeder
{
    /**
     * Seed data unit kerja di BPMP Provinsi Gorontalo.
     */
    public function run(): void
    {
        $units = [
            ["nama_unit" => "Subbagian Umum", "lokasi" => "Gedung Kantor Utama Lantai 1"],
            ["nama_unit" => "Tim Kerja Kemitraan dan Advokasi", "lokasi" => "Gedung Kantor Utama Lantai 2"],
            ["nama_unit" => "Tim Kerja Penjaminan Mutu Pendidikan", "lokasi" => "Gedung Ponuwa"],
            ["nama_unit" => "Tim Kerja Transformasi Pembelajaran", "lokasi" => "Gedung Ponuwa Lantai 2"],
            ["nama_unit" => "Tim Kerja Data, Informasi dan Publikasi (Humas)", "lokasi" => "Gedung Kantor Utama"],
            ["nama_unit" => "Tim Kerja Perencanaan, Keuangan dan Kepegawaian", "lokasi" => "Gedung Kantor Utama Lantai 1"],
            ["nama_unit" => "Tim Kerja Sarana, Prasarana dan BMN", "lokasi" => "Gedung Kantor Utama"],
            ["nama_unit" => "Tim Kerja PAUD dan Nonformal", "lokasi" => "Gedung Ponuwa"],
            ["nama_unit" => "Tim Kerja Pendidikan Dasar (SD)", "lokasi" => "Gedung Ponuwa"],
            ["nama_unit" => "Tim Kerja Pendidikan Menengah (SMP/SMA)", "lokasi" => "Gedung Ponuwa"],
        ];

        foreach ($units as $unit) {
            UnitKerja::firstOrCreate(
                ["nama_unit" => $unit["nama_unit"]],
                ["lokasi" => $unit["lokasi"]]
            );
        }

        $this->command->info("✅  " . count($units) . " unit kerja berhasil di-seed.");
    }
}
