<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cours;
use App\Models\EmploiDuTemps;
use App\Models\Matiere;
use App\Models\Salle;
use Carbon\Carbon;

class CoursTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "Création des cours de test pour l'emploi du temps...\n";
        echo "================================================\n";

        // Récupérer les matières des enseignants
        $matieres = Matiere::with(['enseignant.user', 'unite', 'semestre.promotion'])->get();

        if ($matieres->isEmpty()) {
            echo "Aucune matière trouvée. Veuillez d'abord créer les matières.\n";
            return;
        }

        // Récupérer les salles
        $salles = Salle::all();

        if ($salles->isEmpty()) {
            echo "Aucune salle trouvée. Création d'une salle de test...\n";
            $salle = Salle::create([
                'numero' => 'S001',
                'capacite' => 50,
                'type' => 'Amphithéâtre',
                'description' => 'Salle de test pour les cours',
            ]);
            $salles = collect([$salle]);
        }

        // Récupérer ou créer un emploi du temps actif
        $emploiDuTemps = EmploiDuTemps::where('actif', true)->first();

        if (!$emploiDuTemps) {
            echo "Création d'un emploi du temps actif...\n";
            // Récupérer un semestre pour l'emploi du temps
            $semestre = \App\Models\Semestre::first();

            if (!$semestre) {
                echo "Aucun semestre trouvé. Impossible de créer l'emploi du temps.\n";
                return;
            }

            $emploiDuTemps = EmploiDuTemps::create([
                'semestre_id' => $semestre->id,
                'nom' => 'Emploi du Temps 2025',
                'categorie' => 'principal',
                'actif' => true,
                'debut' => now()->startOfYear()->toDateString(),
                'fin' => now()->endOfYear()->toDateString(),
                'descrpition' => 'Emploi du temps pour l\'année 2025',
            ]);
        }

        // Jours de la semaine (en minuscules)
        $jours = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi'];

        // Types de cours
        $types = ['cours', 'td&tp', 'evaluation', 'devoir', 'examen'];

        // Heures de cours
        $heures = [
            ['08:00', '10:00'],
            ['10:15', '12:15'],
            ['14:00', '16:00'],
            ['16:15', '18:15'],
        ];

        $coursCrees = 0;

        foreach ($matieres as $matiere) {
            // Créer 2-4 cours par matière
            $nombreCours = rand(2, 4);

            for ($i = 0; $i < $nombreCours; $i++) {
                $jour = $jours[array_rand($jours)];
                $heure = $heures[array_rand($heures)];
                $type = $types[array_rand($types)];
                $salle = $salles->random();

                $cours = Cours::create([
                    'matiere_id' => $matiere->id,
                    'emploi_du_temps_id' => $emploiDuTemps->id,
                    'salle_id' => $salle->id,
                    'jour' => $jour,
                    'debut' => $heure[0],
                    'fin' => $heure[1],
                    'type' => $type,
                ]);

                $coursCrees++;

                echo "Cours créé: {$matiere->unite->nom} - {$jour} {$heure[0]}-{$heure[1]} ({$type})\n";
                echo "  - Enseignant: {$matiere->enseignant->user->name}\n";
                echo "  - Promotion: {$matiere->semestre->promotion->nom}\n";
                echo "  - Salle: {$salle->numero}\n";
                echo "  ----------------------------------------\n";
            }
        }

        echo "\nTotal cours créés: {$coursCrees}\n";
        echo "Emploi du temps: {$emploiDuTemps->nom}\n";
        echo "Les enseignants peuvent maintenant voir leur emploi du temps dans le dashboard.\n";
    }
}
