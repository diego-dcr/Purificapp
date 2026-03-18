<?php

use App\Livewire\Users\Index as UsersIndex;
use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\RouteController;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::livewire('users', UsersIndex::class)->name('users.index');

    Route::controller(RouteController::class)->name('routes.')->group(function () {
        Route::get('routes', 'index')->name('index');
        Route::post('routes', 'store')->name('store');
        Route::get('routes/{route}', 'edit')->name('edit');
        Route::put('routes/{route}', 'update')->name('update');
        Route::delete('routes/{route}', 'destroy')->name('destroy');
    });
});

require __DIR__.'/settings.php';
