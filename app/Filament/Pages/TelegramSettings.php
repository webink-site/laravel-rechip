<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api as TelegramApi;
use App\Models\TelegramSetting;

class TelegramSettings extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static string $view = 'filament.pages.telegram-settings';
    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $navigationGroup = 'Telegram';
    protected static ?string $navigationLabel = 'Настройки Telegram';
    protected static ?int $navigationSort = 1;

    public $botStartMessage;
    public $botStopMessage;

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Textarea::make('botStartMessage')
                ->label('Сообщение при старте (/start)')
                ->required()
                ->rows(4)
                ->placeholder('Введите сообщение при запуске бота...'),

            Forms\Components\Textarea::make('botStopMessage')
                ->label('Сообщение при остановке (/stop)')
                ->required()
                ->rows(4)
                ->placeholder('Введите сообщение при остановке бота...'),
        ];
    }

    public function mount()
    {
        $setting = TelegramSetting::first();
        if ($setting) {
            $this->form->fill([
                'botStartMessage' => $setting->bot_start_message,
                'botStopMessage' => $setting->bot_stop_message,
            ]);
        }
    }

    public function setWebhook()
    {
        $telegram = new TelegramApi(config('telegram.bot_token'));
        $webhookUrl = config('app.url') . '/telegram/webhook';

        $response = $telegram->setWebhook(['url' => $webhookUrl]);

        Log::info("Set Telegram webhook: " . $response);

        if ($response) {
            Notification::make()
                ->title('Webhook установлен успешно!')
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Ошибка установки webhook')
                ->body($response)
                ->danger()
                ->send();
        }
    }

    public function saveSettings()
    {
        $data = $this->form->getState();

        $setting = TelegramSetting::first();
        if ($setting) {
            $setting->update([
                'bot_start_message' => $data['botStartMessage'],
                'bot_stop_message' => $data['botStopMessage'],
            ]);

            Notification::make()
                ->title('Настройки сохранены успешно!')
                ->success()
                ->send();
        } else {
            TelegramSetting::create([
                'bot_start_message' => $data['botStartMessage'],
                'bot_stop_message' => $data['botStopMessage'],
            ]);

            Notification::make()
                ->title('Настройки созданы и сохранены успешно!')
                ->success()
                ->send();
        }
    }
}