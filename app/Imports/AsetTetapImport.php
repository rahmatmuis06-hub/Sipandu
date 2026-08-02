<?php

namespace App\Imports;

use App\Models\AssetTetap;
use App\Models\DetailKendaraan; // Pastikan model ini dipanggil
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AsetTetapImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Lewati jika kunci utama kosong
        if (empty($row['kode_barang']) || empty($row['nama_barang'])) {
            return null;
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
            'tanggal_input'     => $this->parseDate($row['tanggal_input']) ?? now(),
            'nama_barang'       => $row['nama_barang'],
            'merek'             => $row['merek'] ?? null,
            'kategori'          => $row['kategori'] ?? null,
            'tanggal_perolehan' => $this->parseDate($row['tanggal_perolehan']),
            'nilai_perolehan'   => $row['nilai_perolehan'] ?? 0,
            'kondisi'           => strtolower($row['kondisi'] ?? 'baik'),
            'lokasi'            => $row['lokasi'] ?? null,
            'jumlah'            => $row['jumlah'] ?? 1,
            'status'            => ucfirst($row['status'] ?? 'Tersedia'),
        ];

        // Jika barang dengan Kode & NUP tersebut sudah ada, Update datanya
        if ($aset) {
            $aset->update($data);
        } 
        // Jika belum ada, langsung simpan (Create) agar kita mendapatkan ID-nya
        else {
            $data['kode_barang'] = $row['kode_barang'];
            $data['nup'] = $row['nup'] ?? null;
            $aset = AssetTetap::create($data);
        }

        // ==========================================
        // LOGIKA PENYIMPANAN DETAIL KENDARAAN
        // ==========================================
        if (isset($row['kategori']) && in_array(strtolower(trim($row['kategori'])), ['kendaraan', 'ALAT ANGKUTAN BERMOTOR'])) {
            $aset->detailKendaraan()->updateOrCreate(
                ['asset_tetap_id' => $aset->id],
                [
                    'nomor_polisi' => $row['nomor_polisi'] ?? null,
                    // Mengantisipasi kemungkinan header di Excel "Nomor BPKB" atau "No BPKB"
                    'no_bpkb'      => $row['nomor_bpkb'] ?? ($row['no_bpkb'] ?? null),
                    'nomor_rangka' => $row['nomor_rangka'] ?? null,
                    'nomor_mesin'  => $row['nomor_mesin'] ?? null,
                ]
            );
        } else {
            // Jika kategori di-update dari 'Kendaraan' ke tipe lain, hapus detail kendaraannya jika ada
            if ($aset->detailKendaraan) {
                $aset->detailKendaraan()->delete();
            }
        }

        // Kembalikan null karena proses insert/update sudah kita tangani secara manual di atas
        return null;
    }

    private function parseDate($date)
    {
        if (!$date) return null;

        if (is_numeric($date)) {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date)->format('Y-m-d');
        }

        return Carbon::parse($date)->format('Y-m-d');
    }
}