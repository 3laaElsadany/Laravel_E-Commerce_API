<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BrandController;


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


// User Authentication Routes

Route::controller(AuthController::class)->group(function () {
    Route::post('/register',  'register');
    Route::post('/login', 'login');

    Route::middleware('jwt')->group(function () {
        Route::get('/user',  'getUser');
        Route::put('/user',  'updateUser');
        Route::post('/logout',  'logout');
    });
});


Route::middleware('jwt')->group(function () {
    Route::middleware('admin')->group(function () {
        // Brand Routes
        Route::apiResource('brands', BrandController::class);

        // Category Routes
        Route::apiResource('categories', CategoryController::class);
    });


    // Location Routes
    Route::apiResource('locations', LocationController::class)->only(['store', 'update', 'destroy']);

    // Product Routes
    Route::apiResource('products', ProductController::class);




    // Order Routes
    Route::group(['prefix' => 'orders', 'middleware' => 'admin', 'controller' => OrderController::class], function () {
        Route::get('/getOrderItems/{order}',  'getOrderItems');
        Route::get('/getUserOrders/{userId}',  'getUserOrders');
        Route::put('/updateOrderStatus/{order}',  'updateStatus');
    });
    Route::apiResource('/orders', OrderController::class)->only(['index', 'show', 'store']);
});
