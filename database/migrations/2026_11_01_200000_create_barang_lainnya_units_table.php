<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('barang_lainnya_units')) {
            Schema::create('barang_lainnya_units', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('barang_lainnya_id');
                $table->string('kode_unit', 50);
                $table->string('status', 30)->default('tersedia'); // tersedia, dipinjam, rusak, hilang
                $table->unsignedBigInteger('peminjaman_aktif_id')->nullable();
                $table->string('keterangan', 255)->nullable();
                $table->timestamps();

                $table->index('barang_lainnya_id', 'idx_blu_barang_id');
                $table->index('status', 'idx_blu_status');
                $table->index('peminjaman_aktif_id', 'idx_blu_pinjam_id');
            });
        }

        if (Schema::hasTable('peminjaman_barang_lainnya')) {
            Schema::table('peminjaman_barang_lainnya', function (Blueprint $table) {
                if (!Schema::hasColumn('peminjaman_barang_lainnya', 'unit_ids')) {
                    $table->text('unit_ids')->nullable()->after('jumlah');
                }
                if (!Schema::hasColumn('peminjaman_barang_lainnya', 'kode_unit')) {
                    $table->string('kode_unit', 255)->nullable()->after('unit_ids');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('peminjaman_barang_lainnya')) {
            Schema::table('peminjaman_barang_lainnya', function (Blueprint $table) {
                if (Schema::hasColumn('peminjaman_barang_lainnya', 'kode_unit')) {
                    $table->dropColumn('kode_unit');
                }
                if (Schema::hasColumn('peminjaman_barang_lainnya', 'unit_ids')) {
                    $table->dropColumn('unit_ids');
                }
            });
        }

        Schema::dropIfExists('barang_lainnya_units');
    }
};
