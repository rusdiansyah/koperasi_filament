<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Pinjaman extends Model
{
    protected $table = 'pinjamen';
    protected $fillable = [
        'anggota_id',
        'jenis_pinjaman_id',
        'tujuan_pinjaman_id',
        'tanggal',
        'jumlah',
        'tenor',
        'bunga',
        'total',
        'is_approve',
        'user_id',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class,'anggota_id');
    }
    public function jenis()
    {
        return $this->belongsTo(JenisPinjaman::class,'jenis_pinjaman_id');
    }
    public function tujuan()
    {
        return $this->belongsTo(TujuanPinjaman::class,'tujuan_pinjaman_id');
    }

    public function angsurans()
    {
        return $this->hasMany(Angsuran::class,'pinjaman_id');
    }

    public function scopeTotalangsuran($query)
    {
        $angsuran = Angsuran::select(DB::raw('SUM(jumlah) as angsuran'))
        ->where('pinjaman_id',$this->id)
        ->where('tanggal_bayar','!=',null)
        ->first();
        return $angsuran->angsuran ?? 0;
    }


}
