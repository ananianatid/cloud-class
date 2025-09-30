<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class Livre extends Model
{
    protected $fillable = [
        'isbn',
        'chemin_fichier',
        'categorie_livre_id'
    ];

    // Nettoyer l'ISBN lors de la sauvegarde
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($livre) {
            if ($livre->isbn) {
                // Nettoyer l'ISBN : supprimer espaces, tirets et autres caractères
                $livre->isbn = preg_replace('/[^0-9X]/', '', $livre->isbn);
            }
        });
    }

    public function categorie()
    {
        return $this->belongsTo(CategorieLivre::class, 'categorie_livre_id');
    }

    // Méthode pour récupérer les informations du livre via l'API Google Books
    public function getGoogleBooksInfo()
    {
        // Utiliser le cache pour éviter les appels API répétés
        $cacheKey = 'google_books_' . $this->isbn;

        return \Cache::remember($cacheKey, 3600, function () {
            try {
                if (empty($this->isbn)) {
                    return null;
                }

                $response = Http::timeout(10)->get("https://www.googleapis.com/books/v1/volumes", [
                    'q' => 'isbn:' . trim($this->isbn),
                    'maxResults' => 1
                ]);

                if ($response->successful()) {
                    $data = $response->json();

                    if (isset($data['items']) && count($data['items']) > 0) {
                        $book = $data['items'][0]['volumeInfo'];

                        return [
                            'titre' => $book['title'] ?? 'Titre non disponible',
                            'auteur' => isset($book['authors']) ? implode(', ', $book['authors']) : 'Auteur inconnu',
                            'description' => $book['description'] ?? 'Aucune description disponible',
                            'image_url' => $book['imageLinks']['thumbnail'] ?? $book['imageLinks']['smallThumbnail'] ?? null,
                            'editeur' => $book['publisher'] ?? 'Éditeur inconnu',
                            'date_publication' => $book['publishedDate'] ?? null,
                            'pages' => $book['pageCount'] ?? null,
                            'categories' => $book['categories'] ?? [],
                            'langue' => $book['language'] ?? 'fr',
                            'infoLink' => $data['items'][0]['volumeInfo']['infoLink'] ?? null
                        ];
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Erreur lors de la récupération des informations Google Books pour ISBN ' . $this->isbn . ': ' . $e->getMessage());
            }

            return null;
        });
    }

    // Accessor pour obtenir le titre depuis Google Books
    public function getTitreAttribute()
    {
        if (empty($this->isbn)) {
            return 'ISBN manquant';
        }

        $info = $this->getGoogleBooksInfo();
        return $info['titre'] ?? 'Titre non disponible';
    }

    // Accessor pour obtenir l'auteur depuis Google Books
    public function getAuteurAttribute()
    {
        if (empty($this->isbn)) {
            return 'ISBN manquant';
        }

        $info = $this->getGoogleBooksInfo();
        return $info['auteur'] ?? 'Auteur inconnu';
    }

    // Accessor pour obtenir l'image depuis Google Books
    public function getImageUrlAttribute()
    {
        if (empty($this->isbn)) {
            return null;
        }

        $info = $this->getGoogleBooksInfo();
        return $info['image_url'] ?? null;
    }
}
