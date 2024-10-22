<?php

namespace App\Filament\Resources\GalleryWorkResource\Pages;

use App\Filament\Resources\GalleryWorkResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGalleryWork extends EditRecord
{
    protected static string $resource = GalleryWorkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
