<?php

use App\Http\Controllers\Api\MobileAuthController;
use App\Http\Controllers\Api\MobileCatalogController;
use App\Http\Controllers\Api\MobileInputController;
use App\Http\Controllers\Api\MobileOutputController;
use Illuminate\Support\Facades\Route;

Route::prefix('mobile')->group(function (): void {
    Route::post('auth/login', [MobileAuthController::class, 'store']);

    Route::get('concepts', [MobileCatalogController::class, 'concepts']);
    Route::get('customers', [MobileCatalogController::class, 'customers']);
    Route::get('routes', [MobileCatalogController::class, 'routes']);

    Route::post('inputs', [MobileInputController::class, 'store']);
    Route::post('outputs', [MobileOutputController::class, 'store']);
});