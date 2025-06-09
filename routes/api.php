<?php

use App\Http\Controllers\AktivController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Blade\ApiUserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/


# Api Clients
Route::post('/login', [ApiAuthController::class, 'login']);

Route::get('/aktivs', [AktivController::class, 'getLots']);
Route::get('/taklif/aktivs', [AktivController::class, 'getTaklifLots']);

 Route::get('/aktivs/filter', [AktivController::class, 'apiFilter']);

    // Get statistics for dashboard
    Route::get('/aktivs/statistics', [AktivController::class, 'apiStatistics']);

    // Get single aktiv details
    Route::get('/aktivs/{aktiv}', [AktivController::class, 'show']);

Route::group(['middleware' => 'api-auth'], function () {
    Route::post('/me', [ApiAuthController::class, 'me']);
    Route::post('/tokens', [ApiAuthController::class, 'getAllTokens']);
    Route::post('/logout', [ApiAuthController::class, 'logout']);
});

Route::group(['middleware' => 'ajax.check'], function () {
    Route::post('/api-user/toggle-status/{user_id}', [ApiUserController::class, 'toggleUserActivation']);
    Route::post('/api-token/toggle-status/{token_id}', [ApiUserController::class, 'toggleTokenActivation']);
});

# Api Products
Route::get('/get/products', [ProductController::class, 'allProduct'])->name('productApiAll');
Route::get('/get/product/{id}', [ProductController::class, 'showProduct'])->name('productApiDetails');
Route::post('/product/update/{id}', [ProductController::class, 'updateProduct'])->name('productApiUpdate');
Route::delete('/product/delete/{id}', [ProductController::class, 'deleteProduct'])->name('productApiDelete');

// Route::post('/get/orders',[OrderController::class, 'getOrders'])->name('orderAll');
// Route::delete('/order/delete/{id}',[OrderController::class, 'deleteOrder'])->name('orderDelete');
// Route::post('/order/complete/{id}',[OrderController::class, 'completeOrder'])->name('orderComplate');
// Route::post('/order/update/{id}',[OrderController::class, 'updateOrderStatus'])->name('orderUpdate');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
