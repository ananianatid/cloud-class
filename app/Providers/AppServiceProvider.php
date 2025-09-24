<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\Etudiant;
use App\Models\Enseignant;
use App\Models\Matiere;
use App\Observers\StatsCacheObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Fix for MySQL "key too long" error
        Schema::defaultStringLength(191);

        // Enregistrer les observers pour invalider le cache
        Etudiant::observe(StatsCacheObserver::class);
        Enseignant::observe(StatsCacheObserver::class);
        Matiere::observe(StatsCacheObserver::class);
    }
}
