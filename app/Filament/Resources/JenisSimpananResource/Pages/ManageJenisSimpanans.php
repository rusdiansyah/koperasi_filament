<?php

namespace App\Filament\Resources\JenisSimpananResource\Pages;

use App\Filament\Resources\JenisSimpananResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageJenisSimpanans extends ManageRecords
{
    protected static string $resource = JenisSimpananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
