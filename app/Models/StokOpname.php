<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class StokOpname extends Model {
 protected $table='stok_opname';
 protected $guarded=['id'];
 protected $casts=['tanggal'=>'date','finalized_at'=>'datetime'];
 public function details(){return $this->hasMany(StokOpnameDetail::class);}
}
