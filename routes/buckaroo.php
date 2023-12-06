<?php

use Buckaroo\Laravel\Http\Controllers\BuckarooController;
use Illuminate\Support\Facades\Route;

Route::controller(BuckarooController::class)->prefix('buckaroo')->name('buckaroo.')->group(function () {
    Route::post('return', 'return')->name('return');
    Route::post('return/cancel', 'returnCancel')->name('return-cancel');
    Route::post('return/error', 'returnError')->name('return-error');
    Route::post('return/reject', 'returnReject')->name('return-reject');

    Route::post('push', 'push')->name('push');
});

