<?php

use App\Http\Controllers\BonjourController;
use App\Http\Controllers\ChambreController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
 
Route::get('/chambres/create', [ChambreController::class, 'create'])->name('chambres.create');

Route::post('/chambres', [ChambreController::class, 'store'])->name('chambres.store');

Route::get('/chambres', [ChambreController::class, 'index'])->name('chambres.index');

// Afficher le formulaire
Route::get('/chambres/{chambre}/edit', [ChambreController::class, 'edit'])->name('chambres.edit');

// Enregistrer la modification
Route::put('/chambres/{chambre}', [ChambreController::class, 'update'])->name('chambres.update');

// Supprimer une chambre
Route::delete('/chambres/{chambre}', [ChambreController::class, 'destroy'])->name('chambres.destroy');