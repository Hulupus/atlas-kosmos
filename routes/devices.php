<?php

use App\Http\Controllers\DeviceController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::prefix('devices')->group(function () {

    Route::name('devices.')->group(function () {

        Route::middleware('auth')->group(function () {

            Route::get('/', [DeviceController::class, 'index'])
                ->name('index');

            Route::get('/create', [DeviceController::class, 'create'])
                ->name('create');

            Route::post('/', [DeviceController::class, 'store'])
                ->name('store');

            Route::get('/{device}/edit', [DeviceController::class, 'edit'])
                ->name('edit');

            Route::get('/epimetheus', function () {
                return Inertia::render('devices/Epimetheus');
            })->name('epimetheus');

            Route::get('/prometheus', function () {
                return Inertia::render('devices/Prometheus');
            })->name('prometheus');
        });
    });
});
