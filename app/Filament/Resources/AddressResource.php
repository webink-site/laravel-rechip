<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AddressResource\Pages;
use App\Models\Address;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;

class AddressResource extends Resource
{
    protected static ?string $model = Address::class;

    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationGroup = 'Адреса';
    protected static ?string $navigationLabel = 'Адреса';
    protected static ?string $pluralModelLabel = 'Адреса';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('city')
                    ->label('Город')
                    ->required(),

                Forms\Components\TextInput::make('address')
                    ->label('Адрес')
                    ->required(),

                Forms\Components\TextInput::make('coordinates')
                    ->label('Координаты (широта, долгота)')
                    ->required(),

                Forms\Components\TextInput::make('yandex_map_link')
                    ->label('Ссылка на Яндекс.Карты')
                    ->url()
                    ->required(),

                Forms\Components\TextInput::make('phone_number')
                    ->label('Номер телефона')
                    ->required(),

                Forms\Components\TextInput::make('work_time')
                    ->label('Время работы')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('city')->label('Город'),
                Tables\Columns\TextColumn::make('address')->label('Адрес'),
                Tables\Columns\TextColumn::make('phone_number')->label('Номер телефона'),
                Tables\Columns\TextColumn::make('work_time')->label('Время работы'),
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
            'index' => Pages\ListAddresses::route('/'),
        ];
    }
}