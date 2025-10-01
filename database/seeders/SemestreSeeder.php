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
        // Récupérer la promotion 2023-2026
        $promotion = \App\Models\Promotion::where('nom', 'LIC-INFO-23-26')->first();

        if (!$promotion) {
            return;
        }

        // Créer les 4 semestres pour la promotion 2023-2026
        $semestres = [
            [
                'numero' => 1,
                'slug' => 'semestre-1-lic-info-23-26',
                'date_debut' => '2023-09-01',
                'date_fin' => '2024-01-31'
            ],
            [
                'numero' => 2,
                'slug' => 'semestre-2-lic-info-23-26',
                'date_debut' => '2024-02-01',
                'date_fin' => '2024-06-30'
            ],
            [
                'numero' => 3,
                'slug' => 'semestre-3-lic-info-23-26',
                'date_debut' => '2024-09-01',
                'date_fin' => '2025-01-31'
            ],
            [
                'numero' => 4,
                'slug' => 'semestre-4-lic-info-23-26',
                'date_debut' => '2025-02-01',
                'date_fin' => '2025-06-30'
            ]
        ];

        foreach ($semestres as $semestreData) {
            \App\Models\Semestre::updateOrCreate(
                [
                    'numero' => $semestreData['numero'],
                    'promotion_id' => $promotion->id,
                ],
                array_merge($semestreData, [
                    'promotion_id' => $promotion->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
