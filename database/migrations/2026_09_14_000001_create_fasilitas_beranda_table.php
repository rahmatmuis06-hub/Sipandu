<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        Schema::create('fasilitas_beranda', function (Blueprint $table) {
            $table->id();
            $table->json('konten');
            $table->unsignedInteger('urutan')->default(0);
            $table->boolean('tampil')->default(true);
            $table->timestamps();
        });
        $items = json_decode(file_get_contents(database_path('seeders/data/fasilitas_beranda.json')), true, 512, JSON_THROW_ON_ERROR);
        foreach ($items as $i => $item) {
            DB::table('fasilitas_beranda')->insert(['konten'=>json_encode($item), 'urutan'=>$i+1, 'tampil'=>true, 'created_at'=>now(), 'updated_at'=>now()]);
        }
    }
    public function down(): void { Schema::dropIfExists('fasilitas_beranda'); }
};
