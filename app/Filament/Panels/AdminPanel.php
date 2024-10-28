<?php

namespace App\Filament\Panels;

use App\Filament\Widgets\CountStats;
use App\Filament\Widgets\TelegramRequestStats;
use Filament\Panel;

class AdminPanel extends Panel
{
    /**
     * Get the widgets for the panel.
     *
     * @return array
     */
    protected function getWidgets(): array
    {
        return [
            CountStats::class, // Добавляем наш виджет с фильтрацией
            TelegramRequestStats::class, // Добавляем наш виджет с фильтрацией
        ];
    }
}
