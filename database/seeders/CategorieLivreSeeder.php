<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CategorieLivre;

class CategorieLivreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'nom' => 'Informatique',
                'description' => 'Livres sur la programmation, les technologies et l\'informatique'
            ],
            [
                'nom' => 'Sciences',
                'description' => 'Ouvrages scientifiques et techniques'
            ],
            [
                'nom' => 'Littérature',
                'description' => 'Romans, nouvelles et œuvres littéraires'
            ],
            [
                'nom' => 'Histoire',
                'description' => 'Livres d\'histoire et de géographie'
            ],
            [
                'nom' => 'Mathématiques',
                'description' => 'Manuels et ouvrages de mathématiques'
            ],
            [
                'nom' => 'Langues',
                'description' => 'Livres d\'apprentissage des langues'
            ],
            [
                'nom' => 'Économie',
                'description' => 'Ouvrages d\'économie et de gestion'
            ],
            [
                'nom' => 'Philosophie',
                'description' => 'Livres de philosophie et de réflexion'
            ]
        ];

        foreach ($categories as $categorie) {
            CategorieLivre::create($categorie);
        }
    }
}
