<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StocksLevelController;
use App\Http\Controllers\PageController;  // Import PageController

// Root route
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// View routes handled by PageController
Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard'); // Dashboard page
// For rendering the stocks page
Route::get('/stock', [PageController::class, 'stocks_page'])->name('stock');

Route::get('/reports', [PageController::class, 'reports'])->name('reports'); // Reports page

// Prefix '/stocks' is now handled here, no need to repeat '/stocks' in the route definition
Route::resource('stocks', StockController::class); // Resource route for StockController

// Prefix '/stockslevel' is now handled here, no need to repeat '/stockslevel' in the route definition
Route::resource('stockslevel', StocksLevelController::class); // Resource route for StockController

Route::get('/fetch-stocks', [StockController::class, 'fetchStocks'])->name('fetch.stocks');

Route::get('/filter-released-stocks', [StockController::class, 'filtersReleasedStocks'])->name('filter.released.stocks');

Route::get('/filter-activated-stocks', [StockController::class, 'filtersActivatedStocks'])->name('filter.activated.stocks');

Route::get('/filter-repaired-stocks', [StockController::class, 'filtersRepairedStocks'])->name('filter.repaired.stocks');

Route::get('/filter-dmur-stocks', [StockController::class, 'filtersdmurStocks']);

Route::get('/filter-released-stocks-options', [StockController::class, 'getFilterOptions']);

Route::get('/fetch-activated-years', [StockController::class, 'fetchactivatedYears'])->name('fetch.activated.years');

Route::get('/fetch-repaired-years', [StockController::class, 'fetchrepairedYears'])->name('fetch.repaired.years');

Route::get('/fetch-years', [StockController::class, 'fetchYears'])->name('fetch.years');

Route::get('/fetch-released-stocks', [StockController::class, 'fetchReleasedStocks'])->name('fetch.released.stocks');

Route::get('/activated-stocks', [StockController::class, 'fetchActivatedStocks']);

Route::get('/fetch-repaired-stocks', [StockController::class, 'fetchRepairedStocks'])->name('fetch.repaired.stocks');

Route::get('/dmur-stocks', [StockController::class, 'fetchdmurStocks']);

Route::get('/total-stocks-level', [StocksLevelController::class, 'getTotalstockslevel']);

Route::get('/total-active-descriptions', [StocksLevelController::class, 'getTotalActiveDescriptions']);

Route::get('/total-descriptions-excluding-active', [StocksLevelController::class, 'getTotalDescriptionsExcludingActive']);

