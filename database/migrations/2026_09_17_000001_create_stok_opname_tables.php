<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::create('stok_opname',function(Blueprint $t){$t->id();$t->string('bulan',7)->unique();$t->date('tanggal');$t->string('status')->default('draft');$t->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();$t->timestamp('finalized_at')->nullable();$t->timestamps();});
  Schema::create('stok_opname_detail',function(Blueprint $t){
   $t->id();$t->foreignId('stok_opname_id')->constrained('stok_opname')->cascadeOnDelete();$t->unsignedBigInteger('persediaan_id');$t->string('kode');$t->string('nama');$t->string('satuan')->nullable();$t->decimal('harga',18,2);$t->unsignedInteger('masuk')->default(0);$t->unsignedInteger('keluar')->default(0);$t->unsignedInteger('stok_buku')->nullable();$t->unsignedInteger('stok_fisik')->nullable();$t->text('catatan')->nullable();$t->timestamps();$t->unique(['stok_opname_id','persediaan_id']);
  });
 }
 public function down(): void {Schema::dropIfExists('stok_opname_detail');Schema::dropIfExists('stok_opname');}
};
