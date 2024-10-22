<?php

namespace App\Filament\Resources\AdditionalServiceResource\RelationManagers;

use App\Models\AdditionalService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Table;

class AdditionalServicesRelationManager extends RelationManager
{
    protected static string $relationship = 'additionalServices'; // Отношение 'additionalServices' в модели Auto
    protected static ?string $recordTitleAttribute = 'service_name';
    protected static ?string $title = "Дополнительные услуги";
    protected static ?string $label = "дополнительную услугу";
    protected static ?string $pluralLabel = "дополнительные услуги";

    public function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('additional_service_id')
                    ->label('Дополнительная услуга')
                    ->options(AdditionalService::all()->pluck('service_name', 'id')) // Вывод всех доступных доп. услуг
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('price')
                    ->label('Цена доп. услуги')
                    ->numeric()
                    ->required(),
            ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('service_name')
                    ->label('Название доп. услуги'),
                Tables\Columns\TextColumn::make('pivot.price')
                    ->label('Цена доп. услуги'),
            ])
            ->filters([])
            ->headerActions([
                // Используем CreateAction для создания связи с дополнительной услугой
                CreateAction::make('create')
                    ->label('Добавить доп. услугу')
                    ->form([
                        Forms\Components\Select::make('additional_service_id')
                            ->label('Дополнительная услуга')
                            ->options(AdditionalService::all()->pluck('service_name', 'id')) // Список существующих доп. услуг
                            ->searchable()
                            ->required(),

                        // Поле для цены в pivot таблице
                        Forms\Components\TextInput::make('pivot.price')
                            ->label('Цена доп. услуги')
                            ->numeric()
                            ->required(),
                    ])
                    ->mutateFormDataUsing(function (array $data): array {
                        // Добавляем auto_id, чтобы создать связь с автомобилем
                        $data['auto_id'] = $this->ownerRecord->id; // Получаем id автомобиля
                        return $data;
                    })
                    ->action(function (array $data) {
                        // Создаем запись в промежуточной таблице с переданными данными
                        $this->ownerRecord->additionalServices()->attach($data['additional_service_id'], [
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
