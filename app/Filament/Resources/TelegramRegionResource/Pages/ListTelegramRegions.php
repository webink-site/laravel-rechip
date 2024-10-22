<?php

namespace App\Filament\Resources\TelegramRegionResource\Pages;

use App\Filament\Resources\TelegramRegionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTelegramRegions extends ListRecords
{
    protected static string $resource = TelegramRegionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
