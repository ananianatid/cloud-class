<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class TeacherStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $user = Auth::user();

        // Vérifier si c'est un enseignant
        if (!$user || $user->role !== 'enseignant') {
            return [];
        }

        $enseignant = \App\Models\Enseignant::where('user_id', $user->id)->first();

        if (!$enseignant) {
            return [];
        }

        // Compter les fichiers uploadés par l'enseignant
        $fichiersCount = \App\Models\Fichier::whereHas('matiere', function($q) use ($enseignant) {
            $q->where('enseignant_id', $enseignant->id);
        })->count();

        // Compter les matières totales assignées à l'enseignant
        $matieresTotalCount = \App\Models\Matiere::where('enseignant_id', $enseignant->id)->count();

        // Compter les matières actives cette année (2025)
        $matieresActivesCount = \App\Models\Matiere::where('enseignant_id', $enseignant->id)
            ->whereHas('semestre', function($q) {
                $q->where('date_debut', '<=', now())
                  ->where('date_fin', '>=', now());
            })->count();

        // Compter les cours dans l'emploi du temps actif
        $coursActifsCount = \App\Models\Cours::whereHas('matiere', function($q) use ($enseignant) {
            $q->where('enseignant_id', $enseignant->id);
        })->whereHas('emploiDuTemps', function($q) {
            $q->where('actif', true);
        })->count();

        return [
            Stat::make('Fichiers Uploadés', $fichiersCount)
                ->description('Total des fichiers partagés')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('success'),

            Stat::make('Matières Totales', $matieresTotalCount)
                ->description('Toutes les matières assignées')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('info'),

            Stat::make('Matières Actives', $matieresActivesCount)
                ->description('Matières de cette année')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('warning'),

            Stat::make('Cours Actifs', $coursActifsCount)
                ->description('Cours dans l\'emploi du temps')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('primary'),
        ];
    }
}
