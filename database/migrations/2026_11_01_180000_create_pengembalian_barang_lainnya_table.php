<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('pengembalian_barang_lainnya')) {
            return;
        }

        Schema::create('pengembalian_barang_lainnya', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('peminjaman_barang_lainnya_id');
            $table->unsignedBigInteger('user_id');

            // Admin Persediaan Verifikasi
            $table->unsignedBigInteger('verified_by_admin_id')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->text('komentar_admin')->nullable();

            // Data Pengembalian
            $table->datetime('tanggal_pengembalian_aktual');
            $table->integer('jumlah_dikembalikan')->default(1);
            $table->text('kondisi_barang');
            $table->text('catatan')->nullable();
            $table->string('foto_sebelum')->nullable();
            $table->string('foto_sesudah')->nullable();

            $table->enum('status_pengembalian', [
                'lengkap',
                'rusak_ringan',
                'rusak_berat',
                'hilang'
            ])->default('lengkap');

            $table->enum('status_verifikasi', [
                'pending',
                'diterima',
                'ditolak',
                'dibatalkan'
            ])->default('pending');

            $table->timestamps();

            // Index
            $table->index('peminjaman_barang_lainnya_id', 'idx_pbl_id');
            $table->index('user_id', 'idx_pbl_user');
            $table->index('status_verifikasi', 'idx_pbl_verif');
            $table->index('status_pengembalian', 'idx_pbl_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengembalian_barang_lainnya');
    }
};
