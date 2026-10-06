<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perbaikan_kerusakan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kerusakan_id')->constrained('kerusakan')->cascadeOnDelete();
            $table->date('tanggal_perbaikan');
            $table->text('tindakan');
            $table->decimal('biaya', 15, 2)->default(0);
            $table->string('pelaksana')->nullable();
            $table->enum('status', ['Proses', 'Selesai', 'Tidak Dapat Diperbaiki'])->default('Proses');
            $table->text('catatan')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['kerusakan_id', 'tanggal_perbaikan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perbaikan_kerusakan');
    }
};
