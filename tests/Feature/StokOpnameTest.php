<?php
namespace Tests\Feature;
use App\Models\{User,Persediaan,StokOpname};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class StokOpnameTest extends TestCase {
 use RefreshDatabase;
 private function setupStock(){
  $this->actingAs(User::factory()->create(['role'=>'adminpersediaan','is_active'=>true]));
  return Persediaan::create(['kode_kategori'=>'01','kategori'=>'ATK','kode_barang'=>'001','nama_barang'=>'Kertas Uji','satuan'=>'Rim','tanggal_masuk'=>today(),'harga_satuan'=>10000,'harga_total'=>100000,'jumlah'=>10]);
 }
 public function test_monthly_count_reports_and_lock(){
  $p=$this->setupStock();
  $this->post(route('adminpersediaan.opname.store'),['bulan'=>today()->format('Y-m'),'tanggal'=>today()->format('Y-m-d')])->assertSessionHasNoErrors()->assertRedirect();
  $op=StokOpname::firstOrFail();$d=$op->details()->firstOrFail();
  $this->assertSame(10,$d->stok_buku);$this->assertNull($d->stok_fisik);
  $this->post(route('adminpersediaan.opname.finalize',$op))->assertSessionHasErrors('opname');
  $this->put(route('adminpersediaan.opname.update',$op),['detail_id'=>$d->id,'stok_buku'=>10,'stok_fisik'=>8,'catatan'=>'Selisih dua rim'])->assertSessionHasNoErrors();
  $this->assertSame(-2,$d->fresh()->selisih);
  $this->get(route('adminpersediaan.opname.show',$op))->assertOk()->assertSee('Kertas Uji');
  $this->get(route('adminpersediaan.opname.report',[$op,'barang'=>$p->id]))->assertOk()->assertSee('80.000,00')->assertSee('-20.000,00');
  $this->get(route('adminpersediaan.opname.report',[$op,'pdf'=>1]))->assertOk()->assertHeader('content-type','application/pdf');
  $this->post(route('adminpersediaan.opname.finalize',$op))->assertSessionHasNoErrors();
  $this->put(route('adminpersediaan.opname.update',$op),['detail_id'=>$d->id,'stok_buku'=>10,'stok_fisik'=>0])->assertForbidden();
  $this->assertSame(10,$p->fresh()->jumlah);
  $this->post(route('adminpersediaan.opname.store'),['bulan'=>today()->format('Y-m'),'tanggal'=>today()->format('Y-m-d')])->assertSessionHasErrors('bulan');
 }
 public function test_historical_balance_is_not_invented(){
  $this->setupStock();$date=today()->subMonthNoOverflow()->endOfMonth();
  $this->post(route('adminpersediaan.opname.store'),['bulan'=>$date->format('Y-m'),'tanggal'=>$date->format('Y-m-d')])->assertSessionHasNoErrors();
  $this->assertNull(StokOpname::firstOrFail()->details()->firstOrFail()->stok_buku);
 }
 public function test_employee_cannot_access_opname(){
  $this->actingAs(User::factory()->create(['role'=>'pegawai','is_active'=>true]))->get(route('adminpersediaan.opname.index'))->assertForbidden();
 }
}
