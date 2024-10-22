<?php

namespace App\Filament\Resources\TelegramRequestResource\Pages;

use App\Filament\Resources\TelegramRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTelegramRequests extends ListRecords
{
    protected static string $resource = TelegramRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
