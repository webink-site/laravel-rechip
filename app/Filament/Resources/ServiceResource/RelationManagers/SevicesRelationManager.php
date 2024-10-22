<?php

namespace App\Filament\Resources\ServiceResource\RelationManagers;

use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Table;

class SevicesRelationManager extends RelationManager
{
    protected static string $relationship = 'services'; // Отношение 'services' в модели Aut
    protected static ?string $recordTitleAttribute = 'service_name';
    protected static ?string $title = "Услуги";
    protected static ?string $label = "услугу";
    protected static ?string $pluralLabel = "услуги";

    public function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('service_id')
                    ->label('Услуга')
                    ->options(Service::all()->pluck('service_name', 'id')) // Вывод всех доступных услуг
                    ->required(),
                Forms\Components\TextInput::make('pivot.price')
                    ->label('Цена услуги')
                    ->numeric()
                    ->required(),
            ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('service_name')
                    ->label('Название услуги'),
                Tables\Columns\TextColumn::make('pivot.price')
                    ->label('Цена услуги'),
            ])
            ->filters([])
            ->headerActions([
                // Используем CreateAction для создания связи с дополнительными данными
                CreateAction::make('create')
                    ->label('Добавить услугу')
                    ->form([
                        Forms\Components\Select::make('service_id')
                            ->label('Услуга')
                            ->options(Service::all()->pluck('service_name', 'id')) // Список существующих услуг
                            ->searchable()
                            ->required(),

                        // Поле для цены в pivot таблице
                        Forms\Components\TextInput::make('pivot.price')
                            ->label('Цена услуги')
                            ->numeric()
                            ->required(),
                    ])
                    ->mutateFormDataUsing(function (array $data): array {
                        // Добавляем auto_id в данные, чтобы создать связь
                        $data['auto_id'] = $this->ownerRecord->id; // Получаем id автомобиля
                        return $data;
                    })
                    ->action(function (array $data) {
                        // Создаем запись в промежуточной таблице с переданными данными
                        $this->ownerRecord->services()->attach($data['service_id'], [
                            'price' => $data['pivot']['price'],
                        ]);
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([]);
    }
}
