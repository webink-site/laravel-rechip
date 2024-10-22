<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Telegram\Bot\Api as TelegramApi;

class SetTelegramWebhook extends Command
{
    protected $signature = 'telegram:set-webhook';
    protected $description = 'Установить вебхук для Telegram бота';

    public function handle()
    {
        $telegram = new TelegramApi(config('telegram.bot_token'));
        $webhookUrl = config('app.url') . '/telegram/webhook';

        $response = $telegram->setWebhook(['url' => $webhookUrl]);

        if ($response->getOk()) {
            $this->info('Webhook установлен успешно!');
        } else {
            $this->error('Ошибка установки webhook: ' . $response->getDescription());
        }
    }
}
