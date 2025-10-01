<?php

namespace Database\Seeders;

use App\Models\Evenement;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EvenementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer un utilisateur pour créer les événements
        $user = User::where('role', 'administrateur')->first();
        if (!$user) {
            $user = User::first();
        }

        if (!$user) {
            echo "Aucun utilisateur trouvé pour créer les événements.\n";
            return;
        }

        // Événements pour ce mois d'octobre 2025
        $evenements = [
            [
                'titre' => 'Rentrée académique 2025-2026',
                'corps' => 'Cérémonie officielle de rentrée pour tous les étudiants de la promotion LIC-INFO-23-26. Présentation du programme, des enseignants et des objectifs de l\'année.',
                'date' => '2025-10-01',
                'heure' => '09:00:00',
                'couleur' => '#3B82F6', // Bleu
            ],
            [
                'titre' => 'Journée portes ouvertes',
                'corps' => 'Découverte des laboratoires informatiques et des équipements. Présentation des projets étudiants et des opportunités de stage.',
                'date' => '2025-10-15',
                'heure' => '14:00:00',
                'couleur' => '#10B981', // Vert
            ],
            [
                'titre' => 'Conférence : Intelligence Artificielle',
                'corps' => 'Conférence donnée par Dr. Marie Dubois sur les dernières avancées en Intelligence Artificielle et Machine Learning. Ouvert à tous les étudiants.',
                'date' => '2025-10-20',
                'heure' => '16:00:00',
                'couleur' => '#8B5CF6', // Violet
            ],
            [
                'titre' => 'Examen de contrôle - Semestre 1',
                'corps' => 'Premier examen de contrôle pour les matières du semestre 1. Algorithmique, Programmation et Mathématiques.',
                'date' => '2025-10-25',
                'heure' => '08:00:00',
                'couleur' => '#EF4444', // Rouge
            ],
            [
                'titre' => 'Fête de l\'Halloween',
                'corps' => 'Soirée festive organisée par l\'association des étudiants. Concours de costumes, jeux et animations. Buffet et boissons disponibles.',
                'date' => '2025-10-31',
                'heure' => '19:00:00',
                'couleur' => '#F59E0B', // Orange
            ]
        ];

        echo "Création des événements du calendrier...\n";
        echo "=====================================\n";

        foreach ($evenements as $evenementData) {
            $evenement = Evenement::create([
                'titre' => $evenementData['titre'],
                'corps' => $evenementData['corps'],
                'date' => $evenementData['date'],
                'heure' => $evenementData['heure'],
                'couleur' => $evenementData['couleur'],
                'created_by' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            echo "✓ Événement créé: {$evenement->titre} - {$evenement->date} à {$evenement->heure}\n";
        }

        echo "\nTotal des événements créés: " . count($evenements) . "\n";
        echo "=====================================\n";
    }
}
