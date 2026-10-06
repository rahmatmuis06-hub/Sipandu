<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class RiwayatKerusakan extends Model {
    protected $table = 'riwayat_kerusakan';
    protected $guarded = ['id'];
    protected $casts = ['data'=>'array'];
    public function pencatat() { return $this->belongsTo(User::class, 'user_id'); }
}
