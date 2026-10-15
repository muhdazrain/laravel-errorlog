<?php

use Azrain\LaravelErrorLog\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(config('errorlog.dashboard.middleware', ['web', 'errorlog.auth']))
    ->prefix(config('errorlog.dashboard.path', 'errorlog-viewer'))
    ->name('errorlog.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('index');
        Route::get('/group/{fingerprint}', [DashboardController::class, 'show'])->name('show');
    });
