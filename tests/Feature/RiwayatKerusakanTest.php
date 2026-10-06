<?php
namespace Tests\Feature;
use App\Models\{Kerusakan, User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RiwayatKerusakanTest extends TestCase {
    use RefreshDatabase;
    public function test_history_and_repair_preserve_previous_condition(): void {
        $this->actingAs(User::factory()->create(['role'=>'adminsarpras','is_active'=>true]));
        $item=Kerusakan::create(['tanggal_input'=>today(),'nama_barang'=>'Kursi','kode_barang'=>'TEST-01','kondisi'=>'Rusak Berat','lokasi'=>'Aula','deskripsi'=>'Kaki patah']);
        $item->update(['lokasi'=>'Bengkel']);
        $this->assertCount(2,$item->riwayat()->get());
        $this->assertSame('Aula',$item->riwayat()->oldest('id')->reorder('id')->first()->data['lokasi']);
        $this->post(route('adminsarpras.kerusakan.perbaikan.store',$item),[
            'tanggal_perbaikan'=>today()->format('Y-m-d'),'status'=>'Selesai','tindakan'=>'Ganti kaki kursi','biaya'=>50000,
        ])->assertSessionHasNoErrors()->assertRedirect();
        $this->assertSame('Baik',$item->fresh()->kondisi);
        $this->assertCount(3,$item->riwayat()->get());
        $this->get(route('adminsarpras.kerusakan.riwayat',$item))->assertOk()->assertSee('Ganti kaki kursi')->assertSee('Rusak Berat');
        $this->delete(route('adminsarpras.kerusakan.destroy',$item))->assertSessionHas('error');
        $this->assertDatabaseHas('kerusakan',['id'=>$item->id]);
    }
    public function test_multiple_kerusakan_can_have_same_kode_barang(): void {
        $admin = User::factory()->create(['role'=>'adminsarpras','is_active'=>true]);
        $this->actingAs($admin);

        // Tambah barang rusak pertama (misal Meja Lipat NUP 9)
        $response1 = $this->post(route('adminsarpras.kerusakan.store'), [
            'tanggal_input' => today()->format('Y-m-d'),
            'nama_barang' => 'Meja Lipat',
            'kode_barang' => '3050201039',
            'nup' => '9',
            'kondisi' => 'Rusak Ringan',
            'lokasi' => 'Gedung Arsip',
            'deskripsi' => 'Baut longgar',
        ]);
        $response1->assertSessionHasNoErrors()->assertRedirect(route('adminsarpras.data-kerusakan'));

        // Tambah barang rusak kedua dengan KODE BARANG YANG SAMA (misal Meja Lipat NUP 10)
        $response2 = $this->post(route('adminsarpras.kerusakan.store'), [
            'tanggal_input' => today()->format('Y-m-d'),
            'nama_barang' => 'Meja Lipat',
            'kode_barang' => '3050201039',
            'nup' => '10',
            'kondisi' => 'Rusak Ringan',
            'lokasi' => 'Ruang Kelas A',
            'deskripsi' => 'Engsel patah',
        ]);
        $response2->assertSessionHasNoErrors()->assertRedirect(route('adminsarpras.data-kerusakan'));

        $this->assertEquals(2, Kerusakan::where('kode_barang', '3050201039')->count());
    }
}
