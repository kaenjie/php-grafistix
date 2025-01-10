<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;

Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth:sanctum');


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/abouts-first', [App\Http\Controllers\AboutController::class, 'first']);
Route::get('/banners-first', [App\Http\Controllers\BannerController::class, 'first']);
Route::apiResource('/posts', App\Http\Controllers\Api\PostController::class);
Route::apiResource('/orders', App\Http\Controllers\OrderController::class);
Route::apiResource('/packages', App\Http\Controllers\PackageController::class);
Route::apiResource('/users', App\Http\Controllers\UserController::class);
Route::apiResource('/titles', App\Http\Controllers\TitleController::class);
Route::apiResource('/photos', App\Http\Controllers\PhotoController::class);
Route::apiResource('/testimonis', App\Http\Controllers\TestimoniController::class);
Route::apiResource('/abouts', App\Http\Controllers\AboutController::class);
Route::apiResource('/faqs', App\Http\Controllers\FAQController::class);
Route::apiResource('/banners', App\Http\Controllers\BannerController::class);
Route::apiResource('/option-packages', App\Http\Controllers\OptionPackageController::class);
Route::apiResource('/package-categories', App\Http\Controllers\PackageCategoryController::class);