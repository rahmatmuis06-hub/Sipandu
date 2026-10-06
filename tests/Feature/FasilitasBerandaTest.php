<?php
namespace Tests\Feature;

use App\Models\FasilitasBeranda;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FasilitasBerandaTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_edit_and_hide_a_facility(): void
    {
        $admin = User::factory()->create(['role'=>'adminsarpras', 'is_active'=>true]);
        $item = FasilitasBeranda::firstOrFail();
        $this->actingAs($admin)->get(route('adminsarpras.fasilitas-beranda.edit',$item))->assertOk();
        $data = array_merge($item->konten, ['name'=>'Fasilitas Uji Baru', 'features'=>"AC\nInternet", 'rules'=>'Jaga kebersihan', 'urutan'=>1, 'tampil'=>1]);
        $this->put(route('adminsarpras.fasilitas-beranda.update',$item),$data)->assertSessionHasNoErrors()->assertRedirect();
        $this->assertSame(['AC','Internet'], $item->fresh()->konten['features']);
        $this->get('/')->assertOk()->assertSee('Fasilitas Uji Baru');
        $data['tampil'] = 0;
        $this->put(route('adminsarpras.fasilitas-beranda.update',$item),$data)->assertSessionHasNoErrors();
        $this->get('/')->assertDontSee('Fasilitas Uji Baru');
    }

    public function test_employee_cannot_edit_public_facilities(): void
    {
        $this->actingAs(User::factory()->create(['role'=>'pegawai', 'is_active'=>true]))
            ->put(route('adminsarpras.fasilitas-beranda.update',FasilitasBeranda::firstOrFail()), [])
            ->assertForbidden();
    }
}
