<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('/posts', App\Http\Controllers\Api\PostController::class);
Route::apiResource('/orders', App\Http\Controllers\OrderController::class);
Route::apiResource('/packages', App\Http\Controllers\PackageController::class);
Route::apiResource('/users', App\Http\Controllers\UserController::class);
Route::apiResource('/titles', App\Http\Controllers\TitleController::class);
Route::apiResource('/photos', App\Http\Controllers\PhotoController::class);