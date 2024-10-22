<?php

namespace App\Providers;

use App\Services\TelegramService;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(TelegramService::class, function ($app) {
            return new TelegramService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //

        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['ru','en']); // also accepts a closure
        });
    }
}
