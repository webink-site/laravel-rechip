<?php
namespace App\Providers;

use App\Filament\Pages\TelegramSettings;
use Filament\Facades\Filament;
use Illuminate\Support\ServiceProvider;

class FilamentServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Регистрация пользовательской страницы
        //Filament::registerPages([
        //    TelegramSettings::class,
        //]);
    }
}