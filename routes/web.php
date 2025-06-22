<?php

use App\Http\Controllers\DataviewController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/', [HomeController::class, 'index'])
        ->name('home');

    Route::get('/measurements', [DataviewController::class, 'databaseView'])
        ->name('measurements');

    Route::get('/graphs', [DataviewController::class, 'graphView'])
        ->name('graphs');

});


require __DIR__ . '/devices.php';
require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
