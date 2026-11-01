<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('system_settings')) {
            Schema::create('system_settings', function (Blueprint $table) {
                $table->id();
                $table->string('key', 100)->unique();
                $table->text('value')->nullable();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('system_settings')) {
            $exists = DB::table('system_settings')->where('key', 'fifo_persediaan_enabled')->exists();
            if (!$exists) {
                DB::table('system_settings')->insert([
                    'key'        => 'fifo_persediaan_enabled',
                    'value'      => '0',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
