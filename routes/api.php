<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// --------------------  ALL ROUTE FOR API ENDPOINT  -------------------------
//posts
Route::apiResource('/posts', App\Http\Controllers\Api\PostController::class);
//news
Route::apiResource('/news', App\Http\Controllers\Api\NewsController::class);
//booking
Route::apiResource('/bookings', App\Http\Controllers\Api\BookingController::class);
//payment
Route::apiResource('/payments', App\Http\Controllers\Api\PaymentController::class);
//transaction
Route::apiResource('/transactions', App\Http\Controllers\Api\TransactionController::class);
