<?php

namespace App\Filament\Resources\ChipTuningParamResource\Pages;

use App\Filament\Resources\ChipTuningParamResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditChipTuningParam extends EditRecord
{
    protected static string $resource = ChipTuningParamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
