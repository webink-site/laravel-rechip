<?php
namespace App\Providers;

use App\Filament\Pages\TelegramSettingsPage;
use Filament\Facades\Filament;
use Illuminate\Support\ServiceProvider;


class FilamentServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Filament::serving(function () {
            Filament::registerPages([
                TelegramSettingsPage::class,
            ]);
        });
    }
}
