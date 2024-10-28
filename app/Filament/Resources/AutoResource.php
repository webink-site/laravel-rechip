<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdditionalServiceResource\RelationManagers\AdditionalServicesRelationManager;
use App\Filament\Resources\AutoResource\Pages;
use App\Filament\Resources\ServiceResource\RelationManagers\SevicesRelationManager;
use App\Models\Auto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class AutoResource extends Resource
{
    protected static ?string $model = Auto::class;
    protected static ?string $modelLabel = "Автомобиль";
    protected static ?string $pluralModelLabel = "Автомобили";
    protected static ?string $navigationGroup = 'Каталог';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'heroicon-o-truck';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(1)
                    ->schema([
                        Forms\Components\TextInput::make('auto_full_name')
                            ->label('Полное наименование авто')
                            ->columns(3)
                            ->required(),
                    ]),
                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\TextInput::make('brand')
                            ->label('Марка авто')
                            ->required(),
                        Forms\Components\TextInput::make('model')
                            ->label('Модель авто')
                            ->required(),
                        Forms\Components\TextInput::make('generation')
                            ->label('Поколение авто')
                            ->required(),
                        Forms\Components\TextInput::make('configuration')
                            ->label('Конфигурация авто')
                            ->required(),
                        Forms\Components\TextInput::make('modification')
                            ->label('Модификация авто')
                            ->required(),
                        Forms\Components\TextInput::make('carbase_modification_id')
                            ->label('ID модификации CarBase.ru')
                            ->required()
                    ]),
                // Добавляем показателей прироста
                Forms\Components\Grid::make(1)
                    ->schema([
                        Forms\Components\Repeater::make('stages_increase_params')
                            ->label('Показатели прироста')
                            ->schema([
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('stage')
                                            ->label('Stage')
                                            ->required(),
                                        Forms\Components\TextInput::make('price')
                                            ->label('Цена уровня тюнинга')
                                            ->numeric()
                                            ->required(),
                                    ]),
                                Forms\Components\Repeater::make('params')
                                    ->label('Параметры')
                                    ->schema([
                                        Forms\Components\TextInput::make('param_name')
                                            ->label('Название параметра')
                                            ->required(),
                                        Forms\Components\TextInput::make('factory_value')
                                            ->label('Заводские значения')
                                            ->required(),
                                        Forms\Components\TextInput::make('tuned_value')
                                            ->label('Значение после тюнинга')
                                            ->required(),
                                        Forms\Components\TextInput::make('increase_value')
                                            ->label('Прибавка в показателе')
                                            ->required(),
                                    ])
                                    ->columns(2),
                            ])
                            ->createItemButtonLabel('Добавить Stage'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('auto_full_name')->label('Полное наименование авто')->searchable(),
                TextColumn::make('brand')->label('Марка авто')->searchable(),
                TextColumn::make('model')->label('Модель авто')->searchable(),
                TextColumn::make('generation')->label('Поколение авто')->searchable(),
                TextColumn::make('configuration')->label('Конфигурация авто')->searchable(),
                TextColumn::make('modification')->label('Модификация авто')->searchable(),
            ])
            ->filters([]);
    }

    public static function getRelations(): array
    {
        return [
            SevicesRelationManager::class, // Добавляем RelationManager для услуг
            AdditionalServicesRelationManager::class, // Добавляем RelationManager для дополнительных услуг
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAutos::route('/'),
            'create' => Pages\CreateAuto::route('/create'),
            'edit' => Pages\EditAuto::route('/{record}/edit'),
        ];
    }
}
