<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisSimpanan extends Model
{
    protected $table = 'jenis_simpanans';
    protected $fillable = [
        'nama_simpanan',
        'nominal',
        'bunga',
        'is_active',
    ];
}
