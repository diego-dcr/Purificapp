<?php

use App\Http\Controllers\Api\MobileAuthController;
use App\Http\Controllers\Api\MobileCatalogController;
use App\Http\Controllers\Api\MobileRetornoController;
use App\Http\Controllers\Api\MobileSaleController;
use Illuminate\Support\Facades\Route;

Route::prefix('mobile')->group(function (): void {
    Route::post('auth/login', [MobileAuthController::class, 'store']);

    Route::get('concepts', [MobileCatalogController::class, 'concepts']);
    Route::get('customers', [MobileCatalogController::class, 'customers']);
    Route::get('routes', [MobileCatalogController::class, 'routes']);

    Route::post('sales', [MobileSaleController::class, 'store']);
    Route::post('retornos', [MobileRetornoController::class, 'store']);
});