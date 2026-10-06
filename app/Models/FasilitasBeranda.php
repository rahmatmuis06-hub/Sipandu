<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class FasilitasBeranda extends Model {
    protected $table = 'fasilitas_beranda';
    protected $fillable = ['konten', 'urutan', 'tampil'];
    protected $casts = ['konten'=>'array', 'tampil'=>'boolean', 'urutan'=>'integer'];
}
