<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('peminjaman_kendaraan')) {
            return;
        }

        Schema::table('peminjaman_kendaraan', function (Blueprint $table) {
            if (!Schema::hasColumn('peminjaman_kendaraan', 'nomor_polisi_saat_pinjam')) {
                $table->string('nomor_polisi_saat_pinjam')->nullable();
            }
            if (!Schema::hasColumn('peminjaman_kendaraan', 'no_bpkb_saat_pinjam')) {
                $table->string('no_bpkb_saat_pinjam')->nullable();
            }
            if (!Schema::hasColumn('peminjaman_kendaraan', 'nomor_rangka_saat_pinjam')) {
                $table->string('nomor_rangka_saat_pinjam')->nullable();
            }
            if (!Schema::hasColumn('peminjaman_kendaraan', 'nomor_mesin_saat_pinjam')) {
                $table->string('nomor_mesin_saat_pinjam')->nullable();
            }
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