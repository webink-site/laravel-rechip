<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EngineResource\Pages;
use App\Models\Engine;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Table;

class EngineResource extends Resource
{
    protected static ?string $model = Engine::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $navigationLabel = 'Двигатели';
    protected static ?string $pluralLabel = 'Двигатели';
    protected static ?string $modelLabel = 'Двигатель';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->unique(Engine::class, 'slug', ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\TextInput::make('volume')
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('power')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('slug')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('volume')->sortable(),
                Tables\Columns\TextColumn::make('power')->sortable(),
                Tables\Columns\TextColumn::make('catalogs_count')
                    ->counts('catalogs')
                    ->label('Количество каталогов'),
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
            'index' => Pages\ListEngines::route('/'),
            'create' => Pages\CreateEngine::route('/create'),
            'edit' => Pages\EditEngine::route('/{record}/edit'),
        ];
    }
}