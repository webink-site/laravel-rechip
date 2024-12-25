<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BrandResource\Pages;
use App\Models\Brand;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Table;

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;

    protected static ?string $navigationIcon = 'heroicon-c-building-library';
    protected static ?string $navigationLabel = 'Бренды';
    protected static ?string $pluralLabel = 'Бренды';
    protected static ?string $modelLabel = 'Бренд';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->unique(Brand::class, 'slug', ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\FileUpload::make('catalog_image')
                    ->label('Логотип бренда')
                    ->image()
                    ->directory('brands') // Каталог для хранения логотипов
                    ->required()
                    ->maxSize(5120) // Максимальный размер файла в КБ (5 MB)
                    ->imagePreviewHeight('150'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('slug')->sortable()->searchable(),
                Tables\Columns\ImageColumn::make('catalog_image')
                    ->label('Логотип') // Отображает логотип в таблице
                    ->square(),
                Tables\Columns\TextColumn::make('catalogs_count')
                    ->counts('catalogs')
                    ->label('Количество каталогов'),
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
            'index' => Pages\ListBrands::route('/'),
            'create' => Pages\CreateBrand::route('/create'),
            'edit' => Pages\EditBrand::route('/{record}/edit'),
        ];
    }
}