<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GalleryWorkResource\Pages;
use App\Models\GalleryWork;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;

class GalleryWorkResource extends Resource
{
    protected static ?string $model = GalleryWork::class;
    protected static ?string $modelLabel = "Галерея работ";
    protected static ?string $pluralModelLabel = "Галерея работ";
    protected static ?string $navigationGroup = 'Информация';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationIcon = 'heroicon-c-clipboard-document-check';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(1)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Название работы')
                            ->required(),

                        Forms\Components\Textarea::make('content')
                            ->label('Описание работы')
                            ->required(),

                        Forms\Components\DatePicker::make('date')
                            ->label('Дата работы')
                            ->required(),

                        Forms\Components\TextInput::make('power_points')
                            ->label('Мощность (л.с.)')
                            ->required(),

                        Forms\Components\TextInput::make('tuning_profit')
                            ->label('Прирост от тюнинга (%)')
                            ->required(),

                        // Поле для загрузки изображений
                        FileUpload::make('gallery')
                            ->label('Галерея изображений')
                            ->multiple() // Разрешаем загружать несколько изображений
                            ->directory('gallery_works') // Указываем директорию для хранения изображений
                            ->required(),

                        // Поле для выбора автомобиля
                        Forms\Components\Select::make('catalog_id')
                            ->label('Автомобиль')
                            ->relationship('auto', 'auto_full_name') // Связываем с моделью Auto и выводим имя автомобиля
                            ->searchable() // Добавляем возможность поиска
                            ->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Название работы')
                    ->searchable(), // Добавляем поиск по названию работы
                TextColumn::make('content')
                    ->label('Описание работы'),
                TextColumn::make('date')
                    ->label('Дата работы')
                    ->date(),
            ])
            ->filters([
                // Фильтр по отсутствию фотографий
                Filter::make('no_photos')
                    ->label('Без фотографий')
                    ->query(function ($query) {
                        return $query->whereNull('gallery')->orWhere('gallery', '[]');
                    }),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGalleryWorks::route('/'),
            'create' => Pages\CreateGalleryWork::route('/create'),
            'edit' => Pages\EditGalleryWork::route('/{record}/edit'),
        ];
    }
}