<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChipTuningParamResource\Pages;
use App\Models\ChipTuningParam;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Table;

class ChipTuningParamResource extends Resource
{
    protected static ?string $model = ChipTuningParam::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog'; // Выберите подходящий значок
    protected static ?string $navigationLabel = 'Параметры Чип-Тюнинга';
    protected static ?string $pluralLabel = 'Параметры Чип-Тюнинга';
    protected static ?string $modelLabel = 'Параметр Чип-Тюнинга';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('catalog_id')
                    ->relationship('catalog', 'slug')
                    ->required(),
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('catalog.slug')->label('Каталог')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('torque')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('stage1_power_value')->label('Стадия 1: Мощность')->sortable(),
                Tables\Columns\TextColumn::make('stage1_torque_value')->label('Стадия 1: Крутящий момент')->sortable(),
                Tables\Columns\TextColumn::make('stage1_price')->label('Стадия 1: Цена')->sortable(),
                Tables\Columns\TextColumn::make('stage2_power_value')->label('Стадия 2: Мощность')->sortable(),
                Tables\Columns\TextColumn::make('stage2_torque_value')->label('Стадия 2: Крутящий момент')->sortable(),
                Tables\Columns\TextColumn::make('stage2_price')->label('Стадия 2: Цена')->sortable(),
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
            'index' => Pages\ListChipTuningParams::route('/'),
            'create' => Pages\CreateChipTuningParam::route('/create'),
            'edit' => Pages\EditChipTuningParam::route('/{record}/edit'),
        ];
    }
}