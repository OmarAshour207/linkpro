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
        Route::get('companies/{id}', [\App\Http\Controllers\Api\CompanyController::class, 'get']);
    });
});

