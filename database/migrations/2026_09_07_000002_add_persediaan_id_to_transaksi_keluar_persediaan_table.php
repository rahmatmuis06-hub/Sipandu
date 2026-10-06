<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi_keluar_persediaan', function (Blueprint $table) {
            $table->foreignId('persediaan_id')->nullable()->after('id')
                ->constrained('persediaan')->nullOnDelete();
        });

        DB::table('transaksi_keluar_persediaan')
            ->select('id', 'kode_kategori', 'kode_barang')
            ->orderBy('id')
            ->chunkById(100, function ($transaksi) {
                foreach ($transaksi as $item) {
                    $persediaanId = DB::table('persediaan')
                        ->where('kode_kategori', $item->kode_kategori)
                        ->where('kode_barang', $item->kode_barang)
                        ->value('id');

                    DB::table('transaksi_keluar_persediaan')->where('id', $item->id)->update([
                        'persediaan_id' => $persediaanId,
                    ]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('transaksi_keluar_persediaan', function (Blueprint $table) {
            $table->dropConstrainedForeignId('persediaan_id');
        });
    }
};
