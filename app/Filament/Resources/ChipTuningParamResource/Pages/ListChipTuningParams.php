<?php

namespace App\Filament\Resources\ChipTuningParamResource\Pages;

use App\Filament\Resources\ChipTuningParamResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListChipTuningParams extends ListRecords
{
    protected static string $resource = ChipTuningParamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
