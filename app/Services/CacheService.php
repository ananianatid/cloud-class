<?php

namespace App\Services;

use App\Models\Promotion;
use App\Models\Semestre;
use App\Models\UniteEnseignement;
use App\Models\Salle;
use Illuminate\Support\Facades\Cache;

class CacheService
{
    /**
     * Durée de cache par défaut (en minutes)
     */
    const DEFAULT_CACHE_DURATION = 60;

    /**
     * Obtenir les promotions avec cache
     */
    public static function getPromotions(): \Illuminate\Support\Collection
    {
        return Cache::remember('promotions', self::DEFAULT_CACHE_DURATION, function () {
            return Promotion::orderBy('nom')->get();
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
