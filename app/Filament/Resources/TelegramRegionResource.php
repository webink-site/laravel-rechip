<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TelegramRegionResource\Pages;
use App\Models\TelegramRegion;
use App\Models\TelegramUser;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;

class TelegramRegionResource extends Resource
{
    protected static ?string $model = TelegramRegion::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';
    protected static ?string $navigationLabel = 'Переадресация заявок';
    protected static ?string $navigationGroup = 'Telegram';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('region_code')
                    ->required()
                    ->label('Код региона'),
                Forms\Components\Select::make('telegram_account')
                    ->required()
                    ->label('ID ответственного менеджера')
                    ->options(TelegramUser::all()->pluck('username', 'username')->toArray()),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('region_code')->label('Код региона'),
                Tables\Columns\TextColumn::make('telegram_account')->label('ID ответственного менеджера'),
                Tables\Columns\TextColumn::make('created_at')->label('Дата создания')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->label('Дата обновления')->dateTime()->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTelegramRegions::route('/'),
            'edit' => Pages\EditTelegramRegion::route('/{record}/edit'),
        ];
    }
}
