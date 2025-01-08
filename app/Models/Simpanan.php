<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Simpanan extends Model
{
    protected $table = 'simpanans';
    protected $fillable = [
        'anggota_id',
        'jenis_simpanan_id',
        'tanggal',
        'jenis_transaksi',
        'jumlah',
        'keterangan',
        'user_id',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class,'anggota_id');
    }
    public function jenis()
    {
        return $this->belongsTo(JenisSimpanan::class,'jenis_simpanan_id')
        ->orderBy('id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function scopeSaldo($anggotaId)
    {
        // Cari rekening berdasarkan user_id
        $rekening = Anggota::where('id', $anggotaId)->with('simpanan')->first();

        if (!$rekening) {
            return response()->json(['message' => 'Rekening tidak ditemukan'], 404);
        }

        // Hitung total setoran dan penarikan
        $totalSetoran = $rekening->simpanan->where('jenis_transaksi', 'setoran')->sum('jumlah');
        $totalPenarikan = $rekening->simpanan->where('jenis_transaksi', 'penarikan')->sum('jumlah');

        // Saldo terkini
        $saldoTerkini = $rekening->saldo_awal + $totalSetoran - $totalPenarikan;
        return $saldoTerkini;

    }
}
