<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use App\Models\Cours;
use App\Models\EmploiDuTemps;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        // Récupérer le mois et l'année depuis la requête ou utiliser la date actuelle
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        // Créer la date de début et fin du mois
        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
        $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth();

        // Récupérer tous les événements du mois
        $evenements = Evenement::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->orderBy('date')
            ->orderBy('heure')
            ->get();

        // Récupérer tous les cours du mois (depuis les emplois du temps actifs)
        $cours = collect();
        $emploisActifs = EmploiDuTemps::where('actif', true)->get();

        foreach ($emploisActifs as $edt) {
            $coursDuMois = Cours::with(['matiere.unite', 'matiere.enseignant.user', 'salle'])
                ->where('emploi_du_temps_id', $edt->id)
                ->get();

            $cours = $cours->merge($coursDuMois);
        }

        // Grouper les événements par date pour l'affichage
        $evenementsByDate = $evenements->groupBy(function ($evenement) {
            return $evenement->date->format('Y-m-d');
        });

        // Données pour la navigation
        $prevMonth = Carbon::create($year, $month, 1)->subMonth();
        $nextMonth = Carbon::create($year, $month, 1)->addMonth();

        // Générer les options d'années (5 ans avant et après l'année actuelle)
        $currentYear = now()->year;
        $years = range($currentYear - 5, $currentYear + 5);

        return view('pages.calendrier', compact(
            'evenements',
            'evenementsByDate',
            'cours',
            'month',
            'year',
            'prevMonth',
            'nextMonth',
            'years',
            'currentYear'
        ));
    }


    public function showEvent(Evenement $evenement)
    {
        return view('pages.evenement-detail', compact('evenement'));
    }
}
