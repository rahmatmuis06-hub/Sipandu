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
        if (Schema::hasTable('kerusakan')) {
            try {
                Schema::table('kerusakan', function (Blueprint $table) {
                    $table->dropUnique('kerusakan_kode_barang_unique');
                });
            } catch (\Throwable $e) {
                try {
                    Schema::table('kerusakan', function (Blueprint $table) {
                        $table->dropUnique(['kode_barang']);
                    });
                } catch (\Throwable $e2) {
                    // Index unique mungkin sudah tidak ada atau tidak didukung driver ini
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('kerusakan')) {
            try {
                Schema::table('kerusakan', function (Blueprint $table) {
                    $table->unique('kode_barang');
                });
            } catch (\Throwable $e) {
                // Ignore
            }
        }
    }
};
