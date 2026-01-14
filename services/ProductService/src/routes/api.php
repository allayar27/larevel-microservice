<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::apiResource('products', \App\Http\Controllers\ProductController::class);
Route::apiResource('categories', CategoryController::class);
