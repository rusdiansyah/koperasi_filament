<?php

namespace App\Filament\Resources\SimpananResource\Pages;

use App\Filament\Resources\SimpananResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;

class CreateSimpanan extends CreateRecord
{
    protected static string $resource = SimpananResource::class;
    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $data['user_id'] = Filament::auth()->id();
        return parent::handleRecordCreation($data);
    }
}
