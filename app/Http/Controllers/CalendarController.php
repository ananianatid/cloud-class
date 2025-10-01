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

        // Grouper les cours par jour de la semaine (pour l'instant, on ne peut pas filtrer par date)
        $coursByDate = $cours->groupBy('jour');

        // Grouper les événements par date
        $evenementsByDate = $evenements->groupBy(function ($evenement) {
            return $evenement->date->format('Y-m-d');
        });

        // Créer le calendrier mensuel
        $calendar = $this->buildCalendar($year, $month, $coursByDate, $evenementsByDate);

        // Données pour la navigation
        $prevMonth = Carbon::create($year, $month, 1)->subMonth();
        $nextMonth = Carbon::create($year, $month, 1)->addMonth();

        return view('pages.calendrier', compact(
            'calendar',
            'evenements',
            'cours',
            'month',
            'year',
            'prevMonth',
            'nextMonth'
        ));
    }

    private function buildCalendar($year, $month, $coursByDate, $evenementsByDate)
    {
        $startOfMonth = Carbon::create($year, $month, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        // Commencer le calendrier au lundi de la première semaine
        $startOfCalendar = $startOfMonth->copy()->startOfWeek(Carbon::MONDAY);
        $endOfCalendar = $endOfMonth->copy()->endOfWeek(Carbon::SUNDAY);

        $calendar = [];
        $current = $startOfCalendar->copy();

        // Mapping des jours de la semaine
        $joursMapping = [
            'lundi' => 1,
            'mardi' => 2,
            'mercredi' => 3,
            'jeudi' => 4,
            'vendredi' => 5,
            'samedi' => 6,
            'dimanche' => 0,
        ];

        while ($current->lte($endOfCalendar)) {
            $week = [];

            for ($i = 0; $i < 7; $i++) {
                $date = $current->copy();
                $dateString = $date->format('Y-m-d');

                // Trouver les cours pour ce jour de la semaine
                $dayOfWeek = $date->dayOfWeek;
                $coursDuJour = collect();

                foreach ($coursByDate as $jour => $cours) {
                    if (isset($joursMapping[$jour]) && $joursMapping[$jour] === $dayOfWeek) {
                        $coursDuJour = $cours;
                        break;
                    }
                }

                $dayData = [
                    'date' => $date,
                    'isCurrentMonth' => $date->month === $month,
                    'isToday' => $date->isToday(),
                    'cours' => $coursDuJour,
                    'evenements' => $evenementsByDate->get($dateString, collect()),
                ];

                $week[] = $dayData;
                $current->addDay();
            }

            $calendar[] = $week;
        }

        return $calendar;
    }

    public function showEvent(Evenement $evenement)
    {
        return view('pages.evenement-detail', compact('evenement'));
    }
}
