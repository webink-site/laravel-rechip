<?php

namespace App\Filament\Resources\CatalogOptionalServiceResource\Pages;

use App\Filament\Resources\CatalogOptionalServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCatalogOptionalService extends EditRecord
{
    protected static string $resource = CatalogOptionalServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
