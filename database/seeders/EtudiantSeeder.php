<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EtudiantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer toutes les promotions de licence
        $promotions = \App\Models\Promotion::where('diplome_id', 2)->get();

        // Prénoms commençant par J
        $prenoms = [
            'Jean', 'Jacques', 'Julien', 'Jérôme', 'Jonathan', 'Jordan',
            'Jules', 'Justin', 'Jérémy', 'Johan', 'Joël', 'Jocelyn',
            'Jade', 'Julie', 'Juliette', 'Jessica', 'Justine', 'Jasmine',
            'Joëlle', 'Joséphine', 'Jocelyne', 'Jacqueline', 'Janine', 'Jeanne'
        ];

        echo "Création des étudiants test pour chaque promotion...\n";
        echo "================================================\n";

        // Créer un fichier pour sauvegarder les informations de connexion
        $credentialsFile = storage_path('app/student_credentials.txt');
        $credentials = "=== INFORMATIONS DE CONNEXION DES ÉTUDIANTS TEST ===\n";
        $credentials .= "Mot de passe pour tous les étudiants: \$helsinki\n";
        $credentials .= "Généré le: " . now()->format('Y-m-d H:i:s') . "\n\n";

        foreach ($promotions as $index => $promotion) {
            // Générer un prénom aléatoire commençant par J
            $prenom = $prenoms[$index % count($prenoms)];

            // Générer un mot de passe aléatoire
            $password = 'Test' . rand(1000, 9999) . '!';

            // Créer l'utilisateur
            $user = \App\Models\User::create([
                'name' => $prenom . ' Doe',
                'email' => strtolower($prenom) . '.doe.' . $promotion->annee_debut . '.' . ($index + 1) . '@test.com',
                'password' => Hash::make('$helsinki'),
                'role' => 'etudiant',
                'sexe' => rand(0, 1) ? 'M' : 'F',
            ]);

            // Générer un matricule unique
            $matricule = 'ETU' . $promotion->annee_debut . str_pad($index + 1, 3, '0', STR_PAD_LEFT);

            // Créer l'étudiant
            \App\Models\Etudiant::create([
                'user_id' => $user->id,
                'promotion_id' => $promotion->id,
                'matricule' => $matricule,
                'naissance' => now()->subYears(rand(18, 25))->format('Y-m-d'),
                'graduation' => $promotion->annee_fin . '-06-30',
                'parent' => 'Parent de ' . $prenom . ' Doe',
                'telephone_parent' => '+237' . rand(600000000, 699999999),
                'statut' => 'actif',
            ]);

            // Afficher les informations de connexion
            echo "Étudiant créé pour " . $promotion->nom . ":\n";
            echo "  - Nom: " . $prenom . " Doe\n";
            echo "  - Email: " . $user->email . "\n";
            echo "  - Mot de passe: \$helsinki\n";
            echo "  - Matricule: " . $matricule . "\n";
            echo "  - Promotion: " . $promotion->nom . "\n";
            echo "  - Filière: " . $promotion->filiere->nom . "\n";
            echo "  - Période: " . $promotion->annee_debut . "-" . $promotion->annee_fin . "\n";
            echo "  ----------------------------------------\n";

            // Ajouter au fichier de credentials
            $credentials .= "Étudiant #" . ($index + 1) . ":\n";
            $credentials .= "  - Nom: " . $prenom . " Doe\n";
            $credentials .= "  - Email: " . $user->email . "\n";
            $credentials .= "  - Mot de passe: \$helsinki\n";
            $credentials .= "  - Matricule: " . $matricule . "\n";
            $credentials .= "  - Promotion: " . $promotion->nom . "\n";
            $credentials .= "  - Filière: " . $promotion->filiere->nom . "\n";
            $credentials .= "  - Période: " . $promotion->annee_debut . "-" . $promotion->annee_fin . "\n";
            $credentials .= "  ----------------------------------------\n\n";
        }

        // Sauvegarder le fichier de credentials
        file_put_contents($credentialsFile, $credentials);

        echo "\nTotal étudiants créés: " . $promotions->count() . "\n";
        echo "Fichier de credentials sauvegardé: " . $credentialsFile . "\n";
        echo "Mot de passe pour tous les étudiants: \$helsinki\n";
    }
}
