<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactResource\Pages;
use App\Filament\Resources\ContactResource\RelationManagers;
use App\Models\Contact;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;
    protected static ?string $navigationIcon = 'heroicon-o-phone';
    protected static ?string $modelLabel = "Контакт";
    protected static ?string $pluralModelLabel = "Контакты";
    protected static ?string $navigationGroup = 'Информация';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('region_code')
                    ->label('Код региона')
                    ->required(),

                Forms\Components\TextInput::make('region_name')
                    ->label('Название региона')
                    ->required(),

                Forms\Components\TextInput::make('url')
                    ->label('URL региона')
                    ->url()
                    ->required(),

                // Поле для социальных ссылок
                Forms\Components\Section::make('social_links')
                    ->label('Социальные ссылки')
                    ->schema([
                        Forms\Components\TextInput::make('social_links.telegram')
                            ->label('Telegram')
                            ->url(),
                        Forms\Components\TextInput::make('social_links.whatsapp')
                            ->label('WhatsApp')
                            ->url(),
                        Forms\Components\TextInput::make('social_links.telegram_channel')
                            ->label('Telegram канал')
                            ->url(),
                        Forms\Components\TextInput::make('social_links.youtube')
                            ->label('YouTube')
                            ->url(),
                        Forms\Components\TextInput::make('social_links.drive2')
                            ->label('Drive2')
                            ->url(),
                        Forms\Components\TextInput::make('social_links.avito')
                            ->label('Avito')
                            ->url(),
                        Forms\Components\TextInput::make('social_links.vk')
                            ->label('VK')
                            ->url(),
                    ]),

                // Юридическая информация
                Forms\Components\Section::make('legal_info')
                    ->label('Юридическая информация')
                    ->schema([
                        Forms\Components\TextInput::make('organization_name')
                            ->label('Название организации')
                            ->required()
                            ->default('ИП Кубашичев Тимур Нурбиевич'),

                        Forms\Components\TextInput::make('inn')
                            ->label('ИНН')
                            ->required()
                            ->default('502988216808'),

                        Forms\Components\TextInput::make('ogrnip')
                            ->label('ОГРНИП')
                            ->required()
                            ->default('324508100060659'),

                        Forms\Components\TextInput::make('legal_address')
                            ->label('Юридический адрес')
                            ->required()
                            ->default('Московская область, город Мытищи'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('region_code')->label('Код региона'),
                TextColumn::make('region_name')->label('Название региона'),
            ])
            ->bulkActions([
                DeleteBulkAction::make()
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\AddressesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContacts::route('/'),
            'create' => Pages\CreateContact::route('/create'),
            'edit' => Pages\EditContact::route('/{record}/edit'),
        ];
    }
}