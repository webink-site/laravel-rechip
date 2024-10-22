<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Http;

class TelegramSettingsPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static string $view = 'filament.pages.telegram-settings-page';
    protected static ?string $navigationGroup = 'Telegram';
    protected static ?string $title = 'Настройки Telegram';

    public function setWebhook()
    {
        $response = Http::asForm()->post("https://api.telegram.org/bot" . config('telegram.bot_token') . "/setWebhook", [
            'url' => config('app.url') . '/telegram/webhook',
        ]);

        if ($response->json('ok')) {
            session()->flash('success', 'Webhook установлен успешно!');
        } else {
            session()->flash('error', 'Ошибка установки webhook: ' . $response->json('description'));
        }

        return redirect()->route('filament.pages.telegram-settings-page');
    }

    protected function getActions(): array
    {
        return [
            //
        ];
    }
}
