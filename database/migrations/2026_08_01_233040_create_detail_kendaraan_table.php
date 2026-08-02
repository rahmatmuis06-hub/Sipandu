<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_kendaraan', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel aset_tetap
            $table->foreignId('aset_tetap_id')->constrained('aset_tetap')->onDelete('cascade');
            
            // Kolom detail khusus kendaraan
            $table->string('nomor_polisi')->nullable();
            $table->string('no_bpkb')->nullable();
            $table->string('nomor_rangka')->nullable();
            $table->string('nomor_mesin')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_kendaraan');
    }
};
