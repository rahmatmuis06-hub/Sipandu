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
        if (Schema::hasColumn('peminjaman_gedung', 'gedung_id')) {
            return;
        }

        Schema::table('peminjaman_gedung', function (Blueprint $table) {
            $table->foreignId('gedung_id')->nullable()->after('user_id')->constrained('gedung')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman_gedung', function (Blueprint $table) {
            $table->dropForeign(['gedung_id']);
            $table->dropColumn('gedung_id');
        });
    }
};
