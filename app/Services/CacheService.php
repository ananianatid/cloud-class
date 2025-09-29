<?php

namespace App\Services;

use App\Models\Promotion;
use App\Models\Semestre;
use App\Models\UniteEnseignement;
use App\Models\Salle;
use App\Models\Diplome;
use App\Models\Filiere;
use App\Models\Matiere;
use Illuminate\Support\Facades\Cache;

class CacheService
{
    /**
     * Durée de cache par défaut (en minutes)
     */
    const DEFAULT_CACHE_DURATION = 60;

    /**
     * Obtenir les promotions avec cache (triées par année de fin décroissante)
     */
    public static function getPromotions(): \Illuminate\Support\Collection
    {
        return Cache::remember('promotions', self::DEFAULT_CACHE_DURATION, function () {
            return Promotion::with(['diplome', 'filiere'])
                ->orderBy('annee_fin', 'desc')
                ->orderBy('annee_debut', 'desc')
                ->get();
        });
    }

    /**
     * Obtenir les semestres avec cache
     */
    public static function getSemestres(): \Illuminate\Support\Collection
    {
        return Cache::remember('semestres', self::DEFAULT_CACHE_DURATION, function () {
            return Semestre::with('promotion')->orderBy('slug')->get();
        });
    }

    /**
     * Obtenir les unités d'enseignement avec cache
     */
    public static function getUnitesEnseignement(): \Illuminate\Support\Collection
    {
        return Cache::remember('unites_enseignement', self::DEFAULT_CACHE_DURATION, function () {
            return UniteEnseignement::orderBy('nom')->get();
        });
    }

    /**
     * Obtenir les salles avec cache
     */
    public static function getSalles(): \Illuminate\Support\Collection
    {
        return Cache::remember('salles', self::DEFAULT_CACHE_DURATION, function () {
            return Salle::orderBy('numero')->get();
        });
    }

    /**
     * Obtenir les diplômes avec cache
     */
    public static function getDiplomes(): \Illuminate\Support\Collection
    {
        return Cache::remember('diplomes', self::DEFAULT_CACHE_DURATION, function () {
            return Diplome::orderBy('nom')->get();
        });
    }

    /**
     * Obtenir les filières avec cache
     */
    public static function getFilieres(): \Illuminate\Support\Collection
    {
        return Cache::remember('filieres', self::DEFAULT_CACHE_DURATION, function () {
            return Filiere::orderBy('nom')->get();
        });
    }

    /**
     * Obtenir les matières avec cache
     */
    public static function getMatieres(): \Illuminate\Support\Collection
    {
        return Cache::remember('matieres', self::DEFAULT_CACHE_DURATION, function () {
            return Matiere::with(['unite', 'semestre.promotion', 'enseignant.user'])
                ->orderBy('id')
                ->get();
        });
    }

    /**
     * Obtenir les semestres par promotion avec cache
     */
    public static function getSemestresByPromotion(int $promotionId): \Illuminate\Support\Collection
    {
        return Cache::remember("semestres_promotion_{$promotionId}", self::DEFAULT_CACHE_DURATION, function () use ($promotionId) {
            return Semestre::where('promotion_id', $promotionId)
                ->with('promotion')
                ->orderBy('slug')
                ->get();
        });
    }

    /**
     * Vider le cache des données statiques
     */
    public static function clearStaticCache(): void
    {
        Cache::forget('promotions');
        Cache::forget('semestres');
        Cache::forget('unites_enseignement');
        Cache::forget('salles');
        Cache::forget('diplomes');
        Cache::forget('filieres');
        Cache::forget('matieres');

        // Vider aussi le cache des semestres par promotion
        $promotions = Promotion::pluck('id');
        foreach ($promotions as $promotionId) {
            Cache::forget("semestres_promotion_{$promotionId}");
        }
    }

    /**
     * Vider tout le cache de l'application
     */
    public static function clearAllCache(): void
    {
        Cache::flush();
    }
}
