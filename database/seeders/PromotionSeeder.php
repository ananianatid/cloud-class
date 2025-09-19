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

        // ID du diplôme Licence (d'après DiplomeSeeder, la licence a l'ID 2)
        $diplomeLicenceId = 2;

        $promotions = [];

        // Créer des promotions de licence pour chaque filière
        foreach ($filieres as $filiere) {
            // Vague 2023-2026
            $promotions[] = [
                'nom' => 'Licence-' . $filiere->code . '-2023-2026',
                'diplome_id' => $diplomeLicenceId,
                'filiere_id' => $filiere->id,
                'annee_debut' => 2023,
                'annee_fin' => 2026,
                'description' => 'Licence ' . $filiere->nom . ' de 2023 à 2026',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Vague 2024-2027
            $promotions[] = [
                'nom' => 'Licence-' . $filiere->code . '-2024-2027',
                'diplome_id' => $diplomeLicenceId,
                'filiere_id' => $filiere->id,
                'annee_debut' => 2024,
                'annee_fin' => 2027,
                'description' => 'Licence ' . $filiere->nom . ' de 2024 à 2027',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Vague 2025-2028
            $promotions[] = [
                'nom' => 'Licence-' . $filiere->code . '-2025-2028',
                'diplome_id' => $diplomeLicenceId,
                'filiere_id' => $filiere->id,
                'annee_debut' => 2025,
                'annee_fin' => 2028,
                'description' => 'Licence ' . $filiere->nom . ' de 2025 à 2028',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Promotion::insert($promotions);
    }
}
