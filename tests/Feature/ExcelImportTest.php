<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;

class ExcelImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_inventory_excel_template_can_be_imported(): void
    {
        $this->requireSpreadsheetExtensions();
        $admin = User::factory()->create(['role' => 'adminpersediaan', 'is_active' => true]);
        $path = $this->makeWorkbook([
            ['kode_unik_barang', 'kategori', 'nama_barang', 'tanggal_masuk', 'harga_satuan', 'jumlah', 'satuan'],
            ['ATK-ATK-TEST-001', 'Alat Tulis Kantor', 'Kertas Uji', '2026-09-02', 50000, 10, 'rim'],
        ]);

        try {
            $response = $this->actingAs($admin)->post('/adminpersediaan/data-persediaan/import', [
                'file_excel' => new UploadedFile($path, 'persediaan.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true),
            ]);

            $response->assertRedirect(route('adminpersediaan.data-persediaan'));
            $response->assertSessionHasNoErrors();
            $this->assertDatabaseHas('persediaan', [
                'kode_barang' => 'ATK-TEST-001',
                'kode_unik_barang' => 'ATK-ATK-TEST-001',
                'nama_barang' => 'Kertas Uji',
                'jumlah' => 10,
            ]);
        } finally {
            @unlink($path);
        }
    }

    public function test_fixed_asset_excel_template_can_be_imported(): void
    {
        $this->requireSpreadsheetExtensions();
        $admin = User::factory()->create(['role' => 'adminasettetap', 'is_active' => true]);
        $path = $this->makeWorkbook([
            ['tanggal_input', 'kode_barang', 'nup', 'nama_barang', 'merek', 'kategori', 'tanggal_perolehan', 'nilai_perolehan', 'kondisi', 'lokasi', 'jumlah', 'status'],
            ['2026-09-02', 'AST-TEST-001', '001', 'Laptop Uji Excel', 'Lenovo', 'Elektronik', '2026-01-01', 15000000, 'baik', 'Ruang IT', 1, 'Tersedia'],
        ]);

        try {
            $response = $this->actingAs($admin)->post('/adminasettetap/data-aset-tetap/import', [
                'file_excel' => new UploadedFile($path, 'aset-tetap.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true),
            ]);

            $response->assertRedirect(route('adminasettetap.data-aset-tetap'));
            $response->assertSessionHasNoErrors();
            $this->assertDatabaseHas('aset_tetap', [
                'kode_barang' => 'AST-TEST-001',
                'nama_barang' => 'Laptop Uji Excel',
                'lokasi' => 'Ruang IT',
            ]);
        } finally {
            @unlink($path);
        }
    }

    public function test_inventory_excel_flexible_import(): void
    {
        $this->requireSpreadsheetExtensions();
        $admin = User::factory()->create(['role' => 'adminpersediaan', 'is_active' => true]);
        // Menggunakan variasi nama kolom non-standar dan kode tanpa strip
        $path = $this->makeWorkbook([
            ['nama', 'kode', 'harga', 'qty', 'unit', 'jenis'],
            ['Spidol Whiteboard Snowman', '12345', 'Rp 15.000', 5, 'buah', 'ATK'],
        ]);

        try {
            $response = $this->actingAs($admin)->post('/adminpersediaan/data-persediaan/import', [
                'file_excel' => new UploadedFile($path, 'persediaan_fleksibel.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true),
            ]);

            $response->assertRedirect(route('adminpersediaan.data-persediaan'));
            $response->assertSessionHasNoErrors();
            $this->assertDatabaseHas('persediaan', [
                'nama_barang' => 'Spidol Whiteboard Snowman',
                'jumlah' => 5,
                'harga_satuan' => 15000,
            ]);
        } finally {
            @unlink($path);
        }
    }

    public function test_user_persediaan_import_from_screenshot(): void
    {
        $this->requireSpreadsheetExtensions();
        $admin = User::factory()->create(['role' => 'adminpersediaan', 'is_active' => true]);
        $path = $this->makeWorkbook([
            ['kode_unik_barang', 'kategori', 'nama_barang', 'tanggal_masuk', 'harga_satuan', 'jumlah', 'satuan'],
            ['1010301001000001', 'Alat Tulis', 'SPIDOL WHITEBOARD', '2026-03-09', 16500, 4, 'buah'],
            ['1010301001000002', 'Alat Tulis', 'SPIDOL PERMANENT HITAM', '2026-03-09', 8250, 12, 'buah'],
            ['1010301013000001', 'Isi Staples', 'ISI HEKTER NO.10 KECIL', '2026-03-09', 2860, 5, 'dos'],
            ['1010301013000001', 'Isi Staples', 'ISI HEKTER NO.10 KECIL', '2026-03-09', 1540, 12, 'dos'],
        ]);

        try {
            $response = $this->actingAs($admin)->post('/adminpersediaan/data-persediaan/import', [
                'file_excel' => new UploadedFile($path, 'persediaan_user.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true),
            ]);

            $response->assertRedirect(route('adminpersediaan.data-persediaan'));
            $response->assertSessionHasNoErrors();
            $this->assertDatabaseHas('persediaan', [
                'nama_barang' => 'SPIDOL WHITEBOARD',
            ]);
        } finally {
            @unlink($path);
        }
    }

    public function test_fixed_asset_excel_flexible_import(): void
    {
        $this->requireSpreadsheetExtensions();
        $admin = User::factory()->create(['role' => 'adminasettetap', 'is_active' => true]);
        // Variasi kolom non-standar: uraian, ruangan, keadaan, plat
        $path = $this->makeWorkbook([
            ['uraian', 'ruangan', 'keadaan', 'jenis', 'plat'],
            ['Toyota Avanza Veloz', 'Garasi Kantor', 'Baik', 'Kendaraan', 'DM 1234 AA'],
        ]);

        try {
            $response = $this->actingAs($admin)->post('/adminasettetap/data-aset-tetap/import', [
                'file_excel' => new UploadedFile($path, 'aset_fleksibel.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true),
            ]);

            $response->assertRedirect(route('adminasettetap.data-aset-tetap'));
            $response->assertSessionHasNoErrors();
            $this->assertDatabaseHas('aset_tetap', [
                'nama_barang' => 'Toyota Avanza Veloz',
                'lokasi' => 'Garasi Kantor',
                'kategori' => 'Kendaraan',
            ]);
            $this->assertDatabaseHas('detail_kendaraan', [
                'nomor_polisi' => 'DM 1234 AA',
            ]);
        } finally {
            @unlink($path);
        }
    }

    private function makeWorkbook(array $rows): string
    {
        $path = sys_get_temp_dir().DIRECTORY_SEPARATOR.'sipandu-import-'.Str::uuid().'.xlsx';
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet()->fromArray($rows);
        (new Xlsx($spreadsheet))->save($path);
        $spreadsheet->disconnectWorksheets();

        return $path;
    }

    private function requireSpreadsheetExtensions(): void
    {
        if (! extension_loaded('zip') || ! extension_loaded('gd')) {
            $this->markTestSkipped('Ekstensi PHP zip dan gd diperlukan untuk impor Excel.');
        }
    }
}
