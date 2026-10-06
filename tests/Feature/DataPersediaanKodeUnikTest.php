<?php

namespace Tests\Feature;

use App\Models\Persediaan;
use App\Models\TransaksiMasukPersediaan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DataPersediaanKodeUnikTest extends TestCase
{
    use RefreshDatabase;

    public function test_data_persediaan_dapat_dibuat_dengan_satu_kode_unik(): void
    {
        $admin = User::factory()->create(['role' => 'adminpersediaan', 'is_active' => true]);

        $this->actingAs($admin)
            ->post(route('adminpersediaan.data-persediaan.store'), $this->dataBarang())
            ->assertRedirect(route('adminpersediaan.data-persediaan'));

        $this->assertDatabaseHas('persediaan', [
            'kode_unik_barang' => '1010301003-000001',
            'kode_kategori' => '1010301003',
            'kode_barang' => '000001',
            'nama_barang' => 'Binder Clips',
        ]);
    }

    public function test_kode_unik_barang_tidak_boleh_duplikat(): void
    {
        $admin = User::factory()->create(['role' => 'adminpersediaan', 'is_active' => true]);
        Persediaan::create($this->modelData());

        $this->actingAs($admin)
            ->from(route('adminpersediaan.data-persediaan'))
            ->post(route('adminpersediaan.data-persediaan.store'), $this->dataBarang())
            ->assertSessionHasErrors('kode_unik_barang');

        $this->assertSame(1, Persediaan::where('kode_unik_barang', '1010301003-000001')->count());
    }

    public function test_data_persediaan_dan_laporan_menampilkan_kode_unik(): void
    {
        $admin = User::factory()->create(['role' => 'adminpersediaan', 'is_active' => true]);
        Persediaan::create($this->modelData());
        TransaksiMasukPersediaan::create([
            'tanggal_input' => '2026-09-07',
            'kode_kategori' => '1010301003',
            'kategori' => 'Penjepit Kertas',
            'kode_barang' => '000001',
            'nama_barang' => 'Binder Clips',
            'jumlah_masuk' => 5,
            'satuan' => 'box',
            'harga_satuan' => 10000,
            'total' => 50000,
            'user_id' => $admin->id,
        ]);

        $this->actingAs($admin)
            ->get(route('adminpersediaan.data-persediaan'))
            ->assertOk()
            ->assertSee('Kode Unik Barang')
            ->assertSee('1010301003-000001');

        $this->actingAs($admin)
            ->get(route('adminpersediaan.laporan-transaksi-masuk'))
            ->assertOk()
            ->assertSee('Kode Unik Barang')
            ->assertSee('1010301003-000001');
    }

    private function dataBarang(): array
    {
        return [
            'kode_unik_barang' => '1010301003-000001',
            'kategori' => 'Penjepit Kertas',
            'nama_barang' => 'Binder Clips',
            'satuan' => 'box',
            'tanggal_masuk' => '2026-09-07',
            'harga_satuan' => 10000,
            'jumlah' => 5,
        ];
    }

    private function modelData(): array
    {
        return [
            'kode_kategori' => '1010301003',
            'kategori' => 'Penjepit Kertas',
            'kode_barang' => '000001',
            'nama_barang' => 'Binder Clips',
            'satuan' => 'box',
            'tanggal_masuk' => '2026-09-07',
            'harga_satuan' => 10000,
            'harga_total' => 50000,
            'jumlah' => 5,
        ];
    }
}
