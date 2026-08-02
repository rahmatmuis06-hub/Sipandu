<?php

namespace App\Imports;

use App\Models\AssetTetap;
use App\Models\DetailKendaraan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

// 1. Ubah ToModel menjadi ToCollection
class AsetTetapImport implements ToCollection, WithHeadingRow
{
    // 2. Ubah public function model() menjadi collection() dan loop foreach
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Lewati jika kunci utama kosong (Pastikan header di Excel sama persis)
            dd($row);
            if (empty($row['kode_barang']) || empty($row['nama_barang'])) {
                continue; 
            }

            // Cari berdasarkan KODE BARANG dan NUP
            $query = AssetTetap::where('kode_barang', $row['kode_barang']);
            if (!empty($row['nup'])) {
                $query->where('nup', $row['nup']);
            } else {
                $query->whereNull('nup');
            }
            
            $aset = $query->first();

            $data = [
                'tanggal_input'     => $this->parseDate($row['tanggal_input'] ?? null) ?? now(),
                'nama_barang'       => $row['nama_barang'],
                'merek'             => $row['merek'] ?? null,
                'kategori'          => $row['kategori'] ?? null,
                'tanggal_perolehan' => $this->parseDate($row['tanggal_perolehan'] ?? null),
                'nilai_perolehan'   => $row['nilai_perolehan'] ?? 0,
                'kondisi'           => strtolower($row['kondisi'] ?? 'baik'),
                'lokasi'            => $row['lokasi'] ?? null,
                'jumlah'            => $row['jumlah'] ?? 1,
                'status'            => ucfirst($row['status'] ?? 'Tersedia'),
            ];

            // Jika barang dengan Kode & NUP sudah ada, Update. Jika belum, Create.
            if ($aset) {
                $aset->update($data);
            } else {
                $data['kode_barang'] = $row['kode_barang'];
                $data['nup'] = $row['nup'] ?? null;
                $aset = AssetTetap::create($data);
            }

            // ==========================================
            // LOGIKA PENYIMPANAN DETAIL KENDARAAN
            // ==========================================
            if (isset($row['kategori']) && in_array(strtolower(trim($row['kategori'])), ['kendaraan', 'alat angkutan bermotor'])) {
                $aset->detailKendaraan()->updateOrCreate(
                    ['aset_tetap_id' => $aset->id], // ✅ FIXED: Typo diperbaiki menjadi 'aset_tetap_id'
                    [
                        'nomor_polisi' => $row['nomor_polisi'] ?? null,
                        'no_bpkb'      => $row['nomor_bpkb'] ?? ($row['no_bpkb'] ?? null),
                        'nomor_rangka' => $row['nomor_rangka'] ?? null,
                        'nomor_mesin'  => $row['nomor_mesin'] ?? null,
                    ]
                );
            } else {
                if ($aset->detailKendaraan) {
                    $aset->detailKendaraan()->delete();
                }
            }
        }
    }

    private function parseDate($date)
    {
        if (!$date) return null;

        if (is_numeric($date)) {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date)->format('Y-m-d');
        }
        
        // Menambahkan blok try-catch agar jika format tanggal string tidak lazim, proses tidak crash
        try {
            return Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}