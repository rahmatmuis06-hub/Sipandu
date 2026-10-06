<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        Schema::create('riwayat_kerusakan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kerusakan_id')->constrained('kerusakan')->restrictOnDelete();
            $table->json('data');
            $table->string('aktivitas');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
        DB::table('kerusakan')->orderBy('id')->chunkById(100, function ($rows) {
            foreach ($rows as $row) {
                DB::table('riwayat_kerusakan')->insert([
                    'kerusakan_id'=>$row->id, 'data'=>json_encode($row),
                    'aktivitas'=>'Data awal saat fitur riwayat diaktifkan',
                    'created_at'=>now(), 'updated_at'=>now(),
                ]);
            }
        });
    }
    public function down(): void { Schema::dropIfExists('riwayat_kerusakan'); }
};
