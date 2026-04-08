<?php

use App\Livewire\Permissions\Index as PermissionsIndex;
use App\Livewire\Retornos\Index as RetornosIndex;
use App\Livewire\Movements\Index as MovementsIndex;
use App\Livewire\Sales\Index as SalesIndex;
use App\Livewire\Roles\Index as RolesIndex;
use App\Livewire\Users\Index as UsersIndex;
use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\ConceptController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeliveryKpiController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\LotController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\CarboyController;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('dashboard/export-finance-report', [DashboardController::class, 'exportFinanceReport'])->name('dashboard.export-finance-report');
    Route::get('delivery-kpi', [DeliveryKpiController::class, 'index'])->name('delivery-kpi.index');
    Route::get('delivery-kpi/export-sales-report', [DeliveryKpiController::class, 'exportSalesReport'])->name('delivery-kpi.export-sales-report');
    Route::livewire('users', UsersIndex::class)->name('users.index');
    Route::livewire('roles', RolesIndex::class)->name('roles.index');
    Route::livewire('permissions', PermissionsIndex::class)->name('permissions.index');

    Route::controller(RouteController::class)->name('routes.')->group(function () {
        Route::get('routes', 'index')->name('index');
        Route::post('routes', 'store')->name('store');
        Route::get('routes/{route}', 'edit')->name('edit');
        Route::put('routes/{route}', 'update')->name('update');
        Route::delete('routes/{route}', 'destroy')->name('destroy');
    });

    Route::controller(CustomerController::class)->name('customers.')->group(function () {
        Route::get('customers', 'index')->name('index');
        Route::post('customers', 'store')->name('store');
        Route::get('customers/{customer}', 'edit')->name('edit');
        Route::put('customers/{customer}', 'update')->name('update');
        Route::delete('customers/{customer}', 'destroy')->name('destroy');
    });

    Route::controller(ConceptController::class)->name('concepts.')->group(function () {
        Route::get('concepts', 'index')->name('index');
        Route::post('concepts', 'store')->name('store');
        Route::get('concepts/{concept}', 'edit')->name('edit');
        Route::put('concepts/{concept}', 'update')->name('update');
        Route::delete('concepts/{concept}', 'destroy')->name('destroy');
    });

    Route::livewire('retornos', RetornosIndex::class)->name('retornos.index');
    Route::livewire('sales', SalesIndex::class)->name('sales.index');

    Route::controller(LotController::class)->name('lots.')->group(function () {
        Route::get('lots', 'index')->name('index');
        Route::post('lots', 'store')->name('store');
        Route::get('lots/{lot}', 'edit')->name('edit');
        Route::put('lots/{lot}', 'update')->name('update');
        Route::delete('lots/{lot}', 'destroy')->name('destroy');
    });

    Route::controller(CarboyController::class)->name('carboys.')->group(function () {
        Route::get('carboys', 'index')->name('index');
        Route::post('carboys', 'store')->name('store');
        Route::get('carboys/{carboy}', 'edit')->name('edit');
        Route::put('carboys/{carboy}', 'update')->name('update');
        Route::delete('carboys/{carboy}', 'destroy')->name('destroy');
    });

    Route::livewire('movements', MovementsIndex::class)->name('movements.index');

    Route::controller(IncomeController::class)->name('incomes.')->group(function () {
        Route::get('incomes', 'index')->name('index');
        Route::post('incomes', 'store')->name('store');
        Route::get('incomes/{systemIncome}', 'edit')->name('edit');
        Route::put('incomes/{systemIncome}', 'update')->name('update');
        Route::delete('incomes/{systemIncome}', 'destroy')->name('destroy');
    });

    Route::controller(ExpenseController::class)->name('expenses.')->group(function () {
        Route::get('expenses', 'index')->name('index');
        Route::post('expenses', 'store')->name('store');
        Route::get('expenses/{expense}', 'edit')->name('edit');
        Route::put('expenses/{expense}', 'update')->name('update');
        Route::delete('expenses/{expense}', 'destroy')->name('destroy');
    });
});

require __DIR__.'/settings.php';
