<?php

namespace App\Filament\Widgets;

use App\Models\Auto;
use App\Models\Contact;
use App\Models\TelegramRequest;
use Filament\Forms\Components\Grid;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;

class CountStats extends BaseWidget
{
    protected static ?string $heading = 'Статистика заявок';

    protected function getCards(): array
    {
        return [
            Card::make('Всего заявок', TelegramRequest::count())
                ->icon('heroicon-c-inbox-arrow-down'),
            Card::make('Всего авто', Auto::count())
                ->icon('heroicon-c-truck'),
            Card::make('Всего регионов', Contact::count())
                ->icon('heroicon-c-star'),
        ];
    }
}