<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerbaikanKerusakan extends Model
{
    use HasFactory;

    protected $table = 'perbaikan_kerusakan';

    protected $fillable = [
        'kerusakan_id',
        'tanggal_perbaikan',
        'tindakan',
        'biaya',
        'pelaksana',
        'status',
        'catatan',
        'user_id',
    ];

    protected $casts = [
        'tanggal_perbaikan' => 'date',
        'biaya' => 'decimal:2',
    ];

    public function kerusakan(): BelongsTo
    {
        return $this->belongsTo(Kerusakan::class);
    }

    public function dicatatOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
