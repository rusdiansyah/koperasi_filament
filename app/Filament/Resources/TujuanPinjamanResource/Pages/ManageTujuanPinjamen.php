<?php

namespace App\Filament\Resources\TujuanPinjamanResource\Pages;

use App\Filament\Resources\TujuanPinjamanResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTujuanPinjamen extends ManageRecords
{
    protected static string $resource = TujuanPinjamanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
