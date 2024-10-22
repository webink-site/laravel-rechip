<x-filament::page>
    <div class="space-y-4">
        <h1 class="text-2xl font-bold">Настройки Telegram</h1>

        @if (session()->has('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-red-100 text-red-700 p-4 rounded">
                {{ session('error') }}
            </div>
        @endif

        <button wire:click="setWebhook" class="px-4 py-2 bg-blue-600 text-white rounded">Установить Webhook</button>

        <div class="mt-4">
            <h2 class="text-xl font-semibold">Информация о боте</h2>
            @php
                $telegram = new \Telegram\Bot\Api(config('telegram.bot_token'));
                $botInfo = $telegram->getMe();
            @endphp
            <p><strong>ID:</strong> {{ $botInfo->getId() }}</p>
            <p><strong>Имя:</strong> {{ $botInfo->getFirstName() }}</p>
            <p><strong>Юзернейм:</strong> @{{ $botInfo->getUsername() }}</p>
        </div>
    </div>
</x-filament::page>
