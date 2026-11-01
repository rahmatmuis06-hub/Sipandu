<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Barang lainnya sudah dipisah ke tabel tersendiri (barang_lainnya)
        if (Schema::hasTable('persediaan')) {
            DB::table('persediaan')->where('kode_kategori', 'BLN')->delete();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('persediaan')) {
            DB::table('persediaan')->where('kode_kategori', 'BLN')->delete();
        }
    }
};
