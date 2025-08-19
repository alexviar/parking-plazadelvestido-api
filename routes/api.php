<?php

use App\Http\Controllers\TariffController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(TariffController::class)
    ->prefix('tariffs')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('/', 'index')->withoutMiddleware('auth:sanctum');
        Route::get('/{tariff}', 'show')->withoutMiddleware('auth:sanctum');
        Route::post('/', 'store');
        Route::put('/{tariff}', 'update');
        Route::delete('/{tariff}', 'destroy');
    });
