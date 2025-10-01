<?php

namespace Database\Seeders;

use App\Models\Matiere;
use App\Models\UniteEnseignement;
use App\Models\Semestre;
use App\Models\Enseignant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MatiereSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer la promotion 2023-2026
        $promotion = \App\Models\Promotion::where('nom', 'LIC-INFO-23-26')->first();
        if (!$promotion) {
            return;
        }

        // Récupérer les semestres
        $semestres = Semestre::where('promotion_id', $promotion->id)->get();
        if ($semestres->isEmpty()) {
            return;
        }

        // Récupérer les enseignants
        $enseignants = Enseignant::all();
        if ($enseignants->isEmpty()) {
            return;
        }

        // Les unités d'enseignement seront créées pour chaque matière

        // Matières par semestre - on va créer des unités d'enseignement pour chaque matière
        $matieresParSemestre = [
            1 => [ // Semestre 1 - 2023-2024
                'Algorithmique',
                'Français',
                'Programmation',
                'Épreuves',
                'Électronique Général',
                'Analyse',
                'IHM',
                'ATO',
                'DEFI Circuit',
                'Système d\'exploitation'
            ],
            2 => [ // Semestre 2 - 2023-2024
                'Algèbre',
                'POO',
                'Programmation-web',
                'Anglais'
            ],
            3 => [ // Semestre 3 - 2024-2025
                'Environnements de télécommunication',
                'Probabilités et statistiques'
            ],
            4 => [ // Semestre 4 - 2024-2025
                'Algorithmique et complexité',
                'Recherche Opérationnelle',
                'Recueil de cours ATO et traitement du signal',
                'Évolutivité des réseaux CCNA3',
                'Applications de bureau et designs patterns',
                'Les réseaux d\'accès filiaires',
                'Analyse Numérique',
                'Initiation au Génie Logiciel'
            ]
        ];

        $enseignantIndex = 0;

        foreach ($semestres as $semestre) {
            $matieres = $matieresParSemestre[$semestre->numero] ?? [];

            foreach ($matieres as $nomMatiere) {
                // Assigner un enseignant (rotation pour avoir 1 prof pour 1-3 matières)
                $enseignant = $enseignants[$enseignantIndex % $enseignants->count()];
                $enseignantIndex++;

                // Créer une unité d'enseignement pour cette matière
                $unite = \App\Models\UniteEnseignement::create([
                    'nom' => $nomMatiere,
                    'code' => strtoupper(substr(str_replace(' ', '', $nomMatiere), 0, 6)),
                    'credits' => 3,
                    'description' => "Unité d'enseignement - {$nomMatiere}",
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Créer la matière
                Matiere::create([
                    'unite_id' => $unite->id,
                    'semestre_id' => $semestre->id,
                    'enseignant_id' => $enseignant->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
