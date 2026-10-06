<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('persediaan', function (Blueprint $table) {
            $table->string('kode_unik_barang', 100)->nullable()->after('kode_barang');
        });

        DB::table('persediaan')
            ->select('id', 'kode_kategori', 'kode_barang')
            ->orderBy('id')
            ->chunkById(100, function ($barang) {
                foreach ($barang as $item) {
                    DB::table('persediaan')->where('id', $item->id)->update([
                        'kode_unik_barang' => trim($item->kode_kategori).'-'.trim($item->kode_barang),
                    ]);
                }
            });

        Schema::table('persediaan', function (Blueprint $table) {
            $table->unique('kode_unik_barang', 'persediaan_kode_unik_barang_unique');
        });
    }

    public function down(): void
    {
        Schema::table('persediaan', function (Blueprint $table) {
            $table->dropUnique('persediaan_kode_unik_barang_unique');
            $table->dropColumn('kode_unik_barang');
        });
    }
};
