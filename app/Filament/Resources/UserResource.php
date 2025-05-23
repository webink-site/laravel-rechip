<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-c-users';
    protected static ?string $modelLabel = "Пользователь";
    protected static ?string $pluralModelLabel = "Пользователи";
    protected static ?string $navigationGroup = 'Настройки';
    protected static ?int $navigationSort = 4;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(1)
                    ->schema([
                        TextInput::make('name')
                            ->label("Имя")
                            ->required(),
                        TextInput::make('email')
                            ->label("E-mail")
                            ->email()
                            ->required(),
                        TextInput::make('password')
                            ->label("Пароль")
                            ->visible()
                            ->password()
                            ->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label("Имя")
                    ->sortable()
                    ->searchable(),
                TextColumn::make('email')
                    ->label("E-mail")
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
