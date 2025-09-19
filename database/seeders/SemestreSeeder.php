<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SemestreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer toutes les promotions de licence
        $promotions = \App\Models\Promotion::where('diplome_id', 2)->get();

        foreach ($promotions as $promotion) {
            $anneeActuelle = 2025;
            $agePromotion = $anneeActuelle - $promotion->annee_debut;

            // Déterminer le nombre de semestres selon l'âge de la promotion
            $nombreSemestres = 0;
            if ($agePromotion >= 2) {
                $nombreSemestres = 4; // Promotions de 2 ans ou plus
            } elseif ($agePromotion == 1) {
                $nombreSemestres = 2; // Promotions de 1 an
            }
            // 0 semestre pour les promotions nouvelles (2025)

            // Créer les semestres pour cette promotion
            for ($i = 1; $i <= $nombreSemestres; $i++) {
                // Calculer les dates de début et fin du semestre
                $anneeSemestre = $promotion->annee_debut + (int)(($i - 1) / 2);
                $semestreDansAnnee = (($i - 1) % 2) + 1;

                // Semestre 1: Janvier à Juin, Semestre 2: Septembre à Décembre
                if ($semestreDansAnnee == 1) {
                    $dateDebut = Carbon::create($anneeSemestre, 1, 1);
                    $dateFin = Carbon::create($anneeSemestre, 6, 30);
                } else {
                    $dateDebut = Carbon::create($anneeSemestre, 9, 1);
                    $dateFin = Carbon::create($anneeSemestre, 12, 31);
                }

                \App\Models\Semestre::create([
                    'numero' => $i,
                    'slug' => 'semestre-' . $i . '-' . $promotion->nom,
                    'promotion_id' => $promotion->id,
                    'date_debut' => $dateDebut->toDateString(),
                    'date_fin' => $dateFin->toDateString(),
                ]);
            }
        }
    }
}
