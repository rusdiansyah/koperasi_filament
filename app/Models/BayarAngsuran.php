<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BayarAngsuran extends Model
{
    protected $table = 'bayar_angsurans';
    protected $fillable = [
        'angsuran_id',
        'tanggal',
        'jumlah',
    ];
}
