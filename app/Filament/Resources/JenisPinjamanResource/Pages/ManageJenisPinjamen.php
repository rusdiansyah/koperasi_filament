<?php

namespace App\Filament\Resources\JenisPinjamanResource\Pages;

use App\Filament\Resources\JenisPinjamanResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageJenisPinjamen extends ManageRecords
{
    protected static string $resource = JenisPinjamanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
