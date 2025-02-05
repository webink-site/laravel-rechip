<?php

namespace App\Filament\Resources\ContactResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;

class AddressesRelationManager extends RelationManager
{
    protected static string $relationship = 'addresses';
    protected static ?string $recordTitleAttribute = 'address';

    public function form(Forms\Form $form): Forms\Form
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

    public function table(Tables\Table $table): Tables\Table
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
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}