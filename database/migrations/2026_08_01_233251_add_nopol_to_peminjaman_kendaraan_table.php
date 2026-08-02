<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman_kendaraan', function (Blueprint $table) {
            // Hapus fungsi ->after() agar kolom otomatis ditaruh di paling akhir
            $table->string('nomor_polisi_saat_pinjam')->nullable();
            $table->string('no_bpkb_saat_pinjam')->nullable();
            $table->string('nomor_rangka_saat_pinjam')->nullable();
            $table->string('nomor_mesin_saat_pinjam')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman_kendaraan', function (Blueprint $table) {
            $table->dropColumn([
                'nomor_polisi_saat_pinjam',
                'no_bpkb_saat_pinjam',
                'nomor_rangka_saat_pinjam',
                'nomor_mesin_saat_pinjam'
            ]);
        });
    }
};