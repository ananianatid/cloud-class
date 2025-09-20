<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Matiere;
use App\Models\Enseignant;
use App\Models\UniteEnseignement;
use App\Models\Semestre;

class MatiereAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "Assignation des matières aux enseignants...\n";
        echo "==========================================\n";

        // Récupérer les enseignants
        $enseignants = Enseignant::with('user')->get();

        if ($enseignants->isEmpty()) {
            echo "Aucun enseignant trouvé. Veuillez d'abord créer les enseignants.\n";
            return;
        }

        // Récupérer quelques semestres
        $semestres = Semestre::take(3)->get();

        if ($semestres->isEmpty()) {
            echo "Aucun semestre trouvé. Veuillez d'abord créer les semestres.\n";
            return;
        }

        // Récupérer quelques unités d'enseignement
        $unites = UniteEnseignement::take(5)->get();

        if ($unites->isEmpty()) {
            echo "Aucune unité d'enseignement trouvée. Veuillez d'abord créer les unités.\n";
            return;
        }

        // Créer des matières et les assigner aux enseignants
        $matieres = [
            [
                'unite_id' => $unites[0]->id,
                'semestre_id' => $semestres[0]->id,
                'enseignant_id' => $enseignants[0]->id,
            ],
            [
                'unite_id' => $unites[0]->id,
                'semestre_id' => $semestres[0]->id,
                'enseignant_id' => $enseignants[0]->id,
            ],
            [
                'unite_id' => $unites[1]->id ?? $unites[0]->id,
                'semestre_id' => $semestres[0]->id,
                'enseignant_id' => $enseignants[1]->id,
            ],
            [
                'unite_id' => $unites[1]->id ?? $unites[0]->id,
                'semestre_id' => $semestres[0]->id,
                'enseignant_id' => $enseignants[1]->id,
            ],
            [
                'unite_id' => $unites[2]->id ?? $unites[0]->id,
                'semestre_id' => $semestres[1]->id ?? $semestres[0]->id,
                'enseignant_id' => $enseignants[2]->id,
            ],
            [
                'unite_id' => $unites[3]->id ?? $unites[0]->id,
                'semestre_id' => $semestres[1]->id ?? $semestres[0]->id,
                'enseignant_id' => $enseignants[3]->id,
            ],
            [
                'unite_id' => $unites[4]->id ?? $unites[0]->id,
                'semestre_id' => $semestres[2]->id ?? $semestres[0]->id,
                'enseignant_id' => $enseignants[4]->id,
            ],
        ];

        foreach ($matieres as $matiereData) {
            $matiere = Matiere::create($matiereData);

            $enseignant = Enseignant::find($matiereData['enseignant_id']);
            $unite = UniteEnseignement::find($matiereData['unite_id']);
            $semestre = Semestre::find($matiereData['semestre_id']);

            echo "Matière créée: ID {$matiere->id}\n";
            echo "  - Enseignant: {$enseignant->user->name}\n";
            echo "  - Unité: {$unite->nom}\n";
            echo "  - Semestre: {$semestre->slug}\n";
            echo "  ----------------------------------------\n";
        }

        echo "\nTotal matières créées: " . count($matieres) . "\n";
        echo "Les enseignants peuvent maintenant voir leurs matières assignées dans le panel Filament.\n";
    }
}
