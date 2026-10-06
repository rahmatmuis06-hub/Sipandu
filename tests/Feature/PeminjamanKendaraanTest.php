<?php

namespace Tests\Feature;

use App\Models\AssetTetap;
use App\Models\User;
use App\Models\PeminjamanKendaraan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PeminjamanKendaraanTest extends TestCase
{
    use RefreshDatabase;

    public function test_pegawai_can_submit_vehicle_loan_successfully(): void
    {
        $pegawai = User::factory()->create(['role' => 'pegawai', 'is_active' => true]);
        
        $aset = AssetTetap::create([
            'nama_barang' => 'Toyota Avanza',
            'kode_barang' => 'KND-001',
            'nup' => '001',
            'merek' => 'Toyota',
            'kategori' => 'Kendaraan',
            'status' => 'Tersedia',
            'jumlah' => 1,
            'tanggal_input' => now()->format('Y-m-d'),
            'tanggal_perolehan' => now()->format('Y-m-d'),
            'nilai_perolehan' => 200000000,
            'kondisi' => 'baik',
            'lokasi' => 'Garasi',
        ]);

        $response = $this->actingAs($pegawai)->post(route('pegawai.peminjaman-kendaraan.store'), [
            'kode_barang' => 'KND-001',
            'nup' => '001',
            'jumlah' => 1,
            'tanggal_peminjaman' => now()->addDay()->format('Y-m-d'),
            'tanggal_pengembalian' => now()->addDays(2)->format('Y-m-d'),
            'deskripsi_peruntukan' => 'Perjalanan dinas ke Limboto',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('peminjaman_kendaraan', [
            'user_id' => $pegawai->id,
            'kode_barang' => 'KND-001',
            'nup' => '001',
            'status' => 'pending',
        ]);
    }

    public function test_cannot_loan_vehicle_for_today(): void
    {
        $pegawai = User::factory()->create(['role' => 'pegawai', 'is_active' => true]);

        $response = $this->actingAs($pegawai)->post(route('pegawai.peminjaman-kendaraan.store'), [
            'kode_barang' => 'KND-001',
            'jumlah' => 1,
            'tanggal_peminjaman' => now()->format('Y-m-d'),
            'tanggal_pengembalian' => now()->addDay()->format('Y-m-d'),
            'deskripsi_peruntukan' => 'Perjalanan mendesak',
        ]);

        $response->assertSessionHasErrors('tanggal_peminjaman');
    }

    public function test_vehicle_loan_submission_gracefully_succeeds_even_without_snapshot_columns(): void
    {
        $pegawai = User::factory()->create(['role' => 'pegawai', 'is_active' => true]);
        
        $aset = AssetTetap::create([
            'nama_barang' => 'Toyota HiAce',
            'kode_barang' => 'KND-002',
            'nup' => '002',
            'merek' => 'Toyota',
            'kategori' => 'Kendaraan',
            'status' => 'Tersedia',
            'jumlah' => 1,
            'tanggal_input' => now()->format('Y-m-d'),
            'tanggal_perolehan' => now()->format('Y-m-d'),
            'nilai_perolehan' => 300000000,
            'kondisi' => 'baik',
            'lokasi' => 'Garasi',
        ]);

        // Temporarily drop columns to simulate production database before migration
        \Illuminate\Support\Facades\Schema::table('peminjaman_kendaraan', function ($table) {
            $table->dropColumn(['nomor_polisi_saat_pinjam', 'no_bpkb_saat_pinjam', 'nomor_rangka_saat_pinjam', 'nomor_mesin_saat_pinjam']);
        });

        $response = $this->actingAs($pegawai)->post(route('pegawai.peminjaman-kendaraan.store'), [
            'kode_barang' => 'KND-002',
            'nup' => '002',
            'jumlah' => 1,
            'tanggal_peminjaman' => now()->addDay()->format('Y-m-d'),
            'tanggal_pengembalian' => now()->addDays(2)->format('Y-m-d'),
            'deskripsi_peruntukan' => 'Perjalanan dinas ke Boalemo',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('peminjaman_kendaraan', [
            'user_id' => $pegawai->id,
            'kode_barang' => 'KND-002',
            'nup' => '002',
            'status' => 'pending',
        ]);
    }
}
