<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BonjourController;
use App\Http\Controllers\ChambreController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;


// Routes d'authentification (PUBLIQUES)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Routes protégées (PRIVÉES)
Route::middleware('auth')->group(function () {
    // Déconnexion
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Profil utilisateur
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [AuthController::class, 'changePassword'])->name('password.update');


 Route::get('/', function () {
        return view('welcome');
    })->name('welcome');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
 
Route::get('/chambres/create', [ChambreController::class, 'create'])->name('chambres.create');
Route::post('/chambres', [ChambreController::class, 'store'])->name('chambres.store');
Route::get('/chambres', [ChambreController::class, 'index'])->name('chambres.index');
Route::get('/chambres/{chambre}/edit', [ChambreController::class, 'edit'])->name('chambres.edit');
Route::put('/chambres/{chambre}', [ChambreController::class, 'update'])->name('chambres.update');
Route::delete('/chambres/{chambre}', [ChambreController::class, 'destroy'])->name('chambres.destroy');

// Routes Clients
Route::get('/clients/create', [ClientController::class, 'create'])->name('clients.create');
Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');
Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
Route::get('/clients/{client}/edit', [ClientController::class, 'edit'])->name('clients.edit');
Route::put('/clients/{client}', [ClientController::class, 'update'])->name('clients.update');
Route::delete('/clients/{client}', [ClientController::class, 'destroy'])->name('clients.destroy');

// Routes Reservations
Route::get('/reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
Route::get('/reservations/{reservation}/edit', [ReservationController::class, 'edit'])->name('reservations.edit');
Route::put('/reservations/{reservation}', [ReservationController::class, 'update'])->name('reservations.update');
Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');


// Paiements et facturation
Route::get('/paiements/create', [PaiementController::class, 'create'])->name('paiements.create');
Route::post('/paiements/store', [PaiementController::class, 'store'])->name('paiements.store');
Route::get('/paiements', [PaiementController::class, 'index'])->name('paiements.index');
Route::get('/paiements/{id}/edit', [PaiementController::class, 'edit'])->name('paiements.edit');
Route::put('/paiements/{id}', [PaiementController::class, 'update'])->name('paiements.update');
Route::delete('/paiements/{id}', [PaiementController::class, 'destroy'])->name('paiements.destroy');

// Routes factures
Route::post('/factures/generer/{reservation}', [FactureController::class, 'generer'])->name('factures.generer');
Route::get('/factures/{facture}', [FactureController::class, 'show'])->name('factures.show');

});