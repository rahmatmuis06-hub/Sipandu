<?php

namespace Tests\Feature;

use App\Models\AjuanMutasi;
use App\Models\AssetTetap;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PrioritySecurityFixesTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_registration_cannot_choose_a_privileged_role(): void
    {
        $response = $this->post('/daftar', [
            'name' => 'Pengguna Publik',
            'nip' => 'NIP-SEC-001',
            'username' => 'publik_security_test',
            'password' => 'rahasia-kuat',
            'password_confirmation' => 'rahasia-kuat',
            'role' => 'superadmin',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('users', [
            'username' => 'publik_security_test',
            'role' => 'tamu',
        ]);
        $this->assertDatabaseMissing('users', [
            'username' => 'publik_security_test',
            'role' => 'superadmin',
        ]);
    }

    public function test_tamu_cannot_open_employee_mutation_feature(): void
    {
        $tamu = User::factory()->create(['role' => 'tamu', 'is_active' => true]);

        $this->actingAs($tamu)->get('/pegawai/ajuan-mutasi')->assertForbidden();
        $this->actingAs($tamu)->get('/ajuan-mutasi')->assertNotFound();
    }

    public function test_authenticated_user_is_redirected_from_login_to_their_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'adminpersediaan', 'is_active' => true]);

        $this->actingAs($admin)
            ->get('/login')
            ->assertRedirect('/adminpersediaan/dashboard');

        $this->actingAs($admin)
            ->post('/login', ['username' => 'irrelevant', 'password' => 'irrelevant'])
            ->assertRedirect('/adminpersediaan/dashboard');
    }

    public function test_asset_submission_creates_only_one_record(): void
    {
        $admin = User::factory()->create(['role' => 'adminasettetap', 'is_active' => true]);

        $response = $this->actingAs($admin)->post('/adminasettetap/data-aset-tetap', [
            'tanggal_input' => now()->format('Y-m-d'),
            'kode_barang' => 'KB-SEC-001',
            'nup' => 'NUP-SEC-001',
            'nama_barang' => 'Laptop Uji',
            'merek' => 'Uji',
            'kategori' => 'Elektronik',
            'tanggal_perolehan' => now()->subYear()->format('Y-m-d'),
            'nilai_perolehan' => 10000000,
            'kondisi' => 'baik',
            'lokasi' => 'Ruang A',
            'jumlah' => 1,
            'status' => 'Tersedia',
        ]);

        $response->assertRedirect(route('adminasettetap.data-aset-tetap'));
        $this->assertSame(1, AssetTetap::where('kode_barang', 'KB-SEC-001')->count());
    }

    public function test_mutation_request_does_not_change_master_asset_before_approval(): void
    {
        $pegawai = User::factory()->create(['role' => 'pegawai', 'is_active' => true]);
        $asset = AssetTetap::create($this->assetData(['lokasi' => 'Ruang Lama']));

        $response = $this->actingAs($pegawai)->post('/pegawai/ajuan-mutasi', [
            'aset_tetap_id' => $asset->id,
            'lokasi_awal' => 'Ruang Lama',
            'lokasi_akhir' => 'Ruang Baru',
            'tanggal_mutasi' => now()->format('Y-m-d'),
            'keterangan' => 'Menunggu persetujuan',
        ]);

        $response->assertOk()->assertJson(['success' => true]);
        $this->assertSame('Ruang Lama', $asset->fresh()->lokasi);
        $this->assertDatabaseHas('ajuan_mutasi', [
            'aset_tetap_id' => $asset->id,
            'user_id' => $pegawai->id,
            'lokasi_akhir' => 'Ruang Baru',
        ]);
    }

    public function test_employee_cannot_read_another_employees_mutation(): void
    {
        $owner = User::factory()->create(['role' => 'pegawai', 'is_active' => true]);
        $attacker = User::factory()->create(['role' => 'pegawai', 'is_active' => true]);
        $asset = AssetTetap::create($this->assetData());
        $request = AjuanMutasi::create([
            'aset_tetap_id' => $asset->id,
            'user_id' => $owner->id,
            'kode_barang' => $asset->kode_barang,
            'nup' => $asset->nup,
            'nama_barang' => $asset->nama_barang,
            'lokasi_awal' => 'Ruang Lama',
            'lokasi_akhir' => 'Ruang Baru',
            'kondisi' => 'baik',
            'tanggal_mutasi' => now()->format('Y-m-d'),
        ]);

        $this->actingAs($attacker)
            ->get('/pegawai/ajuan-mutasi/'.$request->id)
            ->assertNotFound();
    }

    public function test_base64_signature_is_saved_for_the_authenticated_user(): void
    {
        Storage::fake('public');
        $user = User::factory()->create(['role' => 'pegawai', 'is_active' => true]);
        $png = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=';

        $response = $this->actingAs($user)->post('/profile/signature', [
            'signature_base64' => 'data:image/png;base64,'.$png,
        ]);

        $response->assertRedirect();
        $path = $user->fresh()->signature;
        $this->assertNotNull($path);
        Storage::disk('public')->assertExists($path);
    }

    private function assetData(array $overrides = []): array
    {
        return array_merge([
            'tanggal_input' => now()->format('Y-m-d'),
            'kode_barang' => 'KB-MUT-001',
            'nup' => 'NUP-MUT-001',
            'nama_barang' => 'Aset Mutasi',
            'merek' => 'Uji',
            'kategori' => 'Elektronik',
            'tanggal_perolehan' => now()->subYear()->format('Y-m-d'),
            'nilai_perolehan' => 5000000,
            'kondisi' => 'baik',
            'lokasi' => 'Ruang Lama',
            'jumlah' => 1,
            'status' => 'Tersedia',
        ], $overrides);
    }
}
