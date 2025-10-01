<?php

namespace Database\Seeders;

use App\Models\CategorieLivre;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorieLivreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'nom' => 'Intelligence Artificielle',
                'description' => 'Livres sur l\'intelligence artificielle, machine learning et data science'
            ],
            [
                'nom' => 'Marketing Digital',
                'description' => 'Livres sur le marketing digital, e-commerce et stratégies en ligne'
            ],
            [
                'nom' => 'Systèmes d\'exploitation',
                'description' => 'Livres sur les systèmes d\'exploitation et l\'administration système'
            ],
            [
                'nom' => 'Génie Logiciel',
                'description' => 'Livres sur le développement logiciel, architecture et bonnes pratiques'
            ],
            [
                'nom' => 'Génie Civil',
                'description' => 'Livres sur le génie civil, construction et infrastructure'
            ],
            [
                'nom' => 'Informatique Générale',
                'description' => 'Livres généraux sur l\'informatique et les technologies'
            ]
        ];

        echo "Création des catégories de livres...\n";
        echo "===================================\n";

        foreach ($categories as $categorieData) {
            $categorie = CategorieLivre::updateOrCreate(
                ['nom' => $categorieData['nom']],
                $categorieData
            );
            echo "✓ Catégorie créée: {$categorie->nom}\n";
        }

        echo "\nTotal des catégories: " . CategorieLivre::count() . "\n";
        echo "===================================\n";
    }
}
