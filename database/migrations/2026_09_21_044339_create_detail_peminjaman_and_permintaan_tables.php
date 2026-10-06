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
        Schema::create('detail_peminjaman_barang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_barang_id')->constrained('peminjaman_barang')->onDelete('cascade');
            $table->foreignId('aset_tetap_id')->nullable()->constrained('aset_tetap')->nullOnDelete();
            $table->string('kode_barang', 50);
            $table->string('nup', 50)->nullable();
            $table->string('nama_barang');
            $table->string('merek')->nullable();
            $table->string('kategori')->nullable();
            $table->integer('jumlah')->default(1);
            $table->string('kondisi')->nullable();
            $table->timestamps();
        });

        Schema::create('detail_permintaan_persediaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permintaan_persediaan_id')->constrained('permintaan_persediaan')->onDelete('cascade');
            $table->foreignId('persediaan_id')->constrained('persediaan')->onDelete('cascade');
            $table->string('kode_barang', 50)->nullable();
            $table->string('nama_barang');
            $table->string('satuan', 50)->nullable();
            $table->integer('jumlah_diminta')->default(1);
            $table->integer('jumlah_disetujui')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_permintaan_persediaan');
        Schema::dropIfExists('detail_peminjaman_barang');
    }
};
