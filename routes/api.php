<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware'    => 'locale'
], function () {

    Route::get('services', [\App\Http\Controllers\Api\ServiceController::class, 'index']);

    Route::controller(\App\Http\Controllers\Api\UserController::class)->group(function() {
        Route::post('register', 'register');
        Route::post('login', 'login');
    });

    Route::middleware(['auth:sanctum'])->group(function () {

        Route::get('company/get', [\App\Http\Controllers\Api\CompanyController::class, 'getCompany']);

        Route::get('user/get', [\App\Http\Controllers\Api\CompanyController::class, 'getUser']);

        Route::post('tickets/store', [\App\Http\Controllers\Api\OrderController::class, 'storeTicket']);

        Route::post('supplies/store', [\App\Http\Controllers\Api\OrderController::class, 'storeSupply']);

        Route::post('requests/store', [\App\Http\Controllers\Api\OrderController::class, 'storeRequest']);

        Route::get('user/orders', [\App\Http\Controllers\Api\OrderController::class, 'get']);

        Route::post('order/{id}/status', [\App\Http\Controllers\Api\OrderController::class, 'changeStatus']);

        Route::post('comment/store', [\App\Http\Controllers\Api\OrderController::class, 'storeComment']);

    });
});

