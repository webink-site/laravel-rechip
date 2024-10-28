<?php

namespace App\Filament\Resources\PageResource\Pages;

use App\Filament\Resources\PageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if($data['title'] === 'Главная'){
            for($i = 0; $i < count($data['content']['advantages']); $i++){
                $data['content']['advantages'][$i]['icon'] = Storage::disk('public')->url($data['content']['advantages'][$i]['icon']);
            }

        }
        return $data;
    }
}
