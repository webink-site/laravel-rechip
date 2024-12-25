<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarModelResource\Pages;
use App\Models\CarModel;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Table;

class CarModelResource extends Resource
{
    protected static ?string $model = CarModel::class;

    protected static ?string $navigationIcon = 'heroicon-c-cube';
    protected static ?string $navigationLabel = 'Модели';
    protected static ?string $pluralLabel = 'Модели';
    protected static ?string $modelLabel = 'Модель';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->unique(CarModel::class, 'slug', ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\FileUpload::make('catalog_image')
                    ->label('Изображение модели')
                    ->image() // Указывает, что это загрузка изображения
                    ->directory('models') // Каталог для хранения изображений
                    ->required() // Поле обязательно
                    ->maxSize(5120) // Максимальный размер файла в КБ (5 MB)
                    ->imagePreviewHeight('150'), // Высота предпросмотра изображения
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('slug')->sortable()->searchable(),
                Tables\Columns\ImageColumn::make('catalog_image')
                    ->label('Изображение') // Отображает изображение в таблице
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
            'index' => Pages\ListCarModels::route('/'),
            'create' => Pages\CreateCarModel::route('/create'),
            'edit' => Pages\EditCarModel::route('/{record}/edit'),
        ];
    }
}