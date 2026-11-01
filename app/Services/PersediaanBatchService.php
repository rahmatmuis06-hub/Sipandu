<?php

namespace App\Services;

use App\Models\Persediaan;
use App\Models\PersediaanBatch;
use App\Models\TransaksiKeluarPersediaan;
use App\Models\TransaksiMasukPersediaan;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class PersediaanBatchService
{
    protected static bool $tableEnsured = false;
    protected static bool $settingsEnsured = false;
    protected static ?bool $fifoEnabledCache = null;

    /**
     * Pastikan tabel system_settings tersedia (default: FIFO nonaktif = '0').
     */
    public static function ensureSettingsTable(): void
    {
        if (self::$settingsEnsured) {
            return;
        }

        try {
            if (!Schema::hasTable('system_settings')) {
                Schema::create('system_settings', function (Blueprint $table) {
                    $table->id();
                    $table->string('key', 100)->unique();
                    $table->text('value')->nullable();
                    $table->timestamps();
                });
            }

            $exists = DB::table('system_settings')->where('key', 'fifo_persediaan_enabled')->exists();
            if (!$exists) {
                DB::table('system_settings')->insert([
                    'key'        => 'fifo_persediaan_enabled',
                    'value'      => '0',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            self::$settingsEnsured = true;
        } catch (\Throwable $e) {
            // Fallback aman jika DB belum siap
        }
    }

    /**
     * Cek apakah fitur FIFO Persediaan sedang diaktifkan oleh Super Admin (Default: false / disembunyikan).
     */
    public static function isFifoEnabled(): bool
    {
        if (self::$fifoEnabledCache !== null) {
            return self::$fifoEnabledCache;
        }

        self::ensureSettingsTable();

        try {
            if (Schema::hasTable('system_settings')) {
                $val = DB::table('system_settings')->where('key', 'fifo_persediaan_enabled')->value('value');
                self::$fifoEnabledCache = in_array((string) $val, ['1', 'true', 'on'], true);
                return self::$fifoEnabledCache;
            }
        } catch (\Throwable $e) {
            // Default nonaktif
        }

        self::$fifoEnabledCache = false;
        return false;
    }

    /**
     * Aktifkan atau nonaktifkan fitur FIFO Persediaan dari panel Super Admin & hitung ulang nilai master.
     */
    public static function setFifoEnabled(bool $enabled): void
    {
        self::ensureSettingsTable();
        self::ensureTable();

        DB::table('system_settings')->updateOrInsert(
            ['key' => 'fifo_persediaan_enabled'],
            [
                'value'      => $enabled ? '1' : '0',
                'updated_at' => now(),
            ]
        );

        self::$fifoEnabledCache = $enabled;

        // Sinkronkan ulang seluruh item persediaan agar harga_total & harga_satuan sesuai mode aktif/nonaktif
        try {
            if (Schema::hasTable('persediaan') && Schema::hasTable('persediaan_batches')) {
                self::syncAllUnbatched();
                $items = Persediaan::all();
                foreach ($items as $item) {
                    self::recalculatePersediaan($item);
                }
            }
        } catch (\Throwable $e) {
            // Abaikan jika gagal rekalkulasi massal
        }
    }

    /**
     * Pastikan tabel persediaan_batches & kolom pendukung tersedia (aman di hosting sebelum php artisan migrate).
     */
    public static function ensureTable(): void
    {
        if (self::$tableEnsured) {
            return;
        }

        try {
            if (!Schema::hasTable('persediaan_batches')) {
                Schema::create('persediaan_batches', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('persediaan_id')->index();
                    $table->unsignedBigInteger('transaksi_masuk_id')->nullable()->index();
                    $table->date('tanggal_masuk');
                    $table->decimal('harga_satuan', 15, 2)->default(0);
                    $table->integer('jumlah_awal')->default(0);
                    $table->integer('jumlah_sisa')->default(0);
                    $table->string('sumber', 50)->default('stok_awal');
                    $table->string('keterangan', 255)->nullable();
                    $table->timestamps();
                });
            }

            if (Schema::hasTable('transaksi_keluar_persediaan') && !Schema::hasColumn('transaksi_keluar_persediaan', 'batch_alokasi')) {
                Schema::table('transaksi_keluar_persediaan', function (Blueprint $table) {
                    $table->text('batch_alokasi')->nullable();
                });
            }

            self::$tableEnsured = true;
        } catch (\Throwable $e) {
            // Abaikan jika koneksi DB sedang tidak siap
        }
    }

    /**
     * Sinkronisasi otomatis seluruh barang persediaan lama yang belum memiliki data batch.
     * Sangat ringan karena hanya memproses baris persediaan yang belum punya relasi batches.
     */
    public static function syncAllUnbatched(): void
    {
        self::ensureTable();
        self::ensureSettingsTable();

        try {
            if (!Schema::hasTable('persediaan') || !Schema::hasTable('persediaan_batches')) {
                return;
            }

            $unbatchedItems = Persediaan::whereDoesntHave('batches')->get();
            foreach ($unbatchedItems as $item) {
                self::ensureBatchesForItem($item);
            }

            // Sinkronisasi satu kali agar data eksisting yang sebelumnya terhitung (jumlah * hargaTerbaru)
            // otomatis disesuaikan menjadi akumulasi harga lama + harga baru / rata-rata campuran (Cara 2)
            if (Schema::hasTable('system_settings')) {
                $alreadySynced = DB::table('system_settings')
                    ->where('key', 'persediaan_moving_avg_v1_synced')
                    ->value('value');

                if ($alreadySynced !== '1') {
                    $allItems = Persediaan::all();
                    foreach ($allItems as $item) {
                        self::ensureBatchesForItem($item);
                        self::recalculatePersediaan($item);
                    }

                    DB::table('system_settings')->updateOrInsert(
                        ['key' => 'persediaan_moving_avg_v1_synced'],
                        [
                            'value'      => '1',
                            'updated_at' => now(),
                        ]
                    );
                }
            }
        } catch (\Throwable $e) {
            // Jangan gagalkan render halaman jika ada kendala minor
        }
    }

    /**
     * Pastikan 1 item Persediaan sudah memiliki batch awal (termasuk rekonstruksi dari riwayat TransaksiMasuk jika ada).
     */
    public static function ensureBatchesForItem(Persediaan $persediaan, ?int $excludeTransaksiMasukId = null): void
    {
        self::ensureTable();

        $existingCount = PersediaanBatch::where('persediaan_id', $persediaan->id)->count();
        if ($existingCount > 0) {
            return;
        }

        $stokSaatIni = max(0, (int) $persediaan->jumlah);
        $hargaMaster = (float) ($persediaan->getRawOriginal('harga_satuan') ?? $persediaan->harga_satuan ?? 0);
        $tglMaster = $persediaan->tanggal_masuk
            ? ($persediaan->tanggal_masuk instanceof \Carbon\Carbon ? $persediaan->tanggal_masuk->format('Y-m-d') : (string) $persediaan->tanggal_masuk)
            : now()->toDateString();

        $riwayatQuery = TransaksiMasukPersediaan::where('kode_kategori', $persediaan->kode_kategori)
            ->where('kode_barang', $persediaan->kode_barang)
            ->orderBy('tanggal_input', 'asc')
            ->orderBy('id', 'asc');

        if ($excludeTransaksiMasukId) {
            $riwayatQuery->where('id', '!=', $excludeTransaksiMasukId);
        }

        $riwayatMasuk = $riwayatQuery->get();

        if ($riwayatMasuk->isEmpty()) {
            PersediaanBatch::create([
                'persediaan_id'      => $persediaan->id,
                'transaksi_masuk_id' => null,
                'tanggal_masuk'      => $tglMaster,
                'harga_satuan'       => $hargaMaster,
                'jumlah_awal'        => $stokSaatIni,
                'jumlah_sisa'        => $stokSaatIni,
                'sumber'             => 'stok_awal',
                'keterangan'         => 'Stok Awal Persediaan',
            ]);

            self::recalculatePersediaan($persediaan);
            return;
        }

        $totalHistorisMasuk = (int) $riwayatMasuk->sum('jumlah_masuk');

        if ($stokSaatIni > $totalHistorisMasuk) {
            $selisihAwal = $stokSaatIni - $totalHistorisMasuk;
            $tglPalingAwal = $riwayatMasuk->first()->tanggal_input
                ? ($riwayatMasuk->first()->tanggal_input instanceof \Carbon\Carbon
                    ? $riwayatMasuk->first()->tanggal_input->format('Y-m-d')
                    : (string) $riwayatMasuk->first()->tanggal_input)
                : $tglMaster;

            PersediaanBatch::create([
                'persediaan_id'      => $persediaan->id,
                'transaksi_masuk_id' => null,
                'tanggal_masuk'      => $tglPalingAwal,
                'harga_satuan'       => $hargaMaster,
                'jumlah_awal'        => $selisihAwal,
                'jumlah_sisa'        => $selisihAwal,
                'sumber'             => 'stok_awal',
                'keterangan'         => 'Stok Awal Persediaan',
            ]);

            foreach ($riwayatMasuk as $trx) {
                $tglTrx = $trx->tanggal_input instanceof \Carbon\Carbon
                    ? $trx->tanggal_input->format('Y-m-d')
                    : (string) $trx->tanggal_input;
                $hargaTrx = (float) ($trx->getRawOriginal('harga_satuan') ?? $trx->harga_satuan ?? 0);

                PersediaanBatch::create([
                    'persediaan_id'      => $persediaan->id,
                    'transaksi_masuk_id' => $trx->id,
                    'tanggal_masuk'      => $tglTrx,
                    'harga_satuan'       => $hargaTrx,
                    'jumlah_awal'        => (int) $trx->jumlah_masuk,
                    'jumlah_sisa'        => (int) $trx->jumlah_masuk,
                    'sumber'             => 'transaksi_masuk',
                    'keterangan'         => 'Transaksi Masuk #' . $trx->id,
                ]);
            }
        } else {
            // Stok saat ini <= total historis masuk: hitung pemakaian secara FIFO dari batch paling awal
            $sudahKeluar = $totalHistorisMasuk - $stokSaatIni;

            foreach ($riwayatMasuk as $trx) {
                $jmlMasuk = (int) $trx->jumlah_masuk;
                $terpakaiBatchIni = min($sudahKeluar, $jmlMasuk);
                $sisaBatchIni = $jmlMasuk - $terpakaiBatchIni;
                $sudahKeluar -= $terpakaiBatchIni;

                $tglTrx = $trx->tanggal_input instanceof \Carbon\Carbon
                    ? $trx->tanggal_input->format('Y-m-d')
                    : (string) $trx->tanggal_input;
                $hargaTrx = (float) ($trx->getRawOriginal('harga_satuan') ?? $trx->harga_satuan ?? 0);

                PersediaanBatch::create([
                    'persediaan_id'      => $persediaan->id,
                    'transaksi_masuk_id' => $trx->id,
                    'tanggal_masuk'      => $tglTrx,
                    'harga_satuan'       => $hargaTrx,
                    'jumlah_awal'        => $jmlMasuk,
                    'jumlah_sisa'        => $sisaBatchIni,
                    'sumber'             => 'transaksi_masuk',
                    'keterangan'         => 'Transaksi Masuk #' . $trx->id,
                ]);
            }
        }

        self::recalculatePersediaan($persediaan);
    }

    /**
     * Buat batch awal untuk barang baru yang dibuat dari menu Data Persediaan atau Import Excel.
     */
    public static function recordInitialBatch(Persediaan $persediaan, string $sumber = 'stok_awal', ?string $keterangan = null): PersediaanBatch
    {
        self::ensureTable();

        $tgl = $persediaan->tanggal_masuk instanceof \Carbon\Carbon
            ? $persediaan->tanggal_masuk->format('Y-m-d')
            : ($persediaan->tanggal_masuk ?: now()->toDateString());
        $harga = (float) ($persediaan->getRawOriginal('harga_satuan') ?? $persediaan->harga_satuan ?? 0);
        $qty = max(0, (int) $persediaan->jumlah);

        $batch = PersediaanBatch::create([
            'persediaan_id'      => $persediaan->id,
            'transaksi_masuk_id' => null,
            'tanggal_masuk'      => $tgl,
            'harga_satuan'       => $harga,
            'jumlah_awal'        => $qty,
            'jumlah_sisa'        => $qty,
            'sumber'             => $sumber,
            'keterangan'         => $keterangan ?? 'Stok Awal Persediaan',
        ]);

        self::recalculatePersediaan($persediaan);

        return $batch;
    }

    /**
     * Catat Transaksi Masuk sebagai batch harga tersendiri tanpa menimpa nilai batch stok lama.
     */
    public static function recordTransaksiMasuk(TransaksiMasukPersediaan $transaksi): Persediaan
    {
        self::ensureTable();

        $persediaan = Persediaan::where('kode_kategori', $transaksi->kode_kategori)
            ->where('kode_barang', $transaksi->kode_barang)
            ->lockForUpdate()
            ->first();

        $tglMasuk = $transaksi->tanggal_input instanceof \Carbon\Carbon
            ? $transaksi->tanggal_input->format('Y-m-d')
            : (string) $transaksi->tanggal_input;
        $hargaMasuk = (float) ($transaksi->getRawOriginal('harga_satuan') ?? $transaksi->harga_satuan ?? 0);
        $qtyMasuk = (int) $transaksi->jumlah_masuk;

        if (!$persediaan) {
            $persediaan = Persediaan::create([
                'kode_kategori' => $transaksi->kode_kategori,
                'kategori'      => $transaksi->kategori,
                'kode_barang'   => $transaksi->kode_barang,
                'nama_barang'   => $transaksi->nama_barang,
                'satuan'        => $transaksi->satuan,
                'tanggal_masuk' => $tglMasuk,
                'harga_satuan'  => $hargaMasuk,
                'jumlah'        => 0,
                'harga_total'   => 0,
            ]);
        } else {
            // Pastikan stok eksisting sudah tercatat di batch (kecualikan transaksi baru ini agar tidak dobel)
            self::ensureBatchesForItem($persediaan, $transaksi->id);

            $persediaan->update([
                'kode_kategori' => $transaksi->kode_kategori,
                'kategori'      => $transaksi->kategori,
                'nama_barang'   => $transaksi->nama_barang,
                'satuan'        => $transaksi->satuan,
                'tanggal_masuk' => $tglMasuk,
            ]);
        }

        PersediaanBatch::create([
            'persediaan_id'      => $persediaan->id,
            'transaksi_masuk_id' => $transaksi->id,
            'tanggal_masuk'      => $tglMasuk,
            'harga_satuan'       => $hargaMasuk,
            'jumlah_awal'        => $qtyMasuk,
            'jumlah_sisa'        => $qtyMasuk,
            'sumber'             => 'transaksi_masuk',
            'keterangan'         => 'Transaksi Masuk #' . $transaksi->id,
        ]);

        self::recalculatePersediaan($persediaan);

        return $persediaan;
    }

    /**
     * Perbarui Transaksi Masuk beserta batch harganya secara presisi.
     */
    public static function updateTransaksiMasuk(TransaksiMasukPersediaan $transaksiLama, array $newData): void
    {
        self::ensureTable();

        $persediaanLama = Persediaan::where('kode_kategori', $transaksiLama->kode_kategori)
            ->where('kode_barang', $transaksiLama->kode_barang)
            ->lockForUpdate()
            ->first();

        if ($persediaanLama) {
            self::ensureBatchesForItem($persediaanLama);
        }

        $batchLama = PersediaanBatch::where('transaksi_masuk_id', $transaksiLama->id)
            ->lockForUpdate()
            ->first();

        $barangTetapSama = $transaksiLama->kode_kategori === $newData['kode_kategori']
            && $transaksiLama->kode_barang === $newData['kode_barang'];

        if ($batchLama && $barangTetapSama && $persediaanLama) {
            $sudahTerpakai = max(0, (int) $batchLama->jumlah_awal - (int) $batchLama->jumlah_sisa);
            $jumlahBaru = (int) $newData['jumlah_masuk'];

            if ($jumlahBaru < $sudahTerpakai) {
                throw ValidationException::withMessages([
                    'jumlah_masuk' => "Jumlah masuk tidak boleh lebih kecil dari stok batch yang sudah terpakai ({$sudahTerpakai} {$persediaanLama->satuan}).",
                ]);
            }

            $transaksiLama->update($newData);

            $batchLama->update([
                'tanggal_masuk' => $newData['tanggal_input'],
                'harga_satuan'  => (float) $newData['harga_satuan'],
                'jumlah_awal'   => $jumlahBaru,
                'jumlah_sisa'   => $jumlahBaru - $sudahTerpakai,
            ]);

            $persediaanLama->update([
                'kategori'    => $newData['kategori'],
                'nama_barang' => $newData['nama_barang'],
                'satuan'      => $newData['satuan'],
            ]);

            self::recalculatePersediaan($persediaanLama);
            return;
        }

        // Jika kode barang berubah atau batch lama belum terhubung langsung: batalkan batch lama lalu buat baru
        self::revertTransaksiMasuk($transaksiLama);
        $transaksiLama->update($newData);
        self::recordTransaksiMasuk($transaksiLama->fresh());
    }

    /**
     * Batalkan / hapus dampak Transaksi Masuk dari batch dan master Persediaan.
     */
    public static function revertTransaksiMasuk(TransaksiMasukPersediaan $transaksi): void
    {
        self::ensureTable();

        $persediaan = Persediaan::where('kode_kategori', $transaksi->kode_kategori)
            ->where('kode_barang', $transaksi->kode_barang)
            ->lockForUpdate()
            ->first();

        if (!$persediaan) {
            throw ValidationException::withMessages([
                'jumlah_masuk' => 'Data persediaan untuk transaksi ini tidak ditemukan.',
            ]);
        }

        self::ensureBatchesForItem($persediaan);

        $batch = PersediaanBatch::where('transaksi_masuk_id', $transaksi->id)
            ->lockForUpdate()
            ->first();

        if ($batch) {
            if ($batch->jumlah_sisa < $batch->jumlah_awal) {
                throw ValidationException::withMessages([
                    'jumlah_masuk' => 'Transaksi tidak dapat diubah atau dihapus karena sebagian stok dari batch harga ini sudah digunakan.',
                ]);
            }

            $batch->delete();
        } else {
            if ($persediaan->jumlah < $transaksi->jumlah_masuk) {
                throw ValidationException::withMessages([
                    'jumlah_masuk' => 'Transaksi tidak dapat diubah atau dihapus karena sebagian stok sudah digunakan.',
                ]);
            }

            // Kurangi dari batch terbaru yang memiliki sisa stok
            $sisaHapus = (int) $transaksi->jumlah_masuk;
            $batches = PersediaanBatch::where('persediaan_id', $persediaan->id)
                ->where('jumlah_sisa', '>', 0)
                ->orderBy('tanggal_masuk', 'desc')
                ->orderBy('id', 'desc')
                ->lockForUpdate()
                ->get();

            foreach ($batches as $b) {
                if ($sisaHapus <= 0) {
                    break;
                }
                $potong = min($sisaHapus, (int) $b->jumlah_sisa);
                $b->jumlah_sisa -= $potong;
                $b->jumlah_awal = max($b->jumlah_sisa, (int) $b->jumlah_awal - $potong);
                if ($b->jumlah_awal <= 0 && $b->jumlah_sisa <= 0) {
                    $b->delete();
                } else {
                    $b->save();
                }
                $sisaHapus -= $potong;
            }
        }

        $remainingBatchCount = PersediaanBatch::where('persediaan_id', $persediaan->id)->count();
        $remainingSisa = (int) PersediaanBatch::where('persediaan_id', $persediaan->id)->sum('jumlah_sisa');

        if ($remainingBatchCount === 0 && $remainingSisa === 0) {
            $persediaan->delete();
            return;
        }

        self::recalculatePersediaan($persediaan);
    }

    /**
     * Potong stok persediaan menggunakan metode FIFO (First-In, First-Out).
     * Mengembalikan harga rata-rata tertimbang keluaran, total nilai FIFO akurat, dan rincian alokasi batch.
     *
     * @return array{harga_satuan: float, total_nilai: float, alokasi: array<int, array<string, mixed>>}
     */
    public static function deductFifo(Persediaan $persediaan, int $qtyKeluar): array
    {
        self::ensureTable();

        $persediaan = Persediaan::lockForUpdate()->findOrFail($persediaan->id);
        self::ensureBatchesForItem($persediaan);
        self::recalculatePersediaan($persediaan);
        $persediaan->refresh();

        $stokSebelum = max(0, (int) $persediaan->jumlah);
        $hargaMasterSebelum = (float) ($persediaan->getRawOriginal('harga_satuan') ?? $persediaan->harga_satuan ?? 0);
        $nilaiMasterSebelum = (float) ($persediaan->getRawOriginal('harga_total') ?? $persediaan->harga_total ?? ($stokSebelum * $hargaMasterSebelum));

        if ($persediaan->jumlah < $qtyKeluar) {
            throw ValidationException::withMessages([
                'jumlah_keluar' => "Stok barang '{$persediaan->nama_barang}' tidak mencukupi (Tersedia: {$persediaan->jumlah}).",
            ]);
        }

        $activeBatches = PersediaanBatch::where('persediaan_id', $persediaan->id)
            ->where('jumlah_sisa', '>', 0)
            ->orderBy('tanggal_masuk', 'asc')
            ->orderBy('id', 'asc')
            ->lockForUpdate()
            ->get();

        $sisaKebutuhan = $qtyKeluar;
        $totalNilaiKeluar = 0.0;
        $alokasi = [];

        foreach ($activeBatches as $batch) {
            if ($sisaKebutuhan <= 0) {
                break;
            }

            $ambil = min($sisaKebutuhan, (int) $batch->jumlah_sisa);
            $hargaBatch = (float) ($batch->getRawOriginal('harga_satuan') ?? $batch->harga_satuan ?? 0);

            $batch->jumlah_sisa = (int) $batch->jumlah_sisa - $ambil;
            $batch->save();

            $subtotalBatch = $ambil * $hargaBatch;
            $totalNilaiKeluar += $subtotalBatch;
            $sisaKebutuhan -= $ambil;

            $alokasi[] = [
                'batch_id'     => $batch->id,
                'qty'          => $ambil,
                'harga_satuan' => $hargaBatch,
                'subtotal'     => $subtotalBatch,
            ];
        }

        // Fallback pengaman jika ada selisih stok
        if ($sisaKebutuhan > 0) {
            $hargaFallback = $hargaMasterSebelum;
            $subtotalFallback = $sisaKebutuhan * $hargaFallback;
            $totalNilaiKeluar += $subtotalFallback;
            $alokasi[] = [
                'batch_id'     => null,
                'qty'          => $sisaKebutuhan,
                'harga_satuan' => $hargaFallback,
                'subtotal'     => $subtotalFallback,
            ];
        }

        self::recalculatePersediaan($persediaan);

        // Mode Standar (Cara 2: Rata-rata campuran proporsional dari nilai persediaan aktif)
        if (!self::isFifoEnabled()) {
            $nilaiMasterSesudah = (float) ($persediaan->getRawOriginal('harga_total') ?? $persediaan->harga_total ?? 0);
            $totalNilaiRataRata = max(0.0, round($nilaiMasterSebelum - $nilaiMasterSesudah, 2));

            if ($totalNilaiRataRata <= 0 && $qtyKeluar > 0 && $nilaiMasterSebelum > 0 && $stokSebelum > 0) {
                $totalNilaiRataRata = ($qtyKeluar >= $stokSebelum)
                    ? round($nilaiMasterSebelum, 0)
                    : round(($nilaiMasterSebelum * $qtyKeluar) / $stokSebelum, 0);
            }

            $hargaRataRataKeluar = $qtyKeluar > 0
                ? round($totalNilaiRataRata / $qtyKeluar, 2)
                : $hargaMasterSebelum;

            return [
                'harga_satuan' => $hargaRataRataKeluar,
                'total_nilai'  => $totalNilaiRataRata,
                'alokasi'      => $alokasi,
            ];
        }

        $hargaSatuanEfektif = $qtyKeluar > 0
            ? round($totalNilaiKeluar / $qtyKeluar, 2)
            : $hargaMasterSebelum;

        return [
            'harga_satuan' => $hargaSatuanEfektif,
            'total_nilai'  => round($totalNilaiKeluar, 2),
            'alokasi'      => $alokasi,
        ];
    }

    /**
     * Kembalikan stok ke batch asal saat Transaksi Keluar dihapus atau diubah.
     */
    public static function restoreFifo(Persediaan $persediaan, int $qtyRestore, ?array $alokasi = null): void
    {
        self::ensureTable();

        $persediaan = Persediaan::lockForUpdate()->findOrFail($persediaan->id);
        self::ensureBatchesForItem($persediaan);

        $sisaRestore = $qtyRestore;

        // 1. Jika ada jejak batch_alokasi presisi, kembalikan tepat ke batch asalnya
        if (!empty($alokasi) && is_array($alokasi)) {
            foreach ($alokasi as $entry) {
                if ($sisaRestore <= 0) {
                    break;
                }
                $batchId = $entry['batch_id'] ?? null;
                $qtyEntry = min($sisaRestore, (int) ($entry['qty'] ?? 0));
                if ($batchId && $qtyEntry > 0) {
                    $batch = PersediaanBatch::where('persediaan_id', $persediaan->id)
                        ->lockForUpdate()
                        ->find($batchId);

                    if ($batch) {
                        $batch->jumlah_sisa = (int) $batch->jumlah_sisa + $qtyEntry;
                        if ($batch->jumlah_sisa > $batch->jumlah_awal) {
                            $batch->jumlah_awal = $batch->jumlah_sisa;
                        }
                        $batch->save();
                        $sisaRestore -= $qtyEntry;
                    }
                }
            }
        }

        // 2. Fallback untuk transaksi keluar lama (sebelum fitur batch_alokasi): kembalikan ke batch yang terpakai (terbaru lebih dulu)
        if ($sisaRestore > 0) {
            $depletedBatches = PersediaanBatch::where('persediaan_id', $persediaan->id)
                ->whereColumn('jumlah_sisa', '<', 'jumlah_awal')
                ->orderBy('tanggal_masuk', 'desc')
                ->orderBy('id', 'desc')
                ->lockForUpdate()
                ->get();

            foreach ($depletedBatches as $batch) {
                if ($sisaRestore <= 0) {
                    break;
                }
                $kapasitas = max(0, (int) $batch->jumlah_awal - (int) $batch->jumlah_sisa);
                $kembali = min($sisaRestore, $kapasitas);
                if ($kembali > 0) {
                    $batch->jumlah_sisa += $kembali;
                    $batch->save();
                    $sisaRestore -= $kembali;
                }
            }
        }

        // 3. Jika masih ada sisa, tambahkan pada batch paling akhir
        if ($sisaRestore > 0) {
            $latestBatch = PersediaanBatch::where('persediaan_id', $persediaan->id)
                ->orderBy('tanggal_masuk', 'desc')
                ->orderBy('id', 'desc')
                ->lockForUpdate()
                ->first();

            if ($latestBatch) {
                $latestBatch->jumlah_sisa += $sisaRestore;
                $latestBatch->jumlah_awal = max((int) $latestBatch->jumlah_awal, (int) $latestBatch->jumlah_sisa);
                $latestBatch->save();
            } else {
                PersediaanBatch::create([
                    'persediaan_id'      => $persediaan->id,
                    'transaksi_masuk_id' => null,
                    'tanggal_masuk'      => $persediaan->tanggal_masuk ?: now()->toDateString(),
                    'harga_satuan'       => (float) ($persediaan->getRawOriginal('harga_satuan') ?? 0),
                    'jumlah_awal'        => $sisaRestore,
                    'jumlah_sisa'        => $sisaRestore,
                    'sumber'             => 'penyesuaian',
                    'keterangan'         => 'Pemulihan Transaksi Keluar',
                ]);
            }
        }

        self::recalculatePersediaan($persediaan);
    }

    /**
     * Sinkronisasi saat admin mengedit barang dari modal Edit di Data Persediaan atau dari Import Excel.
     * Menjaga keutuhan multi-batch harga jika barang memiliki >1 batch.
     */
    public static function syncManualEdit(
        Persediaan $persediaan,
        int $oldJumlah,
        float $oldHargaSatuan,
        int $newJumlah,
        float $newHargaSatuan,
        string $newTanggalMasuk,
        string $sumber = 'penyesuaian'
    ): void {
        self::ensureTable();
        self::ensureBatchesForItem($persediaan);

        $batches = PersediaanBatch::where('persediaan_id', $persediaan->id)
            ->orderBy('tanggal_masuk', 'asc')
            ->orderBy('id', 'asc')
            ->lockForUpdate()
            ->get();

        // Jika hanya ada 1 batch, update langsung batch tunggal tersebut
        if ($batches->count() <= 1) {
            $single = $batches->first();
            if ($single) {
                $single->update([
                    'tanggal_masuk' => $newTanggalMasuk,
                    'harga_satuan'  => $newHargaSatuan,
                    'jumlah_sisa'   => $newJumlah,
                    'jumlah_awal'   => max((int) $single->jumlah_awal, $newJumlah),
                ]);
            } else {
                PersediaanBatch::create([
                    'persediaan_id'      => $persediaan->id,
                    'transaksi_masuk_id' => null,
                    'tanggal_masuk'      => $newTanggalMasuk,
                    'harga_satuan'       => $newHargaSatuan,
                    'jumlah_awal'        => $newJumlah,
                    'jumlah_sisa'        => $newJumlah,
                    'sumber'             => $sumber,
                    'keterangan'         => 'Penyesuaian Data Persediaan',
                ]);
            }

            self::recalculatePersediaan($persediaan);
            return;
        }

        // Jika memiliki multi-batch (> 1 batch):
        if ($newJumlah > $oldJumlah) {
            $selisihTambah = $newJumlah - $oldJumlah;
            PersediaanBatch::create([
                'persediaan_id'      => $persediaan->id,
                'transaksi_masuk_id' => null,
                'tanggal_masuk'      => $newTanggalMasuk,
                'harga_satuan'       => $newHargaSatuan,
                'jumlah_awal'        => $selisihTambah,
                'jumlah_sisa'        => $selisihTambah,
                'sumber'             => $sumber,
                'keterangan'         => 'Penambahan Stok Manual',
            ]);
        } elseif ($newJumlah < $oldJumlah) {
            $selisihKurang = $oldJumlah - $newJumlah;
            foreach ($batches as $batch) {
                if ($selisihKurang <= 0) {
                    break;
                }
                if ($batch->jumlah_sisa > 0) {
                    $potong = min($selisihKurang, (int) $batch->jumlah_sisa);
                    $batch->jumlah_sisa -= $potong;
                    $batch->save();
                    $selisihKurang -= $potong;
                }
            }
        } elseif (abs($newHargaSatuan - $oldHargaSatuan) > 0.001) {
            // Jumlah tidak berubah tapi harga satuan pada form diubah -> perbarui batch aktif paling baru
            $latestActive = $batches->where('jumlah_sisa', '>', 0)->last() ?? $batches->last();
            if ($latestActive) {
                $latestActive->update([
                    'harga_satuan' => $newHargaSatuan,
                ]);
            }
        }

        self::recalculatePersediaan($persediaan);
    }

    /**
     * Hitung ulang stok total, harga_total, dan harga_satuan pada tabel master persediaan
     * menyesuaikan status aktif/nonaktif fitur FIFO.
     */
    public static function recalculatePersediaan(Persediaan $persediaan): void
    {
        $batches = PersediaanBatch::where('persediaan_id', $persediaan->id)
            ->orderBy('tanggal_masuk', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        if ($batches->isEmpty()) {
            return;
        }

        $fifoActive = self::isFifoEnabled();
        $totalJumlah = 0;
        $totalNilaiFifo = 0.0;

        foreach ($batches as $b) {
            $sisa = max(0, (int) $b->jumlah_sisa);
            $harga = (float) ($b->getRawOriginal('harga_satuan') ?? $b->harga_satuan ?? 0);
            $totalJumlah += $sisa;
            $totalNilaiFifo += ($sisa * $harga);
        }

        $latestRefBatch = $fifoActive
            ? ($batches->where('jumlah_sisa', '>', 0)->last() ?? $batches->last())
            : $batches->last();

        $hargaTerbaru = $latestRefBatch
            ? (float) ($latestRefBatch->getRawOriginal('harga_satuan') ?? $latestRefBatch->harga_satuan ?? 0)
            : (float) ($persediaan->getRawOriginal('harga_satuan') ?? 0);

        $tanggalTerbaru = $latestRefBatch && $latestRefBatch->tanggal_masuk
            ? ($latestRefBatch->tanggal_masuk instanceof \Carbon\Carbon
                ? $latestRefBatch->tanggal_masuk->format('Y-m-d')
                : (string) $latestRefBatch->tanggal_masuk)
            : $persediaan->tanggal_masuk;

        $nilaiMaster = $fifoActive
            ? round($totalNilaiFifo, 2)
            : self::calculateMovingAverageValue($persediaan, $batches, $totalJumlah, $hargaTerbaru);

        DB::table('persediaan')
            ->where('id', $persediaan->id)
            ->update([
                'jumlah'        => $totalJumlah,
                'harga_satuan'  => $hargaTerbaru,
                'harga_total'   => $nilaiMaster,
                'tanggal_masuk' => $tanggalTerbaru,
                'updated_at'    => now(),
            ]);

        $persediaan->refresh();
    }

    /**
     * Hitung Total Nilai Persediaan dengan Metode Akumulasi Masuk + Rata-Rata Campuran (Moving Weighted Average):
     * - Saat barang masuk: total nilai lama + subtotal barang baru (tidak menimpa harga stok lama).
     * - Saat barang keluar: total nilai berkurang proporsional sesuai rata-rata harga campuran saat itu.
     */
    protected static function calculateMovingAverageValue(
        Persediaan $persediaan,
        $batches,
        int $totalJumlah,
        float $hargaTerbaru
    ): float {
        if ($totalJumlah <= 0 || $batches->isEmpty()) {
            return 0.0;
        }

        $totalAwalBatches = 0;
        $totalNilaiAwalBatches = 0.0;

        foreach ($batches as $b) {
            $awal = max(0, (int) $b->jumlah_awal);
            $harga = (float) ($b->getRawOriginal('harga_satuan') ?? $b->harga_satuan ?? 0);
            $totalAwalBatches += $awal;
            $totalNilaiAwalBatches += ($awal * $harga);
        }

        // Jika belum ada stok yang keluar sama sekali, nilainya murni penjumlahan seluruh subtotal masuk
        if ($totalJumlah >= $totalAwalBatches) {
            $extraQty = $totalJumlah - $totalAwalBatches;
            return round($totalNilaiAwalBatches + ($extraQty * $hargaTerbaru), 0);
        }

        // Terdapat stok keluar: putar ulang (replay) riwayat masuk & keluar secara kronologis
        $eventsByDate = [];

        foreach ($batches as $b) {
            $awal = max(0, (int) $b->jumlah_awal);
            if ($awal <= 0) {
                continue;
            }
            $tgl = $b->tanggal_masuk instanceof \Carbon\Carbon
                ? $b->tanggal_masuk->format('Y-m-d')
                : substr((string) ($b->tanggal_masuk ?: '1970-01-01'), 0, 10);

            $eventsByDate[$tgl][] = [
                'type'      => 'in',
                'timestamp' => $b->created_at instanceof \Carbon\Carbon ? $b->created_at->getTimestamp() : 0,
                'id'        => (int) $b->id,
                'qty'       => $awal,
                'harga'     => (float) ($b->getRawOriginal('harga_satuan') ?? $b->harga_satuan ?? 0),
            ];
        }

        if (Schema::hasTable('transaksi_keluar_persediaan')) {
            $hasPersediaanIdCol = Schema::hasColumn('transaksi_keluar_persediaan', 'persediaan_id');

            $keluarRows = TransaksiKeluarPersediaan::where(function ($q) use ($persediaan, $hasPersediaanIdCol) {
                    if ($hasPersediaanIdCol) {
                        $q->where('persediaan_id', $persediaan->id)
                            ->orWhere(function ($sub) use ($persediaan) {
                                $sub->whereNull('persediaan_id')
                                    ->where('kode_kategori', $persediaan->kode_kategori)
                                    ->where('kode_barang', $persediaan->kode_barang);
                            });
                    } else {
                        $q->where('kode_kategori', $persediaan->kode_kategori)
                            ->where('kode_barang', $persediaan->kode_barang);
                    }
                })
                ->orderBy('tanggal_input', 'asc')
                ->orderBy('id', 'asc')
                ->get();

            foreach ($keluarRows as $k) {
                $qtyKeluar = max(0, (int) $k->jumlah_keluar);
                if ($qtyKeluar <= 0) {
                    continue;
                }
                $tgl = $k->tanggal_input instanceof \Carbon\Carbon
                    ? $k->tanggal_input->format('Y-m-d')
                    : substr((string) ($k->tanggal_input ?: '1970-01-01'), 0, 10);

                $eventsByDate[$tgl][] = [
                    'type'      => 'out',
                    'timestamp' => $k->created_at instanceof \Carbon\Carbon ? $k->created_at->getTimestamp() : 0,
                    'id'        => (int) $k->id,
                    'qty'       => $qtyKeluar,
                    'harga'     => 0.0,
                ];
            }
        }

        ksort($eventsByDate);

        $poolQty = 0;
        $poolNilai = 0.0;
        $sisaKuotaKeluar = max(0, $totalAwalBatches - $totalJumlah);

        foreach ($eventsByDate as $dayEvents) {
            usort($dayEvents, function (array $a, array $b) {
                if ($a['timestamp'] > 0 && $b['timestamp'] > 0 && $a['timestamp'] !== $b['timestamp']) {
                    return $a['timestamp'] <=> $b['timestamp'];
                }
                if ($a['type'] !== $b['type']) {
                    return $a['type'] === 'in' ? -1 : 1;
                }
                return $a['id'] <=> $b['id'];
            });

            $deferredOuts = [];

            foreach ($dayEvents as $ev) {
                if ($ev['type'] === 'in') {
                    $poolQty += $ev['qty'];
                    $poolNilai += ($ev['qty'] * $ev['harga']);
                } else {
                    if ($sisaKuotaKeluar <= 0) {
                        continue;
                    }
                    $qtyOut = min($ev['qty'], $sisaKuotaKeluar);
                    // Jika pada tanggal yang sama stok pool belum cukup karena barang masuk dicatat sesudahnya, tunda sebentar
                    if ($qtyOut > $poolQty) {
                        $deferredOuts[] = $qtyOut;
                        continue;
                    }
                    $potong = ($qtyOut >= $poolQty)
                        ? $poolNilai
                        : round(($poolNilai * $qtyOut) / $poolQty, 0);
                    $poolNilai = max(0.0, $poolNilai - $potong);
                    $poolQty -= $qtyOut;
                    $sisaKuotaKeluar -= $qtyOut;
                }
            }

            foreach ($deferredOuts as $qtyOut) {
                if ($sisaKuotaKeluar <= 0 || $poolQty <= 0) {
                    continue;
                }
                $ambil = min($qtyOut, $sisaKuotaKeluar, $poolQty);
                $potong = ($ambil >= $poolQty)
                    ? $poolNilai
                    : round(($poolNilai * $ambil) / $poolQty, 0);
                $poolNilai = max(0.0, $poolNilai - $potong);
                $poolQty -= $ambil;
                $sisaKuotaKeluar -= $ambil;
            }
        }

        // Potong proporsional sisa pengurangan stok yang belum tercatat di tabel transaksi_keluar
        // (misalnya saat deductFifo sedang berjalan sebelum baris transaksi keluar disimpan, atau penyesuaian manual)
        if ($poolQty > $totalJumlah && $poolQty > 0) {
            $ambilAkhir = $poolQty - $totalJumlah;
            $potongAkhir = ($ambilAkhir >= $poolQty)
                ? $poolNilai
                : round(($poolNilai * $ambilAkhir) / $poolQty, 0);
            $poolNilai = max(0.0, $poolNilai - $potongAkhir);
            $poolQty = $totalJumlah;
        } elseif ($poolQty < $totalJumlah) {
            $selisihTambah = $totalJumlah - $poolQty;
            $hargaRef = $poolQty > 0 ? ($poolNilai / $poolQty) : $hargaTerbaru;
            $poolNilai += round($selisihTambah * $hargaRef, 0);
            $poolQty = $totalJumlah;
        }

        return max(0.0, round($poolNilai, 0));
    }
}
