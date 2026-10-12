<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('barang_lainnya')) {
            return;
        }

        Schema::create('barang_lainnya', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang', 50)->nullable();
            $table->string('nama_barang', 200);
            $table->string('kategori', 100)->default('Barang Lainnya');
            $table->string('satuan', 50)->default('buah');
            $table->integer('jumlah')->default(0);
            $table->decimal('harga_satuan', 15, 2)->default(0);
            $table->decimal('harga_total', 15, 2)->default(0);
            $table->date('tanggal_masuk')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang_lainnya');
    }
};
