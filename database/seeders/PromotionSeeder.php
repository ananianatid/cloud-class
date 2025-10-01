<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Promotion;
class PromotionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer toutes les filières
        $filieres = \App\Models\Filiere::all();

        // Récupérer tous les diplômes
        $diplomes = \App\Models\Diplome::all();

        $promotions = [];

        // Créer des promotions pour chaque combinaison filière/diplôme
        foreach ($filieres as $filiere) {
            foreach ($diplomes as $diplome) {
                // Déterminer la durée du diplôme
                $duree = $this->getDiplomeDuree($diplome->nom);

                // Créer des promotions pour les 3 dernières années
                for ($i = 0; $i < 3; $i++) {
                    $anneeDebut = now()->year - 2 + $i;
                    $anneeFin = $anneeDebut + $duree - 1;

                    // Générer le nom de la promotion
                    $nom = $this->generatePromotionName($diplome, $filiere, $anneeDebut, $anneeFin);

                    $promotions[] = [
                        'nom' => $nom,
                        'diplome_id' => $diplome->id,
                        'filiere_id' => $filiere->id,
                        'annee_debut' => $anneeDebut,
                        'annee_fin' => $anneeFin,
                        'description' => $diplome->nom . ' ' . $filiere->nom . ' de ' . $anneeDebut . ' à ' . $anneeFin,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        // Insérer les promotions en évitant les doublons
        foreach ($promotions as $promotion) {
            Promotion::updateOrCreate(
                [
                    'diplome_id' => $promotion['diplome_id'],
                    'filiere_id' => $promotion['filiere_id'],
                    'annee_debut' => $promotion['annee_debut'],
                    'annee_fin' => $promotion['annee_fin'],
                ],
                $promotion
            );
        }
    }

    /**
     * Détermine la durée d'un diplôme en années
     */
    private function getDiplomeDuree(string $diplomeNom): int
    {
        return match (strtolower($diplomeNom)) {
            'licence' => 3,
            'master' => 2,
            'doctorat' => 3,
            'bts' => 2,
            'dut' => 2,
            default => 3,
        };
    }

    /**
     * Génère le nom d'une promotion
     */
    private function generatePromotionName($diplome, $filiere, int $anneeDebut, int $anneeFin): string
    {
        $diplomeCode = strtoupper(substr($diplome->nom, 0, 3));
        $filiereCode = strtoupper($filiere->code);
        $start = substr((string)$anneeDebut, -2);
        $end = substr((string)$anneeFin, -2);

        return sprintf('%s-%s-%s-%s', $diplomeCode, $filiereCode, $start, $end);
    }
}
