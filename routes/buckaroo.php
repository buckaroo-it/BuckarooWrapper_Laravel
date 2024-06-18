<?php

use Buckaroo\Laravel\Http\Controllers\PushController;
use Buckaroo\Laravel\Http\Controllers\ReturnController;
use Illuminate\Support\Facades\Route;

Route::prefix('buckaroo')
    ->as('buckaroo.')
    ->group(function () {
        Route::post('push', PushController::class)->name('push');
        Route::match(['POST', 'GET'], 'return', ReturnController::class)->name('return');
    });
