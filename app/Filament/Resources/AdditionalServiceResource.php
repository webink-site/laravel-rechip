<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdditionalServiceResource\Pages;
use App\Models\AdditionalService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class AdditionalServiceResource extends Resource
{
    protected static ?string $model = AdditionalService::class;
    protected static ?string $modelLabel = "Дополнительную услугу";
    protected static ?string $pluralModelLabel = "Дополнительные услуги";
    protected static ?string $navigationGroup = 'Каталог';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationIcon = 'heroicon-o-wrench';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('service_name')
                    ->label('Название дополнительной услуги')
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->label('Описание дополнительной услуги'),
                Forms\Components\FileUpload::make('image')
                    ->label('Изображение дополнительной услуги'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('service_name')->label('Название услуги'),
                TextColumn::make('description')->label('Описание услуги'),
            ])
            ->filters([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAdditionalServices::route('/'),
            'create' => Pages\CreateAdditionalService::route('/create'),
            'edit' => Pages\EditAdditionalService::route('/{record}/edit'),
        ];
    }
}
