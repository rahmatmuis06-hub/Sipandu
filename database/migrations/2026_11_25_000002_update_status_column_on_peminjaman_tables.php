<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('peminjaman_barang') && Schema::hasColumn('peminjaman_barang', 'status')) {
            Schema::table('peminjaman_barang', function (Blueprint $table) {
                $table->string('status', 100)->default('pending')->change();
            });
        }

        if (Schema::hasTable('peminjaman_kendaraan') && Schema::hasColumn('peminjaman_kendaraan', 'status')) {
            Schema::table('peminjaman_kendaraan', function (Blueprint $table) {
                $table->string('status', 100)->default('pending')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Biarkan kolom tetap bertipe string agar nilai status seperti 'dikembalikan' tidak terpotong saat rollback parsial.
    }
};
