<?php

namespace App\Filament\Resources\AnggotaResource\Pages;

use App\Filament\Resources\AnggotaResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;
class CreateAnggota extends CreateRecord
{
    protected static string $resource = AnggotaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $user = User::create([
            'name' => $data['nama_lengkap'],
            'email' => $data['email'],
            'password' => Hash::make(str_replace('-','',$data['tanggal_lahir'])),
        ]);
        $data['user_id'] = $user->id;
        return parent::handleRecordCreation($data);
    }
}
