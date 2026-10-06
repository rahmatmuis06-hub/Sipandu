<?php

namespace Tests\Feature;

use App\Models\Persediaan;
use App\Models\TransaksiMasukPersediaan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransaksiMasukPersediaanTest extends TestCase
{
    use RefreshDatabase;

    public function test_form_transaksi_masuk_memiliki_dropdown_barang_dari_data_persediaan(): void
    {
        $admin = User::factory()->create(['role' => 'adminpersediaan', 'is_active' => true]);
        $barang = Persediaan::create([
            'kode_kategori' => 'ATK',
            'kategori' => 'Alat Tulis Kantor',
            'kode_barang' => 'ATK-001',
            'nama_barang' => 'Kertas A4',
            'satuan' => 'rim',
            'tanggal_masuk' => '2026-01-01',
            'harga_satuan' => 25000,
            'harga_total' => 125000,
            'jumlah' => 5,
        ]);

        $this->actingAs($admin)
            ->get(route('adminpersediaan.transaksi-masuk'))
            ->assertOk()
            ->assertSee('Kode Unik Barang')
            ->assertSee('Kertas A4')
            ->assertSee('value="'.$barang->id.'"', false)
            ->assertSee('Barang baru belum ada di Data Persediaan');
    }

    public function test_transaksi_masuk_membuat_barang_baru_di_data_persediaan(): void
    {
        $admin = User::factory()->create(['role' => 'adminpersediaan', 'is_active' => true]);

        $response = $this->actingAs($admin)->post(
            route('adminpersediaan.transaksi-masuk.store'),
            $this->transaksiData()
        );

        $response->assertRedirect(route('adminpersediaan.transaksi-masuk'));
        $this->assertDatabaseHas('transaksi_masuk_persediaan', [
            'kode_barang' => 'ATK-001',
            'jumlah_masuk' => 10,
            'total' => 250000,
            'user_id' => $admin->id,
        ]);
        $this->assertDatabaseHas('persediaan', [
            'kode_barang' => 'ATK-001',
            'jumlah' => 10,
            'harga_satuan' => 25000,
            'harga_total' => 250000,
        ]);
    }

    public function test_transaksi_masuk_menambahkan_stok_barang_yang_sudah_ada(): void
    {
        $admin = User::factory()->create(['role' => 'adminpersediaan', 'is_active' => true]);
        Persediaan::create([
            'kode_kategori' => 'ATK',
            'kategori' => 'Alat Tulis Kantor',
            'kode_barang' => 'ATK-001',
            'nama_barang' => 'Kertas A4',
            'satuan' => 'rim',
            'tanggal_masuk' => '2026-01-01',
            'harga_satuan' => 20000,
            'harga_total' => 100000,
            'jumlah' => 5,
        ]);

        $this->actingAs($admin)->post(
            route('adminpersediaan.transaksi-masuk.store'),
            $this->transaksiData(['jumlah_masuk' => 3])
        )->assertRedirect(route('adminpersediaan.transaksi-masuk'));

        $this->assertDatabaseHas('persediaan', [
            'kode_barang' => 'ATK-001',
            'jumlah' => 8,
            'harga_satuan' => 25000,
            'harga_total' => 200000,
        ]);
    }

    public function test_edit_dan_hapus_transaksi_masuk_ikut_menyesuaikan_stok(): void
    {
        $admin = User::factory()->create(['role' => 'adminpersediaan', 'is_active' => true]);

        $this->actingAs($admin)->post(
            route('adminpersediaan.transaksi-masuk.store'),
            $this->transaksiData(['jumlah_masuk' => 10])
        );

        $transaksi = TransaksiMasukPersediaan::where('kode_barang', 'ATK-001')->firstOrFail();

        $this->actingAs($admin)->put(
            route('adminpersediaan.transaksi-masuk.update', $transaksi),
            $this->transaksiData(['jumlah_masuk' => 6])
        )->assertRedirect(route('adminpersediaan.transaksi-masuk'));

        $this->assertDatabaseHas('persediaan', [
            'kode_barang' => 'ATK-001',
            'jumlah' => 6,
            'harga_total' => 150000,
        ]);

        $this->actingAs($admin)->delete(
            route('adminpersediaan.transaksi-masuk.destroy', $transaksi)
        )->assertRedirect(route('adminpersediaan.transaksi-masuk'));

        $this->assertDatabaseMissing('transaksi_masuk_persediaan', ['id' => $transaksi->id]);
        $this->assertDatabaseMissing('persediaan', ['kode_barang' => 'ATK-001']);
    }

    private function transaksiData(array $overrides = []): array
    {
        return array_merge([
            'tanggal_input' => '2026-09-07',
            'kode_kategori' => 'ATK',
            'kategori' => 'Alat Tulis Kantor',
            'kode_barang' => 'ATK-001',
            'nama_barang' => 'Kertas A4',
            'satuan' => 'rim',
            'jumlah_masuk' => 10,
            'harga_satuan' => '25.000',
        ], $overrides);
    }
}
