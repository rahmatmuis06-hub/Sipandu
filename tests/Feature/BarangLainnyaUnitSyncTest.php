<?php

namespace Tests\Feature;

use App\Models\BarangLainnya;
use App\Models\BarangLainnyaUnit;
use App\Models\PeminjamanBarangLainnya;
use App\Models\User;
use App\Services\BarangLainnyaUnitService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BarangLainnyaUnitSyncTest extends TestCase
{
    use RefreshDatabase;

    public function test_stale_borrowed_units_are_reconciled_on_sync(): void
    {
        $pegawai = User::factory()->create(['role' => 'pegawai', 'is_active' => true]);

        $barang = BarangLainnya::create([
            'kode_barang'   => 'BLN-001',
            'nama_barang'   => 'Kabel Roll',
            'kategori'      => 'Barang Lainnya',
            'satuan'        => 'unit',
            'jumlah'        => 0,
            'harga_satuan'  => 50000,
            'harga_total'   => 0,
            'tanggal_masuk' => now()->toDateString(),
        ]);

        $returnedLoan = PeminjamanBarangLainnya::create([
            'user_id'           => $pegawai->id,
            'barang_lainnya_id' => $barang->id,
            'nama_barang'       => $barang->nama_barang,
            'kode_barang'       => $barang->kode_barang,
            'jumlah'            => 1,
            'satuan'            => 'unit',
            'tanggal_pinjam'    => now()->subDays(3)->toDateString(),
            'keperluan'         => 'Rapat',
            'status'            => 'dikembalikan',
        ]);

        $unit1 = BarangLainnyaUnit::create([
            'barang_lainnya_id'   => $barang->id,
            'kode_unit'           => '1',
            'status'              => 'dipinjam',
            'peminjaman_aktif_id' => $returnedLoan->id,
        ]);

        $unit2 = BarangLainnyaUnit::create([
            'barang_lainnya_id'   => $barang->id,
            'kode_unit'           => '2',
            'status'              => 'dipinjam',
            'peminjaman_aktif_id' => 999999, // peminjaman sudah terhapus / yatim
        ]);

        $fixed = BarangLainnyaUnitService::reconcileStaleBorrowedUnits();

        $this->assertSame(2, $fixed);
        $this->assertSame('tersedia', $unit1->fresh()->status);
        $this->assertNull($unit1->fresh()->peminjaman_aktif_id);
        $this->assertSame('tersedia', $unit2->fresh()->status);
        $this->assertNull($unit2->fresh()->peminjaman_aktif_id);
        $this->assertSame(2, $barang->fresh()->jumlah);
    }

    public function test_admin_can_filter_active_loans_and_release_single_borrowed_unit(): void
    {
        $admin = User::factory()->create(['role' => 'adminpersediaan', 'is_active' => true]);
        $pegawai = User::factory()->create(['role' => 'pegawai', 'name' => 'Imelda Zainudin', 'is_active' => true]);

        $barang = BarangLainnya::create([
            'kode_barang'   => 'BLN-002',
            'nama_barang'   => 'Colokan Listrik',
            'kategori'      => 'Barang Lainnya',
            'satuan'        => 'unit',
            'jumlah'        => 1,
            'harga_satuan'  => 25000,
            'harga_total'   => 25000,
            'tanggal_masuk' => now()->toDateString(),
        ]);

        $unit4 = BarangLainnyaUnit::create([
            'barang_lainnya_id' => $barang->id,
            'kode_unit'         => '4',
            'status'            => 'tersedia',
            'keterangan'        => 'Keuangan',
        ]);

        $unit5 = BarangLainnyaUnit::create([
            'barang_lainnya_id' => $barang->id,
            'kode_unit'         => '5',
            'status'            => 'tersedia',
        ]);

        $loan = PeminjamanBarangLainnya::create([
            'user_id'           => $pegawai->id,
            'barang_lainnya_id' => $barang->id,
            'nama_barang'       => $barang->nama_barang,
            'kode_barang'       => $barang->kode_barang,
            'jumlah'            => 1,
            'unit_ids'          => [$unit4->id],
            'kode_unit'         => '4',
            'satuan'            => 'unit',
            'tanggal_pinjam'    => now()->toDateString(),
            'keperluan'         => 'Keuangan',
            'status'            => 'disetujui',
        ]);

        $unit4->update([
            'status'              => 'dipinjam',
            'peminjaman_aktif_id' => $loan->id,
        ]);
        BarangLainnyaUnitService::recalculateAvailableStock($barang);

        $this->assertSame(1, $barang->fresh()->jumlah);

        $response = $this->actingAs($admin)
            ->get('/adminpersediaan/barang-lainnya?tab=pinjam&peminjaman_status=dipinjam&peminjaman_search=Imelda');

        $response->assertOk();
        $response->assertSee('Imelda Zainudin');
        $response->assertSee('1 Dipinjam');

        $releaseResponse = $this->actingAs($admin)
            ->post("/adminpersediaan/barang-lainnya/unit/{$unit4->id}/release");

        $releaseResponse->assertRedirect();
        $this->assertSame('tersedia', $unit4->fresh()->status);
        $this->assertNull($unit4->fresh()->peminjaman_aktif_id);
        $this->assertSame('dikembalikan', $loan->fresh()->status);
        $this->assertSame(2, $barang->fresh()->jumlah);
        $this->assertSame('tersedia', $unit5->fresh()->status);
    }
}
