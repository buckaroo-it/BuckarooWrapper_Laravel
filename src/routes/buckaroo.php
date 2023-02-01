<?php

use Illuminate\Support\Facades\Route;
use Buckaroo\Laravel\Http\Controller\BuckarooController;

Route::post('/webhook/buckaroo', [BuckarooController::class, 'handlePush'])->name('buckaroo.push');
