<?php

use Illuminate\Support\Facades\Route;
use Buckaroo\Laravel\Http\Controller\BuckarooController;

Route::post('buckaroo/push', [BuckarooController::class, 'handlePush'])->name('buckaroo.push');
