<?php

namespace Tests\Feature;

use App\Models\BarangLainnya;
use App\Models\DetailPermintaanPersediaan;
use App\Models\PeminjamanBarang;
use App\Models\PeminjamanBarangLainnya;
use App\Models\PeminjamanKendaraan;
use App\Models\PermintaanPersediaan;
use App\Models\Persediaan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CancelledDeletionBackupTest extends TestCase
{
    use RefreshDatabase;

    public function test_admins_can_delete_cancelled_records_across_all_four_modules_and_superadmin_keeps_backup(): void
    {
        $superadmin = User::factory()->create(['role' => 'superadmin', 'name' => 'Operator Utama', 'is_active' => true]);
        $adminPersediaan = User::factory()->create(['role' => 'adminpersediaan', 'name' => 'Admin Persediaan', 'is_active' => true]);
        $adminAset = User::factory()->create(['role' => 'adminasettetap', 'name' => 'Admin Aset', 'is_active' => true]);
        $pegawai = User::factory()->create(['role' => 'pegawai', 'name' => 'Siti Pegawai', 'is_active' => true]);

        // 1. Seed 4 data berstatus dibatalkan + 1 data pending (tidak boleh terhapus)
        $persediaan = Persediaan::create([
            'kode_barang'   => 'ATK-01',
            'nama_barang'   => 'Kertas A4',
            'kode_kategori' => 'ATK',
            'kategori'      => 'ATK',
            'satuan'        => 'rim',
            'jumlah'        => 10,
            'harga_satuan'  => 50000,
            'harga_total'   => 500000,
            'tanggal_masuk' => now()->toDateString(),
        ]);

        $permintaanCancelled = PermintaanPersediaan::create([
            'kode_barang'        => 'ATK-01',
            'nama_barang'        => 'Kertas A4',
            'persediaan_id'      => $persediaan->id,
            'user_id'            => $pegawai->id,
            'jumlah_diminta'     => 2,
            'satuan'             => 'rim',
            'tanggal_permintaan' => now()->toDateString(),
            'tujuan_penggunaan'  => 'Rapat batal',
            'status'             => 'dibatalkan',
        ]);
        DetailPermintaanPersediaan::create([
            'permintaan_persediaan_id' => $permintaanCancelled->id,
            'persediaan_id'            => $persediaan->id,
            'kode_barang'              => 'ATK-01',
            'nama_barang'              => 'Kertas A4',
            'jumlah_diminta'           => 2,
            'satuan'                   => 'rim',
        ]);

        $permintaanPending = PermintaanPersediaan::create([
            'kode_barang'        => 'ATK-01',
            'nama_barang'        => 'Kertas A4',
            'persediaan_id'      => $persediaan->id,
            'user_id'            => $pegawai->id,
            'jumlah_diminta'     => 1,
            'satuan'             => 'rim',
            'tanggal_permintaan' => now()->toDateString(),
            'tujuan_penggunaan'  => 'Rapat aktif',
            'status'             => 'pending',
        ]);

        $kendaraanCancelled = PeminjamanKendaraan::create([
            'user_id'                  => $pegawai->id,
            'nama_barang'              => 'Toyota Avanza',
            'kode_barang'              => 'KND-01',
            'nup'                      => '1',
            'merek'                    => 'Toyota',
            'jumlah'                   => 1,
            'deskripsi_peruntukan'     => 'Dinas batal',
            'request_date'             => now(),
            'tanggal_peminjaman'       => now(),
            'tanggal_pengembalian'     => now()->addDay(),
            'nomor_polisi_saat_pinjam' => 'DM 1001 A',
            'status'                   => 'dibatalkan',
        ]);

        $barangCancelled = PeminjamanBarang::create([
            'user_id'              => $pegawai->id,
            'nama_barang'          => 'Proyektor Epson',
            'kode_barang'          => 'BMN-01',
            'nup'                  => '5',
            'kategori'             => 'Elektronik',
            'merek'                => 'Epson',
            'jumlah'               => 1,
            'deskripsi_peruntukan' => 'Acara batal',
            'request_date'         => now(),
            'tanggal_peminjaman'   => now(),
            'tanggal_pengembalian' => now()->addDay(),
        ]);
        $barangCancelled->status = 'dibatalkan';
        $barangCancelled->save();

        $barangLainnyaMaster = BarangLainnya::create([
            'kode_barang'   => 'BLN-01',
            'nama_barang'   => 'Cokrol 5 Lubang',
            'kategori'      => 'Barang Lainnya',
            'satuan'        => 'unit',
            'jumlah'        => 3,
            'harga_satuan'  => 50000,
            'harga_total'   => 150000,
            'tanggal_masuk' => now()->toDateString(),
        ]);

        $barangLainnyaCancelled = PeminjamanBarangLainnya::create([
            'user_id'           => $pegawai->id,
            'barang_lainnya_id' => $barangLainnyaMaster->id,
            'nama_barang'       => 'Cokrol 5 Lubang',
            'kode_barang'       => 'BLN-01',
            'jumlah'            => 1,
            'kode_unit'         => 'CKR-01',
            'satuan'            => 'unit',
            'tanggal_pinjam'    => now()->toDateString(),
            'tanggal_kembali'   => now()->addDay()->toDateString(),
            'keperluan'         => 'Acara batal',
            'status'            => 'dibatalkan',
        ]);

        // 2. Data non-dibatalkan (pending) tidak boleh dihapus
        $this->actingAs($adminPersediaan)
            ->delete("/adminpersediaan/permintaan/{$permintaanPending->id}/hapus-dibatalkan")
            ->assertSessionHas('error');
        $this->assertNotNull($permintaanPending->fresh());

        // 3. Halaman admin tampil OK & punya tombol Hapus untuk data dibatalkan
        $this->actingAs($adminPersediaan)->get('/adminpersediaan/permintaan-persediaan')->assertOk()->assertSee('Hapus Semua Dibatalkan');
        $this->actingAs($adminPersediaan)->get('/adminpersediaan/barang-lainnya?tab=pinjam')->assertOk()->assertSee('Hapus Semua Dibatalkan');
        $this->actingAs($adminAset)->get('/adminasettetap/peminjaman-kendaraan')->assertOk()->assertSee('Hapus Semua Dibatalkan');
        $this->actingAs($adminAset)->get('/adminasettetap/peminjaman-barang')->assertOk()->assertSee('Hapus Semua Dibatalkan');

        // 4. Hapus di Admin Persediaan (Permintaan Persediaan & Permintaan Barang Lainnya)
        $this->actingAs($adminPersediaan)
            ->delete("/adminpersediaan/permintaan/{$permintaanCancelled->id}/hapus-dibatalkan")
            ->assertRedirect();

        $this->actingAs($adminPersediaan)
            ->delete("/adminpersediaan/barang-lainnya/peminjaman/{$barangLainnyaCancelled->id}/hapus-dibatalkan")
            ->assertRedirect();

        // 5. Hapus di Admin Aset Tetap (Peminjaman Kendaraan & Peminjaman Barang)
        $this->actingAs($adminAset)
            ->delete("/adminasettetap/peminjaman-kendaraan/{$kendaraanCancelled->id}/hapus-dibatalkan")
            ->assertRedirect();

        $this->actingAs($adminAset)
            ->delete("/adminasettetap/peminjaman-barang/{$barangCancelled->id}/hapus-dibatalkan")
            ->assertRedirect();

        // 6. Semua tidak lagi tampil pada query normal Admin & Pegawai
        $this->assertNull(PermintaanPersediaan::find($permintaanCancelled->id));
        $this->assertNull(PeminjamanKendaraan::find($kendaraanCancelled->id));
        $this->assertNull(PeminjamanBarang::find($barangCancelled->id));
        $this->assertNull(PeminjamanBarangLainnya::find($barangLainnyaCancelled->id));
        $this->assertNotNull(PermintaanPersediaan::find($permintaanPending->id));

        // 7. Semua tetap ada di DB (SoftDeleted) & terlihat di Super Admin Kelola Data
        $this->assertSoftDeleted('permintaan_persediaan', ['id' => $permintaanCancelled->id, 'deleted_by' => $adminPersediaan->id]);
        $this->assertSoftDeleted('peminjaman_kendaraan', ['id' => $kendaraanCancelled->id, 'deleted_by' => $adminAset->id]);
        $this->assertSoftDeleted('peminjaman_barang', ['id' => $barangCancelled->id, 'deleted_by' => $adminAset->id]);
        $this->assertSoftDeleted('peminjaman_barang_lainnya', ['id' => $barangLainnyaCancelled->id, 'deleted_by' => $adminPersediaan->id]);

        $superView = $this->actingAs($superadmin)->get('/superadmin/kelola-data');
        $superView->assertOk();
        $superView->assertSee('Arsip Backup Data Dibatalkan');
        $superView->assertSee('Toyota Avanza');
        $superView->assertSee('Proyektor Epson');
        $superView->assertSee('Cokrol 5 Lubang');

        // 8. Export JSON backup arsip di Super Admin berjalan lancar
        $export = $this->actingAs($superadmin)->get('/superadmin/kelola-data/arsip-dibatalkan/export');
        $export->assertOk();

        // 9. Super Admin memulihkan (Restore) 1 record kendaraan kembali ke Admin
        $this->actingAs($superadmin)
            ->post("/superadmin/kelola-data/arsip-dibatalkan/kendaraan/{$kendaraanCancelled->id}/restore")
            ->assertRedirect();

        $this->assertNotNull(PeminjamanKendaraan::find($kendaraanCancelled->id));
        $this->assertNull($kendaraanCancelled->fresh()->deleted_by);

        // 10. Super Admin menghapus permanen record permintaan persediaan (+ detailnya ikut terhapus)
        $this->actingAs($superadmin)
            ->delete("/superadmin/kelola-data/arsip-dibatalkan/persediaan/{$permintaanCancelled->id}/force")
            ->assertRedirect();

        $this->assertDatabaseMissing('permintaan_persediaan', ['id' => $permintaanCancelled->id]);
        $this->assertDatabaseMissing('detail_permintaan_persediaan', ['permintaan_persediaan_id' => $permintaanCancelled->id]);
    }

    public function test_bulk_delete_all_cancelled_works_across_modules(): void
    {
        $adminPersediaan = User::factory()->create(['role' => 'adminpersediaan', 'is_active' => true]);
        $adminAset = User::factory()->create(['role' => 'adminasettetap', 'is_active' => true]);
        $pegawai = User::factory()->create(['role' => 'pegawai', 'is_active' => true]);

        $persediaan = Persediaan::create([
            'kode_barang'   => 'ATK-BULK',
            'nama_barang'   => 'Spidol',
            'kode_kategori' => 'ATK',
            'kategori'      => 'ATK',
            'satuan'        => 'buah',
            'jumlah'        => 10,
            'harga_satuan'  => 10000,
            'harga_total'   => 100000,
            'tanggal_masuk' => now()->toDateString(),
        ]);

        foreach (range(1, 2) as $i) {
            PermintaanPersediaan::create([
                'kode_barang'        => "ATK-0{$i}",
                'nama_barang'        => "ATK {$i}",
                'persediaan_id'      => $persediaan->id,
                'user_id'            => $pegawai->id,
                'jumlah_diminta'     => 1,
                'satuan'             => 'buah',
                'tanggal_permintaan' => now()->toDateString(),
                'tujuan_penggunaan'  => 'Batal',
                'status'             => 'dibatalkan',
            ]);
            PeminjamanKendaraan::create([
                'user_id'              => $pegawai->id,
                'nama_barang'          => "Mobil {$i}",
                'kode_barang'          => "K-0{$i}",
                'jumlah'               => 1,
                'deskripsi_peruntukan' => 'Batal',
                'request_date'         => now(),
                'tanggal_peminjaman'   => now(),
                'tanggal_pengembalian' => now()->addDay(),
                'status'               => 'dibatalkan',
            ]);
            $pBarang = PeminjamanBarang::create([
                'user_id'              => $pegawai->id,
                'nama_barang'          => "Barang {$i}",
                'kode_barang'          => "B-0{$i}",
                'kategori'             => 'Elektronik',
                'jumlah'               => 1,
                'deskripsi_peruntukan' => 'Batal',
                'request_date'         => now(),
                'tanggal_peminjaman'   => now(),
                'tanggal_pengembalian' => now()->addDay(),
            ]);
            $pBarang->status = 'dibatalkan';
            $pBarang->save();
            PeminjamanBarangLainnya::create([
                'user_id'        => $pegawai->id,
                'nama_barang'    => "Cokrol {$i}",
                'kode_barang'    => "BL-0{$i}",
                'jumlah'         => 1,
                'satuan'         => 'unit',
                'tanggal_pinjam' => now()->toDateString(),
                'keperluan'      => 'Batal',
                'status'         => 'dibatalkan',
            ]);
        }

        $this->actingAs($adminPersediaan)->post('/adminpersediaan/permintaan/hapus-semua-dibatalkan')->assertRedirect();
        $this->actingAs($adminPersediaan)->post('/adminpersediaan/barang-lainnya/peminjaman/hapus-semua-dibatalkan')->assertRedirect();
        $this->actingAs($adminAset)->post('/adminasettetap/peminjaman-kendaraan/hapus-semua-dibatalkan')->assertRedirect();
        $this->actingAs($adminAset)->post('/adminasettetap/peminjaman-barang/hapus-semua-dibatalkan')->assertRedirect();

        $this->assertSame(0, PermintaanPersediaan::count());
        $this->assertSame(0, PeminjamanKendaraan::count());
        $this->assertSame(0, PeminjamanBarang::count());
        $this->assertSame(0, PeminjamanBarangLainnya::count());

        $this->assertSame(2, PermintaanPersediaan::onlyTrashed()->count());
        $this->assertSame(2, PeminjamanKendaraan::onlyTrashed()->count());
        $this->assertSame(2, PeminjamanBarang::onlyTrashed()->count());
        $this->assertSame(2, PeminjamanBarangLainnya::onlyTrashed()->count());
    }

    public function test_unauthorized_pegawai_cannot_delete_admin_records_or_manage_superadmin_archive(): void
    {
        $pegawai = User::factory()->create(['role' => 'pegawai', 'is_active' => true]);

        $this->actingAs($pegawai)->delete('/adminpersediaan/permintaan/1/hapus-dibatalkan')->assertForbidden();
        $this->actingAs($pegawai)->delete('/adminpersediaan/barang-lainnya/peminjaman/1/hapus-dibatalkan')->assertForbidden();
        $this->actingAs($pegawai)->delete('/adminasettetap/peminjaman-kendaraan/1/hapus-dibatalkan')->assertForbidden();
        $this->actingAs($pegawai)->delete('/adminasettetap/peminjaman-barang/1/hapus-dibatalkan')->assertForbidden();
        $this->actingAs($pegawai)->get('/superadmin/kelola-data')->assertForbidden();
        $this->actingAs($pegawai)->post('/superadmin/kelola-data/arsip-dibatalkan/persediaan/1/restore')->assertForbidden();
        $this->actingAs($pegawai)->delete('/superadmin/kelola-data/arsip-dibatalkan/persediaan/1/force')->assertForbidden();
    }
}
