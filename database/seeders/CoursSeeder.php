<?php

namespace Database\Seeders;

use App\Models\Cours;
use App\Models\EmploiDuTemps;
use App\Models\Matiere;
use App\Models\Salle;
use App\Models\Semestre;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CoursSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer la promotion 2023-2026
        $promotion = \App\Models\Promotion::where('nom', 'LIC-INFO-23-26')->first();
        if (!$promotion) {
            echo "Promotion LIC-INFO-23-26 non trouvée.\n";
            return;
        }

        // Récupérer tous les semestres de la promotion
        $semestres = Semestre::where('promotion_id', $promotion->id)->get();
        if ($semestres->isEmpty()) {
            echo "Aucun semestre trouvé pour la promotion.\n";
            return;
        }

        // Créer des salles si elles n'existent pas
        $salles = Salle::all();
        if ($salles->isEmpty()) {
            $salles = collect([
                Salle::create([
                    'numero' => 'A101',
                    'capacite' => 50,
                    'en_service' => true,
                    'type' => 'amphi',
                    'description' => 'Amphithéâtre principal'
                ]),
                Salle::create([
                    'numero' => 'A102',
                    'capacite' => 30,
                    'en_service' => true,
                    'type' => 'cours',
                    'description' => 'Salle de cours standard'
                ]),
                Salle::create([
                    'numero' => 'B101',
                    'capacite' => 25,
                    'en_service' => true,
                    'type' => 'td_tp',
                    'description' => 'Salle de travaux pratiques'
                ]),
                Salle::create([
                    'numero' => 'B102',
                    'capacite' => 20,
                    'en_service' => true,
                    'type' => 'laboratoire',
                    'description' => 'Laboratoire informatique'
                ])
            ]);
        }

        $jours = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi'];

        // Horaire selon les spécifications : 7-10 pause 10-11 11-13 cours 13-14 pause 14-18
        $slots = [
            ['07:00', '10:00'], // 7h-10h
            ['10:00', '11:00'], // pause 10h-11h
            ['11:00', '13:00'], // cours 11h-13h
            ['13:00', '14:00'], // pause déjeuner 13h-14h
            ['14:00', '18:00']  // cours 14h-18h
        ];

        echo "Création des emplois du temps pour tous les semestres...\n";
        echo "================================================\n";

        foreach ($semestres as $semestre) {
            // Récupérer l'emploi du temps principal pour ce semestre
            $edt = EmploiDuTemps::where('semestre_id', $semestre->id)
                ->where('categorie', 'principal')
                ->first();

            if (!$edt) {
                echo "Aucun emploi du temps principal trouvé pour le semestre {$semestre->numero}.\n";
                continue;
            }

            // Récupérer les matières de ce semestre
            $matieres = Matiere::where('semestre_id', $semestre->id)
                ->with('unite')
                ->get();

            if ($matieres->isEmpty()) {
                echo "Aucune matière trouvée pour le semestre {$semestre->numero}.\n";
                continue;
            }

            echo "Semestre {$semestre->numero} - {$matieres->count()} matières\n";

            $matiereIndex = 0;
            $salleIndex = 0;

            foreach ($jours as $jour) {
                foreach ($slots as $slotIndex => [$debut, $fin]) {
                    // Pause de 10h-11h et 13h-14h : pas de cours
                    if ($slotIndex == 1 || $slotIndex == 3) {
                        continue;
                    }

                    $matiere = $matieres[$matiereIndex % $matieres->count()];
                    $salle = $salles[$salleIndex % $salles->count()];

                    Cours::updateOrCreate(
                        [
                            'emploi_du_temps_id' => $edt->id,
                            'jour' => $jour,
                            'debut' => $debut,
                            'fin' => $fin,
                        ],
                        [
                            'matiere_id' => $matiere->id,
                            'salle_id' => $salle->id,
                            'type' => 'cours',
                        ]
                    );

                    $matiereIndex++;
                    $salleIndex++;
                }
            }

            echo "  ✓ Emploi du temps créé pour le semestre {$semestre->numero}\n";
        }

        echo "\nTotal des cours créés: " . Cours::count() . "\n";
        echo "================================================\n";
    }
}
