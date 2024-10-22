<?php
use App\Filament\Pages\TelegramSettingsPage;
use Filament\Facades\Filament;

public function boot()
{
    Filament::serving(function () {
        Filament::registerPages([
            TelegramSettingsPage::class,
        ]);
    });
}
