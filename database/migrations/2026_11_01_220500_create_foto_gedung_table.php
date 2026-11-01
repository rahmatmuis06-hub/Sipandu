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
        if (!Schema::hasTable('foto_gedung')) {
            Schema::create('foto_gedung', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('gedung_id')->index();
                $table->string('foto_path');
                $table->integer('urutan')->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foto_gedung');
    }
};
