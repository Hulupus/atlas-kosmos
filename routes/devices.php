<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::prefix('devices')->group(function () {
    Route::name('devices.')->group(function () {
        Route::middleware('auth')->group(function () {
            Route::get('/', function () {
                return Inertia::render('devices/Index');
            })->name('index');

            Route::get('/epimetheus', function () {
                return Inertia::render('devices/Epimetheus');
            })->name('epimetheus');

            Route::get('/prometheus', function () {
                return Inertia::render('devices/Prometheus');
            })->name('prometheus');
        });
    });
});
