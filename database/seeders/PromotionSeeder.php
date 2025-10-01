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
        // Créer une seule promotion 2023-2026 avec 4 semestres
        $promotion = Promotion::updateOrCreate(
            [
                'diplome_id' => 2, // Licence
                'filiere_id' => 1, // Informatique
                'annee_debut' => 2023,
                'annee_fin' => 2026,
            ],
            [
                'nom' => 'LIC-INFO-23-26',
                'diplome_id' => 2,
                'filiere_id' => 1,
                'annee_debut' => 2023,
                'annee_fin' => 2026,
                'description' => 'Licence Informatique de 2023 à 2026 - Promotion unique avec 4 semestres',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
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
