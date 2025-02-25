<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CatalogResource\Pages;
use App\Models\Catalog;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class CatalogResource extends Resource
{
    protected static ?string $model = Catalog::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Каталоги';
    protected static ?string $pluralLabel = 'Каталоги';
    protected static ?string $modelLabel = 'Каталог';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('brand_id')
                    ->relationship('brand', 'name')
                    ->required()
                    ->searchable(),
                Forms\Components\Select::make('model_id')
                    ->relationship('carModel', 'name')
                    ->required()
                    ->searchable(),
                Forms\Components\Select::make('configuration_id')
                    ->relationship('configuration', 'name')
                    ->required()
                    ->searchable(),
                Forms\Components\Select::make('engine_id')
                    ->relationship('engine', 'slug') // или другое поле, которое удобно
                    ->required()
                    ->searchable(),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->unique(Catalog::class, 'slug', ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\Select::make('service_id')
                    ->relationship('service', 'name')
                    ->required()
                    ->searchable(),
                Forms\Components\TextInput::make('service_main_price')
                    ->numeric(),
                Forms\Components\TextInput::make('service_sale_price')
                    ->numeric(),
                Forms\Components\Repeater::make('chipTuningParam')
                    ->relationship()
                    ->schema([
                        Forms\Components\TextInput::make('torque')
                            ->required()
                            ->maxLength(50),
                        Forms\Components\TextInput::make('stage1_power_value')
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('stage1_torque_value')
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('stage1_price')
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('stage2_power_value')
                            ->numeric()
                            ->nullable(),
                        Forms\Components\TextInput::make('stage2_torque_value')
                            ->numeric()
                            ->nullable(),
                        Forms\Components\TextInput::make('stage2_price')
                            ->numeric()
                            ->nullable(),
                    ])
                    ->columns(2)
                    ->createItemButtonLabel('Добавить параметры чип-тюнинга')
                    ->required(),
                Forms\Components\Repeater::make('catalogOptionalServices')
                    ->relationship()
                    ->schema([
                        Forms\Components\Select::make('service_id')
                            ->relationship('service', 'name')
                            ->required()
                            ->searchable(),
                        Forms\Components\TextInput::make('main_price')
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('sale_price')
                            ->numeric()
                            ->nullable(),
                    ])
                    ->columns(3)
                    ->createItemButtonLabel('Добавить опциональную услугу')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('slug')->sortable()->searchable()->label('Слаг'),
                Tables\Columns\TextColumn::make('service.name')->sortable()->searchable()->label('Услуга'),
                Tables\Columns\TextColumn::make('brand.name')->sortable()->searchable()->label('Бренд'),
                Tables\Columns\TextColumn::make('carModel.name')->sortable()->searchable()->label('Модель'),
                Tables\Columns\TextColumn::make('configuration.name')->sortable()->searchable()->label('Конфигурация'),
                Tables\Columns\TextColumn::make('engine.slug')->sortable()->searchable()->label('Двигатель'),
                Tables\Columns\TextColumn::make('service_main_price')->sortable()->label('Основная цена'),
                Tables\Columns\TextColumn::make('chipTuningParam.torque')->label('Крутящий момент'),
                Tables\Columns\TextColumn::make('chipTuningParam.stage1_power_value')->label('Стадия 1: Мощность'),
                Tables\Columns\TextColumn::make('chipTuningParam.stage1_torque_value')->label('Стадия 1: Крутящий момент'),
                Tables\Columns\TextColumn::make('chipTuningParam.stage1_price')->label('Стадия 1: Цена'),
            ])
            ->filters([
                SelectFilter::make('service_id')
                    ->relationship('service', 'name')
                    ->label('Услуга'),
                SelectFilter::make('brand_id')
                    ->relationship('brand', 'name')
                    ->label('Бренд'),
                SelectFilter::make('model_id')
                    ->relationship('carModel', 'name')
                    ->label('Модель'),
                SelectFilter::make('configuration_id')
                    ->relationship('configuration', 'name')
                    ->label('Конфигурация'),
                SelectFilter::make('engine_id')
                    ->relationship('engine', 'slug')
                    ->label('Двигатель'),
                Tables\Filters\Filter::make('catalog_slug')
                    ->form([
                        Forms\Components\TextInput::make('slug')
                            ->placeholder('Введите слаг каталога')
                            ->label('Слаг Каталога'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query->where('slug', 'like', "%{$data['slug']}%");
                    }),
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
            'index' => Pages\ListCatalogs::route('/'),
            'create' => Pages\CreateCatalog::route('/create'),
            'edit' => Pages\EditCatalog::route('/{record}/edit'),
        ];
    }
}