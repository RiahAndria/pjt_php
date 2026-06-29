<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrganismeController;
use App\Http\Controllers\EtudiantController;

//Etudiant routes
Route::get('/etudiants', [EtudiantController::class, 'index'])->name('etudiants.index');
Route::post('/etudiants', [EtudiantController::class, 'store'])->name('etudiants.store');
Route::get('/etudiants/{matricule}/edit', [EtudiantController::class, 'edit'])->name('etudiants.edit');
Route::put('/etudiants/{matricule}', [EtudiantController::class, 'update'])->name('etudiants.update');
Route::delete('/etudiants/{matricule}', [EtudiantController::class, 'destroy'])->name('etudiants.destroy');

// Organisme routes
Route::get('/organismes', [OrganismeController::class, 'index'])->name('organismes.index');
Route::post('/organismes', [OrganismeController::class, 'store'])->name('organismes.store');

Route::get('/', function () {
    return view('welcome');


});
