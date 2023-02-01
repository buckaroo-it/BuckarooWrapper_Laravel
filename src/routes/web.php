<?php

use Illuminate\Support\Facades\Route;
use Buckaroo\Laravel\Http\Controller\BuckarooController;

Route::post('/webhook/buckaroo', [BuckarooController::class, 'handle'])->name('webhook.buckaroo');
