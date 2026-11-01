<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('peminjaman_barang_lainnya')) {
            return;
        }

        Schema::create('peminjaman_barang_lainnya', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('barang_lainnya_id')->nullable();
            $table->string('nama_barang', 200);
            $table->string('kode_barang', 50)->nullable();
            $table->integer('jumlah')->default(1);
            $table->string('satuan', 50)->default('buah');
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali')->nullable();
            $table->text('keperluan')->nullable();
            $table->enum('status', ['pending', 'disetujui', 'ditolak', 'dikembalikan', 'dibatalkan'])->default('pending');
            $table->text('catatan_admin')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('barang_lainnya_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman_barang_lainnya');
    }
};
