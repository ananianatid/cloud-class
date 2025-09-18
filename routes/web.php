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


    Route::get('/semestres', function () {
        return view('pages.semestres');
    })->name('semestres');

    Route::get('/semestre-{semestre}', [PagesController::class,'diplaySemestre'])->name('semestre');

    Route::get('/semestre-1/matiere-1', function () {
        return view('pages.matiere');
    })->name('matiere');


    Route::get('/emplois-du-temps', function () {
        return view('pages.emplois-du-temps');
    })->name('emplois-du-temps');

    Route::get('/emploi-du-temps', function () {
        return view('pages.emploi-du-temps');
    })->name('emploi-du-temps');
});
