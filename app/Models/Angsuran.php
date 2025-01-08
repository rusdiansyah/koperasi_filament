<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Angsuran extends Model
{
    protected $table = 'angsurans';
    protected $fillable = [
        'pinjaman_id',
        'tanggal_jatuh_tempo',
        'tanggal_bayar',
        'jumlah',
    ];
}
