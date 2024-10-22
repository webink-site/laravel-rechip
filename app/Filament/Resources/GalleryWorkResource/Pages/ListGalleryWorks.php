<?php

namespace App\Filament\Resources\GalleryWorkResource\Pages;

use App\Filament\Resources\GalleryWorkResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGalleryWorks extends ListRecords
{
    protected static string $resource = GalleryWorkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
