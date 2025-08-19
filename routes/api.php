<?php

use App\Http\Controllers\TariffController;
use App\Http\Controllers\TicketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(TariffController::class)
    ->prefix('tariffs')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('/', 'index');
        Route::get('/{tariff}', 'show');
        Route::post('/', 'store');
        Route::put('/{tariff}', 'update');
        Route::delete('/{tariff}', 'destroy');
    });

Route::controller(TicketController::class)
    ->prefix('tickets')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('/', 'index');
        Route::get('/{ticket}', 'show');
        Route::post('/', 'store');
        Route::put('/{ticket}', 'update');
        Route::delete('/{ticket}', 'destroy');
    });
