<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class StokOpnameDetail extends Model {
 protected $table='stok_opname_detail';
 protected $guarded=['id'];
 protected $casts=['harga'=>'decimal:2','stok_buku'=>'integer','stok_fisik'=>'integer','masuk'=>'integer','keluar'=>'integer'];
 public function getSelisihAttribute(){return $this->stok_fisik===null || $this->stok_buku===null ? null : $this->stok_fisik-$this->stok_buku;}
}
