<?php

namespace Database\Seeders;

use App\Models\Livre;
use App\Models\CategorieLivre;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LivreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer les catégories
        $categorieIA = CategorieLivre::where('nom', 'Intelligence Artificielle')->first();
        $categorieMarketing = CategorieLivre::where('nom', 'Marketing Digital')->first();
        $categorieInformatique = CategorieLivre::where('nom', 'Informatique Générale')->first();

        if (!$categorieIA || !$categorieMarketing || !$categorieInformatique) {
            echo "Veuillez d'abord exécuter CategorieLivreSeeder.\n";
            return;
        }

        // Livres avec ISBN fournis
        $livres = [
            [
                'isbn' => '9780134610993',
                'titre' => 'Artificial Intelligence: A Modern Approach',
                'auteur' => 'Russell & Norvig',
                'categorie_id' => $categorieIA->id,
                'chemin_fichier' => 'livres/ai-modern-approach.pdf',
                'description' => 'L\'ouvrage de référence en intelligence artificielle, couvrant tous les aspects de l\'IA moderne.',
                'edition' => '4ème édition',
                'annee' => 2020
            ],
            [
                'isbn' => '9781449361327',
                'titre' => 'Data Science for Business',
                'auteur' => 'Foster Provost & Tom Fawcett',
                'categorie_id' => $categorieIA->id,
                'chemin_fichier' => 'livres/data-science-business.pdf',
                'description' => 'Guide pratique pour appliquer la science des données dans le contexte business.',
                'edition' => '2ème édition',
                'annee' => 2013
            ],
            [
                'isbn' => '9780998713816',
                'titre' => 'Digital Marketing Essentials',
                'auteur' => 'Jeff Larson & Stuart Draper',
                'categorie_id' => $categorieMarketing->id,
                'chemin_fichier' => 'livres/digital-marketing-essentials.pdf',
                'description' => 'Les fondamentaux du marketing digital et des stratégies en ligne.',
                'edition' => '1ère édition',
                'annee' => 2017
            ],
            // Livres supplémentaires sans ISBN spécifique
            [
                'isbn' => '9781118063330',
                'titre' => 'Operating System Concepts',
                'auteur' => 'Abraham Silberschatz, Peter Baer Galvin, Greg Gagne',
                'categorie_id' => $categorieInformatique->id,
                'chemin_fichier' => 'livres/operating-system-concepts.pdf',
                'description' => 'Concepts fondamentaux des systèmes d\'exploitation.',
                'edition' => '10ème édition',
                'annee' => 2018
            ],
            [
                'isbn' => '9780132350884',
                'titre' => 'Clean Code: A Handbook of Agile Software Craftsmanship',
                'auteur' => 'Robert C. Martin',
                'categorie_id' => $categorieInformatique->id,
                'chemin_fichier' => 'livres/clean-code.pdf',
                'description' => 'Guide pour écrire du code propre et maintenable.',
                'edition' => '1ère édition',
                'annee' => 2008
            ],
            [
                'isbn' => '9780133943030',
                'titre' => 'Software Engineering: A Practitioner\'s Approach',
                'auteur' => 'Roger S. Pressman, Bruce R. Maxim',
                'categorie_id' => $categorieInformatique->id,
                'chemin_fichier' => 'livres/software-engineering.pdf',
                'description' => 'Approche pratique du génie logiciel et des méthodologies de développement.',
                'edition' => '8ème édition',
                'annee' => 2014
            ],
            [
                'isbn' => '9781118766579',
                'titre' => 'Civil Engineering Handbook',
                'auteur' => 'W.F. Chen, J.Y. Richard Liew',
                'categorie_id' => CategorieLivre::where('nom', 'Génie Civil')->first()->id,
                'chemin_fichier' => 'livres/civil-engineering-handbook.pdf',
                'description' => 'Manuel de référence complet pour le génie civil.',
                'edition' => '2ème édition',
                'annee' => 2019
            ]
        ];

        echo "Création des livres de la bibliothèque...\n";
        echo "========================================\n";

        foreach ($livres as $livreData) {
            $livre = Livre::updateOrCreate(
                ['isbn' => $livreData['isbn']],
                [
                    'isbn' => $livreData['isbn'],
                    'categorie_livre_id' => $livreData['categorie_id'],
                    'chemin_fichier' => $livreData['chemin_fichier'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            echo "✓ Livre ajouté: {$livreData['titre']} - {$livreData['auteur']}\n";
            echo "  ISBN: {$livreData['isbn']}\n";
            echo "  Catégorie: " . CategorieLivre::find($livreData['categorie_id'])->nom . "\n";
            echo "  ----------------------------------------\n";
        }

        echo "\nTotal des livres: " . Livre::count() . "\n";
        echo "========================================\n";
    }
}
