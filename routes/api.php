<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\OrderApiController;
use App\Http\Controllers\Api\WishlistApiController;
use App\Http\Controllers\Api\DiscountApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| PRODUCTS API
|--------------------------------------------------------------------------
*/
Route::prefix('products')->group(function () {
    Route::get('/', [ProductApiController::class, 'index']);
    Route::get('/top-selling', [ProductApiController::class, 'topSelling']);
    Route::get('/categories', [ProductApiController::class, 'categories']);
    Route::get('/{product}', [ProductApiController::class, 'show']);
});

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    
    /*
    |--------------------------------------------------------------------------
    | ORDERS API
    |--------------------------------------------------------------------------
    */
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderApiController::class, 'index']);
        Route::get('/{order}', [OrderApiController::class, 'show']);
        Route::post('/{order}/cancel', [OrderApiController::class, 'cancel']);
    });

    /*
    |--------------------------------------------------------------------------
    | WISHLIST API
    |--------------------------------------------------------------------------
    */
    Route::prefix('wishlists')->group(function () {
        Route::get('/', [WishlistApiController::class, 'index']);
        Route::post('/add', [WishlistApiController::class, 'add']);
        Route::delete('/{product}', [WishlistApiController::class, 'remove']);
        Route::get('/{product}/check', [WishlistApiController::class, 'check']);
    });

    /*
    |--------------------------------------------------------------------------
    | DISCOUNT API
    |--------------------------------------------------------------------------
    */
    Route::post('/discounts/validate', [DiscountApiController::class, 'validateDiscount']);
});

/*
|--------------------------------------------------------------------------
| PAYMENT WEBHOOK API (SEPAY / CASSO / BANK AUTO-SYNC)
|--------------------------------------------------------------------------
*/
Route::post('/payment/webhook', [\App\Http\Controllers\Api\PaymentWebhookController::class, 'handle']);
Route::post('/payment/webhook/sepay', [\App\Http\Controllers\Api\PaymentWebhookController::class, 'handle']);


