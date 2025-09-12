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
    Route::get('/dashboard',[PagesController::class,'displaySemestres'])->name('dashboard');
    Route::get('/semestre-{semestre}/matieres',[PagesController::class,'displayMatieres'])->name('matieres');

    Route::get('/test',[TablesController::class,'createSemestre'])->name('test');


});
