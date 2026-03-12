<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('dashboard'));

Route::get('/login',  [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');

Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Chambres
    Route::resource('rooms', App\Http\Controllers\RoomController::class)->except(['show']);
    Route::post('rooms/check-availability', [App\Http\Controllers\RoomController::class, 'checkAvailability'])->name('rooms.checkAvailability');

    // Clients
    Route::get('api/clients/search',              [ClientController::class, 'search'])->name('clients.search');
    Route::get('api/clients/{client}/reservations',[ClientController::class, 'reservations'])->name('clients.reservations');
    Route::resource('clients', App\Http\Controllers\ClientController::class)->except(['show']);

    // Réservations
    Route::get('reservations/history', [ReservationController::class, 'history'])->name('reservations.history');
    Route::resource('reservations', ReservationController::class)->whereNumber('reservation');
    Route::get('reservations/{reservation}/invoice', [ReservationController::class, 'invoice'])->name('reservations.invoice');

    // Paiements
    Route::get('payments/history', [PaymentController::class, 'history'])->name('payments.history');
    Route::resource('payments', PaymentController::class)
        ->only(['index','create','store','show','destroy'])
        ->whereNumber('payment');

    // Utilisateurs
    Route::resource('users', App\Http\Controllers\UserController::class)->except(['show']);

    // Paramètres
    Route::get('settings',  [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingController::class, 'store'])->name('settings.store');

    // Logs
    Route::get('logs',       [LogController::class, 'index'])->name('logs.index');
    Route::get('logs/{log}', [LogController::class, 'show'])->name('logs.show');
});