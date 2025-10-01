<?php

namespace Database\Seeders;

use App\Models\Etudiant;
use App\Models\User;
use App\Models\Promotion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class EtudiantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer la promotion 2023-2026
        $promotion = Promotion::where('nom', 'LIC-INFO-23-26')->first();
        if (!$promotion) {
            echo "Promotion LIC-INFO-23-26 non trouvée. Veuillez d'abord créer la promotion.\n";
            return;
        }

        // Créer ou récupérer l'utilisateur John Doe
        $user = User::firstOrCreate(
            ['email' => 'johndoe@gmail.com'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('password'),
                'role' => 'etudiant',
                'sexe' => 'M',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Créer le profil étudiant pour John Doe
        $etudiant = Etudiant::updateOrCreate(
            [
                'user_id' => $user->id,
                'promotion_id' => $promotion->id,
            ],
            [
                'matricule' => 'ETU2023001',
                'naissance' => Carbon::create(2000, 5, 15)->toDateString(),
                'graduation' => Carbon::create(2026, 6, 30)->toDateString(),
                'parent' => 'Jane Doe',
                'telephone_parent' => '+237600123999',
                'statut' => 'actif',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        echo "Étudiant créé avec succès:\n";
        echo "================================\n";
        echo "Nom: {$user->name}\n";
        echo "Email: {$user->email}\n";
        echo "Matricule: {$etudiant->matricule}\n";
        echo "Promotion: {$promotion->nom}\n";
        echo "Date de naissance: {$etudiant->naissance}\n";
        echo "Date de graduation prévue: {$etudiant->graduation}\n";
        echo "Parent: {$etudiant->parent}\n";
        echo "Téléphone parent: {$etudiant->telephone_parent}\n";
        echo "Statut: {$etudiant->statut}\n";
        echo "Mot de passe: password\n";
        echo "================================\n";
    }
}
