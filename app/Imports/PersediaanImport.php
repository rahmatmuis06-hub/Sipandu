<?php

namespace App\Imports;

use App\Models\Persediaan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PersediaanImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (empty($row['kode_barang']) || empty($row['nama_barang'])) {
            return null;
        }

        try {
           $persediaan = Persediaan::where('kode_kategori', $row['kode_kategori'])
                        ->where('kode_barang', $row['kode_barang'])
                        ->first();

            $hargaSatuan = floatval(preg_replace('/[^\d.]/', '', $row['harga_satuan'] ?? 0));
            $jumlah = intval($row['jumlah'] ?? 1);
            $hargaTotal = $hargaSatuan * $jumlah;
            $tanggalMasuk = $this->parseDate($row['tanggal_masuk']) ?? now();

            if ($persediaan) {
                // Update persediaan jika kode barang sama
                $persediaan->update([
                    'kategori'      => $row['kategori'] ?? $persediaan->kategori,
                    'kode_kategori' => $row['kode_kategori'] ?? $persediaan->kode_kategori,
                    'nama_barang'   => $row['nama_barang'],
                    'tanggal_masuk' => $tanggalMasuk,
                    'harga_satuan'  => $hargaSatuan,
                    'jumlah'        => $jumlah, // Anda bisa ubah ke $persediaan->jumlah + $jumlah jika ingin sistem akumulasi
                    'satuan'        => $row['satuan'] ?? $persediaan->satuan,
                    'harga_total'   => $hargaTotal,
                ]);
                return null;
            }

            // Create baru
            return new Persediaan([
                'kode_kategori' => $row['kode_kategori'] ?? '-',
                'kategori'      => $row['kategori'] ?? '-',
                'kode_barang'   => $row['kode_barang'],
                'nama_barang'   => $row['nama_barang'],
                'tanggal_masuk' => $tanggalMasuk,
                'harga_satuan'  => $hargaSatuan,
                'jumlah'        => $jumlah,
                'satuan'        => $row['satuan'] ?? '-',
                'harga_total'   => $hargaTotal,
            ]);
        } catch (\Exception $e) {
            Log::error('Error Import Persediaan Baris: ' . json_encode($row) . ' - Error: ' . $e->getMessage());
            return null;
        }
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
