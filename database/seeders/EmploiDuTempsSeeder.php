<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Semestre;
use App\Models\EmploiDuTemps;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EmploiDuTempsSeeder extends Seeder
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

        // Dates importantes pour l'année académique 2023-2024 et 2024-2025
        $datesImportantes = [
            // Année 2023-2024
            '2023-09-01' => 'Rentrée académique 2023-2024',
            '2023-10-15' => 'Devoir de contrôle - Semestre 1',
            '2023-11-15' => 'Devoir de contrôle - Semestre 1',
            '2023-12-20' => 'Vacances de Noël',
            '2024-01-08' => 'Rentrée après vacances de Noël',
            '2024-01-15' => 'Examen Semestre 1',
            '2024-01-31' => 'Fin Semestre 1',
            '2024-02-01' => 'Début Semestre 2',
            '2024-03-15' => 'Devoir de contrôle - Semestre 2',
            '2024-04-15' => 'Devoir de contrôle - Semestre 2',
            '2024-04-20' => 'Vacances de Pâques',
            '2024-04-30' => 'Rentrée après vacances de Pâques',
            '2024-05-15' => 'Examen Semestre 2',
            '2024-06-30' => 'Fin Semestre 2',
            '2024-07-01' => 'Vacances d\'été',

            // Année 2024-2025
            '2024-09-01' => 'Rentrée académique 2024-2025',
            '2024-10-15' => 'Devoir de contrôle - Semestre 3',
            '2024-11-15' => 'Devoir de contrôle - Semestre 3',
            '2024-12-20' => 'Vacances de Noël',
            '2025-01-08' => 'Rentrée après vacances de Noël',
            '2025-01-15' => 'Examen Semestre 3',
            '2025-01-31' => 'Fin Semestre 3',
            '2025-02-01' => 'Début Semestre 4',
            '2025-03-15' => 'Devoir de contrôle - Semestre 4',
            '2025-04-15' => 'Devoir de contrôle - Semestre 4',
            '2025-04-20' => 'Vacances de Pâques',
            '2025-04-30' => 'Rentrée après vacances de Pâques',
            '2025-05-15' => 'Examen Semestre 4',
            '2025-06-30' => 'Fin Semestre 4',

            // Fêtes et jours fériés
            '2023-12-25' => 'Noël',
            '2024-01-01' => 'Jour de l\'An',
            '2024-04-01' => 'Lundi de Pâques',
            '2024-05-01' => 'Fête du Travail',
            '2024-05-08' => 'Victoire 1945',
            '2024-05-20' => 'Ascension',
            '2024-05-31' => 'Pentecôte',
            '2024-07-14' => 'Fête Nationale',
            '2024-08-15' => 'Assomption',
            '2024-11-01' => 'Toussaint',
            '2024-11-11' => 'Armistice',
            '2024-12-25' => 'Noël',
            '2025-01-01' => 'Jour de l\'An',
            '2025-04-21' => 'Lundi de Pâques',
            '2025-05-01' => 'Fête du Travail',
            '2025-05-08' => 'Victoire 1945',
            '2025-05-29' => 'Ascension',
            '2025-06-09' => 'Pentecôte',
            '2025-07-14' => 'Fête Nationale',
            '2025-08-15' => 'Assomption',
            '2025-11-01' => 'Toussaint',
            '2025-11-11' => 'Armistice',
            '2025-12-25' => 'Noël'
        ];

        // Créer les emplois du temps pour chaque semestre
        foreach ($semestres as $semestre) {
            $promotionName = $promotion->nom;

            // Emploi du temps principal
            $nomPrincipal = Str::slug($promotionName . '-' . $semestre->slug . '-principal');
            EmploiDuTemps::updateOrCreate(
                ['nom' => $nomPrincipal],
                [
                    'semestre_id' => $semestre->id,
                    'categorie' => 'principal',
                    'actif' => true,
                    'debut' => $semestre->date_debut,
                    'fin' => $semestre->date_fin,
                    'descrpition' => "Emploi du temps principal - Semestre {$semestre->numero}",
                ]
            );

            // Emploi du temps des examens
            $nomExamen = Str::slug($promotionName . '-' . $semestre->slug . '-examen');
            $dateExamen = $this->getDateExamen($semestre->numero);
            EmploiDuTemps::updateOrCreate(
                ['nom' => $nomExamen],
                [
                    'semestre_id' => $semestre->id,
                    'categorie' => 'examen',
                    'actif' => false,
                    'debut' => $dateExamen,
                    'fin' => Carbon::parse($dateExamen)->addDays(7)->toDateString(),
                    'descrpition' => "Période d'examens - Semestre {$semestre->numero}",
                ]
            );

            // Emploi du temps des devoirs
            $nomDevoir = Str::slug($promotionName . '-' . $semestre->slug . '-devoir');
            $dateDevoir = $this->getDateDevoir($semestre->numero);
                EmploiDuTemps::updateOrCreate(
                ['nom' => $nomDevoir],
                    [
                        'semestre_id' => $semestre->id,
                    'categorie' => 'devoir',
                    'actif' => false,
                    'debut' => $dateDevoir,
                    'fin' => Carbon::parse($dateDevoir)->addDays(3)->toDateString(),
                    'descrpition' => "Période de devoirs - Semestre {$semestre->numero}",
                ]
            );
        }
    }

    /**
     * Obtenir la date d'examen selon le semestre
     */
    private function getDateExamen(int $numeroSemestre): string
    {
        return match ($numeroSemestre) {
            1 => '2024-01-15',
            2 => '2024-05-15',
            3 => '2025-01-15',
            4 => '2025-05-15',
            default => '2024-01-15',
        };
    }

    /**
     * Obtenir la date de devoir selon le semestre
     */
    private function getDateDevoir(int $numeroSemestre): string
    {
        return match ($numeroSemestre) {
            1 => '2023-10-15',
            2 => '2024-03-15',
            3 => '2024-10-15',
            4 => '2025-03-15',
            default => '2023-10-15',
        };
    }
}
