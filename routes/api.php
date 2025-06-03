<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\MidtransWebhookController;

Route::post('/midtrans/webhook', [MidtransWebhookController::class, 'handle']);


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
//transaction
Route::apiResource('/transactions', App\Http\Controllers\Api\TransactionController::class);
//komunitas
Route::apiResource('/komunitas', App\Http\Controllers\Api\KomunitasController::class);
//test
Route::apiResource('/test', App\Http\Controllers\Api\TestController::class);
//court
Route::apiResource('/courts', App\Http\Controllers\Api\CourtController::class);
//midtrans
Route::apiResource('/midtrans', App\Http\Controllers\Api\MidtransController::class);

// routes/api.php
Route::get('transactions/by-order/{orderId}', [TransactionController::class, 'getByOrderId']);
Route::get('/transactions/{id}/generate-snap', [TransactionController::class, 'generateSnapToken']);

