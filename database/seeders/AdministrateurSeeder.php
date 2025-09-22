<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Administrateur;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AdministrateurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer un super administrateur par défaut
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@cloud-class.com'],
            [
                'name' => 'Super Administrateur',
                'password' => 'password',
                'role' => 'administrateur',
                'sexe' => 'M',
            ]
        );

        // Créer le profil administrateur
        Administrateur::firstOrCreate(
            ['user_id' => $superAdmin->id],
            [
                'niveau' => 'super_admin',
                'statut' => 'actif',
                'departement' => 'Informatique',
                'permissions' => json_encode([
                    'gestion_utilisateurs' => true,
                    'gestion_cours' => true,
                    'gestion_fichiers' => true,
                    'gestion_emploi_temps' => true,
                    'gestion_promotions' => true,
                    'gestion_matieres' => true,
                    'rapports' => true,
                    'parametres_systeme' => true,
                ]),
                'derniere_connexion' => Carbon::now(),
                'telephone_bureau' => '+237 6XX XX XX XX',
                'notes' => 'Super administrateur principal du système Cloud Class',
            ]
        );

        // Créer un administrateur modérateur
        $moderateur = User::firstOrCreate(
            ['email' => 'moderateur@cloud-class.com'],
            [
                'name' => 'Modérateur Système',
                'password' => 'password',
                'role' => 'administrateur',
                'sexe' => 'F',
            ]
        );

        Administrateur::firstOrCreate(
            ['user_id' => $moderateur->id],
            [
                'niveau' => 'moderateur',
                'statut' => 'actif',
                'departement' => 'Gestion',
                'permissions' => json_encode([
                    'gestion_utilisateurs' => false,
                    'gestion_cours' => true,
                    'gestion_fichiers' => true,
                    'gestion_emploi_temps' => true,
                    'gestion_promotions' => false,
                    'gestion_matieres' => true,
                    'rapports' => true,
                    'parametres_systeme' => false,
                ]),
                'derniere_connexion' => Carbon::now()->subDays(2),
                'telephone_bureau' => '+237 6XX XX XX XY',
                'notes' => 'Modérateur responsable de la gestion des cours et fichiers',
            ]
        );
    }
}
