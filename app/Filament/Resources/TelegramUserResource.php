<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TelegramUserResource\Pages;
use App\Models\TelegramUser;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;

class TelegramUserResource extends Resource
{
    protected static ?string $model = TelegramUser::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Пользователи Telegram';
    protected static ?string $navigationGroup = 'Telegram';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->label('Telegram User ID')
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('username')
                    ->required()
                    ->label('Имя пользователя')
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('first_name')
                    ->required()
                    ->label('Имя'),
                Forms\Components\TextInput::make('last_name')
                    ->label('Фамилия'),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_id')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('username')->label('Имя пользователя'),
                Tables\Columns\TextColumn::make('first_name')->label('Имя'),
                Tables\Columns\TextColumn::make('last_name')->label('Фамилия'),
                Tables\Columns\TextColumn::make('created_at')->label('Дата регистрации')->dateTime()->sortable(),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTelegramUsers::route('/'),
            'create' => Pages\CreateTelegramUser::route('/create'),
            'edit' => Pages\EditTelegramUser::route('/{record}/edit'),
        ];
    }
}
