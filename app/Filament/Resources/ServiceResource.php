<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Filament\Resources\ServiceResource\RelationManagers\ServiceSeoSettingsRelationManager;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-c-clipboard-document-list';
    protected static ?string $navigationLabel = 'Услуги';
    protected static ?string $pluralLabel = 'Услуги';
    protected static ?string $modelLabel = 'Услуга';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->unique(Service::class, 'slug', ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\Textarea::make('short_description')
                    ->required()
                    ->maxLength(65535),
                Forms\Components\Textarea::make('description')
                    ->required(),
                Forms\Components\TextInput::make('post_title')
                    ->maxLength(255),
                Forms\Components\Textarea::make('page_content'),
                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->directory('services/images')
                    ->nullable(),
                Forms\Components\FileUpload::make('image_wide')
                    ->image()
                    ->directory('services/images_wide')
                    ->nullable(),
                Forms\Components\Repeater::make('minimal_prices')
                    ->schema([
                        Forms\Components\TextInput::make('price_type')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('price')
                            ->required()
                            ->numeric(),
                    ])
                    ->defaultItems(1)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('slug')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('short_description')->limit(50),
                Tables\Columns\IconColumn::make('image_wide')
                    ->boolean()
                    ->label('Есть широкое изображение'),
                Tables\Columns\TextColumn::make('minimal_prices')
                    ->label('Минимальные цены')
                    ->formatStateUsing(fn ($state) => is_array($state) ? count($state) : 0),
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
            ServiceSeoSettingsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}