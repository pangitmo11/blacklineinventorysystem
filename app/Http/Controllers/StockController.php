<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\StockMaterial;
use App\Models\StocksLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class StockController extends Controller
{
    public function index()
    {
        // Eager load the stockMaterials and the related stocksdescLevel (description from stocks_level)
        $stocks = Stock::with(['stockMaterials.stocksdescLevel:id,description'])->get();

        // Return response as JSON with 'stocks' key
        return response()->json(['stocks' => $stocks]);
    }

    public function fetchStocks(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');
        $status = $request->input('status');

        $query = Stock::query();

        if ($month && $month !== 'All') {
            $query->whereMonth('date_active', '=', date('m', strtotime($month)));
        }

        if ($year && $year !== 'All') {
            $query->whereYear('date_active', '=', $year);
        }

        if (isset($status) && $status !== 'All') {
            $query->where('status', '=', $status); // Make sure the column 'status' values match the filter
        }

        $stocks = $query->get();

        return response()->json(['stocks' => $stocks]);
    }

    public function filtersReleasedStocks(Request $request)
    {
        $team_tech = $request->input('team_tech');
        $month = $request->input('month');
        $year = $request->input('year');

        $releasedStocks = Stock::with(['stockMaterials.stocksdescLevel:id,description'])
            ->when($team_tech !== 'All', function ($query) use ($team_tech) {
                $query->where('team_tech', $team_tech);
            })
            ->when($month !== 'All', function ($query) use ($month) {
                $query->whereMonth('date_released', $month);
            })
            ->when($year !== 'All', function ($query) use ($year) {
                $query->whereYear('date_released', $year);
            })
            ->whereNotNull('date_released')
            ->where('status', 1)
            ->get();

        $releasedStocks = $releasedStocks->map(function ($stock) {
            $stock->total_quantity = $stock->stockMaterials->count();
            return $stock;
        });

        return response()->json(['stocks' => $releasedStocks]);
    }


    public function filtersActivatedStocks(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');

        $query = Stock::query();

        // Filter by month if provided and not 'All'
        if ($month && $month !== 'All') {
            $query->whereMonth('date_used', '=', $month);
        }

        // Filter by year if provided and not 'All'
        if ($year && $year !== 'All') {
            $query->whereYear('date_used', '=', $year);
        }

        // Filter by status (include status 0 = Activation and 2 = Activated)
        $query->whereIn('status', [0, 2]);

        // Fetch the filtered data
        $stocks = $query->get();

        // Send the response back as JSON
        return response()->json([
            'stocks' => $stocks,
            'year' => $year,
        ]);
    }


    public function filtersRepairedStocks(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');

        $query = Stock::query();

        // Filter by month if provided and not 'All'
        if ($month && $month !== 'All') {
            $query->whereMonth('date_repaired', '=', $month);
        }

        // Filter by year if provided and not 'All'
        if ($year && $year !== 'All') {
            $query->whereYear('date_repaired', '=', $year);
        }

        // Filter by active status (status = 1)
        $query->where('status', '=', 3);

        // Fetch the filtered data
        $stocks = $query->get();

        // Send the response back as JSON
        return response()->json([
            'stocks' => $stocks,
            'year' => $year
        ]);
    }

    public function filtersdmurStocks(Request $request)
    {
        // Retrieve filters from the request
        $month = $request->input('month');
        $year = $request->input('year');

        // Initialize query
        $query = Stock::with(['stockMaterials.stocksdescLevel:id,description']);

        // Apply month filter if provided and not 'All'
        if ($month && $month !== 'All') {
            $query->whereMonth('date_used', '=', $month);
        }

        // Apply year filter if provided and not 'All'
        if ($year && $year !== 'All') {
            $query->whereYear('date_used', '=', $year);
        }

        // Fetch the filtered data
        $stocks = $query->get();

        // Send the response back as JSON
        return response()->json([
            'stocks' => $stocks,
            'year' => $year
        ]);
    }

    public function fetchYears()
    {
        $years = Stock::selectRaw('YEAR(date_active) as year')
            ->whereNotNull('date_active')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return response()->json($years);
    }

    public function fetchactivatedYears()
    {
        $years = Stock::selectRaw('YEAR(date_used) as year')
            ->whereNotNull('date_used')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return response()->json($years);
    }

    public function fetchrepairedYears()
    {
        $years = Stock::selectRaw('YEAR(date_repaired) as year')
            ->whereNotNull('date_repaired')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return response()->json($years);
    }


    public function getFilterOptions()
    {
        // Fetch distinct team_tech values for the filter with status = 1
        $teamTechs = Stock::select('team_tech')
            ->where('status', 1)
            ->distinct()
            ->get();

        // Fetch distinct years from date_released for the filter, excluding null values
        $years = Stock::selectRaw('YEAR(date_released) as year')
            ->whereNotNull('date_released')
            ->distinct()
            ->get();

        // Send the response back as JSON
        return response()->json([
            'team_techs' => $teamTechs,
            'years' => $years
        ]);
    }

    public function fetchReleasedStocks()
    {
        $releasedStocks = Stock::with(['stockMaterials.stocksdescLevel:id,description'])
            ->whereNotNull('date_released') // Ensure 'date_released' is not null
            ->whereNotNull('team_tech')
            ->where('status', 1) // Released status
            ->where('status', '!=', 0)
            ->where('status', '!=', 2)
            ->where('status', '!=', 3)
            ->get();

        // Calculate total quantity for each stock
        $releasedStocks = $releasedStocks->map(function ($stock) {
            // Calculate the total count of all stock materials
            $totalQuantity = $stock->stockMaterials->count();

            $stock->total_quantity = $totalQuantity;

            return $stock;
        });

        return response()->json($releasedStocks);
    }

    public function fetchActivatedStocks()
    {
        // Fetch all records where 'j_o_no' and 'date_used' are not null
        $activatedstocks = Stock::with(['stockMaterials.stocksdescLevel:id,description'])
            ->whereNotNull('date_used')
            ->whereNotNull('j_o_no')
            ->whereIn('status', [0, 2]) // Include Activation (0) and Activated (2) statuses
            ->get();

        // Return the data as JSON for the frontend to render
        return response()->json($activatedstocks);
    }


    public function fetchRepairedStocks(Request $request)
    {
        // Fetch all records where 'j_o_no' and 'date_used' are not null
        $repairedstocks = Stock::whereNotNull('serial_new_no')
                        ->whereNotNull('ticket_no')
                        ->whereNotNull('date_repaired')
                        ->where('status', 3)   // Include only repaired status
                        ->get();

        // Return the data as JSON for the frontend to render
        return response()->json($repairedstocks);
    }

    public function fetchdmurStocks()
    {
        // Fetch all records where 'j_o_no' and 'date_used' are not null
        $dmurstocks = Stock::with(['stockMaterials.stocksdescLevel:id,description'])
                        ->whereNotNull('sar_no')
                        ->whereNotNull('subsaccount_no')
                        ->whereNotNull('subsname')
                        ->get();

        // Return the data as JSON for the frontend to render
        return response()->json($dmurstocks);
    }

    public function store(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'product_name' => 'nullable|string|max:255',
            'description_id' => 'nullable|array', // Expect an array of description_ids
            'description_id.*' => 'integer|exists:stocks_level,id', // Ensure each description_id exists in stocks_level
            'team_tech' => 'nullable|string|max:255',
            'subsname' => 'nullable|string|max:255',
            'subsaccount_no' => 'nullable|string|max:255',
            'account_no' => 'nullable|string|max:255',
            'j_o_no' => 'nullable|string|max:255',
            'sar_no' => 'nullable|string|max:255',
            'serial_no' => 'nullable|string|max:255',
            'serial_new_no' => 'nullable|string|max:255',
            'ticket_no' => 'nullable|string|max:255',
            'date_active' => 'nullable|date',
            'date_released' => 'nullable|date',
            'date_used' => 'nullable|date',
            'date_repaired' => 'nullable|date',
            'status' => 'nullable|in:0,1,2,3,4',
        ]);

        // Extract only the required fields from the request
        $stocksData = $request->only([
            'product_name',
            'team_tech',
            'subsname',
            'subsaccount_no',
            'account_no',
            'j_o_no',
            'sar_no',
            'serial_no',
            'serial_new_no',
            'ticket_no',
            'date_active',
            'date_released',
            'date_used',
            'date_repaired',
            'status',
        ]);

        // Create a new stock record
        $stocks = Stock::create($stocksData);

        // Prepare data for the relationship between stocks and stocks_level (stocks_materials)
        if ($request->has('description_id') && !empty($request->description_id)) {
            $stocksLevelData = [];
            foreach ($request->input('description_id') as $description_id) {
                $stocksLevelData[] = [
                    'stocks_id' => $stocks->id,
                    'description_id' => $description_id,
                ];
            }

            // Insert data into the stocks_materials table
            StockMaterial::insert($stocksLevelData);
        }

        // Update the status of the selected description_ids in the stocks_level table
        if ($request->has('description_id') && !empty($request->description_id)) {
            DB::table('stocks_level')
                ->whereIn('id', $request->input('description_id'))
                ->update(['stocks_level_status' => $request->status]);
        }

        // Return a success response
        return response()->json(['status' => 'success', 'message' => 'Stock created successfully.']);

    }

    public function show($id, Request $request)
    {
        $stocks = Stock::with(['stockMaterials.stocksdescLevel:id,description']) // Load description_id with the material description
            ->where('id', $id)
            ->first();

        return response()->json(['stocks' => $stocks]);
    }


    public function update(Request $request, $id)
    {
        // Validate incoming request
        $request->validate([
            'product_name' => 'nullable|string|max:255',
            'description_id' => 'nullable|array', // Expect an array of description_ids
            'description_id.*' => 'integer|exists:stocks_level,id', // Ensure each description_id exists in stocks_level
            'team_tech' => 'nullable|string|max:255',
            'subsname' => 'nullable|string|max:255',
            'subsaccount_no' => 'nullable|string|max:255',
            'account_no' => 'nullable|string|max:255',
            'j_o_no' => 'nullable|string|max:255',
            'sar_no' => 'nullable|string|max:255',
            'serial_no' => 'nullable|string|max:255',
            'serial_new_no' => 'nullable|string|max:255',
            'ticket_no' => 'nullable|string|max:255',
            'date_active' => 'nullable|date',
            'date_released' => 'nullable|date',
            'date_used' => 'nullable|date',
            'date_repaired' => 'nullable|date',
            'status' => 'nullable|in:0,1,2,3,4',
        ]);

        // Find the stock by ID
        $stocks = Stock::findOrFail($id);

        // Update the stock record with the incoming data
        $stocks->update($request->only([
            'product_name',
            'team_tech',
            'subsname',
            'subsaccount_no',
            'account_no',
            'j_o_no',
            'sar_no',
            'serial_no',
            'serial_new_no',
            'ticket_no',
            'date_active',
            'date_released',
            'date_used',
            'date_repaired',
            'status',
        ]));

        // Handle the relationship between stocks and stocks_level (stockMaterials)
        if ($request->has('description_id') && !empty($request->description_id)) {
            // Delete existing records in the stocks_materials table for this stock
            $stocks->stockMaterials()->delete();

            // Prepare data for the relationship and insert new records
            $stocksLevelData = [];
            foreach ($request->input('description_id') as $description_id) {
                $stocksLevelData[] = [
                    'stocks_id' => $stocks->id,
                    'description_id' => $description_id,
                ];
            }

            // Insert new relationships
            StockMaterial::insert($stocksLevelData);
        }

        // Update the status of the selected description_ids in the stocks_level table
        if ($request->has('description_id') && !empty($request->description_id)) {
            DB::table('stocks_level')
                ->whereIn('id', $request->input('description_id'))
                ->update(['stocks_level_status' => $request->status]);
        }

        // Return a success response
        return response()->json(['status' => 'success', 'message' => 'Stock updated successfully.']);
    }


    public function destroy($id)
    {
        try {
            // Find the stock record by ID
            $Stocks = Stock::findOrFail($id);

            // Get the related description IDs from the stockMaterials relationship
            $descriptionIds = $Stocks->stockMaterials()->pluck('description_id')->toArray();

            // Delete the stock record
            $Stocks->delete();

            // Update the status of the related description IDs in the stocks_level table
            if (!empty($descriptionIds)) {
                DB::table('stocks_level')
                    ->whereIn('id', $descriptionIds)
                    ->update(['stocks_level_status' => 4]);
            }

            // Return a success response
            return response()->json([
                'status' => 'success',
                'message' => 'Stock deleted successfully.',
                'data' => null,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Stock not found.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete stock. Please try again later.',
            ], 500);
        }
    }

}
