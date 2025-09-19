<?php

namespace Database\Seeders;

use App\Models\Enseignant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EnseignantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer 5 enseignants
        $enseignants = [
            [
                'name' => 'Dr. Marie Dubois',
                'email' => 'marie.dubois@cloudclass.edu',
                'password' => \Hash::make('$helsinki'),
                'role' => 'enseignant',
                'sexe' => 'F',
                'statut' => 'permanent',
                'bio' => 'Docteure en Intelligence Artificielle avec 10 ans d\'expérience dans l\'enseignement et la recherche. Spécialisée en machine learning et deep learning.',
                'specialite' => 'Intelligence Artificielle',
                'telephone' => '+237600123456',
                'bureau' => 'Bureau A-101'
            ],
            [
                'name' => 'Prof. Jean Martin',
                'email' => 'jean.martin@cloudclass.edu',
                'password' => \Hash::make('$helsinki'),
                'role' => 'enseignant',
                'sexe' => 'M',
                'statut' => 'permanent',
                'bio' => 'Professeur en Génie Logiciel, expert en développement d\'applications web et mobile. 15 ans d\'expérience dans l\'industrie.',
                'specialite' => 'Génie Logiciel',
                'telephone' => '+237600123457',
                'bureau' => 'Bureau A-102'
            ],
            [
                'name' => 'Dr. Fatou Ndiaye',
                'email' => 'fatou.ndiaye@cloudclass.edu',
                'password' => \Hash::make('$helsinki'),
                'role' => 'enseignant',
                'sexe' => 'F',
                'statut' => 'vacataire',
                'bio' => 'Spécialiste en Systèmes et Réseaux, consultante en cybersécurité. Expertise en administration de serveurs et réseaux.',
                'specialite' => 'Systèmes et Réseaux',
                'telephone' => '+237600123458',
                'bureau' => 'Bureau A-103'
            ],
            [
                'name' => 'Prof. Ahmed Hassan',
                'email' => 'ahmed.hassan@cloudclass.edu',
                'password' => \Hash::make('$helsinki'),
                'role' => 'enseignant',
                'sexe' => 'M',
                'statut' => 'permanent',
                'bio' => 'Professeur en Génie Civil, expert en construction durable et gestion de projets. 20 ans d\'expérience dans le domaine.',
                'specialite' => 'Génie Civil',
                'telephone' => '+237600123459',
                'bureau' => 'Bureau B-101'
            ],
            [
                'name' => 'Dr. Sophie Laurent',
                'email' => 'sophie.laurent@cloudclass.edu',
                'password' => \Hash::make('$helsinki'),
                'role' => 'enseignant',
                'sexe' => 'F',
                'statut' => 'permanent',
                'bio' => 'Docteure en Communication Digitale, experte en marketing digital et stratégies de communication. 12 ans d\'expérience.',
                'specialite' => 'Communication Digitale',
                'telephone' => '+237600123460',
                'bureau' => 'Bureau B-102'
            ]
        ];

        // Créer 5 administrateurs
        $administrateurs = [
            [
                'name' => 'Admin Principal',
                'email' => 'admin@cloudclass.edu',
                'password' => \Hash::make('$helsinki'),
                'role' => 'administrateur',
                'sexe' => 'M',
                'poste' => 'Directeur Général',
                'telephone' => '+237600123461',
                'bureau' => 'Bureau Principal'
            ],
            [
                'name' => 'Admin Académique',
                'email' => 'academic@cloudclass.edu',
                'password' => \Hash::make('$helsinki'),
                'role' => 'administrateur',
                'sexe' => 'F',
                'poste' => 'Directrice Académique',
                'telephone' => '+237600123462',
                'bureau' => 'Bureau Académique'
            ],
            [
                'name' => 'Admin Technique',
                'email' => 'tech@cloudclass.edu',
                'password' => \Hash::make('$helsinki'),
                'role' => 'administrateur',
                'sexe' => 'M',
                'poste' => 'Administrateur Système',
                'telephone' => '+237600123463',
                'bureau' => 'Bureau IT'
            ],
            [
                'name' => 'Admin Financier',
                'email' => 'finance@cloudclass.edu',
                'password' => \Hash::make('$helsinki'),
                'role' => 'administrateur',
                'sexe' => 'F',
                'poste' => 'Directrice Financière',
                'telephone' => '+237600123464',
                'bureau' => 'Bureau Finances'
            ],
            [
                'name' => 'Admin RH',
                'email' => 'rh@cloudclass.edu',
                'password' => \Hash::make('$helsinki'),
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
