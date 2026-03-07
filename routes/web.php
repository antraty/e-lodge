<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\LogController;

// Route accueil
Route::get('/', function () {
    return view('welcome');
});

// Routes d'authentification (administrateur)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');

// Routes protégées (authentifiées)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Gestion des chambres
    Route::resource('rooms', App\Http\Controllers\RoomController::class)->except(['show']);
    Route::post('rooms/check-availability', [App\Http\Controllers\RoomController::class, 'checkAvailability'])->name('rooms.checkAvailability');
    
    // Gestion des clients
    Route::resource('clients', App\Http\Controllers\ClientController::class)->except(['show']);
    
    // Gestion des réservations
    // history route must be declared BEFORE the resource so /reservations/history is not captured by {reservation}
    Route::get('reservations/history', [App\Http\Controllers\ReservationController::class, 'history'])->name('reservations.history');
    Route::resource('reservations', App\Http\Controllers\ReservationController::class)->whereNumber('reservation');
    // Invoice PDF for reservation
    Route::get('reservations/{reservation}/invoice', [App\Http\Controllers\ReservationController::class, 'invoice'])->name('reservations.invoice');
    
    // Gestion des paiements
    // history route must be declared before the resource so that /payments/history
    // is not captured by the {payment} wildcard of the resource show route.
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
