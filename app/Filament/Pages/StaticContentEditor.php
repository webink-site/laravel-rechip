<?php

namespace App\Filament\Pages;

use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page as BasePage;

class StaticContentEditor extends BasePage implements HasForms
{
    use Forms\Concerns\InteractsWithForms;

    public $robots;
    public $offer;
    public $terms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Статический контент';
    protected static ?string $navigationLabel = 'Статические файлы';
    protected static string $view = 'filament.pages.static-content-editor';

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Textarea::make('robots')
                ->label('Robots.txt')
                ->rows(10)
                ->required(),
            Forms\Components\RichEditor::make('offer')
                ->label('Публичная офферта')
                ->required(),
            Forms\Components\RichEditor::make('terms')
                ->label('Пользовательское соглашение')
                ->required(),
        ];
    }

    public function mount(): void
    {
        // Загружаем или создаём записи по slug
        $robotsPage = Page::firstOrCreate(
            ['slug' => 'robots.txt'],
            ['title' => 'Robots.txt', 'content' => "User-agent: *\nDisallow:"]
        );
        $offerPage = Page::firstOrCreate(
            ['slug' => 'offer'],
            ['title' => 'Публичная офферта', 'content' => "<h1>Публичная офферта</h1><p>Текст офферты...</p>"]
        );
        $termsPage = Page::firstOrCreate(
            ['slug' => 'terms'],
            ['title' => 'Пользовательское соглашение', 'content' => "<h1>Пользовательское соглашение</h1><p>Текст соглашения...</p>"]
        );

        $this->robots = $robotsPage->content;
        $this->offer = $offerPage->content;
        $this->terms = $termsPage->content;

        $this->form->fill([
            'robots' => $this->robots,
            'offer'  => $this->offer,
            'terms'  => $this->terms,
        ]);
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        Page::updateOrCreate(
            ['slug' => 'robots.txt'],
            ['title' => 'Robots.txt', 'content' => $data['robots']]
        );
        Page::updateOrCreate(
            ['slug' => 'offer'],
            ['title' => 'Публичная офферта', 'content' => $data['offer']]
        );
        Page::updateOrCreate(
            ['slug' => 'terms'],
            ['title' => 'Пользовательское соглашение', 'content' => $data['terms']]
        );

        Notification::make()
            ->title('Контент обновлён')
            ->success()
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            Forms\Components\Actions\ButtonAction::make('submit')
                ->label('Сохранить')
                ->action('submit'),
        ];
    }
}