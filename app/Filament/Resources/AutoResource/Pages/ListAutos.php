<?php

namespace App\Filament\Resources\AutoResource\Pages;

use App\Filament\Resources\AutoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAutos extends ListRecords
{
    protected static string $resource = AutoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
