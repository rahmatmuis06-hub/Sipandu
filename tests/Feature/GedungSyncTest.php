<?php

namespace Tests\Feature;

use App\Models\FasilitasBeranda;
use App\Models\Gedung;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GedungSyncTest extends TestCase
{
    use RefreshDatabase;

    public function test_gedung_auto_syncs_from_fasilitas_beranda_when_empty(): void
    {
        $admin = User::factory()->create(['role' => 'adminsarpras', 'is_active' => true]);

        FasilitasBeranda::create([
            'konten' => [
                'name' => 'Aula Dulohupa BPMP',
                'category' => 'ruang',
                'location' => 'Gedung Utama',
                'capacity' => '300 Orang',
                'luas' => '420 m²',
                'features' => ['AC', 'Sound System', 'Videotron'],
                'images' => ['/storage/fasilitas/gedung_aula.jpeg'],
            ],
            'urutan' => 1,
            'tampil' => 1,
        ]);

        $this->assertSame(0, Gedung::count());

        $response = $this->actingAs($admin)->get(route('adminsarpras.data-gedung'));
        $response->assertOk();

        $this->assertDatabaseHas('gedung', [
            'nama_gedung' => 'Aula Dulohupa BPMP',
            'kategori' => 'ruang',
            'kapasitas' => 300,
            'lokasi' => 'Gedung Utama',
            'foto_url' => 'fasilitas/gedung_aula.jpeg',
        ]);
    }

    public function test_admin_can_trigger_manual_sync_from_fasilitas_beranda(): void
    {
        $admin = User::factory()->create(['role' => 'adminsarpras', 'is_active' => true]);

        FasilitasBeranda::create([
            'konten' => [
                'name' => 'Ruang Tilango 1',
                'category' => 'kelas',
                'location' => 'Sayap Barat',
                'capacity' => '30 Orang',
                'luas' => '68 m²',
                'features' => ['AC', 'Proyektor'],
                'images' => ['/storage/fasilitas/tilango.jpg'],
            ],
            'urutan' => 1,
            'tampil' => 1,
        ]);

        $response = $this->actingAs($admin)->post(route('adminsarpras.data-gedung.sync-fasilitas'));
        $response->assertRedirect(route('adminsarpras.data-gedung'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('gedung', [
            'nama_gedung' => 'Ruang Tilango 1',
            'kategori' => 'kelas',
            'kapasitas' => 30,
        ]);
    }
}
