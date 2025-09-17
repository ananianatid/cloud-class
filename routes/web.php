<?php

use App\Http\Controllers\PagesController;
use App\Http\Controllers\TablesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard',[PagesController::class,'displayDashboard'])->name('dashboard');
    Route::get('/emploi-du-temps-actif',[PagesController::class,'displayEmploisDuTempsActif'])->name('emploi-du-temps-actif');
    // Route::get('/semestre-{semestre}/matieres',[PagesController::class,'displayMatieres'])->name('matieres');
    // Route::get('/semestre-{semestre}/{matiere}',[PagesController::class,'displayFichiers'])->name('fichiers');
    Route::get('/emplois-du-temps',[PagesController::class,'displayEmploisDuTemps'])->name('emplois-du-temps');

    Route::get('/test',[TablesController::class,'createSemestre'])->name('test');
});
