<?php

namespace App\Filament\Widgets;

use App\Models\Catalog;
use App\Models\Contact;
use App\Models\TelegramRequest;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class CountStats extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Всего заявок', TelegramRequest::count())
                ->icon('heroicon-c-inbox-arrow-down'),
            Card::make('Всего записей в каталоге', Catalog::count())
                ->icon('heroicon-c-truck'),
            Card::make('Всего регионов', Contact::count())
                ->icon('heroicon-c-star'),
        ];
    }
}