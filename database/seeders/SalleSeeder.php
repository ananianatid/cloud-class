<?php

namespace Database\Seeders;

use App\Models\Salle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SalleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "Création des salles...\n";
        echo "========================\n";

        $salles = [
            // Salles de cours principales
            ['numero' => 'A-101', 'capacite' => 50, 'en_service' => true, 'type' => 'cours', 'description' => 'Salle de cours principale - Bâtiment A'],
            ['numero' => 'A-102', 'capacite' => 45, 'en_service' => true, 'type' => 'cours', 'description' => 'Salle de cours - Bâtiment A'],
            ['numero' => 'A-103', 'capacite' => 40, 'en_service' => true, 'type' => 'cours', 'description' => 'Salle de cours - Bâtiment A'],
            ['numero' => 'A-201', 'capacite' => 60, 'en_service' => true, 'type' => 'cours', 'description' => 'Amphithéâtre - Bâtiment A'],
            ['numero' => 'A-202', 'capacite' => 55, 'en_service' => true, 'type' => 'cours', 'description' => 'Salle de cours - Bâtiment A'],
            ['numero' => 'A-203', 'capacite' => 35, 'en_service' => true, 'type' => 'cours', 'description' => 'Salle de cours - Bâtiment A'],

            // Salles de TD/TP
            ['numero' => 'B-101', 'capacite' => 30, 'en_service' => true, 'type' => 'td_tp', 'description' => 'Salle de TD/TP - Bâtiment B'],
            ['numero' => 'B-102', 'capacite' => 25, 'en_service' => true, 'type' => 'td_tp', 'description' => 'Salle de TD/TP - Bâtiment B'],
            ['numero' => 'B-103', 'capacite' => 20, 'en_service' => true, 'type' => 'td_tp', 'description' => 'Salle de TD/TP - Bâtiment B'],
            ['numero' => 'B-201', 'capacite' => 35, 'en_service' => true, 'type' => 'td_tp', 'description' => 'Salle de TD/TP - Bâtiment B'],
            ['numero' => 'B-202', 'capacite' => 30, 'en_service' => true, 'type' => 'td_tp', 'description' => 'Salle de TD/TP - Bâtiment B'],

            // Laboratoires informatiques
            ['numero' => 'C-101', 'capacite' => 30, 'en_service' => true, 'type' => 'laboratoire', 'description' => 'Laboratoire informatique - Bâtiment C'],
            ['numero' => 'C-102', 'capacite' => 25, 'en_service' => true, 'type' => 'laboratoire', 'description' => 'Laboratoire informatique - Bâtiment C'],
            ['numero' => 'C-201', 'capacite' => 40, 'en_service' => true, 'type' => 'laboratoire', 'description' => 'Laboratoire informatique - Bâtiment C'],
            ['numero' => 'C-202', 'capacite' => 35, 'en_service' => true, 'type' => 'laboratoire', 'description' => 'Laboratoire informatique - Bâtiment C'],

            // Salles d'examen
            ['numero' => 'D-101', 'capacite' => 80, 'en_service' => true, 'type' => 'examen', 'description' => 'Salle d\'examen - Bâtiment D'],
            ['numero' => 'D-102', 'capacite' => 70, 'en_service' => true, 'type' => 'examen', 'description' => 'Salle d\'examen - Bâtiment D'],
            ['numero' => 'D-201', 'capacite' => 100, 'en_service' => true, 'type' => 'examen', 'description' => 'Grande salle d\'examen - Bâtiment D'],

            // Salles de réunion/conférence
            ['numero' => 'E-101', 'capacite' => 20, 'en_service' => true, 'type' => 'reunion', 'description' => 'Salle de réunion - Bâtiment E'],
            ['numero' => 'E-102', 'capacite' => 15, 'en_service' => true, 'type' => 'reunion', 'description' => 'Salle de réunion - Bâtiment E'],
            ['numero' => 'E-201', 'capacite' => 50, 'en_service' => true, 'type' => 'conference', 'description' => 'Salle de conférence - Bâtiment E'],

            // Salles spécialisées
            ['numero' => 'F-101', 'capacite' => 25, 'en_service' => true, 'type' => 'atelier', 'description' => 'Atelier pratique - Bâtiment F'],
            ['numero' => 'F-102', 'capacite' => 20, 'en_service' => true, 'type' => 'atelier', 'description' => 'Atelier pratique - Bâtiment F'],
            ['numero' => 'F-201', 'capacite' => 30, 'en_service' => true, 'type' => 'studio', 'description' => 'Studio multimédia - Bâtiment F'],

            // Salles en maintenance
            ['numero' => 'A-104', 'capacite' => 40, 'en_service' => false, 'type' => 'cours', 'description' => 'Salle en maintenance - Bâtiment A'],
            ['numero' => 'B-203', 'capacite' => 25, 'en_service' => false, 'type' => 'td_tp', 'description' => 'Salle en rénovation - Bâtiment B'],
        ];

        foreach ($salles as $salleData) {
            $salle = Salle::create($salleData);
            echo "Salle créée: {$salle->numero} ({$salle->type}) - Capacité: {$salle->capacite} places\n";
            echo "  - Description: {$salle->description}\n";
            echo "  - En service: " . ($salle->en_service ? 'Oui' : 'Non') . "\n";
            echo "  ----------------------------------------\n";
        }

        echo "\nTotal créé:\n";
        echo "- Salles de cours: " . Salle::where('type', 'cours')->count() . "\n";
        echo "- Salles TD/TP: " . Salle::where('type', 'td_tp')->count() . "\n";
        echo "- Laboratoires: " . Salle::where('type', 'laboratoire')->count() . "\n";
        echo "- Salles d'examen: " . Salle::where('type', 'examen')->count() . "\n";
        echo "- Salles de réunion: " . Salle::where('type', 'reunion')->count() . "\n";
        echo "- Salles de conférence: " . Salle::where('type', 'conference')->count() . "\n";
        echo "- Ateliers: " . Salle::where('type', 'atelier')->count() . "\n";
        echo "- Studios: " . Salle::where('type', 'studio')->count() . "\n";
        echo "- Total: " . Salle::count() . " salles\n";
        echo "- En service: " . Salle::where('en_service', true)->count() . "\n";
        echo "- En maintenance: " . Salle::where('en_service', false)->count() . "\n";
    }
}
