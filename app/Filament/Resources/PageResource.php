<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use FilamentTiptapEditor\TiptapEditor;
use PHPUnit\Metadata\Group;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;
    protected static ?string $modelLabel = "Страницы";
    protected static ?string $pluralModelLabel = "Страницы";
    protected static ?string $navigationGroup = 'Информация';
    protected static ?int $navigationSort = 0;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    // Настройка формы для редактирования страниц
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Название страницы')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Grid::make(1)
                    ->schema([
                        Forms\Components\Textarea::make('content.header')
                            ->label('Заголовок')
                            ->required()
                            ->visible(fn (?Page $record) => $record?->title === 'Сотрудничество'), // Проверяем, что $record существует

                        Forms\Components\Textarea::make('content.subheader')
                            ->label('Подзаголовок')
                            ->required()
                            ->visible(fn (?Page $record) => $record?->title === 'Сотрудничество'),

                        TiptapEditor::make('content.main_text')
                            ->label('Основной текст')
                            ->visible(fn (?Page $record) => $record?->title === 'Сотрудничество'),

                        TiptapEditor::make('content.second_text')
                            ->label('Дополнительный текст')
                            ->visible(fn (?Page $record) => $record?->title === 'Сотрудничество'),
                    ])
                    ->visible(fn (?Page $record) => $record?->title === 'Сотрудничество'),

                Forms\Components\Grid::make(1)
                    ->schema([
                        Forms\Components\Repeater::make('content.advantages')
                            ->label('Преимущества')
                            ->schema([
                                Forms\Components\FileUpload::make('icon')
                                    ->label('Иконка (URL)')
                                    ->required(),
                                Forms\Components\TextInput::make('header')
                                    ->label('Заголовок преимущества')
                                    ->required(),
                                Forms\Components\Textarea::make('description')
                                    ->label('Описание преимущества')
                                    ->required(),
                            ])
                            ->visible(fn (?Page $record) => $record?->title === 'Главная'), // Проверяем, что $record существует
                    ])
                    ->visible(fn (?Page $record) => $record?->title === 'Главная'), // Проверяем, что $record существует,

                Forms\Components\Section::make("Форма")
                    ->schema([
                        Forms\Components\Textarea::make('content.form_text.header')
                            ->label('Заголовок формы')
                            ->required()
                            ->visible(fn (?Page $record) => $record?->title === 'Главная'),

                        Forms\Components\Textarea::make('content.form_text.subheader')
                            ->label('Подзаголовок формы')
                            ->required()
                            ->visible(fn (?Page $record) => $record?->title === 'Главная'),

                        Forms\Components\Textarea::make('content.form_text.near_button_text')
                            ->label('Текст около кнопки')
                            ->required()
                            ->visible(fn (?Page $record) => $record?->title === 'Главная'),
                    ])
                    ->visible(fn (?Page $record) => $record?->title === 'Главная'),
            ]);
    }

    // Настройка таблицы для отображения списка страниц
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Название страницы'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
