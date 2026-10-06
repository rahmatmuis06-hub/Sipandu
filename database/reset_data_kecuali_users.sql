-- ====================================================================
-- SIPANDU - SCRIPT RESET DATA MASTER & TRANSAKSI
-- PERHATIAN: 
-- 1. Script ini MENGHAPUS SEMUA DATA transaksi, peminjaman, permohonan,
--    dan master data (Persediaan, Aset Tetap, Gedung).
-- 2. Tabel `users` (akun login Superadmin, Kasubag, Admin, Pegawai, dll)
--    TETAP AMAN & TIDAK DIHAPUS sama sekali.
-- 3. AUTO_INCREMENT id akan di-reset kembali mulai dari 1.
-- ====================================================================

-- 1. Matikan pengecekan Foreign Key agar tidak ada error constraint
SET FOREIGN_KEY_CHECKS = 0;

-- ====================================================================
-- KELOMPOK A: TRANSAKSI & RIWAYAT PERSEDIAAN
-- ====================================================================
TRUNCATE TABLE `detail_permintaan_persediaan`;
TRUNCATE TABLE `permintaan_persediaan`;
TRUNCATE TABLE `transaksi_masuk_persediaan`;
TRUNCATE TABLE `transaksi_keluar_persediaan`;
TRUNCATE TABLE `stok_opname_detail`;
TRUNCATE TABLE `stok_opname`;

-- ====================================================================
-- KELOMPOK B: TRANSAKSI & RIWAYAT ASET TETAP & KENDARAAN
-- ====================================================================
TRUNCATE TABLE `detail_peminjaman_barang`;
TRUNCATE TABLE `pengembalian_barang`;
TRUNCATE TABLE `peminjaman_barang`;
TRUNCATE TABLE `pengembalian_kendaraan`;
TRUNCATE TABLE `peminjaman_kendaraan`;
TRUNCATE TABLE `detail_kendaraan`;
TRUNCATE TABLE `transaksi_masuk_aset_tetap`;
TRUNCATE TABLE `transaksi_keluar_aset_tetap`;
TRUNCATE TABLE `mutasi_barang`;
TRUNCATE TABLE `ajuan_mutasi`;
TRUNCATE TABLE `riwayat_kerusakan`;
TRUNCATE TABLE `perbaikan_kerusakan`;
TRUNCATE TABLE `kerusakan`;

-- ====================================================================
-- KELOMPOK C: TRANSAKSI LAYANAN GEDUNG
-- ====================================================================
TRUNCATE TABLE `peminjaman_gedung`;

-- ====================================================================
-- KELOMPOK D: MASTER DATA UTAMA
-- ====================================================================
TRUNCATE TABLE `persediaan`;
TRUNCATE TABLE `aset_tetap`;
TRUNCATE TABLE `gedung`;

-- ====================================================================
-- KELOMPOK E (OPSIONAL):
-- Hapus tanda komentar (--) di bawah ini JIKA Anda juga ingin 
-- mengosongkan data Fasilitas Beranda / Unit Kerja:
-- ====================================================================
-- TRUNCATE TABLE `fasilitas_beranda`;
-- TRUNCATE TABLE `unit_kerjas`;

-- 2. Hidupkan kembali pengecekan Foreign Key
SET FOREIGN_KEY_CHECKS = 1;
