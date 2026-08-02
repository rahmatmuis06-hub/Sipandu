<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed data pengguna sistem SIBMN BPMP Gorontalo.
     *
     * Akun & password sesuai data demo pada loginmodal.blade.php:
     *   superadmin / super123
     *   kepalabpmp / kepala123
     *   kasubag    / kasubag123
     *   adminpersediaan / persediaan123
     *   adminsarpras    / sarpras123
     *   adminasettetap  / aset123
     *   pegawai         / pegawai123
     *   tamu            / tamu123
     */
    public function run(): void
    {
        $users = [
            // ── Super Admin ──────────────────────────────────────────────
            [
                'name'     => 'Rasyid Hidayat, S.Kom',
                'username' => 'Operator',
                'email'    => 'superadmin@bpmpgorontalo.id',
                'password' => Hash::make('super123'),
                'role'     => 'superadmin',
                'nip'      => '196501011990031001',
                'jabatan'  => 'Operator Sistem',
                'is_active' => true,
            ],

            // ── Kepala BPMP ──────────────────────────────────────────────
            [
                'name'     => 'Rudi Syaifullah, S.Si., M.M.',
                'username' => 'kepalabpmp',
                'email'    => 'kepalabpmp@bpmpgorontalo.id',
                'password' => Hash::make('kepala123'),
                'role'     => 'kepalabpmp',
                'nip'      => '197606272003121002',
                'jabatan'  => 'Kepala BPMP Provinsi Gorontalo',
                'is_active' => true,
            ],

            // ── Kasubag TU ───────────────────────────────────────────────
            [
                'name'     => 'Ardin, S.E., M.E',
                'username' => 'kasubag',
                'email'    => 'kasubag@bpmpgorontalo.id',
                'password' => Hash::make('kasubag123'),
                'role'     => 'kasubag',
                'nip'      => '197810242002121001',
                'jabatan'  => 'Kepala Sub Bagian Tata Usaha',
                'is_active' => true,
            ],

            // ── Admin Persediaan ─────────────────────────────────────────
            [
                'name'     => 'Rahmat Hidayat, S.Kom.',
                'username' => 'adminpersediaan',
                'email'    => 'persediaan@bpmpgorontalo.id',
                'password' => Hash::make('persediaan123'),
                'role'     => 'adminpersediaan',
                'nip'      => '199001152015031002',
                'jabatan'  => 'Pengelola Barang Persediaan',
                'is_active' => true,
            ],

            // ── Admin Sarpras ────────────────────────────────────────────
            [
                'name'     => 'Dewi Anggraini, S.T.',
                'username' => 'adminsarpras',
                'email'    => 'sarpras@bpmpgorontalo.id',
                'password' => Hash::make('sarpras123'),
                'role'     => 'adminsarpras',
                'nip'      => '199204082017042001',
                'jabatan'  => 'Pengelola Sarana dan Prasarana',
                'is_active' => true,
            ],

            // ── Admin Aset Tetap ─────────────────────────────────────────
            [
                'name'     => 'Lintang Cahyani Putri',
                'username' => 'adminasettetap',
                'email'    => 'asettetap@bpmpgorontalo.id',
                'password' => Hash::make('aset123'),
                'role'     => 'adminasettetap',
                'nip'      => '199307212018031003',
                'jabatan'  => 'Pengelola Aset Tetap / BMN',
                'is_active' => true,
            ],

            // ── Pegawai (contoh umum) ─────────────────────────────────────
            [
                'name'     => 'Budi Santoso, S.E.',
                'username' => 'pegawai',
                'email'    => 'pegawai@bpmpgorontalo.id',
                'password' => Hash::make('pegawai123'),
                'role'     => 'pegawai',
                'nip'      => '199510302019031004',
                'jabatan'  => 'Staf Tata Usaha',
                'is_active' => true,
            ],

            // ── Tamu ────────────────────────────────────────────────────
            [
                'name'     => 'Pengguna Tamu',
                'username' => 'tamu',
                'email'    => 'tamu@bpmpgorontalo.id',
                'password' => Hash::make('tamu123'),
                'role'     => 'tamu',
                'nip'      => null,
                'jabatan'  => 'Tamu / Pengunjung',
                'is_active' => true,
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['username' => $userData['username']],
                $userData
            );
        }

        $this->command->info('✅  ' . count($users) . ' pengguna sistem SIBMN berhasil di-seed.');
    }
}
