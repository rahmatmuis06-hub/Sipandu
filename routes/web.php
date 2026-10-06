<?php

use App\Http\Controllers\AdminSarprasController;
use App\Http\Controllers\AdminAsettetapController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\AjuanMutasiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KepalaBPMPController;
use App\Http\Controllers\KasubagController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UnitKerjaController;
use App\Http\Controllers\AdminPersediaanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\SuperadminController;
use Illuminate\Support\Facades\Route;


// ──────────────────────────────────────────────────────────────────────
// Halaman Publik
// ──────────────────────────────────────────────────────────────────────

// Route::get('/', function () {
//     return view('welcome');
// })->name('home');

Route::get('/', [AdminAsettetapController::class, 'index'])->name('home');
Route::get('/', [AdminAsettetapController::class, 'landingpage'])->name('home');
// ========== PENGADUAN dan SURVEY KEPUASAN PUBLIC FORM (TIDAK PERLU LOGIN) ==========
Route::post('/pengaduan/store', [AdminAsettetapController::class, 'pengaduanStore'])->name('pengaduan.store');
Route::post('/survey/store', [AdminAsettetapController::class, 'surveyStore'])->name('survey.store');

// ──────────────────────────────────────────────────────────────────────
// Autentikasi – hanya untuk tamu (belum login)
// ──────────────────────────────────────────────────────────────────────

Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');

Route::middleware('guest')->group(function () {

    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    // Registrasi
    Route::get('/daftar',  [RegisterController::class, 'showRegister'])->name('register');
    Route::post('/daftar', [RegisterController::class, 'register'])->name('register.post');
});

// Superadmin Register - PAKAI MIDDLEWARE ANDA!
Route::middleware('checkrole:superadmin')->group(function () {
    Route::get('/register', function () {
        return view('auth.register');
    })->name('register.show');
    Route::post('/register', [AuthController::class, 'register'])->name('register.admin.store');
});

Route::middleware('auth')->group(function () {

    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/signature', [AuthController::class, 'updateSignature'])->name('profile.signature');
    Route::post('/password/change', [AuthController::class, 'changePassword'])->name('password.change');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    // ──────────────────────────────────────────────────────────────────
    // SUPER ADMIN – akses penuh ke semua fitur
    // ──────────────────────────────────────────────────────────────────
    Route::prefix('superadmin')
        ->name('superadmin.')
        ->middleware('role:superadmin')
        ->group(function () {
            Route::get('/dashboard', [SuperadminController::class, 'dashboard'])->name('dashboard');
            Route::get('manajemen-user', [SuperadminController::class, 'manajemenUser'])->name('manajemen-user');
            Route::post('/pengguna', [SuperadminController::class, 'storePengguna'])->name('pengguna.store');
            Route::put('/pengguna/{user}', [SuperadminController::class, 'updatePengguna'])->name('pengguna.update');
            Route::delete('/pengguna/{user}', [SuperadminController::class, 'destroyPengguna'])->name('pengguna.destroy');
            Route::patch('/pengguna/{user}/toggle-status', [SuperadminController::class, 'toggleStatus'])->name('pengguna.toggle-status');
            Route::get('/unit-kerja', [UnitKerjaController::class, 'index'])->name('unit_kerja.index');
            Route::post('/unit-kerja', [UnitKerjaController::class, 'store'])->name('unit_kerja.store');
            Route::put('/unit-kerja/{id}', [UnitKerjaController::class, 'update'])->name('unit_kerja.update');
            Route::delete('/unit-kerja/{id}', [UnitKerjaController::class, 'destroy'])->name('unit_kerja.destroy');
        });

    // ──────────────────────────────────────────────────────────────────
    // KEPALA BPMP – lihat laporan & persetujuan
    // ──────────────────────────────────────────────────────────────────
    Route::prefix('kepalabpmp')
        ->name('kepalabpmp.')
        ->middleware('role:kepalabpmp,superadmin')
        ->group(function () {
            Route::get('/dashboard', [KepalaBPMPController::class, 'dashboard'])->name('dashboard');
            Route::get('/laporan', [KepalaBPMPController::class, 'laporan'])->name('laporan');
            Route::get('/pengaturan-akun', [AuthController::class, 'showProfile'])->name('pengaturan-akun');

            // 📥 DOWNLOAD LAPORAN PDF
            Route::get('/laporan/download-persediaan', [KepalaBPMPController::class, 'downloadLaporanPersediaan'])->name('laporan.download-persediaan');
            Route::get('/laporan/download-aset-tetap', [KepalaBPMPController::class, 'downloadLaporanAsetTetap'])->name('laporan.download-aset-tetap');
            Route::get('/laporan/download-sarpras', [KepalaBPMPController::class, 'downloadLaporanSarpras'])->name('laporan.download-sarpras');
            Route::get('/laporan/download-lengkap', [KepalaBPMPController::class, 'downloadLaporanLengkap'])->name('laporan.download-lengkap');
        });

    // ──────────────────────────────────────────────────────────────────
    // KASUBAG TU – pengelolaan administrasi
    // ──────────────────────────────────────────────────────────────────
    Route::prefix('kasubag')
        ->name('kasubag.')
        ->middleware('role:kasubag,superadmin')
        ->group(function () {
            Route::get('/dashboard', [KasubagController::class, 'dashboard'])->name('dashboard');
            Route::get('/pengaturan-akun', [KasubagController::class, 'pengaturanAkun'])->name('pengaturan-akun');

            //PEMINJAMAN BARANG
            Route::get('/persetujuan-peminjaman-barang', [KasubagController::class, 'persetujuanPeminjamanBarang'])->name('persetujuan-peminjaman-barang');
            Route::get('/peminjaman-barang', [KasubagController::class, 'PeminjamanBarang'])->name('peminjaman-barang');
            Route::post('/peminjaman-barang/{id}/approve', [KasubagController::class, 'approvePeminjamanBarang'])->name('peminjaman-barang.approve');
            Route::get('/peminjaman-barang/{id}/detail', [KasubagController::class, 'detailPeminjamanBarang'])->name('peminjaman-barang.detail');


            //PEMINJAMAN KENDARAAN
            Route::get('/persetujuan-peminjaman-kendaraan', [KasubagController::class, 'persetujuanPeminjamanKendaraan'])->name('persetujuan-peminjaman-kendaraan');
            Route::post('/peminjaman-kendaraan/{id}/approve', [App\Http\Controllers\KasubagController::class, 'approveKendaraan'])->name('peminjaman-kendaraan.approve');
            Route::get('/peminjaman-kendaraan/{id}/json', [App\Http\Controllers\KasubagController::class, 'showJsonKendaraan']);

            //PERMINTAAN PERSEDIAAN
            Route::get('/persetujuan-permintaan-persediaan', [KasubagController::class, 'persetujuanPermintaanPersediaan'])->name('persetujuan-permintaan-persediaan');
            Route::get('/permintaan-persediaan', [KasubagController::class, 'permintaanPersediaan'])->name('permintaan-persediaan');
            Route::post('/permintaan/{permintaan}/approve', [KasubagController::class, 'approvePermintaan'])->name('approve-permintaan');
            Route::get('/permintaan-persediaan/{id}', [KasubagController::class, 'showPermintaan'])->name('permintaan-persediaan.show');

            // PEMINJAMAN GEDUNG
            Route::get('/peminjaman-gedung/{peminjaman}', [KasubagController::class, 'show'])->name('peminjaman-gedung.show');
            Route::post('/peminjaman-gedung/{peminjaman}/approve', [KasubagController::class, 'approveByKasubag']);
            Route::post('/peminjaman-gedung/{peminjaman}/reject', [KasubagController::class, 'rejectByKasubag']);
            Route::get('/peminjaman-gedung/{peminjaman}/download', [KasubagController::class, 'downloadSurat']);
            Route::get('/persetujuan-peminjaman-gedung', [KasubagController::class, 'persetujuanPeminjamanGedung'])->name('persetujuan-peminjaman-gedung');
            Route::get('/peminjaman/{peminjaman}/surat/download', [KasubagController::class, 'downloadSurat'])->name('download-surat');
            Route::get('/kasubag/peminjaman-gedung/{peminjaman}', [KasubagController::class, 'show'])->name('kasubag.peminjaman-gedung.show');

            // MONITORING KASUBAG (Persediaan, Barang / Aset Tetap, Kendaraan)
            Route::get('/monitoring-persediaan', [KasubagController::class, 'monitoringPersediaan'])->name('monitoring-persediaan');
            Route::get('/monitoring-persediaan/{id}/json', [KasubagController::class, 'detailMonitoringPersediaanJson'])->name('monitoring-persediaan.json');
            Route::get('/monitoring-aset-tetap', [KasubagController::class, 'monitoringAsetTetap'])->name('monitoring-aset-tetap');
            Route::get('/monitoring-kendaraan', [KasubagController::class, 'monitoringKendaraan'])->name('monitoring-kendaraan');

            // MONITORING & LAPORAN TRANSAKSI KELUAR
            Route::get('/transaksi-keluar', [KasubagController::class, 'transaksiKeluar'])->name('transaksi-keluar');
            Route::get('/laporan-transaksi-keluar', [KasubagController::class, 'laporanTransaksiKeluar'])->name('laporan-transaksi-keluar');
            Route::get('/laporan-transaksi-keluar/pdf', [KasubagController::class, 'exportLaporanTransaksiKeluarPdf'])->name('laporan-transaksi-keluar.pdf');

            //PENGATURAN AKUN
            Route::get('/pengaturan-akun', [AuthController::class, 'showProfile'])->name('pengaturan-akun');
        });

    // ──────────────────────────────────────────────────────────────────
    // ADMIN PERSEDIAAN – kelola barang persediaan
    // ──────────────────────────────────────────────────────────────────
    Route::prefix('adminpersediaan')
        ->name('adminpersediaan.')
        ->middleware('role:adminpersediaan,kasubag,superadmin')
        ->group(function () {

            // Dashboard
            Route::get('/dashboard', [AdminPersediaanController::class, 'dashboard'])->name('dashboard');
            Route::get('/pengaturan-akun', [AuthController::class, 'showProfile'])->name('pengaturan-akun');

            // 📋 DATA PERSEDIAAN - Custom Routes (bukan resource)
            Route::get('/data-persediaan', [AdminPersediaanController::class, 'dataPersediaan'])->name('data-persediaan');
            Route::get('/data-persediaan/create', [AdminPersediaanController::class, 'create'])->name('data-persediaan.create');
            // Rute untuk mengunduh template Excel khusus data persediaan
            Route::get('/data-persediaan/template', [AdminPersediaanController::class, 'downloadTemplate'])->name('data-persediaan.template');
            // Rute untuk memproses file Excel persediaan yang diunggah oleh admin
            Route::post('/data-persediaan/import', [AdminPersediaanController::class, 'importPersediaan'])->name('data-persediaan.import');

            Route::post('/data-persediaan', [AdminPersediaanController::class, 'store'])->name('data-persediaan.store');
            Route::get('/data-persediaan/{persediaan}', [AdminPersediaanController::class, 'show'])->name('data-persediaan.show');
            Route::get('/data-persediaan/{persediaan}/edit', [AdminPersediaanController::class, 'edit'])->name('data-persediaan.edit');
            Route::put('/data-persediaan/{persediaan}', [AdminPersediaanController::class, 'update'])->name('data-persediaan.update');
            Route::delete('/data-persediaan/{persediaan}', [AdminPersediaanController::class, 'destroy'])->name('data-persediaan.destroy');



            // 📤 TRANSAKSI KELUAR
            Route::get('/stok-opname', [\App\Http\Controllers\StokOpnameController::class, 'index'])->name('opname.index');
            Route::post('/stok-opname', [\App\Http\Controllers\StokOpnameController::class, 'store'])->name('opname.store');
            Route::get('/stok-opname/{opname}', [\App\Http\Controllers\StokOpnameController::class, 'show'])->name('opname.show');
            Route::put('/stok-opname/{opname}', [\App\Http\Controllers\StokOpnameController::class, 'update'])->name('opname.update');
            Route::post('/stok-opname/{opname}/finalisasi', [\App\Http\Controllers\StokOpnameController::class, 'finalize'])->name('opname.finalize');
            Route::get('/stok-opname/{opname}/laporan', [\App\Http\Controllers\StokOpnameController::class, 'report'])->name('opname.report');
            Route::get('/transaksi-keluar', [AdminPersediaanController::class, 'transaksiKeluar'])->name('transaksi-keluar');
            Route::get('/transaksi-keluar/create', [AdminPersediaanController::class, 'createTransaksiKeluar'])->name('transaksi-keluar.create');
            Route::post('/transaksi-keluar', [AdminPersediaanController::class, 'storeTransaksiKeluar'])->name('transaksi-keluar.store');
            Route::get('/transaksi-keluar/{transaksiKeluar}', [AdminPersediaanController::class, 'showTransaksiKeluar'])->name('transaksi-keluar.show');
            Route::get('/transaksi-keluar/{transaksiKeluar}/edit', [AdminPersediaanController::class, 'editTransaksiKeluar'])->name('transaksi-keluar.edit');
            Route::put('/transaksi-keluar/{transaksiKeluar}', [AdminPersediaanController::class, 'updateTransaksiKeluar'])->name('transaksi-keluar.update');
            Route::delete('/transaksi-keluar/{transaksiKeluar}', [AdminPersediaanController::class, 'destroyTransaksiKeluar'])->name('transaksi-keluar.destroy');

            // TRANSAKSI MASUK
            Route::get('/transaksi-masuk', [AdminPersediaanController::class, 'TransaksiMasuk'])->name('transaksi-masuk');
            Route::get('/transaksi-masuk/create', [AdminPersediaanController::class, 'createTransaksiMasuk'])->name('transaksi-masuk.create');
            Route::post('/transaksi-masuk', [AdminPersediaanController::class, 'storeTransaksiMasuk'])->name('transaksi-masuk.store');
            Route::get('/transaksi-masuk/{transaksiMasuk}', [AdminPersediaanController::class, 'showTransaksiMasuk'])->name('transaksi-masuk.show');
            Route::get('/transaksi-masuk/{transaksiMasuk}/edit', [AdminPersediaanController::class, 'editTransaksiMasuk'])->name('transaksi-masuk.edit');
            Route::put('/transaksi-masuk/{transaksiMasuk}', [AdminPersediaanController::class, 'updateTransaksiMasuk'])->name('transaksi-masuk.update');
            Route::delete('/transaksi-masuk/{transaksiMasuk}', [AdminPersediaanController::class, 'destroyTransaksiMasuk'])->name('transaksi-masuk.destroy');

            //PERMINTAAN PERSEDIAAN
            Route::get('/permintaan-persediaan', [AdminPersediaanController::class, 'PermintaanPersediaan'])->name('permintaan-persediaan');
            Route::get('/permintaan/{id}', [AdminPersediaanController::class, 'showPermintaan'])->name('permintaan.show');

            Route::post('/permintaan/{permintaan}/review', [AdminPersediaanController::class, 'reviewPermintaan'])->name('review-permintaan');
            Route::get('/surat/{permintaan}', [AdminPersediaanController::class, 'generateSuratPermintaan'])->name('surat-permintaan');
            Route::post('/permintaan/{permintaan}/upload-bast', [AdminPersediaanController::class, 'uploadSuratBast'])->name('upload-bast');

            //LAPORAN
            Route::get('/laporan-permintaan-persediaan', [AdminPersediaanController::class, 'laporanPermintaanPersediaan'])->name('laporan-permintaan-persediaan');
            Route::get('/laporan-transaksi-masuk', [AdminPersediaanController::class, 'laporanTransaksiMasuk'])->name('laporan-transaksi-masuk');
            Route::get('/laporan-transaksi-masuk/pdf', [AdminPersediaanController::class, 'downloadLaporanTransaksiMasuk'])->name('laporan-transaksi-masuk.pdf');
            Route::get('/laporan-transaksi-keluar/pdf', [AdminPersediaanController::class, 'downloadLaporanTransaksiKeluarPdf'])->name('laporan-transaksi-keluar.pdf');
            Route::get('/laporan-permintaan/download', [AdminPersediaanController::class, 'downloadLaporanPermintaan'])->name('laporan.download');
            Route::get('/laporan-transaksi-keluar', [AdminPersediaanController::class, 'laporanTransaksiKeluar'])->name('laporan-transaksi-keluar');
        });

    // ──────────────────────────────────────────────────────────────────
    // ADMIN SARPRAS – kelola sarana dan prasarana
    // ──────────────────────────────────────────────────────────────────
    Route::prefix('adminsarpras')
        ->name('adminsarpras.')
        ->middleware('role:adminsarpras,kasubag,superadmin')
        ->group(function () {
            Route::get('/dashboard', [AdminSarprasController::class, 'dashboard'])->name('dashboard');
            Route::get('/data-gedung', [AdminSarprasController::class, 'dataGedung'])->name('data-gedung');
            Route::get('/data-kerusakan', [AdminSarprasController::class, 'dataKerusakan'])->name('data-kerusakan');
            Route::post('/simpan-kerusakan', [AdminSarprasController::class, 'storeKerusakan'])->name('simpan-kerusakan');
            Route::get('/daftar-peminjaman', [AdminSarprasController::class, 'daftarPeminjaman'])->name('daftar-peminjaman');
            Route::put('/peminjaman/{id}/update', [AdminSarprasController::class, 'updatePeminjaman'])->name('peminjaman.update');
            Route::get('/daftar-pengembalian', [AdminSarprasController::class, 'daftarPengembalian'])->name('daftar-pengembalian');
            Route::get('/laporan-peminjaman-gedung', [AdminSarprasController::class, 'laporanPeminjamanGedung'])->name('laporan-peminjaman-gedung');
            Route::get('/laporan-kerusakan', [AdminSarprasController::class, 'laporanKerusakan'])->name('laporan-kerusakan');
            Route::get('/peminjaman/{peminjaman}/surat/download', [AdminSarprasController::class, 'downloadSurat'])->name('download-surat');
            Route::get('/pengaturan-akun', [AuthController::class, 'showProfile'])->name('pengaturan-akun');
            Route::get('/laporan-peminjaman/download', [AdminSarprasController::class, 'downloadLaporanPeminjaman'])->name('laporan.peminjaman.download');
            Route::get('/laporan-kerusakan/download', [AdminSarprasController::class, 'downloadLaporanKerusakan'])->name('laporan.kerusakan.download');
            Route::get('/integrasi-ult', [AdminSarprasController::class, 'integrasiUlt'])->name('integrasi-ult');

            //DATA GEDUNG
            Route::get('/fasilitas-beranda', [\App\Http\Controllers\FasilitasBerandaController::class, 'index'])->name('fasilitas-beranda.index');
            Route::get('/fasilitas-beranda/{fasilitas}/edit', [\App\Http\Controllers\FasilitasBerandaController::class, 'edit'])->name('fasilitas-beranda.edit');
            Route::put('/fasilitas-beranda/{fasilitas}', [\App\Http\Controllers\FasilitasBerandaController::class, 'update'])->name('fasilitas-beranda.update');
            Route::post('/data-gedung', [AdminSarprasController::class, 'storeGedung'])->name('data-gedung.store');
            Route::post('/data-gedung/sync-fasilitas', [AdminSarprasController::class, 'syncGedungFromFasilitas'])->name('data-gedung.sync-fasilitas');
            Route::get('/gedung/{gedung}', [AdminSarprasController::class, 'showGedungJson'])->name('gedung.show');
            Route::put('/gedung/{gedung}', [AdminSarprasController::class, 'updateGedung'])->name('gedung.update');
            Route::delete('/gedung/{gedung}', [AdminSarprasController::class, 'destroyGedung'])->name('gedung.destroy');
            Route::get('data-gedung/{gedung}', [AdminSarprasController::class, 'showGedungJson'])->name('data-gedung.show');


            // Modal AJAX Routes (BARU)
            Route::get('/data-kerusakan-pilih-barang', [AdminSarprasController::class, 'getBarangPilihan'])->name('kerusakan.pilih-barang');
            Route::get('/data-kerusakan/{kerusakan}/edit', [AdminSarprasController::class, 'editKerusakanJson'])->name('kerusakan.edit.json');
            Route::get('/data-kerusakan/{kerusakan}', [AdminSarprasController::class, 'showKerusakanJson'])->name('kerusakan.show.json');
            Route::post('/data-kerusakan', [AdminSarprasController::class, 'storeKerusakan'])->name('kerusakan.store');
            Route::post('/data-kerusakan/{kerusakan}/update', [AdminSarprasController::class, 'updateKerusakanAjax'])->name('kerusakan.update.ajax');
            Route::post('/data-kerusakan/{kerusakan}/perbaikan', [AdminSarprasController::class, 'storePerbaikanKerusakan'])->name('kerusakan.perbaikan.store');
            Route::get('/data-kerusakan/{kerusakan}/riwayat', [AdminSarprasController::class, 'riwayatKerusakan'])->name('kerusakan.riwayat');
            Route::delete('/data-kerusakan/{kerusakan}', [AdminSarprasController::class, 'destroyKerusakan'])->name('kerusakan.destroy');

            //ROUTE PERSETUJUAN PEMINJAMAN
            Route::post('/peminjaman/{peminjaman}/forward', [AdminSarprasController::class, 'forwardToKasubag'])->name('peminjaman.forward');
            Route::post('/peminjaman/{peminjaman}/reject', [AdminSarprasController::class, 'rejectByAdmin'])->name('peminjaman.reject');
            Route::get('/peminjaman-gedung/download', [AdminSarprasController::class, 'downloadLaporanPeminjaman'])->name('peminjaman.download');
            Route::get('/peminjaman-gedung/{peminjaman}/generate-surat', [AdminSarprasController::class, 'generateSuratPerjanjianSewa'])
                ->name('peminjaman.generate-surat');
            Route::post('/peminjaman-gedung/{peminjaman}/upload-surat', [AdminSarprasController::class, 'uploadSuratPerjanjianSewa'])
                ->name('peminjaman.upload-surat');


            // Route untuk Bulk Delete
            Route::post('/peminjaman-gedung/bulk-delete', [AdminSarprasController::class, 'bulkDeletePeminjaman'])->name('peminjaman.bulk-delete');

            // Route untuk Review (Form Button)
            Route::post('/peminjaman-gedung/{id}/review', [AdminSarprasController::class, 'reviewPeminjaman'])->name('review-peminjaman');
        });


    // ──────────────────────────────────────────────────────────────────
    // ADMIN ASET TETAP – kelola aset tetap / BMN
    // ──────────────────────────────────────────────────────────────────
    Route::prefix('adminasettetap')
        ->name('adminasettetap.')
        ->middleware('role:adminasettetap,kasubag,superadmin')
        ->group(function () {
            Route::get('/dashboard', [AdminAsettetapController::class, 'dashboard'])->name('dashboard');
            Route::get('/data-aset-tetap', [AdminAsettetapController::class, 'dataAsetTetap'])->name('data-aset-tetap');
            Route::get('/transaksi-masuk', [AdminAsettetapController::class, 'TransaksiMasuk'])->name('transaksi-masuk');
            Route::get('/transaksi-keluar', [AdminAsettetapController::class, 'TransaksiKeluar'])->name('transaksi-keluar');
            Route::get('/mutasi-barang', [AdminAsettetapController::class, 'mutasiBarang'])->name('mutasi-barang');
            Route::get('/pengaduan', [AdminAsettetapController::class, 'pengaduan'])->name('pengaduan');
            Route::get('/survey-kepuasan', [AdminAsettetapController::class, 'surveyKepuasan'])->name('survey-kepuasan');
            Route::get('/pengaturan-akun', [AuthController::class, 'showProfile'])->name('pengaturan-akun');

            // Rute untuk mengunduh template Excel format standar sistem
            Route::get('/data-aset-tetap/template', [AdminAsettetapController::class, 'downloadTemplate'])->name('data-aset-tetap.template');

            // Rute untuk memproses file Excel yang diunggah oleh admin
            Route::post('/data-aset-tetap/import', [AdminAsettetapController::class, 'importAset'])->name('data-aset-tetap.import');
            Route::post('/data-aset-tetap/catat-kerusakan', [AdminAsettetapController::class, 'storeKerusakanAset'])->name('data-aset-tetap.catat-kerusakan');

            // Route untuk fitur Info Ajuan Mutasi
            Route::get('info-ajuan', [AdminAsettetapController::class, 'infoAjuanIndex'])->name('info-ajuan.index');
            Route::get('info-ajuan/{id}', [AdminAsettetapController::class, 'infoAjuanShow'])->name('info-ajuan.show');
            Route::delete('info-ajuan/{id}', [AdminAsettetapController::class, 'infoAjuanDestroy'])->name('info-ajuan.destroy');

            //PENGEMBALIAN BARANG
            Route::get('/pengembalian-barang', [AdminAsettetapController::class, 'PengembalianBarang'])->name('pengembalian-barang');
            Route::post('/pengembalian-barang/{id}/verifikasi', [AdminAsettetapController::class, 'verifikasiPengembalianBarang'])->name('pengembalian-barang.verifikasi');
            Route::get('/pengembalian-barang/{id}/json', [AdminAsettetapController::class, 'showPengembalianJsonAdmin']);
            Route::get('/pengembalian-barang/{id}/cetak', [AdminAsettetapController::class, 'cetakSuratPengembalianBarang'])->name('pengembalian-barang.cetak');


            //PEMINJAMAN KENDARAAN
            Route::get('/peminjaman-kendaraan', [AdminAsettetapController::class, 'PeminjamanKendaraan'])->name('peminjaman-kendaraan');
            Route::post('/peminjaman-kendaraan/{id}/review', [AdminAsettetapController::class, 'reviewPeminjamanKendaraan']);
            Route::post('/peminjaman-kendaraan/{id}/upload', [AdminAsettetapController::class, 'uploadBastKendaraan']);
            Route::get('/peminjaman-kendaraan/{peminjaman}/print', [AdminAsettetapController::class, 'generateSuratPeminjamanKendaraan'])->name('peminjaman-kendaraan.print');
            Route::get('/peminjaman-kendaraan/{id}/json', [AdminAsettetapController::class, 'showJsonKendaraan']);

            //PENGAMBLIAN KENDARAAN
            Route::get('/pengembalian-kendaraan', [AdminAsettetapController::class, 'PengembalianKendaraan'])->name('pengembalian-kendaraan');
            Route::post('/pengembalian-kendaraan/{id}/verifikasi', [AdminAsettetapController::class, 'verifikasiPengembalianKendaraan'])->name('pengembalian-kendaraan.verifikasi');
            Route::get('/pengembalian-kendaraan/{id}/json', [AdminAsettetapController::class, 'showPengembalianKendaraanJsonAdmin']);
            Route::get('/pengembalian-kendaraan/{id}/cetak', [AdminAsettetapController::class, 'cetakSuratPengembalianKendaraan'])->name('pengembalian-kendaraan.cetak');

            //PEMINJAMAN BARANG
            Route::get('/peminjaman-barang', [AdminAsettetapController::class, 'PeminjamanBarang'])->name('peminjaman-barang');
            Route::post('/peminjaman-barang/{id}/review', [AdminAsettetapController::class, 'reviewPeminjaman'])->name('peminjaman-barang.review');
            Route::post('/peminjaman-barang/{id}/upload-bast', [AdminAsettetapController::class, 'uploadSuratBast'])->name('peminjaman-barang.upload-bast');
            Route::get('/peminjaman-barang/{peminjaman}/generate-surat', [AdminAsettetapController::class, 'generateSuratPeminjaman'])->name('peminjaman-barang.print');

            // ========== CRUD DATA ASET TETAP ==========
            Route::get('/data-aset-tetap/create', [AdminAsettetapController::class, 'createDataAsetTetap'])->name('data-aset-tetap.create');
            Route::post('/data-aset-tetap', [AdminAsettetapController::class, 'storeDataAsetTetap'])->name('data-aset-tetap.store');
            Route::get('/data-aset-tetap/{aset}', [AdminAsettetapController::class, 'showDataAsetTetap'])->name('data-aset-tetap.show');
            Route::get('/data-aset-tetap/{aset}/edit', [AdminAsettetapController::class, 'editDataAsetTetap'])->name('data-aset-tetap.edit');
            Route::put('/data-aset-tetap/{aset}', [AdminAsettetapController::class, 'updateDataAsetTetap'])->name('data-aset-tetap.update');
            Route::delete('/data-aset-tetap/{aset}', [AdminAsettetapController::class, 'destroyDataAsetTetap'])->name('data-aset-tetap.destroy');

            // ========== CRUD TRANSAKSI MASUK ASET TETAP ==========
            Route::get('/transaksi-masuk/create', [AdminAsettetapController::class, 'createTransaksiMasuk'])->name('transaksi-masuk.create');
            Route::post('/transaksi-masuk', [AdminAsettetapController::class, 'storeTransaksiMasuk'])->name('transaksi-masuk.store');
            Route::get('/transaksi-masuk/{transaksi}', [AdminAsettetapController::class, 'showTransaksiMasuk'])->name('transaksi-masuk.show');
            Route::get('/transaksi-masuk/{transaksi}/edit', [AdminAsettetapController::class, 'editTransaksiMasuk'])->name('transaksi-masuk.edit');
            Route::put('/transaksi-masuk/{transaksi}', [AdminAsettetapController::class, 'updateTransaksiMasuk'])->name('transaksi-masuk.update');
            Route::delete('/transaksi-masuk/{transaksi}', [AdminAsettetapController::class, 'destroyTransaksiMasuk'])->name('transaksi-masuk.destroy');
            
            
            // ========== CRUD TRANSAKSI KELUAR ASET TETAP ==========
            Route::get('/transaksi-keluar/create', [AdminAsettetapController::class, 'createTransaksiKeluar'])->name('transaksi-keluar.create');
            Route::post('/transaksi-keluar', [AdminAsettetapController::class, 'storeTransaksiKeluar'])->name('transaksi-keluar.store');
            Route::put('/transaksi-keluar/{transaksi}', [AdminAsettetapController::class, 'updateTransaksiKeluar'])->name('transaksi-keluar.update');
            Route::delete('/transaksi-keluar/{transaksi}', [AdminAsettetapController::class, 'destroyTransaksiKeluar'])->name('transaksi-keluar.destroy');

            // ========== AJAX & JSON ENDPOINTS ==========
            // Route untuk mendapatkan data aset (Auto-Fill)
            Route::get('/aset-tetap/{id}', [AdminAsettetapController::class, 'getAsetData'])->name('aset-data');
            // Route untuk mengambil detail transaksi (Modal Detail)
            Route::get('/transaksi-keluar/{id}/show-aset', [AdminAsettetapController::class, 'showWithAset'])->name('transaksi-keluar.show-aset');
            // Route untuk mengambil data transaksi lama (Modal Edit)
            Route::get('/transaksi-keluar/{id}/edit-json', [AdminAsettetapController::class, 'editJson'])->name('transaksi-keluar.edit-json');

            //CRUD MUTASI BARANG
            // Mutasi Barang Routes
            Route::post('/mutasi-barang', [AdminAsettetapController::class, 'mutasiBarangStore'])->name('mutasi-barang.store');
            Route::put('/mutasi-barang/{id}', [AdminAsettetapController::class, 'mutasiBarangUpdate'])->name('mutasi-barang.update');
            Route::delete('/mutasi-barang/{id}', [AdminAsettetapController::class, 'mutasiBarangDestroy'])->name('mutasi-barang.destroy');
            Route::get('/mutasi-barang/{id}', [AdminAsettetapController::class, 'mutasiBarangShow']);
            Route::get('/mutasi-barang/{id}/edit', [AdminAsettetapController::class, 'mutasiBarangEdit']);
            Route::get('/mutasi-barang/aset/{id}', [AdminAsettetapController::class, 'getAsetTetapData'])->name('mutasi-barang.aset');

            //CRUD PENGADUAN
            Route::get('/pengaduan/{pengaduan}', [AdminAsettetapController::class, 'pengaduanShow'])->name('pengaduan.show');
            Route::put('/pengaduan/{pengaduan}', [AdminAsettetapController::class, 'pengaduanUpdate'])->name('pengaduanUpdate');
            Route::delete('/pengaduan/{pengaduan}', [AdminAsettetapController::class, 'pengaduanDestroy'])->name('pengaduanDestroy');

            //DELET SURVEY KEPUASAN
            Route::delete('/survey/{survey}', [AdminAsettetapController::class, 'surveyDestroy'])->name('survey.destroy');
            Route::get('/survey-kepuasan/export-excel', [AdminAsettetapController::class, 'exportExcel'])->name('survey.excel-export');

            // 📊 LAPORAN & ANALITIK (BARU ⭐)
            Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
            Route::get('/laporan/filter', [LaporanController::class, 'filter'])->name('laporan.filter');
            Route::get('/laporan-transaksi-keluar', [AdminAsettetapController::class, 'laporanTransaksiKeluar'])->name('laporan-transaksi-keluar');

            // 📥 DOWNLOAD ROUTES (LENGKAP)
            Route::get('/transaksi-masuk/download', [LaporanController::class, 'downloadTransaksiMasuk'])->name('transaksi-masuk.download');
            Route::get('/transaksi-keluar/download', [LaporanController::class, 'downloadTransaksiKeluar'])->name('transaksi-keluar.download');
            Route::get('/pengaduan/download', [LaporanController::class, 'downloadPengaduan'])->name('pengaduan.download');
            Route::get('/survey/download', [LaporanController::class, 'downloadSurvey'])->name('survey.download');
            Route::get('/peminjaman/download', [LaporanController::class, 'downloadPeminjaman'])->name('peminjaman.download');
            Route::get('/dashboard-summary/download', [LaporanController::class, 'downloadDashboardSummary'])->name('dashboard.download');
            Route::get('/all/download', [LaporanController::class, 'downloadAll'])->name('all.download');

            Route::get('/pengembalian/download', [LaporanController::class, 'downloadPengembalian'])->name('pengembalian.download');
            Route::get('/mutasi/download', [LaporanController::class, 'downloadMutasi'])->name('mutasi.download');
        });


    // ──────────────────────────────────────────────────────────────────


    // ──────────────────────────────────────────────────────────────────
    // PEGAWAI – akses terbatas (pengajuan peminjaman, dll.)
    // ──────────────────────────────────────────────────────────────────
    Route::prefix('pegawai')
        ->name('pegawai.')
        ->middleware('role:pegawai,superadmin')
        ->group(function () {
            Route::get('/dashboard', [PegawaiController::class, 'dashboard'])->name('dashboard');

            // 1. Route API autofill Aset Tetap
            Route::get('ajuan-mutasi/aset/{id}', [AjuanMutasiController::class, 'getAsetTetapData'])->name('ajuan-mutasi.aset');

            // 2. Route Resource CRUD
            Route::resource('ajuan-mutasi', AjuanMutasiController::class)->except(['create', 'show']);

            // 3. Route pop-up detail
            Route::get('ajuan-mutasi/{id}', [AjuanMutasiController::class, 'show'])->name('ajuan-mutasi.show');

            //PEMINJAMAN BARANG
            Route::get('/peminjaman-barang', [PegawaiController::class, 'peminjamanBarang'])->name('peminjaman-barang');
            Route::post('/peminjaman-barang', [PegawaiController::class, 'storePeminjamanBarang'])->name('peminjaman-barang.store');
            Route::get('/peminjaman-barang/{id}/detail', [PegawaiController::class, 'detailPeminjaman'])->name('peminjaman-barang.detail');
            Route::delete('/peminjaman-barang/{id}/cancel', [PegawaiController::class, 'cancelPeminjaman'])->name('peminjaman-barang.cancel');


            //PENGEMBALIAN BARANG
            Route::get('/pengembalian-barang', [PegawaiController::class, 'pengembalianBarang'])->name('pengembalian-barang');
            Route::post('/pengembalian-barang', [PegawaiController::class, 'storePengembalianBarang'])->name('pengembalian-barang.store');
            Route::get('/pengembalian-barang/{id}/json', [PegawaiController::class, 'showPengembalianJson']);
            Route::get('/peminjaman-barang/{id}/json', [PegawaiController::class, 'getPeminjamanJson']); // Untuk auto-fill preview
            Route::delete('/pengembalian-barang/{id}', [PegawaiController::class, 'cancelPengembalian'])->name('pengembalian-barang.cancel');

            //PEMINJAMAN KENDARAAN
            Route::get('/peminjaman-kendaraan', [PegawaiController::class, 'peminjamanKendaraan'])->name('peminjaman-kendaraan');
            Route::post('/peminjaman-kendaraan/store', [PegawaiController::class, 'storePeminjamanKendaraan'])->name('peminjaman-kendaraan.store');
            Route::delete('/peminjaman-kendaraan/{id}/cancel', [PegawaiController::class, 'cancelPeminjamanKendaraan']);
            Route::get('/peminjaman-kendaraan/{id}/show', [PegawaiController::class, 'showPeminjamanKendaraan'])->name('peminjaman-kendaraan.show');


            //PENGEMBALIAN KENDARAAN
            Route::get('/pengembalian-kendaraan', [PegawaiController::class, 'pengembalianKendaraan'])->name('pengembalian-kendaraan');
            Route::post('/pengembalian-kendaraan', [PegawaiController::class, 'storePengembalianKendaraan'])->name('pengembalian-kendaraan.store');
            Route::delete('/pengembalian-kendaraan/{id}', [PegawaiController::class, 'cancelPengembalianKendaraan'])->name('pengembalian-kendaraan.cancel');
            Route::get('/pengembalian-kendaraan/{id}/json', [PegawaiController::class, 'showPengembalianKendaraanJson']);
            Route::get('/peminjaman-kendaraan/{id}/json', [PegawaiController::class, 'getPeminjamanKendaraanJson']);

            //PERMINTAAN PERSEDIAAN
            Route::get('/permintaan-persediaan', [PegawaiController::class, 'permintaanPersediaan'])->name('permintaan-persediaan');
            Route::post('/permintaan-persediaan', [PegawaiController::class, 'storePermintaanPersediaan'])->name('permintaan-persediaan.store');
            Route::get('/permintaan-persediaan/{id}', [PegawaiController::class, 'detailPermintaanPersediaan'])->name('permintaan_persediaan.detail');
            Route::post('/permintaan-persediaan/{id}/cancel', [PegawaiController::class, 'cancelPermintaanPersediaan'])->name('permintaan_persediaan.cancel');
            Route::get('/permintaan-persediaan/{id}/json', [PegawaiController::class, 'showPermintaanPersediaanJson']);

        //PENGATURAN AKUN
        Route::get('/pengaturan-akun', [AuthController::class, 'showProfile'])->name('pengaturan-akun');
    });

    // ──────────────────────────────────────────────────────────────────
    // ENDPOINT API INTEGRASI SISTEM ULT (UNIT LAYANAN TERPADU)
    // ──────────────────────────────────────────────────────────────────
    Route::prefix('api/ult')->group(function () {
        Route::get('/ketersediaan-gedung', [\App\Http\Controllers\Api\UltIntegrationController::class, 'ketersediaanGedung']);
        Route::get('/katalog-layanan', [\App\Http\Controllers\Api\UltIntegrationController::class, 'katalogLayanan']);
        Route::post('/permohonan-sarpras', [\App\Http\Controllers\Api\UltIntegrationController::class, 'storePermohonanSarpras']);
        Route::get('/tracking-layanan/{kode_tiket}', [\App\Http\Controllers\Api\UltIntegrationController::class, 'trackingLayanan']);
        Route::get('/ringkasan-layanan', [\App\Http\Controllers\Api\UltIntegrationController::class, 'ringkasanLayanan']);
    });
});
