<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Matiere;

class MatieresStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Matieres',
                \Illuminate\Support\Facades\Cache::remember(
                    'matieres_count',
                    300, // 5 minutes de cache
                    fn () => Matiere::count()
                )
            ),
        ];
    }
}
