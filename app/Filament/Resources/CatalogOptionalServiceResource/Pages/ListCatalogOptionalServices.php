<?php

namespace App\Filament\Resources\CatalogOptionalServiceResource\Pages;

use App\Filament\Resources\CatalogOptionalServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCatalogOptionalServices extends ListRecords
{
    protected static string $resource = CatalogOptionalServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
