<?php

namespace App\Filament\Resources\PinjamanResource\Pages;

use App\Filament\Resources\PinjamanResource;
use App\Models\Angsuran;
use App\Models\Pinjaman;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;

class CreatePinjaman extends CreateRecord
{
    protected static string $resource = PinjamanResource::class;
    // protected function getRedirectUrl(): string
    // {
    //     return $this->getResource()::getUrl('index');
    // }
    // protected function mutateFormDataBeforeCreate(array $data): array
    // {
    //     $pinjaman = Pinjaman::create([
    //         'anggota_id' => $data['anggota_id'],
    //         'jenis_pinjaman_id' => $data['jenis_pinjaman_id'],
    //         'tujuan_pinjaman_id' => $data['tujuan_pinjaman_id'],
    //         'tanggal' => $data['tanggal'],
    //         'jumlah' => $data['jumlah'],
    //         'tenor' => $data['tenor'],
    //         'bunga' => $data['bunga'],
    //         'is_approve ' => $data['is_approve'],
    //         'total' => str_replace(',', '', $data['total']),
    //         'user_id' => Filament::auth()->id()
    //     ]);
    //     $tenor = $data['tenor'];
    //     $angusran_bulanan = ($data['jumlah'] / $tenor) + (($data['jumlah'] * $data['bunga']) / 100);
    //     for ($x = 0; $x < $tenor; $x++) {
    //         $bln = $x + 1;
    //         Angsuran::create([
    //             'pinjaman_id' => $pinjaman->id,
    //             'tanggal_jatuh_tempo' => date('Y-m-d', strtotime("+$bln months", strtotime($data['tanggal']))),
    //             'jumlah' => $angusran_bulanan,
    //         ]);
    //     }
    //     return $pinjaman;
    // }

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $total = str_replace(',', '', $data['total']);
        $pinjaman = Pinjaman::create([
            'anggota_id' => $data['anggota_id'],
            'jenis_pinjaman_id' => $data['jenis_pinjaman_id'],
            'tujuan_pinjaman_id' => $data['tujuan_pinjaman_id'],
            'tanggal' => $data['tanggal'],
            'jumlah' => $data['jumlah'],
            'tenor' => $data['tenor'],
            'bunga' => $data['bunga'],
            'is_approve' => $data['is_approve'],
            'total' => $total,
            'user_id' => Filament::auth()->id()
        ]);
        $tenor = $data['tenor'];
        $angusran_bulanan = ($data['jumlah'] / $tenor) + (($data['jumlah'] * $data['bunga']) / 100);
        for ($x = 0; $x < $tenor; $x++) {
            $bln = $x + 1;
            Angsuran::create([
                'pinjaman_id' => $pinjaman->id,
                'tanggal_jatuh_tempo' => date('Y-m-d', strtotime("+$bln months", strtotime($data['tanggal']))),
                'jumlah' => $angusran_bulanan,
            ]);
        }
        return $pinjaman;
        // return parent::handleRecordCreation($data);
    }
}
