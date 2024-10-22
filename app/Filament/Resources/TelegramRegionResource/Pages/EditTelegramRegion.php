<?php

namespace App\Filament\Resources\TelegramRegionResource\Pages;

use App\Filament\Resources\TelegramRegionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTelegramRegion extends EditRecord
{
    protected static string $resource = TelegramRegionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
