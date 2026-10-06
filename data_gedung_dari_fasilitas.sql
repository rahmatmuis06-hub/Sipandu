-- ==============================================================================
-- SQL Script: Sinkronisasi Master Gedung dari Fasilitas Beranda (32 Fasilitas)
-- Jalankan query ini di phpMyAdmin pada database `sipandu`
-- ==============================================================================

-- 1. Pastikan kolom kategori mendukung string / varchar
ALTER TABLE `gedung` MODIFY COLUMN `kategori` VARCHAR(100) NULL;

-- 2. Bersihkan atau insert data gedung dari fasilitas beranda
INSERT INTO `gedung` (`id`, `nama_gedung`, `foto_url`, `lokasi`, `luas_bangunan`, `tarif_sewa`, `kapasitas`, `ketersediaan`, `fasilitas`, `kategori`, `created_at`, `updated_at`) VALUES
(1, 'Kantor Ponuwa 1', 'fasilitas/kantor_utama.jpeg', 'Gedung Kantor Utama', '500', 0, 100, 'Tersedia', 'Ruang Tunggu Nyaman, Unit Pelayanan Terpadu (ULT), Ruang Tamu VIP, Koneksi Internet, CCTV & Keamanan 24 Jam', 'kantor', NOW(), NOW()),
(2, 'Kantor Ponuwa 2', 'fasilitas/Kantorponuwa_2.jpeg', 'Gedung Kantor Utama', '500', 0, 100, 'Tersedia', 'Ruang Tunggu Nyaman, Unit Pelayanan Terpadu (ULT), Ruang Tamu VIP, Koneksi Internet , CCTV & Keamanan 24 Jam', 'kantor', NOW(), NOW()),
(3, 'Ruang Pertemuan Aula Dulohupa', 'fasilitas/gedung_aula.jpeg', 'Gedung Aula Utama', '420', 1500000, 300, 'Tersedia', 'AC, Standar Sound System , vidio trond, Meja Kursi, Toilet Bersih', 'ruang', NOW(), NOW()),
(4, 'Ruang Pertemuan Huyula', 'fasilitas/gedung_huyula.jpg', 'Gedung Pertemuan Huyula', '420', 1500000, 300, 'Tersedia', 'AC, Standar Sound System , vidio trond, Meja Kursi, Toilet Bersih', 'ruang', NOW(), NOW()),
(5, 'Ruang Kelas Tilango 1 BPMP Gorontalo', 'fasilitas/tilango.jpg', 'BPMP Gorontalo', '140', 500000, 30, 'Tersedia', 'AC , Proyektor, Papan Tulis Whiteboard, meja kursi, Sound system', 'kelas', NOW(), NOW()),
(6, 'Ruang Kelas Tilango 2 BPMP Gorontalo', 'fasilitas/TILANGO_2.jpeg', 'BPMP Gorontalo', '68', 500000, 30, 'Tersedia', 'AC , Proyektor, Papan Tulis Whiteboard, meja kursi, Sound system', 'kelas', NOW(), NOW()),
(7, 'Ruang Kelas Tilango 3 BPMP Gorontalo', 'fasilitas/TILANGO_3.jpeg', 'BPMP Gorontalo', '68', 500000, 30, 'Tersedia', 'AC , Proyektor, Papan Tulis Whiteboard, meja kursi, Sound system', 'kelas', NOW(), NOW()),
(8, 'Ruang Kelas Tinelo 1 BPMP Gorontalo', 'fasilitas/tinelo_1.jpeg', 'BPMP Gorontalo', '68', 500000, 30, 'Tersedia', 'AC , Proyektor, Papan Tulis Whiteboard, meja kursi, Sound system', 'kelas', NOW(), NOW()),
(9, 'Ruang Kelas Tinelo 2 BPMP Gorontalo', 'fasilitas/TINELO_2.jpeg', 'BPMP Gorontalo', '68', 500000, 30, 'Tersedia', 'AC , Proyektor, Papan Tulis Whiteboard, meja kursi, Sound system', 'kelas', NOW(), NOW()),
(10, 'Ruang Kelas Tinelo 3 BPMP Gorontalo', 'fasilitas/tinelo_3.jpeg', 'BPMP Gorontalo', '68', 500000, 30, 'Tersedia', 'AC , Proyektor, Papan Tulis Whiteboard, meja kursi, Sound system', 'kelas', NOW(), NOW()),
(11, 'Ruang Kelas Tinelo 4 BPMP Gorontalo', 'fasilitas/tinelo_4.jpeg', 'BPMP Gorontalo', '68', 500000, 30, 'Tersedia', 'AC , Proyektor, Papan Tulis Whiteboard, meja kursi, Sound system', 'kelas', NOW(), NOW()),
(12, 'Ruang Kelas Tinelo 5 BPMP Gorontalo', 'fasilitas/tinelo_5.jpeg', 'BPMP Gorontalo', '68', 500000, 30, 'Tersedia', 'AC , Proyektor, Papan Tulis Whiteboard, meja kursi, Sound system', 'kelas', NOW(), NOW()),
(13, 'Mess Bandayo kiki 1 BPMP Gorontalo', 'fasilitas/bandayokiki_1.jpeg', 'Area Mess Bandayo', '136.5', 500000, 4, 'Tersedia', 'Kamar Mandi Dalam, kamar tidur, Ruang kumpul Bersama, Ac', 'penginapan', NOW(), NOW()),
(14, 'Mess Bandayo kiki 2 BPMP Gorontalo', 'fasilitas/bandayokiki.jpeg', 'Area Mess Bandayo', '120.75', 500000, 4, 'Tersedia', 'Kamar Mandi Dalam, kamar tidur, Ruang kumpul Bersama, Ac', 'penginapan', NOW(), NOW()),
(15, 'Mess Bandayo kiki 3 BPMP Gorontalo', 'fasilitas/bandayokiki3.jpeg', 'Area Mess Bandayo', '120.75', 500000, 4, 'Tersedia', 'Kamar Mandi Dalam, kamar tidur, Ruang kumpul Bersama, Ac', 'penginapan', NOW(), NOW()),
(16, 'Mess Bandayo kiki 4 BPMP Gorontalo', 'fasilitas/bandayokiki4.jpeg', 'Area Mess Bandayo', '120.75', 500000, 4, 'Tersedia', 'Kamar Mandi Dalam, kamar tidur, Ruang kumpul Bersama, Ac', 'penginapan', NOW(), NOW()),
(17, 'Mess Bandayo Daa BPMP Gorontalo', 'fasilitas/bandayodaa.jpeg', 'Area Mess Bandayo', '211.5', 500000, 12, 'Tersedia', 'Kamar Mandi Dalam, kamar tidur, Ruang kumpul Bersama, Ac', 'penginapan', NOW(), NOW()),
(18, 'Ruang Asrama Beledaa 1 BPMP Gorontalo', 'fasilitas/beledaa1.jpeg', 'Gedung Asrama Bele Daa, Wongkaditi Timur', '522', 1000000, 32, 'Tersedia', 'Tempat Tidur, Kamar Mandi Dalam, Ruang Berkumpul Bersama', 'penginapan', NOW(), NOW()),
(19, 'Ruang Asrama Beledaa 2 BPMP Gorontalo', 'fasilitas/asrama_baledaa.jpeg', 'Gedung Asrama Bele Daa, Wongkaditi Timur', '522', 1000000, 32, 'Tersedia', 'Tempat Tidur, Kamar Mandi Dalam, Ruang Berkumpul Bersama', 'penginapan', NOW(), NOW()),
(20, 'Ruang Asrama Beledaa 3 BPMP Gorontalo', 'fasilitas/beledaa3.jpg', 'Gedung Asrama Bele Daa, Wongkaditi Timur', '522', 1000000, 32, 'Tersedia', 'Tempat Tidur, Kamar Mandi Dalam, Ruang Berkumpul Bersama', 'penginapan', NOW(), NOW()),
(21, 'Ruang Asrama Beledaa 4 BPMP Gorontalo', 'fasilitas/beledaa4.jpeg', 'Gedung Asrama Bele Daa, Wongkaditi Timur', '522', 1000000, 32, 'Tersedia', 'Tempat Tidur, Kamar Mandi Dalam, Ruang Berkumpul Bersama', 'penginapan', NOW(), NOW()),
(22, 'Ruang Makan Olamita 1 BPMP Gorontalo', 'fasilitas/olamita1_depan.jpeg', 'Gedung Olamita, Wongkaditi Timur', '198', 300000, 160, 'Tersedia', 'Meja & Kursi Makan , Area Cuci Tangan (Wastafel), Gazebo Outdoor Olamita, Toilet, Ac', 'ruang_makan', NOW(), NOW()),
(23, 'Ruang Makan Olamita 2 BPMP Gorontalo', 'fasilitas/olamita_2.jpeg', 'Gedung Olamita, Wongkaditi Timur', '198', 300000, 40, 'Tersedia', 'Meja & Kursi Makan , Area Cuci Tangan (Wastafel), Gazebo Outdoor Olamita, Toilet, Ac', 'ruang_makan', NOW(), NOW()),
(24, 'Lapangan Tenis BPMP Gorontalo', 'fasilitas/lapangan_tenis.jpeg', 'Area Sport Center BPMP', '800', 100000, 50, 'Tersedia', 'Lapangan Tenis Hardcourt', 'outdoor', NOW(), NOW()),
(25, 'Lapangan Voli/Takraw BPMP Gorontalo', 'fasilitas/lapangan_olahraga.jpeg', 'Area Sport Center BPMP', '800', 100000, 50, 'Tersedia', 'Lapangan Voli/Takraw', 'outdoor', NOW(), NOW()),
(26, 'Jogging Track BPMP Gorontalo', 'fasilitas/jogging_treck.jpeg', 'Area Sport Center BPMP', '800', 50000, 100, 'Tersedia', 'Jogging Track', 'outdoor', NOW(), NOW()),
(27, 'Lapangan Fustal BPMP Gorontalo', 'fasilitas/lapanganfutsal.jpeg', 'Area Sport Center BPMP', '800', 100000, 50, 'Tersedia', 'Lapangan Futsal', 'outdoor', NOW(), NOW()),
(28, 'Mushollah BPMP Gorontalo', 'fasilitas/musolla_depan.jpg', 'Samping Gedung Utama', '100', 0, 50, 'Tersedia', 'Mukena Bersih, Tempat Wudhu Terpisah (Pria/Wanita), Sound System Adzan, Toilet', 'sarana_ibadah', NOW(), NOW()),
(29, 'Klinik BPMP Gorontalo', 'fasilitas/klinik.jpeg', 'Gedung Layanan Kesehatan, Lantai 1', '40', 0, 5, 'Tersedia', 'Kotak P3K Lengkap, Obat-obatan Standar, kursi roda, tempat tidur pasien', 'kesehatan', NOW(), NOW()),
(30, 'Lapangan Upacara BPMP Gorontalo', 'fasilitas/upacara.jpg', 'Halaman Depan Gedung Utama', '1200', 200000, 500, 'Tersedia', 'Tiang Bendera, Lantai Paving Blok Rata, Sound System Luar Ruang, podium upacara', 'lapangan_Upacara', NOW(), NOW()),
(31, 'Gedung Arsip BPMP Gorontalo', 'fasilitas/gedung_arsip.jpeg', 'Area Belakang', '150', 0, 10, 'Tersedia', 'Lemari arsip', 'gedung', NOW(), NOW()),
(32, 'Ruang Laboratorium BPMP Gorontalo', 'fasilitas/gedung_laboratorium.jpeg', 'Gedung Laboratorium Lt. 2', '61', 500000, 30, 'Tersedia', 'Laboratorium Komputer, Multimedia, Meja Kursi Komputer', 'ruang', NOW(), NOW())
ON DUPLICATE KEY UPDATE 
    `nama_gedung` = VALUES(`nama_gedung`),
    `foto_url` = VALUES(`foto_url`),
    `lokasi` = VALUES(`lokasi`),
    `luas_bangunan` = VALUES(`luas_bangunan`),
    `tarif_sewa` = VALUES(`tarif_sewa`),
    `kapasitas` = VALUES(`kapasitas`),
    `ketersediaan` = VALUES(`ketersediaan`),
    `fasilitas` = VALUES(`fasilitas`),
    `kategori` = VALUES(`kategori`),
    `updated_at` = NOW();
