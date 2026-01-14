<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('gateway.auth')->group(function () {
    Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index']);
    Route::post('/orders', [\App\Http\Controllers\OrderController::class, 'store']);
});

