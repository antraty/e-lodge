<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\LogController;
use Illuminate\Support\Facades\Route;

// Route accueil
Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
  
    Route::resource('rooms', App\Http\Controllers\RoomController::class)->except(['show']);
    Route::post('rooms/check-availability', [App\Http\Controllers\RoomController::class, 'checkAvailability'])->name('rooms.checkAvailability');
    
    
    // API de recherche
    Route::get('api/clients/search', [ClientController::class, 'search'])
        ->name('clients.search');
    
    // Gestion des clients
    Route::resource('clients', App\Http\Controllers\ClientController::class)->except(['show']);
    
    // Gestion des réservations
    Route::get('reservations/history', [App\Http\Controllers\ReservationController::class, 'history'])->name('reservations.history');
    Route::resource('reservations', App\Http\Controllers\ReservationController::class)->whereNumber('reservation');
    // Invoice PDF for reservation
    Route::get('reservations/{reservation}/invoice', [App\Http\Controllers\ReservationController::class, 'invoice'])->name('reservations.invoice');
    
    // Gestion des paiements
    Route::get('payments/history', [PaymentController::class, 'history'])->name('payments.history');
    Route::resource('payments', PaymentController::class)
        ->only(['index','create','store','show','destroy'])
        ->whereNumber('payment');

    // Gestion des comptes utilisateurs
    Route::resource('users', App\Http\Controllers\UserController::class)->except(['show']);
    
    // Paramètres
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingController::class, 'store'])->name('settings.store');
    
    // Logs
    Route::get('logs', [LogController::class, 'index'])->name('logs.index');
    Route::get('logs/{log}', [LogController::class, 'show'])->name('logs.show');
});
