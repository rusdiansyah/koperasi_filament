<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TujuanPinjaman extends Model
{
    protected $table = 'tujuan_pinjamen';
    protected $fillable = [
        'nama_tujuan',
        'is_active',
    ];
}
