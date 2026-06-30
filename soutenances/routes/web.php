<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrganismeController;
use App\Http\Controllers\EtudiantController;
use App\Http\Controllers\SoutenanceController;
use App\Http\Controllers\ProfesseurController;

//Etudiant routes
Route::get('/etudiants', [EtudiantController::class, 'index'])->name('etudiants.index');
Route::post('/etudiants', [EtudiantController::class, 'store'])->name('etudiants.store');
Route::get('/etudiants/{matricule}/edit', [EtudiantController::class, 'edit'])->name('etudiants.edit');
Route::put('/etudiants/{matricule}', [EtudiantController::class, 'update'])->name('etudiants.update');
Route::delete('/etudiants/{matricule}', [EtudiantController::class, 'destroy'])->name('etudiants.destroy');

// Soutenance routes
Route::get('/soutenances', [SoutenanceController::class, 'index'])->name('soutenances.index');
Route::post('/soutenances', [SoutenanceController::class, 'store'])->name('soutenances.store');
Route::get('/soutenances/{id}/edit', [SoutenanceController::class, 'edit'])->name('soutenances.edit');
Route::put('/soutenances/{id}', [SoutenanceController::class, 'update'])->name('soutenances.update');
Route::delete('/soutenances/{id}', [SoutenanceController::class, 'destroy'])->name('soutenances.destroy');

// Professeur routes
Route::get('/professeurs', [ProfesseurController::class, 'index'])->name('professeurs.index');
Route::post('/professeurs', [ProfesseurController::class, 'store'])->name('professeurs.store');
Route::get('/professeurs/{idprof}/edit', [ProfesseurController::class, 'edit'])->name('professeurs.edit');
Route::put('/professeurs/{idprof}', [ProfesseurController::class, 'update'])->name('professeurs.update');
Route::delete('/professeurs/{idprof}', [ProfesseurController::class, 'destroy'])->name('professeurs.destroy');

// Route pour afficher la page (ex: http://127.0.0.1:8000/organismes)
Route::get('/organismes', [OrganismeController::class, 'index'])->name('organismes.index');

// Route pour capter le formulaire d'ajout
Route::post('/organismes', [OrganismeController::class, 'store'])->name('organismes.store');
// Routes pour modification et suppression
Route::get('/organismes/{idorg}/edit', [OrganismeController::class, 'edit'])->name('organismes.edit');
Route::put('/organismes/{idorg}', [OrganismeController::class, 'update'])->name('organismes.update');
Route::delete('/organismes/{idorg}', [OrganismeController::class, 'destroy'])->name('organismes.destroy');

Route::get('/', function () {
    return view('welcome');


});
