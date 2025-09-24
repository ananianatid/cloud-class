<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Enseignant;

class EnseignantsStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Enseignants',
                \Illuminate\Support\Facades\Cache::remember(
                    'enseignants_count',
                    300, // 5 minutes de cache
                    fn () => Enseignant::count()
                )
            ),
        ];
    }
}
