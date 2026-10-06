<?php

namespace App\Imports;

use App\Models\Persediaan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PersediaanImport implements ToCollection, WithHeadingRow
{
    public int $rowsCount = 0;
    public int $insertedCount = 0;
    public int $updatedCount = 0;
    public int $skippedCount = 0;
    public ?string $lastError = null;
    public array $debugKeys = [];

    public function collection(Collection $rows)
    {
        $this->rowsCount = $rows->count();

        if ($rows->isNotEmpty()) {
            $first = $rows->first();
            $firstArr = $first instanceof Collection ? $first->toArray() : (array) $first;
            $this->debugKeys = array_keys($firstArr);
        }

        $hasKodeUnik = false;
        try {
            $hasKodeUnik = \Illuminate\Support\Facades\Schema::hasColumn('persediaan', 'kode_unik_barang');
        } catch (\Throwable $e) {
            $hasKodeUnik = false;
        }

        foreach ($rows as $index => $row) {
            if ($row instanceof Collection) {
                $row = $row->toArray();
            }

            // 1. Ambil Nama Barang secara fleksibel dari berbagai variasi header atau posisi kolom ke-3 (index 2)
            $namaBarang = $this->getValue($row, ['nama_barang', 'nama', 'uraian_barang', 'uraian', 'nama_item', 'deskripsi', 'barang'], 2);
            
            // Lewati jika nama barang kosong atau jika baris adalah duplikasi nama kolom header
            if (empty($namaBarang) || in_array(strtolower(trim($namaBarang)), ['nama_barang', 'nama barang', 'nama'])) {
                $this->skippedCount++;
                continue;
            }

            // 2. Ambil Kategori secara fleksibel atau posisi kolom ke-2 (index 1)
            $kategori = $this->getValue($row, ['kategori', 'nama_kategori', 'jenis_barang', 'jenis', 'kelompok_barang'], 1) ?? 'Persediaan';

            // 3. Ambil Kode Barang & Kode Kategori secara fleksibel atau posisi kolom ke-1 (index 0)
            $rawKode = $this->getValue($row, ['kode_unik_barang', 'kode_barang', 'kode', 'kode_unik', 'no_barang', 'barcode', 'id_barang'], 0);
            $rawKodeKategori = $this->getValue($row, ['kode_kategori', 'kd_kategori', 'kategori_kode']);

            $kodeUnik = '';
            $kodeKategori = '';
            $kodeBarang = '';

            if (!empty($rawKode) && strpos($rawKode, '-') !== false) {
                // Ada pemisah strip '-' (contoh: ATK-001)
                $posisi = strpos($rawKode, '-');
                $kodeKategori = trim(substr($rawKode, 0, $posisi));
                $kodeBarang   = trim(substr($rawKode, $posisi + 1));
                $kodeUnik     = trim($rawKode);
            } elseif (!empty($rawKodeKategori) && !empty($rawKode)) {
                // Kolom kode_kategori dan kode_barang terpisah
                $kodeKategori = trim($rawKodeKategori);
                $kodeBarang   = trim($rawKode);
                $kodeUnik     = $kodeKategori . '-' . $kodeBarang;
            } elseif (!empty($rawKode)) {
                // Hanya ada kode barang tunggal tanpa strip (contoh: 1010301001000001 atau ATK001)
                $singkatan = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $kategori), 0, 4));
                $kodeKategori = !empty($singkatan) ? $singkatan : 'UMUM';
                $kodeBarang   = trim($rawKode);
                $kodeUnik     = trim($rawKode);
            } else {
                // Kode benar-benar kosong, otomatis buatkan kode unik agar data tetap tersimpan
                $singkatan = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $kategori), 0, 4));
                $kodeKategori = !empty($singkatan) ? $singkatan : 'BRG';
                $kodeBarang   = sprintf('%04d', ($index + 1));
                $kodeUnik     = $kodeKategori . '-' . $kodeBarang;
            }

            try {
                // 4. Cari apakah barang sudah ada di database (aman walau kolom kode_unik_barang belum ada di DB)
                if ($hasKodeUnik) {
                    $persediaan = Persediaan::where('kode_unik_barang', $kodeUnik)
                        ->orWhere(function ($q) use ($kodeKategori, $kodeBarang) {
                            $q->where('kode_kategori', $kodeKategori)
                              ->where('kode_barang', $kodeBarang);
                        })
                        ->first();
                } else {
                    $persediaan = Persediaan::where('kode_kategori', $kodeKategori)
                        ->where('kode_barang', $kodeBarang)
                        ->first();
                }

                // 5. Normalisasi Nilai Harga Satuan atau posisi kolom ke-5 (index 4)
                $rawHarga = $this->getValue($row, ['harga_satuan', 'harga', 'nilai_satuan', 'satuan_harga', 'nilai'], 4) ?? 0;
                $hargaSatuan = $this->parseCurrency($rawHarga);

                // 6. Normalisasi Jumlah / Qty atau posisi kolom ke-6 (index 5)
                $jumlah = intval($this->getValue($row, ['jumlah', 'qty', 'kuantitas', 'stok', 'volume', 'banyaknya'], 5) ?? 1);
                if ($jumlah <= 0) $jumlah = 1;
                $hargaTotal = $hargaSatuan * $jumlah;

                // 7. Normalisasi Tanggal Masuk atau posisi kolom ke-4 (index 3)
                $rawTgl = $this->getValue($row, ['tanggal_masuk', 'tgl_masuk', 'tanggal', 'tgl', 'tanggal_perolehan'], 3);
                $tanggalMasuk = $this->parseDate($rawTgl) ?? now()->format('Y-m-d');

                // 8. Normalisasi Satuan atau posisi kolom ke-7 (index 6)
                $satuan = $this->getValue($row, ['satuan', 'unit', 'satuan_barang'], 6) ?? 'buah';

                $payload = [
                    'kategori'      => $kategori,
                    'kode_kategori' => $kodeKategori,
                    'kode_barang'   => $kodeBarang,
                    'nama_barang'   => $namaBarang,
                    'tanggal_masuk' => $tanggalMasuk,
                    'harga_satuan'  => $hargaSatuan,
                    'jumlah'        => $jumlah,
                    'satuan'        => $satuan,
                    'harga_total'   => $hargaTotal,
                ];

                if ($hasKodeUnik) {
                    $payload['kode_unik_barang'] = $kodeUnik;
                }

                if ($persediaan) {
                    $persediaan->update($payload);
                    $this->updatedCount++;
                } else {
                    Persediaan::create($payload);
                    $this->insertedCount++;
                }
            } catch (\Throwable $e) {
                $this->lastError = $e->getMessage();
                Log::error('Error Import Persediaan Baris ' . ($index + 1) . ': ' . $e->getMessage());
                $this->skippedCount++;
            }
        }
    }

    /**
     * Helper fleksibel untuk mencari nilai dari daftar kemungkinan nama kolom,
     * dengan fallback ke indeks urutan kolom jika nama kolom tidak cocok.
     */
    private function getValue(array $row, array $keys, ?int $fallbackIndex = null)
    {
        // 1. Cek langsung kecocokan persis nama key
        foreach ($keys as $key) {
            if (isset($row[$key]) && trim((string)$row[$key]) !== '') {
                return trim((string)$row[$key]);
            }
        }

        // 2. Cek fuzzy (abaikan huruf besar/kecil, spasi, tanda strip, atau titik)
        foreach ($row as $rowKey => $rowVal) {
            $normalizedRowKey = strtolower(trim(str_replace([' ', '-', '.', '_'], '', (string)$rowKey)));
            foreach ($keys as $key) {
                $normalizedKey = strtolower(trim(str_replace([' ', '-', '.', '_'], '', $key)));
                if ($normalizedRowKey === $normalizedKey && trim((string)$rowVal) !== '') {
                    return trim((string)$rowVal);
                }
            }
        }

        // 3. Fallback ke urutan posisi kolom (0, 1, 2, dst.) jika key tidak dikenali
        if ($fallbackIndex !== null) {
            $values = array_values($row);
            if (isset($values[$fallbackIndex]) && trim((string)$values[$fallbackIndex]) !== '') {
                return trim((string)$values[$fallbackIndex]);
            }
        }

        return null;
    }

    /**
     * Parser tanggal yang mendukung format Excel angka serial maupun string tanggal umum.
     */
    private function parseDate($date)
    {
        if (!$date) return null;
        if (is_numeric($date)) {
            try {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date)->format('Y-m-d');
            } catch (\Exception $e) {
                return null;
            }
        }
        try {
            return Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Parser nominal uang yang mendukung angka murni, format rupiah, dan pemisah ribuan.
     */
    private function parseCurrency($value): float
    {
        if (is_numeric($value)) {
            return (float) $value;
        }

        $str = trim((string) $value);
        $str = preg_replace('/[^\d.,]/', '', $str);

        if ($str === '') {
            return 0.0;
        }

        if (strpos($str, '.') !== false && strpos($str, ',') !== false) {
            if (strrpos($str, ',') > strrpos($str, '.')) {
                $str = str_replace('.', '', $str);
                $str = str_replace(',', '.', $str);
            } else {
                $str = str_replace(',', '', $str);
            }
        } elseif (strpos($str, ',') !== false) {
            if (preg_match('/,\d{3}$/', $str)) {
                $str = str_replace(',', '', $str);
            } else {
                $str = str_replace(',', '.', $str);
            }
        } elseif (strpos($str, '.') !== false) {
            if (preg_match('/\.\d{3}$/', $str)) {
                $str = str_replace('.', '', $str);
            }
        }

        return (float) $str;
    }
}
