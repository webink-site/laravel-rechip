<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use FilamentTiptapEditor\TiptapEditor;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;
    protected static ?string $modelLabel = "Услугу";
    protected static ?string $pluralModelLabel = "Услуги";
    protected static ?string $navigationGroup = 'Каталог';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationIcon = 'heroicon-o-cog';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('service_name')
                            ->label('Название услуги')
                            ->required(),
                        Forms\Components\TextInput::make('post_title')
                            ->label('Заголовок страницы услуги'),
                    ]),
                Forms\Components\Grid::make(1)
                    ->schema([
                        Forms\Components\TextInput::make('short_description')
                            ->label('Краткое описание услуги'),
                        TiptapEditor::make('description')
                            ->label('Описание'),
                        TiptapEditor::make('page_content')
                            ->label('Содержание страницы'),
                        Forms\Components\Repeater::make('minimal_prices')
                            ->label('Минимальные цены')
                            ->schema([
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\FileUpload::make('icon')
                                            ->label('Иконка'),
                                        Forms\Components\Group::make()
                                            ->schema([
                                                Forms\Components\TextInput::make('description')
                                                    ->label('Краткое описание'),
                                                Forms\Components\TextInput::make('price')
                                                    ->label('Цена')
                                            ]),
                                    ])
                            ])
                        ->columns(2)
                    ]),
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->label('Изображение'),
                        Forms\Components\FileUpload::make('image_wide')
                            ->label('Широкое изображение для заголовка страницы'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('service_name')->label('Название услуги'),
                TextColumn::make('short_description')->label('Краткое описание'),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
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
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
