<?php
use Illuminate\Support\Facades\Route;

use App\Buckaroo\Http\Controllers\PushController;
use App\Buckaroo\Http\Controllers\ReturnController;

Route::controller(ReturnController::class)->group(function () {
    Route::post('return', 'return')->name('return');
    Route::post('return/cancel', 'returnCancel')->name('return-cancel');
    Route::post('return/error', 'returnError')->name('return-error');
    Route::post('return/reject', 'returnReject')->name('return-reject');
});

Route::post('push', [PushController::class, 'push'])->name('push');