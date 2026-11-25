<?php

namespace App\Services;

use App\Models\BarangLainnya;
use App\Models\BarangLainnyaUnit;
use App\Models\PeminjamanBarang;
use App\Models\PeminjamanBarangLainnya;
use App\Models\PeminjamanKendaraan;
use App\Models\PermintaanPersediaan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CancelledArchiveService
{
    public const CANCELLED_STATUSES = ['dibatalkan', 'cancelled'];

    /**
     * Definisi kategori yang mendukung hapus data dibatalkan di Admin
     * dengan backup otomatis (SoftDeletes) di Super Admin.
     *
     * @return array<string, array<string, mixed>>
     */
    public static function categories(): array
    {
        return [
            'persediaan' => [
                'model'       => PermintaanPersediaan::class,
                'table'       => 'permintaan_persediaan',
                'label'       => 'Permintaan Persediaan',
                'badge_bg'    => '#dcfce7',
                'badge_color' => '#15803d',
                'with'        => ['user', 'deletedByUser', 'persediaan', 'items.persediaan'],
            ],
            'kendaraan' => [
                'model'       => PeminjamanKendaraan::class,
                'table'       => 'peminjaman_kendaraan',
                'label'       => 'Peminjaman Kendaraan',
                'badge_bg'    => '#e0e7ff',
                'badge_color' => '#4338ca',
                'with'        => ['user', 'deletedByUser'],
            ],
            'barang' => [
                'model'       => PeminjamanBarang::class,
                'table'       => 'peminjaman_barang',
                'label'       => 'Peminjaman Barang',
                'badge_bg'    => '#ede9fe',
                'badge_color' => '#6d28d9',
                'with'        => ['user', 'deletedByUser', 'items'],
            ],
            'barang_lainnya' => [
                'model'       => PeminjamanBarangLainnya::class,
                'table'       => 'peminjaman_barang_lainnya',
                'label'       => 'Permintaan Barang Lainnya',
                'badge_bg'    => '#fef3c7',
                'badge_color' => '#b45309',
                'with'        => ['user', 'deletedByUser'],
            ],
        ];
    }

    public static function isCancelledStatus(?string $status): bool
    {
        return in_array(strtolower(trim((string) $status)), self::CANCELLED_STATUSES, true);
    }

    /**
     * Hapus 1 data berstatus dibatalkan dari daftar Admin (SoftDelete: terbackup di Super Admin).
     */
    public static function deleteCancelledRecord(Model $record, ?int $deletedByUserId = null): void
    {
        if (!self::isCancelledStatus($record->status ?? null)) {
            throw new \RuntimeException('Hanya data yang berstatus dibatalkan yang dapat dihapus dari daftar admin.');
        }

        DB::transaction(function () use ($record, $deletedByUserId) {
            // Pengamanan tambahan: pastikan unit barang lainnya tidak tertahan jika record dibatalkan dihapus
            if ($record instanceof PeminjamanBarangLainnya && Schema::hasTable('barang_lainnya_units')) {
                $stuckUnits = BarangLainnyaUnit::where('peminjaman_aktif_id', $record->id)->get();
                if ($stuckUnits->isNotEmpty()) {
                    foreach ($stuckUnits as $unit) {
                        $unit->update([
                            'status'              => 'tersedia',
                            'peminjaman_aktif_id' => null,
                        ]);
                    }
                    $barang = $record->barang_lainnya_id ? BarangLainnya::find($record->barang_lainnya_id) : null;
                    if ($barang) {
                        BarangLainnyaUnitService::recalculateAvailableStock($barang);
                    }
                }
            }

            $record->deleted_by = $deletedByUserId;
            $record->save();
            $record->delete();
        });
    }

    /**
     * Hapus massal seluruh data berstatus dibatalkan pada satu kategori (SoftDelete).
     */
    public static function deleteAllCancelled(string $categoryKey, ?int $deletedByUserId = null): int
    {
        $categories = self::categories();
        if (!isset($categories[$categoryKey])) {
            throw new \InvalidArgumentException("Kategori arsip '{$categoryKey}' tidak valid.");
        }

        $meta = $categories[$categoryKey];
        if (!Schema::hasTable($meta['table'])) {
            return 0;
        }

        /** @var class-string<Model> $modelClass */
        $modelClass = $meta['model'];
        $records = $modelClass::whereIn('status', self::CANCELLED_STATUSES)->get();

        $count = 0;
        DB::transaction(function () use ($records, $deletedByUserId, &$count) {
            foreach ($records as $record) {
                self::deleteCancelledRecord($record, $deletedByUserId);
                $count++;
            }
        });

        return $count;
    }

    /**
     * Ambil seluruh data backup/arsip yang dihapus admin (onlyTrashed) untuk panel Super Admin.
     *
     * @return array{counts: array<string, int>, items: Collection<int, array<string, mixed>>}
     */
    public static function getArchiveSummary(string $filterCategory = 'all', ?string $search = null): array
    {
        $categories = self::categories();
        $counts = [
            'persediaan'     => 0,
            'kendaraan'      => 0,
            'barang'         => 0,
            'barang_lainnya' => 0,
            'total'          => 0,
        ];

        $collected = collect();
        $searchLower = mb_strtolower(trim((string) $search));

        foreach ($categories as $key => $meta) {
            if (!Schema::hasTable($meta['table'])) {
                continue;
            }

            /** @var class-string<Model> $modelClass */
            $modelClass = $meta['model'];
            $trashedCount = $modelClass::onlyTrashed()->count();
            $counts[$key] = $trashedCount;
            $counts['total'] += $trashedCount;

            if ($filterCategory !== 'all' && $filterCategory !== $key) {
                continue;
            }

            $rows = $modelClass::onlyTrashed()
                ->with($meta['with'])
                ->orderByDesc('deleted_at')
                ->get();

            foreach ($rows as $row) {
                $formatted = self::formatArchiveRow($key, $meta, $row);
                if ($searchLower !== '') {
                    $haystack = mb_strtolower(implode(' ', [
                        $formatted['kategori_label'],
                        $formatted['pemohon'],
                        $formatted['judul_barang'],
                        $formatted['detail_ringkas'],
                        $formatted['alasan_atau_catatan'],
                        $formatted['deleted_by_nama'],
                    ]));
                    if (!str_contains($haystack, $searchLower)) {
                        continue;
                    }
                }
                $collected->push($formatted);
            }
        }

        $sorted = $collected->sortByDesc(fn($item) => $item['deleted_at_ts'] ?? 0)->values();

        return [
            'counts' => $counts,
            'items'  => $sorted,
        ];
    }

    public static function restore(string $categoryKey, int $id): Model
    {
        $meta = self::categories()[$categoryKey] ?? null;
        if (!$meta) {
            throw new \InvalidArgumentException('Kategori arsip tidak valid.');
        }

        /** @var class-string<Model> $modelClass */
        $modelClass = $meta['model'];
        $item = $modelClass::onlyTrashed()->findOrFail($id);

        $item->deleted_by = null;
        $item->save();
        $item->restore();

        return $item;
    }

    public static function forceDelete(string $categoryKey, int $id): void
    {
        $meta = self::categories()[$categoryKey] ?? null;
        if (!$meta) {
            throw new \InvalidArgumentException('Kategori arsip tidak valid.');
        }

        /** @var class-string<Model> $modelClass */
        $modelClass = $meta['model'];

        DB::transaction(function () use ($modelClass, $id) {
            $item = $modelClass::onlyTrashed()->findOrFail($id);

            if ($item instanceof PermintaanPersediaan || $item instanceof PeminjamanBarang) {
                $item->items()->delete();
            }

            $item->forceDelete();
        });
    }

    protected static function formatArchiveRow(string $key, array $meta, Model $row): array
    {
        $pemohon = $row->user?->name ?? ($row->nama_lengkap ?? 'Tanpa Nama');
        $deletedByNama = $row->deletedByUser?->name ?? 'Admin';
        $judul = '-';
        $detail = '-';
        $jumlahLabel = '-';
        $tglPengajuan = $row->created_at?->format('d/m/Y') ?? '-';
        $catatan = '-';

        if ($key === 'persediaan') {
            /** @var PermintaanPersediaan $row */
            $items = $row->items ?? collect();
            if ($items->count() > 1) {
                $judul = $items->count() . ' Macam Barang Persediaan';
                $detail = $items->map(fn($d) => ($d->nama_barang ?? '-') . ' (' . ($d->jumlah_diminta ?? 0) . ')')->implode(', ');
                $jumlahLabel = $items->sum('jumlah_diminta') . ' unit total';
            } else {
                $only = $items->first();
                $judul = $only?->nama_barang ?? $row->nama_barang ?? ($row->persediaan?->nama_barang ?? '-');
                $kode = $only?->kode_barang ?? $row->kode_barang ?? ($row->persediaan?->kode_barang ?? '-');
                $detail = 'Kode: ' . $kode;
                $qty = $only?->jumlah_diminta ?? $row->jumlah_diminta ?? 0;
                $sat = $only?->satuan ?? $row->satuan ?? ($row->persediaan?->satuan ?? 'Unit');
                $jumlahLabel = $qty . ' ' . $sat;
            }
            $tglPengajuan = $row->tanggal_permintaan?->format('d/m/Y') ?? $tglPengajuan;
            $catatan = $row->tujuan_penggunaan ?? '-';
        } elseif ($key === 'kendaraan') {
            /** @var PeminjamanKendaraan $row */
            $judul = $row->nama_barang ?? '-';
            $nopol = $row->nomor_polisi_saat_pinjam ?? '-';
            $detail = trim(($row->merek ? $row->merek . ' • ' : '') . 'Nopol: ' . $nopol . ($row->nup ? ' • NUP: ' . $row->nup : ''));
            $jumlahLabel = ($row->jumlah ?? 1) . ' Unit';
            $tglPinjam = $row->tanggal_peminjaman ? $row->tanggal_peminjaman->format('d/m/Y') : '-';
            $tglKembali = $row->tanggal_pengembalian ? $row->tanggal_pengembalian->format('d/m/Y') : '-';
            $tglPengajuan = $tglPinjam . ' s/d ' . $tglKembali;
            $catatan = $row->deskripsi_peruntukan ?? '-';
        } elseif ($key === 'barang') {
            /** @var PeminjamanBarang $row */
            $items = $row->items ?? collect();
            if ($items->count() > 1) {
                $judul = $items->count() . ' Barang BMN';
                $detail = $items->map(fn($d) => ($d->nama_barang ?? '-') . ' (NUP ' . ($d->nup ?? '-') . ')')->implode(', ');
                $jumlahLabel = $items->sum('jumlah') . ' Unit';
            } else {
                $judul = $row->nama_barang ?? '-';
                $detail = trim('Kode: ' . ($row->kode_barang ?? '-') . ' • NUP: ' . ($row->nup ?? '-'));
                $jumlahLabel = ($row->jumlah ?? 1) . ' Unit';
            }
            $tglPinjam = $row->tanggal_peminjaman ? $row->tanggal_peminjaman->format('d/m/Y') : '-';
            $tglKembali = $row->tanggal_pengembalian ? $row->tanggal_pengembalian->format('d/m/Y') : '-';
            $tglPengajuan = $tglPinjam . ' s/d ' . $tglKembali;
            $catatan = $row->deskripsi_peruntukan ?? '-';
        } elseif ($key === 'barang_lainnya') {
            /** @var PeminjamanBarangLainnya $row */
            $judul = $row->nama_barang ?? '-';
            $detail = trim('Kode: ' . ($row->kode_barang ?? '-') . ($row->kode_unit ? ' • Unit: ' . $row->kode_unit : ''));
            $jumlahLabel = ($row->jumlah ?? 0) . ' ' . ($row->satuan ?? 'unit');
            $tglPinjam = $row->tanggal_pinjam?->format('d/m/Y') ?? '-';
            $tglKembali = $row->tanggal_kembali?->format('d/m/Y') ?? '-';
            $tglPengajuan = $tglPinjam . ' s/d ' . $tglKembali;
            $catatan = $row->keperluan ?? '-';
        }

        return [
            'id'                  => $row->id,
            'kategori'            => $key,
            'kategori_label'      => $meta['label'],
            'badge_bg'            => $meta['badge_bg'],
            'badge_color'         => $meta['badge_color'],
            'pemohon'             => $pemohon,
            'judul_barang'        => $judul,
            'detail_ringkas'      => $detail,
            'jumlah_label'        => $jumlahLabel,
            'tanggal_pengajuan'   => $tglPengajuan,
            'alasan_atau_catatan' => $catatan,
            'status'              => $row->status,
            'deleted_at'          => $row->deleted_at?->format('d/m/Y H:i') ?? '-',
            'deleted_at_ts'       => $row->deleted_at?->getTimestamp() ?? 0,
            'deleted_by_nama'     => $deletedByNama,
            'raw_snapshot'        => $row->toArray(),
        ];
    }
}
