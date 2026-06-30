<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrganismeController;
use App\Http\Controllers\EtudiantController;
use App\Http\Controllers\SoutenanceController;

//Etudiant routes
Route::get('/etudiants', [EtudiantController::class, 'index'])->name('etudiants.index');
Route::post('/etudiants', [EtudiantController::class, 'store'])->name('etudiants.store');
Route::get('/etudiants/{matricule}/edit', [EtudiantController::class, 'edit'])->name('etudiants.edit');
Route::put('/etudiants/{matricule}', [EtudiantController::class, 'update'])->name('etudiants.update');
Route::delete('/etudiants/{matricule}', [EtudiantController::class, 'destroy'])->name('etudiants.destroy');

// Organisme routes
Route::get('/organismes', [OrganismeController::class, 'index'])->name('organismes.index');
Route::post('/organismes', [OrganismeController::class, 'store'])->name('organismes.store');


// Soutenance routes
Route::get('/soutenances', [SoutenanceController::class, 'index'])->name('soutenances.index');
Route::post('/soutenances', [SoutenanceController::class, 'store'])->name('soutenances.store');
Route::get('/soutenances/{id}/edit', [SoutenanceController::class, 'edit'])->name('soutenances.edit');
Route::put('/soutenances/{id}', [SoutenanceController::class, 'update'])->name('soutenances.update');
Route::delete('/soutenances/{id}', [SoutenanceController::class, 'destroy'])->name('soutenances.destroy');
Route::get('/', function () {
    return view('welcome');


});
