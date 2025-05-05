<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WebhookController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/user/register', RegisterController::class);
Route::post('/user/login', LoginController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/payment', [PaymentController::class, 'pay'])->name('payment.pay');

    Route::apiResource('carts', CartController::class);
    Route::apiResource('cartItems', CartItemController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('products', ProductController::class);
});

// Webhook route for Stripe
Route::post('/webhook/stripe', WebhookController::class)->name('webhook.stripe');
Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/payment/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');