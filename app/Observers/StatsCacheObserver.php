<?php

namespace App\Observers;

use Illuminate\Support\Facades\Cache;

class StatsCacheObserver
{
    /**
     * Invalider le cache des statistiques lors des modifications
     */
    public function saved($model)
    {
        $this->invalidateStatsCache($model);
    }

    /**
     * Invalider le cache des statistiques lors des suppressions
     */
    public function deleted($model)
    {
        $this->invalidateStatsCache($model);
    }

    /**
     * Invalider le cache des statistiques selon le modèle
     */
    private function invalidateStatsCache($model)
    {
        $modelClass = get_class($model);

        switch ($modelClass) {
            case \App\Models\Etudiant::class:
                Cache::forget('etudiants_count');
                break;
            case \App\Models\Enseignant::class:
                Cache::forget('enseignants_count');
                break;
            case \App\Models\Matiere::class:
                Cache::forget('matieres_count');
                break;
        }
    }
}
