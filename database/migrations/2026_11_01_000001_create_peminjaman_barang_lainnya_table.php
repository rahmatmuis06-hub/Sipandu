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
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('barang_lainnya_id')->nullable()->constrained('barang_lainnya')->nullOnDelete();
            $table->string('nama_barang', 200);
            $table->string('kode_barang', 50)->nullable();
            $table->integer('jumlah')->default(1);
            $table->string('satuan', 50)->default('buah');
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali')->nullable();
            $table->text('keperluan')->nullable();
            $table->enum('status', ['pending', 'disetujui', 'ditolak', 'dikembalikan', 'dibatalkan'])->default('pending');
            $table->text('catatan_admin')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman_barang_lainnya');
    }
};
