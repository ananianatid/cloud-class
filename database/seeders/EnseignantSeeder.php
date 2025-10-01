<?php

namespace Database\Seeders;

use App\Models\Enseignant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EnseignantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer des enseignants spécialisés pour les matières de la promotion 2023-2026
        $enseignants = [
            [
                'name' => 'Dr. Marie Dubois',
                'email' => 'marie.dubois@cloudclass.edu',
                'password' => Hash::make('$helsinki'),
                'role' => 'enseignant',
                'sexe' => 'F',
                'statut' => 'permanent',
                'bio' => 'Docteure en Informatique avec 10 ans d\'expérience. Spécialisée en algorithmique, programmation et analyse.',
                'specialite' => 'Algorithmique et Programmation',
                'telephone' => '+237600123456',
                'bureau' => 'Bureau A-101'
            ],
            [
                'name' => 'Prof. Jean Martin',
                'email' => 'jean.martin@cloudclass.edu',
                'password' => Hash::make('$helsinki'),
                'role' => 'enseignant',
                'sexe' => 'M',
                'statut' => 'permanent',
                'bio' => 'Professeur en Génie Logiciel, expert en POO, développement web et génie logiciel. 15 ans d\'expérience.',
                'specialite' => 'Génie Logiciel et Web',
                'telephone' => '+237600123457',
                'bureau' => 'Bureau A-102'
            ],
            [
                'name' => 'Dr. Fatou Ndiaye',
                'email' => 'fatou.ndiaye@cloudclass.edu',
                'password' => Hash::make('$helsinki'),
                'role' => 'enseignant',
                'sexe' => 'F',
                'statut' => 'permanent',
                'bio' => 'Spécialiste en Systèmes et Réseaux, expert en télécommunications et réseaux. 12 ans d\'expérience.',
                'specialite' => 'Systèmes et Réseaux',
                'telephone' => '+237600123458',
                'bureau' => 'Bureau A-103'
            ],
            [
                'name' => 'Prof. Ahmed Hassan',
                'email' => 'ahmed.hassan@cloudclass.edu',
                'password' => Hash::make('$helsinki'),
                'role' => 'enseignant',
                'sexe' => 'M',
                'statut' => 'permanent',
                'bio' => 'Professeur en Mathématiques et Électronique, expert en algèbre, analyse et électronique. 18 ans d\'expérience.',
                'specialite' => 'Mathématiques et Électronique',
                'telephone' => '+237600123459',
                'bureau' => 'Bureau B-101'
            ],
            [
                'name' => 'Dr. Sophie Laurent',
                'email' => 'sophie.laurent@cloudclass.edu',
                'password' => Hash::make('$helsinki'),
                'role' => 'enseignant',
                'sexe' => 'F',
                'statut' => 'permanent',
                'bio' => 'Docteure en Langues et Communication, experte en français et anglais. 10 ans d\'expérience.',
                'specialite' => 'Langues et Communication',
                'telephone' => '+237600123460',
                'bureau' => 'Bureau B-102'
            ],
            [
                'name' => 'Prof. Pierre Moreau',
                'email' => 'pierre.moreau@cloudclass.edu',
                'password' => Hash::make('$helsinki'),
                'role' => 'enseignant',
                'sexe' => 'M',
                'statut' => 'vacataire',
                'bio' => 'Expert en Interface Homme-Machine et Design Patterns. 8 ans d\'expérience dans l\'industrie.',
                'specialite' => 'IHM et Design Patterns',
                'telephone' => '+237600123461',
                'bureau' => 'Bureau C-101'
            ],
            [
                'name' => 'Dr. Claire Bernard',
                'email' => 'claire.bernard@cloudclass.edu',
                'password' => Hash::make('$helsinki'),
                'role' => 'enseignant',
                'sexe' => 'F',
                'statut' => 'permanent',
                'bio' => 'Spécialiste en Probabilités, Statistiques et Recherche Opérationnelle. 14 ans d\'expérience.',
                'specialite' => 'Probabilités et Statistiques',
                'telephone' => '+237600123462',
                'bureau' => 'Bureau C-102'
            ]
        ];

        // Créer 5 administrateurs
        $administrateurs = [
            [
                'name' => 'Admin Principal',
                'email' => 'admin@cloudclass.edu',
                'password' => Hash::make('$helsinki'),
                'role' => 'administrateur',
                'sexe' => 'M',
                'poste' => 'Directeur Général',
                'telephone' => '+237600123461',
                'bureau' => 'Bureau Principal'
            ],
            [
                'name' => 'Admin Académique',
                'email' => 'academic@cloudclass.edu',
                'password' => Hash::make('$helsinki'),
                'role' => 'administrateur',
                'sexe' => 'F',
                'poste' => 'Directrice Académique',
                'telephone' => '+237600123462',
                'bureau' => 'Bureau Académique'
            ],
            [
                'name' => 'Admin Technique',
                'email' => 'tech@cloudclass.edu',
                'password' => Hash::make('$helsinki'),
                'role' => 'administrateur',
                'sexe' => 'M',
                'poste' => 'Administrateur Système',
                'telephone' => '+237600123463',
                'bureau' => 'Bureau IT'
            ],
            [
                'name' => 'Admin Financier',
                'email' => 'finance@cloudclass.edu',
                'password' => Hash::make('$helsinki'),
                'role' => 'administrateur',
                'sexe' => 'F',
                'poste' => 'Directrice Financière',
                'telephone' => '+237600123464',
                'bureau' => 'Bureau Finances'
            ],
            [
                'name' => 'Admin RH',
                'email' => 'rh@cloudclass.edu',
                'password' => Hash::make('$helsinki'),
                'role' => 'administrateur',
                'sexe' => 'M',
                'poste' => 'Directeur des Ressources Humaines',
                'telephone' => '+237600123465',
                'bureau' => 'Bureau RH'
            ]
        ];

        echo "Création des enseignants et administrateurs...\n";
        echo "============================================\n";

        // Créer les enseignants
        foreach ($enseignants as $index => $enseignantData) {
            $user = \App\Models\User::create([
                'name' => $enseignantData['name'],
                'email' => $enseignantData['email'],
                'password' => $enseignantData['password'],
                'role' => $enseignantData['role'],
                'sexe' => $enseignantData['sexe'],
            ]);

            Enseignant::create([
                'user_id' => $user->id,
                'statut' => $enseignantData['statut'],
                'bio' => $enseignantData['bio'],
            ]);

            echo "Enseignant créé: {$enseignantData['name']} ({$enseignantData['email']})\n";
            echo "  - Spécialité: {$enseignantData['specialite']}\n";
            echo "  - Statut: {$enseignantData['statut']}\n";
            echo "  - Bureau: {$enseignantData['bureau']}\n";
            echo "  - Mot de passe: \$helsinki\n";
            echo "  ----------------------------------------\n";
        }

        // Créer les administrateurs
        foreach ($administrateurs as $index => $adminData) {
            $user = \App\Models\User::create([
                'name' => $adminData['name'],
                'email' => $adminData['email'],
                'password' => $adminData['password'],
                'role' => $adminData['role'],
                'sexe' => $adminData['sexe'],
            ]);

            echo "Administrateur créé: {$adminData['name']} ({$adminData['email']})\n";
            echo "  - Poste: {$adminData['poste']}\n";
            echo "  - Bureau: {$adminData['bureau']}\n";
            echo "  - Mot de passe: \$helsinki\n";
            echo "  ----------------------------------------\n";
        }

        echo "\nTotal créé:\n";
        echo "- Enseignants: " . count($enseignants) . "\n";
        echo "- Administrateurs: " . count($administrateurs) . "\n";
        echo "- Mot de passe pour tous: \$helsinki\n";
    }
}
