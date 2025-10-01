<?php

use App\Http\Controllers\PagesController;
use App\Http\Controllers\TablesController;
use App\Http\Controllers\CalendarController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'role:etudiant',
])->group(function () {
    Route::get('/dashboard',[PagesController::class,'displayDashboard'])->name('dashboard');


    Route::get('/semestres', [PagesController::class,'displaySemestres'])->name('semestres');

    Route::get('/semestre/{semestre}', [PagesController::class,'diplaySemestre'])->name('semestre');

    Route::get('/matiere/{matiere}', [PagesController::class, 'displayMatiere'])->name('matiere');


    Route::get('/emplois-du-temps', [PagesController::class,'displayEmploisDuTemps'])->name('emplois-du-temps');
    Route::get('/emplois-du-temps-{emploiDuTemps}', [PagesController::class,'displayEmploiDuTemps'])->name('emploi-du-temps');
    Route::get('/emploi-du-temps-actif', [PagesController::class,'displayEmploiDuTempsActif'])->name('emploi-du-temps-actif');

    Route::get('/bibliotheque', [PagesController::class,'displayBibliotheque'])->name('bibliotheque');
    Route::get('/livre/{livre}/telecharger', [PagesController::class,'telechargerLivre'])->name('livre.telecharger');

    // Routes du calendrier
    Route::get('/calendrier', [CalendarController::class, 'index'])->name('calendrier');
    Route::get('/evenement/{evenement}', [CalendarController::class, 'showEvent'])->name('evenement.show');
});
