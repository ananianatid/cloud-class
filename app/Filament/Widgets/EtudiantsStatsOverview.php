<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Etudiant;

class EtudiantsStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Etudiants',
                \Illuminate\Support\Facades\Cache::remember(
                    'etudiants_count',
                    300, // 5 minutes de cache
                    fn () => Etudiant::count()
                )
            ),
        ];
    }
}
