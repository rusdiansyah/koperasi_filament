<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisPinjaman extends Model
{
    protected $table = 'jenis_pinjamen';
    protected $fillable = [
        'nama_pinjaman',
        'nominal',
        'tenor',
        'bunga',
        'is_active',
    ];
}
