<?php

use App\Http\Controllers\BonjourController;
use App\Http\Controllers\ChambreController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
 
Route::get('/chambres/create', [ChambreController::class, 'create'])->name('chambres.create');

Route::post('/chambres', [ChambreController::class, 'store'])->name('chambres.store');