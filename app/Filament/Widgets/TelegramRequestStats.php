<?php

namespace App\Filament\Widgets;

use App\Models\TelegramRequest;
use Filament\Forms\Components\Grid;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;

class TelegramRequestStats extends BaseWidget
{
    protected static ?string $heading = 'Статистика заявок';

    protected function getCards(): array
    {
        return [
            Card::make('Всего заявок', '192.1k'),
            Card::make('Всего авто', '21%'),
            Card::make('Всего регионов', '3:12'),
        ];
    }

    protected function getStats(): array
    {
        // Применение фильтрации
        $query = TelegramRequest::query();

        // Статистика по каждому статусу
        return [
            Stat::make('Новые заявки', $query->where('status', 'new')->count())
                ->description('Заявки со статусом "new"')
                ->chart([0, $query->where('status', 'new')->count()])
                ->color('success'),

            Stat::make('Заявки с ошибками', $query->where('status', 'error')->count())
                ->description('Заявки со статусом "error"')
                ->chart([0, $query->where('status', 'error')->count()])
                ->color('danger'),

            Stat::make('Заявки в спам', $query->where('status', 'spam')->count())
                ->description('Заявки со статусом "spam"')
                ->chart([0, $query->where('status', 'spam')->count()])
                ->color('warning'),

            Stat::make('Завершенные заявки', $query->where('status', 'completed')->count())
                ->description('Заявки со статусом "completed"')
                ->chart([0, $query->where('status', 'completed')->count()])
                ->color('success'),
        ];
    }
}