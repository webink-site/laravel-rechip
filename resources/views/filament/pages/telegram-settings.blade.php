<!-- resources/views/filament/pages/telegram-settings.blade.php -->
<?php
use Telegram\Bot\Api as TelegramApi;
$telegram = new TelegramApi(config('telegram.bot_token'));
$botInfo = $telegram->getMe();

// Получение информации о webhook
try {
    $webhookInfoResponse = json_decode(file_get_contents("https://api.telegram.org/bot" . config('telegram.bot_token') . "/getWebhookInfo"), true);
    $webhookInfo = $webhookInfoResponse['ok'] ? $webhookInfoResponse['result'] : $webhookInfoResponse;
} catch (\Exception $e) {
    $webhookInfo = [];
}
?>
<x-filament::page>
    <div class="space-y-6">
        <!-- Управление Webhook -->
        <x-filament::card>
            <x-slot name="header">
                <h2 class="text-xl font-semibold">Управление Webhook</h2>
            </x-slot>

            <div class="flex items-center justify-between">
                <div>
                    <p>
                        <strong>Статус Webhook:</strong>
                        @if(isset($webhookInfo['url']) && !empty($webhookInfo['url']))
                            <span class="text-green-600" style="color:green">• Активен</span>
                        @else
                            <span class="text-red-600" style="color:red">• Неактивен</span>
                        @endif
                    </p>
                    <p>
                        <strong>Необработанные обновления:</strong> {{ $webhookInfo['pending_update_count'] ?? 0 }}
                    </p>
                </div>
                <div>
                    <x-filament::button color="primary" wire:click="setWebhook">
                        Установить Webhook
                    </x-filament::button>
                </div>
            </div>
        </x-filament::card>

        <!-- Информация о боте -->
        <x-filament::card>
            <x-slot name="header">
                <h2 class="text-xl font-semibold">Информация о боте</h2>
            </x-slot>

            <div class="space-y-2">
                <p><strong>ID:</strong> {{ $botInfo->getId() }}</p>
                <p><strong>Имя:</strong> {{ $botInfo->getFirstName() }}</p>
                <p><strong>Юзернейм:</strong> {{ '@' . $botInfo->getUsername() }}</p>
            </div>
        </x-filament::card>

        <!-- Форма для настройки сообщений бота -->
        <x-filament::card>
            <x-slot name="header">
                <h2 class="text-xl font-semibold">Настройки сообщений</h2>
            </x-slot>

            {{ $this->form }}

            <div class="flex justify-end mt-4" style="margin-top: 20px">
                <x-filament::button type="submit" color="success" wire:click="saveSettings">
                    Сохранить Настройки
                </x-filament::button>
            </div>
        </x-filament::card>
    </div>
</x-filament::page>
