<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    protected $fillable = [
        'nik',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'provinsi_id',
        'kabkota_id',
        'kecamatan_id',
        'desa_id',
        'alamat',
        'rt',
        'rw',
        'no_hp',
        'email',
        'tanggal_masuk',
        'is_active',
        'user_id',
    ];

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class);
    }
    public function kabkota()
    {
        return $this->belongsTo(KabKota::class);
    }
    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }
    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function simpanan()
    {
        return $this->hasMany(Simpanan::class,'anggota_id');
    }
}
