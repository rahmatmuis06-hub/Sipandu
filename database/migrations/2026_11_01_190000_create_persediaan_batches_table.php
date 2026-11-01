<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('persediaan_batches')) {
            Schema::create('persediaan_batches', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('persediaan_id')->index();
                $table->unsignedBigInteger('transaksi_masuk_id')->nullable()->index();
                $table->date('tanggal_masuk');
                $table->decimal('harga_satuan', 15, 2)->default(0);
                $table->integer('jumlah_awal')->default(0);
                $table->integer('jumlah_sisa')->default(0);
                $table->string('sumber', 50)->default('stok_awal');
                $table->string('keterangan', 255)->nullable();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('transaksi_keluar_persediaan') && !Schema::hasColumn('transaksi_keluar_persediaan', 'batch_alokasi')) {
            Schema::table('transaksi_keluar_persediaan', function (Blueprint $table) {
                $table->text('batch_alokasi')->nullable()->after('total');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('transaksi_keluar_persediaan') && Schema::hasColumn('transaksi_keluar_persediaan', 'batch_alokasi')) {
            Schema::table('transaksi_keluar_persediaan', function (Blueprint $table) {
                $table->dropColumn('batch_alokasi');
            });
        }

        Schema::dropIfExists('persediaan_batches');
    }
};
