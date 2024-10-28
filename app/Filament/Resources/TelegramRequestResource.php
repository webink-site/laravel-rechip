<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TelegramRequestResource\Pages;
use App\Models\TelegramRequest;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Tables\Filters\SelectFilter;

class TelegramRequestResource extends Resource
{
    protected static ?string $model = TelegramRequest::class;

    protected static ?string $navigationIcon = 'heroicon-c-bell-alert';
    protected static ?string $navigationLabel = 'Заявки';
    protected static ?string $navigationGroup = 'Telegram';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('contact')
                    ->required()
                    ->label('Контакт'),
                Forms\Components\TextInput::make('product')
                    ->label('Продукт'),
                Forms\Components\TextInput::make('region_code')
                    ->required()
                    ->label('Код региона'),
                Forms\Components\Textarea::make('request_data')
                    ->required()
                    ->label('Данные заявки'),
                Forms\Components\Select::make('status')
                    ->options([
                        'new' => 'Новая',
                        'completed' => 'Обработана',
                        'spam' => 'Спам',
                        'error' => 'Ошибка',
                    ])
                    ->required()
                    ->default('new')
                    ->label('Статус'),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('contact')->label('Контакт'),
                Tables\Columns\TextColumn::make('product')->label('Продукт'),
                Tables\Columns\TextColumn::make('region_code')->label('Код региона'),
                Tables\Columns\TextColumn::make('status')->label('Статус')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label('Дата создания')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Фильтр по статусу')
                    ->options([
                        'new' => 'Новая',
                        'completed' => 'Обработана',
                        'spam' => 'Спам',
                        'error' => 'Ошибка',
                    ])
                    ->default('new')
                    ->query(function ($query, $state) {
                        // Применяем фильтрацию к запросу на основе выбранного значения
                        return $query->where('status', $state);
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTelegramRequests::route('/'),
            'edit' => Pages\EditTelegramRequest::route('/{record}/edit'),
        ];
    }
}