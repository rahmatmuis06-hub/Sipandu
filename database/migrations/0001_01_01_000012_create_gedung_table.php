<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
         Schema::create('gedung', function (Blueprint $table) {
            $table->id();
            $table->string('nama_gedung');
            $table->string('foto_url')->nullable();
            $table->string('lokasi');
            $table->string('luas_bangunan');
            $table->integer('tarif_sewa');
            $table->integer('kapasitas');
            $table->enum('ketersediaan', ['Tersedia', 'Sedang Dipakai', 'Renovasi', 'Perlu Perbaikan']);
            $table->text('fasilitas')->nullable();
            $table->string('kategori', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gedung');
    }
};
