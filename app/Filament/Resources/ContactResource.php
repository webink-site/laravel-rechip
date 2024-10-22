<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactResource\Pages;
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
    protected static ?string $modelLabel = "Контакты";
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

                Forms\Components\TextInput::make('address')
                    ->label('Адрес')
                    ->required(),

                Forms\Components\TextInput::make('phone_number')
                    ->label('Номер телефона')
                    ->required(),

                Forms\Components\TextInput::make('work_time')
                    ->label('Время работы')
                    ->required(),

                Forms\Components\TextInput::make('coordinates')
                    ->label('Координаты (широта, долгота)')
                    ->required(),

                // Поле для социальных ссылок
                Forms\Components\Repeater::make('social_links')
                    ->label('Социальные ссылки')
                    ->schema([
                        Forms\Components\TextInput::make('telegram')
                            ->label('Telegram')
                            ->url(),
                        Forms\Components\TextInput::make('whatsapp')
                            ->label('WhatsApp')
                            ->url(),
                        Forms\Components\TextInput::make('telegram_channel')
                            ->label('Telegram канал')
                            ->url(),
                        Forms\Components\TextInput::make('youtube')
                            ->label('YouTube')
                            ->url(),
                        Forms\Components\TextInput::make('drive2')
                            ->label('Drive2')
                            ->url(),
                        Forms\Components\TextInput::make('avito')
                            ->label('Avito')
                            ->url(),
                    ]),

                // Поле для юридической информации
                Forms\Components\Repeater::make('legal_info')
                    ->label('Юридическая информация')
                    ->schema([
                        Forms\Components\TextInput::make('inn')
                            ->label('ИНН')
                            ->required(),
                        Forms\Components\TextInput::make('kpp')
                            ->label('КПП')
                            ->required(),
                        Forms\Components\TextInput::make('ogrn')
                            ->label('ОГРН')
                            ->required(),
                        Forms\Components\TextInput::make('legal_address')
                            ->label('Юридический адрес')
                            ->required(),
                        Forms\Components\TextInput::make('phisical_address')
                            ->label('Физический адрес')
                            ->required(),
                        Forms\Components\TextInput::make('general_director')
                            ->label('Генеральный директор')
                            ->required(),
                        Forms\Components\Textarea::make('footer_tiny_text')
                            ->label('Текст в подвале')
                            ->required(),
                    ]),

                Forms\Components\TextInput::make('url')
                    ->label('URL региона')
                    ->url()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('region_code')->label('Код региона'),
                TextColumn::make('region_name')->label('Название региона'),
                TextColumn::make('address')->label('Адрес'),
                TextColumn::make('phone_number')->label('Номер телефона'),
                TextColumn::make('work_time')->label('Время работы'),
            ])
            ->bulkActions([
                DeleteBulkAction::make()
            ]);
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
