<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GoogleBooksService
{
    private const BASE_URL = 'https://www.googleapis.com/books/v1/volumes';
    private const CACHE_DURATION = 3600; // 1 heure

    /**
     * Recherche un livre par ISBN
     */
    public function searchByIsbn(string $isbn): ?array
    {
        $cacheKey = "google_books_isbn_{$isbn}";

        return Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($isbn) {
            try {
                $response = Http::get(self::BASE_URL, [
                    'q' => "isbn:{$isbn}",
                    'maxResults' => 1
                ]);

                if ($response->successful()) {
                    $data = $response->json();

                    if (isset($data['items']) && count($data['items']) > 0) {
                        return $this->formatBookData($data['items'][0]);
                    }
                }

                return null;
            } catch (\Exception $e) {
                Log::error('Erreur lors de la recherche Google Books', [
                    'isbn' => $isbn,
                    'error' => $e->getMessage()
                ]);
                return null;
            }
        });
    }

    /**
     * Recherche des livres par terme de recherche
     */
    public function searchBooks(string $query, int $maxResults = 10): array
    {
        $cacheKey = "google_books_search_" . md5($query . $maxResults);

        return Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($query, $maxResults) {
            try {
                $response = Http::get(self::BASE_URL, [
                    'q' => $query,
                    'maxResults' => $maxResults
                ]);

                if ($response->successful()) {
                    $data = $response->json();

                    if (isset($data['items'])) {
                        return array_map([$this, 'formatBookData'], $data['items']);
                    }
                }

                return [];
            } catch (\Exception $e) {
                Log::error('Erreur lors de la recherche Google Books', [
                    'query' => $query,
                    'error' => $e->getMessage()
                ]);
                return [];
            }
        });
    }

    /**
     * Formate les données d'un livre
     */
    private function formatBookData(array $bookData): array
    {
        $volumeInfo = $bookData['volumeInfo'] ?? [];

        return [
            'id' => $bookData['id'] ?? null,
            'title' => $volumeInfo['title'] ?? 'Titre non disponible',
            'authors' => $volumeInfo['authors'] ?? ['Auteur inconnu'],
            'publisher' => $volumeInfo['publisher'] ?? 'Éditeur inconnu',
            'published_date' => $volumeInfo['publishedDate'] ?? null,
            'description' => $volumeInfo['description'] ?? null,
            'page_count' => $volumeInfo['pageCount'] ?? null,
            'categories' => $volumeInfo['categories'] ?? [],
            'language' => $volumeInfo['language'] ?? 'fr',
            'thumbnail' => $volumeInfo['imageLinks']['thumbnail'] ?? null,
            'small_thumbnail' => $volumeInfo['imageLinks']['smallThumbnail'] ?? null,
            'preview_link' => $volumeInfo['previewLink'] ?? null,
            'info_link' => $volumeInfo['infoLink'] ?? null,
            'isbn_13' => $this->extractIsbn13($volumeInfo['industryIdentifiers'] ?? []),
            'isbn_10' => $this->extractIsbn10($volumeInfo['industryIdentifiers'] ?? []),
        ];
    }

    /**
     * Extrait l'ISBN-13 des identifiants industriels
     */
    private function extractIsbn13(array $identifiers): ?string
    {
        foreach ($identifiers as $identifier) {
            if ($identifier['type'] === 'ISBN_13') {
                return $identifier['identifier'];
            }
        }
        return null;
    }

    /**
     * Extrait l'ISBN-10 des identifiants industriels
     */
    private function extractIsbn10(array $identifiers): ?string
    {
        foreach ($identifiers as $identifier) {
            if ($identifier['type'] === 'ISBN_10') {
                return $identifier['identifier'];
            }
        }
        return null;
    }
}
