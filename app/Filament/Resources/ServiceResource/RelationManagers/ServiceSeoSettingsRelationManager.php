<?php

namespace App\Filament\Resources\ServiceResource\RelationManagers;

use App\Models\ServiceSeoSetting;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ServiceSeoSettingsRelationManager extends RelationManager
{
    protected static string $relationship = 'seoSettings';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $pluralLabel = 'SEO-настройки';
    protected static ?string $label = 'SEO-настройка';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('level')
                    ->label('Уровень')
                    ->options([
                        'service'       => 'Услуга',
                        'brand'         => 'Марка',
                        'model'         => 'Модель',
                        'configuration' => 'Конфигурация',
                        'engine'        => 'Двигатель',
                    ])
                    ->required()
                    ->rules(['in:service,brand,model,configuration,engine']),

                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->label('Заголовок'),

                Forms\Components\Textarea::make('description')
                    ->required()
                    ->label('Описание'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('level')
                    ->sortable()
                    ->searchable()
                    ->label('Уровень')
                    ->formatStateUsing(function ($state) {
                        return match($state) {
                            'service'       => 'Услуга',
                            'brand'         => 'Марка',
                            'model'         => 'Модель',
                            'configuration' => 'Конфигурация',
                            'engine'        => 'Двигатель',
                            default         => $state,
                        };
                    }),
                Tables\Columns\TextColumn::make('title')
                    ->sortable()
                    ->searchable()
                    ->label('Заголовок'),
                Tables\Columns\TextColumn::make('description')
                    ->limit(50)
                    ->label('Описание')
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