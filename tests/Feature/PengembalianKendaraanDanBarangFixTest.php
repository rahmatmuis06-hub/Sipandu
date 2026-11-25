<?php

namespace Tests\Feature;

use App\Models\AssetTetap;
use App\Models\DetailPeminjamanBarang;
use App\Models\PeminjamanBarang;
use App\Models\PeminjamanKendaraan;
use App\Models\PengembalianBarang;
use App\Models\PengembalianKendaraan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PengembalianKendaraanDanBarangFixTest extends TestCase
{
    use RefreshDatabase;

    public function test_pegawai_submitting_returns_updates_parent_peminjaman_status_to_proses_pengembalian(): void
    {
        Storage::fake('public');

        $pegawai = User::factory()->create(['role' => 'pegawai', 'is_active' => true]);

        $pinjamKendaraan = PeminjamanKendaraan::create([
            'user_id' => $pegawai->id,
            'nama_barang' => 'Mobil Dinas Avanza',
            'kode_barang' => 'KND-001',
            'nup' => '1',
            'merek' => 'Toyota',
            'nomor_polisi_saat_pinjam' => 'DM 1234 AA',
            'jumlah' => 1,
            'deskripsi_peruntukan' => 'Perjalanan dinas luar kota',
            'request_date' => now(),
            'tanggal_peminjaman' => now()->subDay()->toDateString(),
            'tanggal_pengembalian' => now()->addDay()->toDateString(),
            'status' => 'disetujui',
        ]);

        $pinjamBarang = PeminjamanBarang::create([
            'user_id' => $pegawai->id,
            'nama_barang' => 'Proyektor Epson',
            'kode_barang' => 'BRG-001',
            'nup' => '2',
            'kategori' => 'Elektronik',
            'merek' => 'Epson',
            'jumlah' => 1,
            'deskripsi_peruntukan' => 'Rapat tim kerja',
            'request_date' => now(),
            'tanggal_peminjaman' => now()->subDay()->toDateString(),
            'tanggal_pengembalian' => now()->addDay()->toDateString(),
            'status' => 'disetujui',
        ]);

        $resKendaraan = $this->actingAs($pegawai)->post(route('pegawai.pengembalian-kendaraan.store'), [
            'peminjaman_kendaraan_id' => $pinjamKendaraan->id,
            'tanggal_pengembalian_aktual' => now()->format('Y-m-d'),
            'kondisi_kendaraan' => 'baik',
            'catatan' => 'Kendaraan bersih dan lengkap',
            'foto_sebelum' => UploadedFile::fake()->image('sebelum.jpg'),
            'foto_sesudah' => UploadedFile::fake()->image('sesudah.jpg'),
        ]);
        $resKendaraan->assertRedirect();
        $resKendaraan->assertSessionHasNoErrors();

        $resBarang = $this->actingAs($pegawai)->post(route('pegawai.pengembalian-barang.store'), [
            'peminjaman_barang_id' => $pinjamBarang->id,
            'tanggal_pengembalian_aktual' => now()->format('Y-m-d'),
            'jumlah_dikembalikan' => 1,
            'kondisi_barang' => 'baik',
            'catatan' => 'Kabel lengkap',
            'foto_sesudah' => UploadedFile::fake()->image('barang.jpg'),
        ]);
        $resBarang->assertRedirect();
        $resBarang->assertSessionHasNoErrors();

        $this->assertSame('proses_pengembalian', $pinjamKendaraan->fresh()->status);
        $this->assertSame('proses_pengembalian', $pinjamBarang->fresh()->status);
    }

    public function test_admin_pengembalian_kendaraan_and_barang_search_and_filters_return_200(): void
    {
        $admin = User::factory()->create(['role' => 'adminasettetap', 'is_active' => true]);
        $pegawai = User::factory()->create(['role' => 'pegawai', 'name' => 'Rina Peminjam', 'is_active' => true]);

        $pinjam1 = PeminjamanKendaraan::create([
            'user_id' => $pegawai->id,
            'nama_barang' => 'Toyota Avanza',
            'kode_barang' => 'KND-101',
            'nup' => '1',
            'merek' => 'Toyota',
            'nomor_polisi_saat_pinjam' => 'DM 1234 AA',
            'jumlah' => 1,
            'deskripsi_peruntukan' => 'Kunjungan sekolah',
            'request_date' => now(),
            'tanggal_peminjaman' => now()->subDays(2)->toDateString(),
            'tanggal_pengembalian' => now()->toDateString(),
            'status' => 'proses_pengembalian',
        ]);

        $pinjam2 = PeminjamanKendaraan::create([
            'user_id' => $pegawai->id,
            'nama_barang' => 'Kijang Innova',
            'kode_barang' => 'KND-102',
            'nup' => '2',
            'merek' => 'Toyota',
            'nomor_polisi_saat_pinjam' => 'DM 9999 ZZ',
            'jumlah' => 1,
            'deskripsi_peruntukan' => 'Monitoring daerah',
            'request_date' => now(),
            'tanggal_peminjaman' => now()->subDays(3)->toDateString(),
            'tanggal_pengembalian' => now()->subDay()->toDateString(),
            'status' => 'dikembalikan',
        ]);

        PengembalianKendaraan::create([
            'peminjaman_kendaraan_id' => $pinjam1->id,
            'user_id' => $pegawai->id,
            'tanggal_pengembalian_aktual' => now(),
            'kondisi_kendaraan' => 'baik',
            'catatan' => 'Sudah dicuci bersih',
            'status_pengembalian' => 'lengkap',
            'status_verifikasi' => 'pending',
        ]);

        PengembalianKendaraan::create([
            'peminjaman_kendaraan_id' => $pinjam2->id,
            'user_id' => $pegawai->id,
            'tanggal_pengembalian_aktual' => now()->subDay(),
            'kondisi_kendaraan' => 'rusak-ringan',
            'catatan' => 'Baret halus',
            'status_pengembalian' => 'rusak_ringan',
            'status_verifikasi' => 'diterima',
        ]);

        // Search by plate number must not throw 500 BadMethodCallException
        $searchByPlate = $this->actingAs($admin)->get('/adminasettetap/pengembalian-kendaraan?search=DM+1234+AA');
        $searchByPlate->assertOk();
        $searchByPlate->assertSee('Toyota Avanza');
        $searchByPlate->assertDontSee('Kijang Innova');

        // Search combined with status filter must still respect status filter (no ungrouped orWhere leak)
        $combinedFilter = $this->actingAs($admin)->get('/adminasettetap/pengembalian-kendaraan?search=Innova&status=pending');
        $combinedFilter->assertOk();
        $combinedFilter->assertDontSee('Kijang Innova');

        // Pengembalian barang search + condition filter
        $pinjamBarang = PeminjamanBarang::create([
            'user_id' => $pegawai->id,
            'nama_barang' => 'Proyektor Epson EB',
            'kode_barang' => 'BRG-500',
            'nup' => '5',
            'kategori' => 'Elektronik',
            'merek' => 'Epson',
            'jumlah' => 1,
            'deskripsi_peruntukan' => 'Sosialisasi',
            'request_date' => now(),
            'tanggal_peminjaman' => now()->subDays(2)->toDateString(),
            'tanggal_pengembalian' => now()->toDateString(),
            'status' => 'proses_pengembalian',
        ]);

        PengembalianBarang::create([
            'peminjaman_barang_id' => $pinjamBarang->id,
            'user_id' => $pegawai->id,
            'tanggal_pengembalian_aktual' => now(),
            'jumlah_dikembalikan' => 1,
            'kondisi_barang' => 'baik',
            'catatan' => 'Kabel lengkap',
            'status_pengembalian' => 'lengkap',
            'status_verifikasi' => 'pending',
        ]);

        $searchBarang = $this->actingAs($admin)->get('/adminasettetap/pengembalian-barang?search=Proyektor&kondisi=rusak-berat');
        $searchBarang->assertOk();
        $searchBarang->assertDontSee('Proyektor Epson EB');
    }

    public function test_verifikasi_pengembalian_kendaraan_diterima_and_ditolak_updates_loan_and_restores_asset(): void
    {
        $admin = User::factory()->create(['role' => 'adminasettetap', 'name' => 'Admin Aset', 'is_active' => true]);
        $pegawai = User::factory()->create(['role' => 'pegawai', 'is_active' => true]);

        $aset1 = AssetTetap::create([
            'tanggal_input' => now()->toDateString(),
            'kode_barang' => '3020102001',
            'nup' => '1',
            'nama_barang' => 'Toyota Avanza Veloz',
            'merek' => 'Toyota',
            'kategori' => 'Kendaraan Bermotor',
            'tanggal_perolehan' => now()->subYears(2)->toDateString(),
            'nilai_perolehan' => 250000000,
            'jumlah' => 1,
            'lokasi' => 'Garasi Kantor',
            'kondisi' => 'baik',
            'status' => 'Dipinjam',
        ]);

        $pinjam1 = PeminjamanKendaraan::create([
            'user_id' => $pegawai->id,
            'nama_barang' => 'Toyota Avanza Veloz',
            'kode_barang' => '3020102001',
            'nup' => '1',
            'merek' => 'Toyota',
            'nomor_polisi_saat_pinjam' => 'DM 1001 AA',
            'jumlah' => 1,
            'deskripsi_peruntukan' => 'Dinas ke kabupaten',
            'request_date' => now(),
            'tanggal_peminjaman' => now()->subDay()->toDateString(),
            'tanggal_pengembalian' => now()->toDateString(),
            'status' => 'proses_pengembalian',
        ]);

        $kembali1 = PengembalianKendaraan::create([
            'peminjaman_kendaraan_id' => $pinjam1->id,
            'user_id' => $pegawai->id,
            'tanggal_pengembalian_aktual' => now(),
            'kondisi_kendaraan' => 'baik',
            'status_pengembalian' => 'lengkap',
            'status_verifikasi' => 'pending',
        ]);

        $resAccept = $this->actingAs($admin)->post(route('adminasettetap.pengembalian-kendaraan.verifikasi', $kembali1->id), [
            'status_verifikasi' => 'diterima',
            'komentar_admin' => 'Diterima dalam kondisi baik',
        ]);
        $resAccept->assertRedirect();
        $resAccept->assertSessionHas('success');

        $kembali1->refresh();
        $this->assertSame('diterima', $kembali1->status_verifikasi);
        $this->assertSame($admin->id, $kembali1->verified_by_admin_id);
        $this->assertNotNull($kembali1->verified_at);
        $this->assertSame($admin->id, $kembali1->admin?->id);
        $this->assertSame('dikembalikan', $pinjam1->fresh()->status);
        $this->assertSame('Tersedia', $aset1->fresh()->status);

        // Pastikan tampilan admin & pegawai menampilkan badge Dikembalikan (bukan Unknown/Pending)
        $adminList = $this->actingAs($admin)->get('/adminasettetap/peminjaman-kendaraan?status=Dikembalikan');
        $adminList->assertOk();
        $adminList->assertSee('Toyota Avanza Veloz');
        $adminList->assertSee('Dikembalikan');

        $pegawaiList = $this->actingAs($pegawai)->get('/pegawai/peminjaman-kendaraan');
        $pegawaiList->assertOk();
        $pegawaiList->assertSee('Dikembalikan');

        // Rejection case reverts parent loan status to disetujui and leaves asset Dipinjam
        $aset2 = AssetTetap::create([
            'tanggal_input' => now()->toDateString(),
            'kode_barang' => '3020102002',
            'nup' => '2',
            'nama_barang' => 'Toyota Rush',
            'merek' => 'Toyota',
            'kategori' => 'Kendaraan Bermotor',
            'tanggal_perolehan' => now()->subYear()->toDateString(),
            'nilai_perolehan' => 280000000,
            'jumlah' => 1,
            'lokasi' => 'Garasi Kantor',
            'kondisi' => 'baik',
            'status' => 'Dipinjam',
        ]);

        $pinjam2 = PeminjamanKendaraan::create([
            'user_id' => $pegawai->id,
            'nama_barang' => 'Toyota Rush',
            'kode_barang' => '3020102002',
            'nup' => '2',
            'merek' => 'Toyota',
            'nomor_polisi_saat_pinjam' => 'DM 2002 BB',
            'jumlah' => 1,
            'deskripsi_peruntukan' => 'Tugas lapangan',
            'request_date' => now(),
            'tanggal_peminjaman' => now()->subDay()->toDateString(),
            'tanggal_pengembalian' => now()->toDateString(),
            'status' => 'proses_pengembalian',
        ]);

        $kembali2 = PengembalianKendaraan::create([
            'peminjaman_kendaraan_id' => $pinjam2->id,
            'user_id' => $pegawai->id,
            'tanggal_pengembalian_aktual' => now(),
            'kondisi_kendaraan' => 'baik',
            'status_pengembalian' => 'lengkap',
            'status_verifikasi' => 'pending',
        ]);

        $resReject = $this->actingAs($admin)->post(route('adminasettetap.pengembalian-kendaraan.verifikasi', $kembali2->id), [
            'status_verifikasi' => 'ditolak',
            'komentar_admin' => 'Foto belum jelas',
        ]);
        $resReject->assertRedirect();

        $this->assertSame('ditolak', $kembali2->fresh()->status_verifikasi);
        $this->assertSame('disetujui', $pinjam2->fresh()->status);
        $this->assertSame('Dipinjam', $aset2->fresh()->status);
    }

    public function test_verifikasi_pengembalian_barang_updates_dikembalikan_verified_at_and_restores_assets(): void
    {
        $admin = User::factory()->create(['role' => 'adminasettetap', 'is_active' => true]);
        $pegawai = User::factory()->create(['role' => 'pegawai', 'is_active' => true]);

        $asetA = AssetTetap::create([
            'tanggal_input' => now()->toDateString(),
            'kode_barang' => '3100102001',
            'nup' => '10',
            'nama_barang' => 'Laptop Dell Latitude',
            'merek' => 'Dell',
            'kategori' => 'Peralatan IT',
            'tanggal_perolehan' => now()->subYear()->toDateString(),
            'nilai_perolehan' => 15000000,
            'jumlah' => 1,
            'lokasi' => 'Ruang TI',
            'kondisi' => 'baik',
            'status' => 'Dipinjam',
        ]);

        $asetB = AssetTetap::create([
            'tanggal_input' => now()->toDateString(),
            'kode_barang' => '3100102002',
            'nup' => '11',
            'nama_barang' => 'Webcam Logitech',
            'merek' => 'Logitech',
            'kategori' => 'Peralatan IT',
            'tanggal_perolehan' => now()->subYear()->toDateString(),
            'nilai_perolehan' => 1500000,
            'jumlah' => 1,
            'lokasi' => 'Ruang TI',
            'kondisi' => 'baik',
            'status' => 'Dipinjam',
        ]);

        $pinjamMulti = PeminjamanBarang::create([
            'user_id' => $pegawai->id,
            'nama_barang' => 'Laptop Dell Latitude (2 Barang)',
            'kode_barang' => '3100102001',
            'nup' => '10',
            'kategori' => 'Peralatan IT',
            'merek' => 'Dell',
            'jumlah' => 2,
            'deskripsi_peruntukan' => 'Zoom meeting kegiatan',
            'request_date' => now(),
            'tanggal_peminjaman' => now()->subDay()->toDateString(),
            'tanggal_pengembalian' => now()->toDateString(),
            'status' => 'proses_pengembalian',
        ]);

        DetailPeminjamanBarang::create([
            'peminjaman_barang_id' => $pinjamMulti->id,
            'aset_tetap_id' => $asetA->id,
            'kode_barang' => $asetA->kode_barang,
            'nup' => $asetA->nup,
            'nama_barang' => $asetA->nama_barang,
            'merek' => $asetA->merek,
            'kategori' => $asetA->kategori,
            'jumlah' => 1,
            'kondisi' => 'baik',
        ]);

        DetailPeminjamanBarang::create([
            'peminjaman_barang_id' => $pinjamMulti->id,
            'aset_tetap_id' => $asetB->id,
            'kode_barang' => $asetB->kode_barang,
            'nup' => $asetB->nup,
            'nama_barang' => $asetB->nama_barang,
            'merek' => $asetB->merek,
            'kategori' => $asetB->kategori,
            'jumlah' => 1,
            'kondisi' => 'baik',
        ]);

        $kembaliMulti = PengembalianBarang::create([
            'peminjaman_barang_id' => $pinjamMulti->id,
            'user_id' => $pegawai->id,
            'tanggal_pengembalian_aktual' => now(),
            'jumlah_dikembalikan' => 2,
            'kondisi_barang' => 'baik',
            'status_pengembalian' => 'lengkap',
            'status_verifikasi' => 'pending',
        ]);

        $res = $this->actingAs($admin)->post(route('adminasettetap.pengembalian-barang.verifikasi', $kembaliMulti->id), [
            'status_verifikasi' => 'diterima',
            'komentar_admin' => 'Semua unit kembali lengkap',
        ]);
        $res->assertRedirect();
        $res->assertSessionHas('success');

        $kembaliMulti->refresh();
        $this->assertSame('diterima', $kembaliMulti->status_verifikasi);
        $this->assertNotNull($kembaliMulti->verified_at);
        $this->assertSame('dikembalikan', $pinjamMulti->fresh()->status);
        $this->assertSame('Tersedia', $asetA->fresh()->status);
        $this->assertSame('Tersedia', $asetB->fresh()->status);

        // Single item without detail rows & rusak_berat restores Rusak / rusak berat properly
        $asetRusak = AssetTetap::create([
            'tanggal_input' => now()->toDateString(),
            'kode_barang' => '3100102099',
            'nup' => '99',
            'nama_barang' => 'Kamera DSLR Canon',
            'merek' => 'Canon',
            'kategori' => 'Dokumentasi',
            'tanggal_perolehan' => now()->subYear()->toDateString(),
            'nilai_perolehan' => 9000000,
            'jumlah' => 1,
            'lokasi' => 'Gudang BMN',
            'kondisi' => 'baik',
            'status' => 'Dipinjam',
        ]);

        $pinjamSingle = PeminjamanBarang::create([
            'user_id' => $pegawai->id,
            'nama_barang' => 'Kamera DSLR Canon',
            'kode_barang' => '3100102099',
            'nup' => '99',
            'kategori' => 'Dokumentasi',
            'merek' => 'Canon',
            'jumlah' => 1,
            'deskripsi_peruntukan' => 'Liputan lapangan',
            'request_date' => now(),
            'tanggal_peminjaman' => now()->subDay()->toDateString(),
            'tanggal_pengembalian' => now()->toDateString(),
            'status' => 'proses_pengembalian',
        ]);

        $kembaliRusak = PengembalianBarang::create([
            'peminjaman_barang_id' => $pinjamSingle->id,
            'user_id' => $pegawai->id,
            'tanggal_pengembalian_aktual' => now(),
            'jumlah_dikembalikan' => 1,
            'kondisi_barang' => 'rusak-berat',
            'status_pengembalian' => 'rusak_berat',
            'status_verifikasi' => 'pending',
        ]);

        $this->actingAs($admin)->post(route('adminasettetap.pengembalian-barang.verifikasi', $kembaliRusak->id), [
            'status_verifikasi' => 'diterima',
            'komentar_admin' => 'Lensa pecah',
        ])->assertRedirect();

        $this->assertSame('dikembalikan', $pinjamSingle->fresh()->status);
        $this->assertSame('Rusak', $asetRusak->fresh()->status);
        $this->assertSame('rusak berat', $asetRusak->fresh()->kondisi);

        // Filter & view badge check
        $adminBarangList = $this->actingAs($admin)->get('/adminasettetap/peminjaman-barang?status=Dikembalikan');
        $adminBarangList->assertOk();
        $adminBarangList->assertSee('Laptop Dell Latitude (2 Barang)');
        $adminBarangList->assertSee('Dikembalikan');
    }
}
