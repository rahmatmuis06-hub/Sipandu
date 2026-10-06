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
        Schema::create('kerusakan', function (Blueprint $table) {
            $table->id();
            
            $table->date('tanggal_input'); // Tanggal input kerusakan
            $table->string('nama_barang'); // Nama barang yang rusak
            $table->string('kode_barang'); // Kode barang
            $table->string('nup')->nullable(); // Nomor Urut Pola (opsional)
            $table->enum('kondisi', ['Baik', 'Rusak Ringan', 'Rusak Berat'])->default('Rusak Ringan');
            $table->string('foto')->nullable(); // Path foto kerusakan
            $table->text('lokasi'); // Lokasi barang rusak
            $table->text('deskripsi'); // Deskripsi kerusakan
            
            // Timestamps
            $table->timestamps();
            
            // Indexes untuk performa query
            $table->index(['kondisi']);
            $table->index(['kode_barang']);
            $table->index(['tanggal_input']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kerusakan');
    }
};