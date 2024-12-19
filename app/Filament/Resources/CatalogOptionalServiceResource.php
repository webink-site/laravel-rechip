<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CatalogOptionalServiceResource\Pages;
use App\Models\CatalogOptionalService;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Table;

class CatalogOptionalServiceResource extends Resource
{
    protected static ?string $model = CatalogOptionalService::class;

    protected static ?string $navigationIcon = 'heroicon-s-clipboard-document-list';
    protected static ?string $navigationLabel = 'Опциональные Услуги';
    protected static ?string $pluralLabel = 'Опциональные Услуги';
    protected static ?string $modelLabel = 'Опциональная Услуга';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('catalog_id')
                    ->relationship('catalog', 'slug')
                    ->required(),
                Forms\Components\Select::make('service_id')
                    ->relationship('service', 'slug')
                    ->required(),
                Forms\Components\TextInput::make('main_price')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('sale_price')
                    ->numeric()
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('catalog.slug')->label('Каталог')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('service.slug')->label('Услуга')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('main_price')->label('Основная цена')->sortable(),
                Tables\Columns\TextColumn::make('sale_price')->label('Цена со скидкой')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCatalogOptionalServices::route('/'),
            'create' => Pages\CreateCatalogOptionalService::route('/create'),
            'edit' => Pages\EditCatalogOptionalService::route('/{record}/edit'),
        ];
    }
}