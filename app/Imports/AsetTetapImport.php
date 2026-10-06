<?php

namespace App\Imports;

use App\Models\AssetTetap;
use App\Models\DetailKendaraan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AsetTetapImport implements ToCollection, WithHeadingRow
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

        foreach ($rows as $index => $row) {
            if ($row instanceof Collection) {
                $row = $row->toArray();
            }

            // 1. Ambil Nama Barang secara fleksibel dari berbagai variasi header
            $namaBarang = $this->getValue($row, ['nama_barang', 'nama', 'nama_aset', 'uraian_barang', 'uraian', 'deskripsi', 'nama_item', 'barang']);
            
            // Lewati jika nama barang kosong sama sekali
            if (empty($namaBarang)) {
                $this->skippedCount++;
                continue;
            }

            // 2. Kode Barang fleksibel
            $kodeBarang = $this->getValue($row, ['kode_barang', 'kode', 'kode_aset', 'kd_barang', 'no_aset', 'id_barang']);
            if (empty($kodeBarang)) {
                $kodeBarang = 'AST-' . date('Y') . '-' . sprintf('%04d', $index + 1);
            }

            // 3. NUP fleksibel (wajib terisi karena NOT NULL di DB)
            $nup = $this->getValue($row, ['nup', 'no_urut_pendaftaran', 'nomor_nup', 'no_nup', 'urut']) ?? '1';

            // 4. Kategori fleksibel
            $kategori = $this->getValue($row, ['kategori', 'jenis', 'jenis_aset', 'kategori_barang', 'kelompok']) ?? 'Aset Tetap';

            // 5. Lokasi fleksibel (wajib terisi karena NOT NULL di DB)
            $lokasi = $this->getValue($row, ['lokasi', 'ruangan', 'posisi', 'tempat', 'gedung']) ?? 'BPMP Provinsi Gorontalo';

            // 6. Kondisi fleksibel
            $kondisi = strtolower($this->getValue($row, ['kondisi', 'keadaan', 'status_kondisi']) ?? 'baik');

            // 7. Status fleksibel
            $status = ucfirst($this->getValue($row, ['status', 'ketersediaan', 'status_barang']) ?? 'Tersedia');

            // 8. Merek fleksibel
            $merek = $this->getValue($row, ['merek', 'merk', 'merek_tipe', 'merk_type', 'type']);

            // 9. Nilai / Harga Perolehan (Mendukung angka mentah, format Rp, dan ribuan bertitik)
            $rawNilai = $this->getValue($row, ['nilai_perolehan', 'harga', 'harga_perolehan', 'nilai', 'harga_satuan']) ?? 0;
            $nilaiPerolehan = $this->parseCurrency($rawNilai);

            // 10. Jumlah
            $jumlah = intval($this->getValue($row, ['jumlah', 'qty', 'volume', 'kuantitas', 'banyaknya']) ?? 1);
            if ($jumlah <= 0) $jumlah = 1;

            // 11. Tanggal Perolehan & Tanggal Input
            $rawTglPerolehan = $this->getValue($row, ['tanggal_perolehan', 'tgl_perolehan', 'tanggal_pembelian', 'tahun_perolehan', 'tanggal', 'tgl']);
            $tanggalPerolehan = $this->parseDate($rawTglPerolehan) ?? now()->format('Y-m-d');

            $rawTglInput = $this->getValue($row, ['tanggal_input', 'tgl_input', 'tanggal_masuk']);
            $tanggalInput = $this->parseDate($rawTglInput) ?? now()->format('Y-m-d');

            try {
                // Cari apakah barang dengan Kode & NUP sudah ada
                $query = AssetTetap::where('kode_barang', $kodeBarang);
                if (!empty($nup)) {
                    $query->where('nup', $nup);
                }
                $aset = $query->first();

                $data = [
                    'tanggal_input'     => $tanggalInput,
                    'nama_barang'       => $namaBarang,
                    'merek'             => $merek,
                    'kategori'          => $kategori,
                    'tanggal_perolehan' => $tanggalPerolehan,
                    'nilai_perolehan'   => $nilaiPerolehan,
                    'kondisi'           => $kondisi,
                    'lokasi'            => $lokasi,
                    'jumlah'            => $jumlah,
                    'status'            => $status,
                ];

                if ($aset) {
                    $aset->update($data);
                    $this->updatedCount++;
                } else {
                    $data['kode_barang'] = $kodeBarang;
                    $data['nup']         = $nup;
                    $aset = AssetTetap::create($data);
                    $this->insertedCount++;
                }

                // ==========================================
                // DETAIL KENDARAAN (JIKA KATEGORI KENDARAAN)
                // ==========================================
                $isKendaraan = in_array(strtolower(trim($kategori)), ['kendaraan', 'alat angkutan bermotor', 'kendaraan bermotor', 'mobil', 'motor']);
                $nomorPolisi = $this->getValue($row, ['nomor_polisi', 'no_polisi', 'nopol', 'plat', 'plat_nomor']);

                if ($isKendaraan || !empty($nomorPolisi)) {
                    $aset->detailKendaraan()->updateOrCreate(
                        ['aset_tetap_id' => $aset->id],
                        [
                            'nomor_polisi' => $nomorPolisi,
                            'no_bpkb'      => $this->getValue($row, ['nomor_bpkb', 'no_bpkb', 'bpkb']),
                            'nomor_rangka' => $this->getValue($row, ['nomor_rangka', 'no_rangka', 'rangka']),
                            'nomor_mesin'  => $this->getValue($row, ['nomor_mesin', 'no_mesin', 'mesin']),
                        ]
                    );
                }
            } catch (\Exception $e) {
                Log::error('Error Import Aset Tetap Baris ' . ($index + 1) . ': ' . $e->getMessage());
                $this->skippedCount++;
            }
        }
    }

    /**
     * Helper fleksibel untuk mencari nilai dari daftar kemungkinan nama kolom.
     */
    private function getValue(array $row, array $keys)
    {
        // Cek langsung kecocokan persis
        foreach ($keys as $key) {
            if (isset($row[$key]) && trim((string)$row[$key]) !== '') {
                return trim((string)$row[$key]);
            }
        }

        // Cek fuzzy (case insensitive atau spasi diganti underscore)
        foreach ($row as $rowKey => $rowVal) {
            $normalizedRowKey = strtolower(trim(str_replace([' ', '-', '.'], '_', (string)$rowKey)));
            foreach ($keys as $key) {
                if ($normalizedRowKey === strtolower($key) && trim((string)$rowVal) !== '') {
                    return trim((string)$rowVal);
                }
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