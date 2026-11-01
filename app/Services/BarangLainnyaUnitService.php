<?php

namespace App\Services;

use App\Models\BarangLainnya;
use App\Models\BarangLainnyaUnit;
use App\Models\PeminjamanBarangLainnya;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class BarangLainnyaUnitService
{
    /**
     * Pastikan tabel barang_lainnya_units dan kolom unit_ids / kode_unit ada secara otomatis
     */
    public static function ensureSchema(): void
    {
        try {
            if (!Schema::hasTable('barang_lainnya_units')) {
                Schema::create('barang_lainnya_units', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('barang_lainnya_id');
                    $table->string('kode_unit', 50);
                    $table->string('status', 30)->default('tersedia'); // tersedia, dipinjam, rusak, hilang
                    $table->unsignedBigInteger('peminjaman_aktif_id')->nullable();
                    $table->string('keterangan', 255)->nullable();
                    $table->timestamps();

                    $table->index('barang_lainnya_id', 'idx_blu_barang_id');
                    $table->index('status', 'idx_blu_status');
                    $table->index('peminjaman_aktif_id', 'idx_blu_pinjam_id');
                });
            }

            if (Schema::hasTable('peminjaman_barang_lainnya')) {
                Schema::table('peminjaman_barang_lainnya', function (Blueprint $table) {
                    if (!Schema::hasColumn('peminjaman_barang_lainnya', 'unit_ids')) {
                        $table->text('unit_ids')->nullable()->after('jumlah');
                    }
                    if (!Schema::hasColumn('peminjaman_barang_lainnya', 'kode_unit')) {
                        $table->string('kode_unit', 255)->nullable()->after('unit_ids');
                    }
                });
            }
        } catch (\Throwable $e) {
            Log::warning('BarangLainnyaUnitService::ensureSchema: ' . $e->getMessage());
        }
    }

    /**
     * Konversi indeks 0-based menjadi huruf alfabet berurutan (0=A, 1=B, ..., 25=Z, 26=AA, 27=AB)
     */
    public static function indexToLetters(int $index): string
    {
        $index = max(0, $index);
        $letters = '';
        while ($index >= 0) {
            $letters = chr(($index % 26) + 65) . $letters;
            $index = intdiv($index, 26) - 1;
        }
        return $letters;
    }

    /**
     * Hasilkan daftar kode unit alfabet berikutnya yang belum terpakai pada satu barang
     */
    public static function generateNextCodes(int $barangLainnyaId, int $needed): array
    {
        if ($needed <= 0) {
            return [];
        }

        $existingCodes = BarangLainnyaUnit::where('barang_lainnya_id', $barangLainnyaId)
            ->pluck('kode_unit')
            ->map(fn($c) => mb_strtoupper(trim((string) $c)))
            ->all();

        $existingLookup = array_flip($existingCodes);
        $codes = [];
        $idx = 0;

        while (count($codes) < $needed) {
            $candidate = self::indexToLetters($idx);
            $upper = mb_strtoupper($candidate);
            if (!isset($existingLookup[$upper])) {
                $codes[] = $candidate;
                $existingLookup[$upper] = true;
            }
            $idx++;
        }

        return $codes;
    }

    /**
     * Backfill otomatis untuk data BarangLainnya lama yang belum memiliki baris di barang_lainnya_units
     */
    public static function syncAllUnindexed(): void
    {
        self::ensureSchema();

        if (!Schema::hasTable('barang_lainnya') || !Schema::hasTable('barang_lainnya_units')) {
            return;
        }

        try {
            $itemsWithoutUnits = BarangLainnya::whereDoesntHave('units')->get();
            foreach ($itemsWithoutUnits as $barang) {
                $activeLoans = collect([]);
                if (Schema::hasTable('peminjaman_barang_lainnya')) {
                    $activeLoans = PeminjamanBarangLainnya::where('barang_lainnya_id', $barang->id)
                        ->whereIn('status', ['disetujui', 'proses_pengembalian'])
                        ->orderBy('id', 'asc')
                        ->get();
                }

                $borrowedCount = (int) $activeLoans->sum('jumlah');
                $availableCount = max(0, (int) $barang->jumlah);
                $totalUnits = $borrowedCount + $availableCount;

                if ($totalUnits <= 0) {
                    continue;
                }

                $codes = self::generateNextCodes($barang->id, $totalUnits);
                $cursor = 0;

                // 1. Alokasikan ke peminjaman yang sedang berjalan (jika ada)
                foreach ($activeLoans as $loan) {
                    $qty = max(1, (int) $loan->jumlah);
                    $loanUnitIds = [];
                    $loanUnitCodes = [];

                    for ($i = 0; $i < $qty && $cursor < count($codes); $i++, $cursor++) {
                        $unit = BarangLainnyaUnit::create([
                            'barang_lainnya_id'   => $barang->id,
                            'kode_unit'           => $codes[$cursor],
                            'status'              => 'dipinjam',
                            'peminjaman_aktif_id' => $loan->id,
                        ]);
                        $loanUnitIds[] = $unit->id;
                        $loanUnitCodes[] = $unit->kode_unit;
                    }

                    if (!empty($loanUnitIds)) {
                        $loan->update([
                            'unit_ids'  => $loanUnitIds,
                            'kode_unit' => implode(', ', $loanUnitCodes),
                        ]);
                    }
                }

                // 2. Sisanya berstatus tersedia sesuai stok saat ini
                while ($cursor < count($codes)) {
                    BarangLainnyaUnit::create([
                        'barang_lainnya_id'   => $barang->id,
                        'kode_unit'           => $codes[$cursor],
                        'status'              => 'tersedia',
                        'peminjaman_aktif_id' => null,
                    ]);
                    $cursor++;
                }

                self::recalculateAvailableStock($barang);
            }
        } catch (\Throwable $e) {
            Log::warning('BarangLainnyaUnitService::syncAllUnindexed: ' . $e->getMessage());
        }
    }

    /**
     * Buat unit otomatis saat barang baru ditambahkan
     */
    public static function createInitialUnits(BarangLainnya $barang, int $count): void
    {
        self::ensureSchema();
        $codes = self::generateNextCodes($barang->id, max(0, $count));
        foreach ($codes as $code) {
            BarangLainnyaUnit::create([
                'barang_lainnya_id' => $barang->id,
                'kode_unit'         => $code,
                'status'            => 'tersedia',
            ]);
        }
        self::recalculateAvailableStock($barang);
    }

    /**
     * Sinkronisasi jumlah unit tersedia ketika Admin mengubah angka Stok di Edit Barang Lainnya
     */
    public static function syncUnitsFromAdminEdit(BarangLainnya $barang, int $desiredAvailable): void
    {
        self::ensureSchema();
        $desiredAvailable = max(0, $desiredAvailable);

        $availableUnits = BarangLainnyaUnit::where('barang_lainnya_id', $barang->id)
            ->where('status', 'tersedia')
            ->orderBy('id', 'asc')
            ->get();

        $currentAvailable = $availableUnits->count();

        if ($desiredAvailable > $currentAvailable) {
            $diff = $desiredAvailable - $currentAvailable;
            $codes = self::generateNextCodes($barang->id, $diff);
            foreach ($codes as $code) {
                BarangLainnyaUnit::create([
                    'barang_lainnya_id' => $barang->id,
                    'kode_unit'         => $code,
                    'status'            => 'tersedia',
                ]);
            }
        } elseif ($desiredAvailable < $currentAvailable) {
            $toRemove = $currentAvailable - $desiredAvailable;
            $idsToDelete = $availableUnits->reverse()->take($toRemove)->pluck('id')->all();
            if (!empty($idsToDelete)) {
                BarangLainnyaUnit::whereIn('id', $idsToDelete)->delete();
            }
        }

        self::recalculateAvailableStock($barang);
    }

    /**
     * Hitung ulang stok tersedia (jumlah) pada tabel barang_lainnya dari jumlah unit berstatus 'tersedia'
     */
    public static function recalculateAvailableStock(BarangLainnya $barang): void
    {
        $availableCount = BarangLainnyaUnit::where('barang_lainnya_id', $barang->id)
            ->where('status', 'tersedia')
            ->count();

        $barang->jumlah = $availableCount;
        $barang->harga_total = $availableCount * (float) $barang->harga_satuan;
        $barang->save();
    }

    /**
     * Tambah 1 unit baru secara manual (opsional kode custom atau huruf otomatis berikutnya)
     */
    public static function addSingleUnit(BarangLainnya $barang, ?string $customCode = null, ?string $keterangan = null): BarangLainnyaUnit
    {
        self::ensureSchema();

        $kode = trim((string) $customCode);
        if ($kode === '') {
            $next = self::generateNextCodes($barang->id, 1);
            $kode = $next[0] ?? 'A';
        } else {
            $exists = BarangLainnyaUnit::where('barang_lainnya_id', $barang->id)
                ->whereRaw('UPPER(kode_unit) = ?', [mb_strtoupper($kode)])
                ->exists();
            if ($exists) {
                throw new \RuntimeException("Kode unit '{$kode}' sudah digunakan pada barang ini.");
            }
        }

        $unit = BarangLainnyaUnit::create([
            'barang_lainnya_id' => $barang->id,
            'kode_unit'         => $kode,
            'status'            => 'tersedia',
            'keterangan'        => $keterangan,
        ]);

        self::recalculateAvailableStock($barang);
        return $unit;
    }

    /**
     * Update manual kode unit / status oleh Admin
     */
    public static function updateSingleUnit(BarangLainnyaUnit $unit, string $newKodeUnit, ?string $newStatus = null, ?string $keterangan = null): BarangLainnyaUnit
    {
        self::ensureSchema();

        $cleanCode = trim($newKodeUnit);
        if ($cleanCode === '') {
            throw new \RuntimeException('Kode unit tidak boleh kosong.');
        }

        $duplicate = BarangLainnyaUnit::where('barang_lainnya_id', $unit->barang_lainnya_id)
            ->where('id', '!=', $unit->id)
            ->whereRaw('UPPER(kode_unit) = ?', [mb_strtoupper($cleanCode)])
            ->exists();

        if ($duplicate) {
            throw new \RuntimeException("Kode unit '{$cleanCode}' sudah dipakai oleh unit lain pada barang ini.");
        }

        $oldCode = $unit->kode_unit;
        $unit->kode_unit = $cleanCode;
        if ($keterangan !== null) {
            $unit->keterangan = trim($keterangan) !== '' ? trim($keterangan) : null;
        }

        // Jika tidak sedang dipinjam, Admin boleh mengubah status (tersedia / rusak / hilang)
        if ($unit->status !== 'dipinjam' && $newStatus && in_array($newStatus, ['tersedia', 'rusak', 'hilang'], true)) {
            $unit->status = $newStatus;
        }

        $unit->save();

        // Jika kode_unit diganti saat sedang dipinjam atau ada di pengajuan pending, perbarui label kode_unit pada peminjaman aktifnya
        if ($oldCode !== $cleanCode && $unit->peminjaman_aktif_id) {
            $loan = PeminjamanBarangLainnya::find($unit->peminjaman_aktif_id);
            if ($loan && is_array($loan->unit_ids) && !empty($loan->unit_ids)) {
                $codes = BarangLainnyaUnit::whereIn('id', $loan->unit_ids)->orderBy('id', 'asc')->pluck('kode_unit')->all();
                if (!empty($codes)) {
                    $loan->update(['kode_unit' => implode(', ', $codes)]);
                }
            }
        }

        $barang = BarangLainnya::find($unit->barang_lainnya_id);
        if ($barang) {
            self::recalculateAvailableStock($barang);
        }

        return $unit;
    }

    /**
     * Hapus 1 unit (hanya jika tidak sedang dipinjam)
     */
    public static function deleteSingleUnit(BarangLainnyaUnit $unit): void
    {
        if ($unit->status === 'dipinjam') {
            throw new \RuntimeException("Unit dengan kode '{$unit->kode_unit}' sedang dipinjam dan tidak dapat dihapus.");
        }

        $barangId = $unit->barang_lainnya_id;
        $unit->delete();

        $barang = BarangLainnya::find($barangId);
        if ($barang) {
            self::recalculateAvailableStock($barang);
        }
    }

    /**
     * Opsi 3 (Hybrid): Setujui peminjaman dengan kode unit yang diverifikasi / disesuaikan oleh Admin
     */
    public static function assignUnitsOnApproval(PeminjamanBarangLainnya $pinjam, array $requestedUnitIds, ?int $approverId = null, ?string $catatanAdmin = null): void
    {
        self::ensureSchema();

        DB::transaction(function () use ($pinjam, $requestedUnitIds, $approverId, $catatanAdmin) {
            $barang = $pinjam->barang_lainnya_id ? BarangLainnya::lockForUpdate()->find($pinjam->barang_lainnya_id) : null;

            if (!$barang) {
                $pinjam->update([
                    'status'        => 'disetujui',
                    'approved_by'   => $approverId,
                    'catatan_admin' => $catatanAdmin ?? $pinjam->catatan_admin,
                ]);
                return;
            }

            $unitIds = array_values(array_unique(array_map('intval', array_filter($requestedUnitIds))));

            // Fallback: jika unitIds kosong, gunakan unit_ids dari pengajuan pegawai atau ambil otomatis unit tersedia
            if (empty($unitIds) && is_array($pinjam->unit_ids) && !empty($pinjam->unit_ids)) {
                $unitIds = array_values(array_unique(array_map('intval', $pinjam->unit_ids)));
            }

            if (!empty($unitIds)) {
                $units = BarangLainnyaUnit::where('barang_lainnya_id', $barang->id)
                    ->whereIn('id', $unitIds)
                    ->lockForUpdate()
                    ->orderBy('id', 'asc')
                    ->get();

                if ($units->count() !== count($unitIds)) {
                    throw new \RuntimeException('Sebagian kode unit yang dipilih tidak ditemukan pada barang ini.');
                }

                $unavailable = $units->filter(fn($u) => $u->status !== 'tersedia');
                if ($unavailable->isNotEmpty()) {
                    $badCodes = $unavailable->pluck('kode_unit')->implode(', ');
                    throw new \RuntimeException("Kode unit [{$badCodes}] saat ini tidak tersedia / sedang dipinjam. Silakan pilih kode unit lain yang tersedia.");
                }
            } else {
                // Legacy fallback bila pengajuan lama belum memilih kode unit spesifik
                $needed = max(1, (int) $pinjam->jumlah);
                $units = BarangLainnyaUnit::where('barang_lainnya_id', $barang->id)
                    ->where('status', 'tersedia')
                    ->lockForUpdate()
                    ->orderBy('id', 'asc')
                    ->limit($needed)
                    ->get();

                if ($units->count() < $needed) {
                    throw new \RuntimeException("Stok unit '{$barang->nama_barang}' tidak mencukupi (Tersedia: {$units->count()} {$barang->satuan}).");
                }
            }

            $finalIds = [];
            $finalCodes = [];
            foreach ($units as $unit) {
                $unit->update([
                    'status'              => 'dipinjam',
                    'peminjaman_aktif_id' => $pinjam->id,
                ]);
                $finalIds[] = $unit->id;
                $finalCodes[] = $unit->kode_unit;
            }

            $updatePayload = [
                'jumlah'      => count($finalIds),
                'unit_ids'    => $finalIds,
                'kode_unit'   => implode(', ', $finalCodes),
                'status'      => 'disetujui',
                'approved_by' => $approverId,
            ];
            if ($catatanAdmin !== null && trim($catatanAdmin) !== '') {
                $updatePayload['catatan_admin'] = trim($catatanAdmin);
            }

            $pinjam->update($updatePayload);
            self::recalculateAvailableStock($barang);
        });
    }

    /**
     * Kembalikan status kode unit saat barang selesai dikembalikan / diverifikasi Admin
     */
    public static function releaseUnitsOnReturn(PeminjamanBarangLainnya $pinjam, string $statusPengembalian = 'lengkap'): void
    {
        self::ensureSchema();

        DB::transaction(function () use ($pinjam, $statusPengembalian) {
            $targetStatus = match ($statusPengembalian) {
                'hilang'      => 'hilang',
                'rusak_berat' => 'rusak',
                default       => 'tersedia',
            };

            $units = BarangLainnyaUnit::where('peminjaman_aktif_id', $pinjam->id)
                ->lockForUpdate()
                ->get();

            if ($units->isEmpty() && is_array($pinjam->unit_ids) && !empty($pinjam->unit_ids)) {
                $units = BarangLainnyaUnit::whereIn('id', $pinjam->unit_ids)
                    ->where('status', 'dipinjam')
                    ->lockForUpdate()
                    ->get();
            }

            $barang = $pinjam->barang_lainnya_id ? BarangLainnya::lockForUpdate()->find($pinjam->barang_lainnya_id) : null;

            if ($units->isNotEmpty()) {
                foreach ($units as $unit) {
                    $unit->update([
                        'status'              => $targetStatus,
                        'peminjaman_aktif_id' => null,
                    ]);
                }
                if ($barang) {
                    self::recalculateAvailableStock($barang);
                }
            } elseif ($barang) {
                // Fallback data lama yang belum punya unit record sama sekali
                if ($targetStatus === 'tersedia') {
                    $codes = self::generateNextCodes($barang->id, max(1, (int) $pinjam->jumlah));
                    foreach ($codes as $code) {
                        BarangLainnyaUnit::create([
                            'barang_lainnya_id' => $barang->id,
                            'kode_unit'         => $code,
                            'status'            => 'tersedia',
                        ]);
                    }
                }
                self::recalculateAvailableStock($barang);
            }
        });
    }
}
