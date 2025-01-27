<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StocksLevelController;
use App\Http\Controllers\PortUtilizationController;
use App\Http\Controllers\TeamTechController;
use App\Http\Controllers\PageController;  // Import PageController

// Root route
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// View routes handled by PageController
Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard'); // Dashboard page
// For rendering the stocks page
Route::get('/stock', [PageController::class, 'stocks_page'])->name('stock');

Route::get('/port_utilization', [PageController::class, 'port_utilization'])->name('port_utilization'); // Port utilization page

Route::get('/materials_inventory_reports', [PageController::class, 'materials_inventory_reports'])->name('materials_inventory_reports'); // Reports page

Route::get('/teamtech_inventory_reports', [PageController::class, 'teamtech_inventory_reports'])->name('teamtech_inventory_reports'); // Reports page

Route::get('/stocks_inventory_reports', [PageController::class, 'stocks_inventory_reports'])->name('stocks_inventory_reports'); // Stocks inventory reports page

// Prefix '/stocks' is now handled here, no need to repeat '/stocks' in the route definition
Route::resource('stocks', StockController::class); // Resource route for StockController

// Prefix '/stockslevel' is now handled here, no need to repeat '/stockslevel' in the route definition
Route::resource('stockslevel', StocksLevelController::class); // Resource route for StockController

Route::resource('teamtech', TeamTechController::class); // Resource route for TeamTechController

Route::resource('portutilization', PortUtilizationController::class); // Resource route for PortUtilizationController

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

Route::get('/total-deployed-ports', [PortUtilizationController::class, 'totalDeployedPorts']);

Route::get('/total-active-ports', [PortUtilizationController::class, 'totalActivePorts']);

Route::get('/total-available-ports', [PortUtilizationController::class, 'totalAvailablePorts']);

Route::get('/utilization-percentage', [PortUtilizationController::class, 'getUtilizationPercentage']);

// Route for fetching municipalities
Route::get('/municipalities', [PortUtilizationController::class, 'getMunicipalities']);

// Route for fetching barangays based on the selected municipality
Route::get('/barangays', [PortUtilizationController::class, 'getBarangaysByMunicipality']);

// Route for fetching brgy codes based on the selected barangay
Route::get('/brgycodes', [PortUtilizationController::class, 'getBrgyCodesByBarangay']);;

// Route for fetching port utilization data with filters
Route::get('/portutilization', [PortUtilizationController::class, 'getPortUtilization']);

Route::get('/import-sheet-data', [PortUtilizationController::class, 'importGoogleSheetData']);

Route::get('/import-stocks-sheet-data', [StockController::class, 'importGoogleSheetData']);

Route::get('/get-stock-data', [StockController::class, 'getStockData']);

Route::get('/get-materials-data', [StocksLevelController::class, 'getMaterialsData']);

// Route for fetching available years based on the date_released field
Route::get('/get-available-years', [StockController::class, 'getAvailableYears'])->name('get.available.years');

Route::get('/tech-count', [TeamTechController::class, 'techcount']);

Route::get('/tech-assigned-count', [TeamTechController::class, 'countTeamTechAssigned']);

Route::post('/stocks/delete-multiple', [StockController::class, 'destroyMultiple'])->name('stocks.destroyMultiple');

Route::get('/team-tech-details', [StockController::class, 'teamTechDetails']);

