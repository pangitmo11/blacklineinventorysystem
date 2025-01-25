<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\StockMaterial;
use App\Models\StocksLevel;
use App\Models\TeamTech;
use App\Services\GoogleSheetsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class StockController extends Controller
{
    public function index()
    {
        // Eager load the stockMaterials and the related stocksdescLevel (description from stocks_level)
        $stocks = Stock::with([
            'stockMaterials.stocksdescLevel:id,description',
            'techName:id,tech_name'])->get();

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
    // Get filter inputs
    $tech_name_id = $request->input('tech_name_id');
    $month = $request->input('month');
    $year = $request->input('year');

    // Fetch released stocks with filters applied
    $releasedStocks = Stock::with(['stockMaterials.stocksdescLevel:id,description', 'techName:id,tech_name'])
        ->when($tech_name_id !== 'All', function ($query) use ($tech_name_id) {
            $query->where('tech_name_id', $tech_name_id);
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

    // Calculate total quantity for each stock
    $releasedStocks = $releasedStocks->map(function ($stock) {
        $stock->total_quantity = $stock->stockMaterials->count();
        return $stock;
    });

    // Fetch all team techs with their names
    $teamTechs = TeamTech::select('id as tech_name_id', 'tech_name')->get();

    // Fetch available years from stocks
    $years = Stock::selectRaw('YEAR(date_released) as year')
        ->whereNotNull('date_released')
        ->groupBy('year')
        ->orderBy('year', 'desc')
        ->get();

    // Return response
    return response()->json([
        'stocks' => $releasedStocks,
        'team_techs' => $teamTechs,
        'years' => $years,
    ]);
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
        $query = Stock::with(['stockMaterials.stocksdescLevel:id,description',
            'techName:id,tech_name']);

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
        $teamTechs = Stock::select('tech_name_id', 'team_tech.tech_name')
            ->join('team_tech', 'stocks_tbl.tech_name_id', '=', 'team_tech.id')
            ->where('stocks_tbl.status', 1)
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
        $releasedStocks = Stock::with(['stockMaterials.stocksdescLevel:id,description',
            'techName:id,tech_name'])
            ->whereNotNull('date_released') // Ensure 'date_released' is not null
            ->whereNotNull('tech_name_id')
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
        $activatedstocks = Stock::with(['stockMaterials.stocksdescLevel:id,description','techName:id,tech_name'])
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
        $dmurstocks = Stock::with(['stockMaterials.stocksdescLevel:id,description','techName:id,tech_name'])
                        ->whereNotNull('sar_no')
                        ->whereNotNull('subsaccount_no')
                        ->whereNotNull('description_id')
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
            'tech_name_id' => 'nullable|exists:team_tech,id',
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
            'tech_name_id',
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
        ]);

        // Set default status to 5 if not provided in the request
        $stocksData['status'] = $request->input('status', 5);

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
                ->update(['stocks_level_status' => $stocksData['status']]);
        }

        // Return a success response
        return response()->json(['status' => 'success', 'message' => 'Stock created successfully.']);
    }


    public function show($id, Request $request)
    {
        $stocks = Stock::with(['stockMaterials.stocksdescLevel:id,description',
        'techName:id,tech_name']) // Load description_id with the material description
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
            'tech_name_id' => 'nullable|exists:team_tech,id',
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
            'tech_name_id',
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

    public function destroyMultiple(Request $request)
    {
        try {
            // Validate the request to ensure 'ids' is provided as an array
            $ids = $request->input('ids');
            if (!$ids || !is_array($ids)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid request. IDs must be an array.',
                ], 400);
            }

            // Find the stocks and related description IDs
            $stocks = Stock::whereIn('id', $ids)->get();
            if ($stocks->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No matching stocks found.',
                ], 404);
            }

            $descriptionIds = [];
            foreach ($stocks as $stock) {
                $descriptionIds = array_merge(
                    $descriptionIds,
                    $stock->stockMaterials()->pluck('description_id')->toArray()
                );
            }

            // Delete the stocks
            Stock::whereIn('id', $ids)->delete();

            // Update related descriptions in the stocks_level table
            if (!empty($descriptionIds)) {
                DB::table('stocks_level')
                    ->whereIn('id', $descriptionIds)
                    ->update(['stocks_level_status' => 4]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Selected stocks deleted successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete stocks. Please try again later.',
            ], 500);
        }
    }

    public function getAvailableYears(Request $request)
    {
        // Fetch distinct years based on the date_released field
        $years = Stock::selectRaw('YEAR(date_released) as year')
                    ->distinct()
                    ->orderBy('year', 'desc') // Optional: Sort years in descending order
                    ->pluck('year');

        return response()->json(['years' => $years]);
    }

    public function getStockData(Request $request)
    {
        // Get selected month and year from the request (fallback to current month/year)
        $month = $request->input('month');
        $year = $request->input('year');

        // Get selected start and end dates from the request (optional)
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Validate the inputs (set default values if not provided)
        $month = $month ? $month : Carbon::now()->month;
        $year = $year ? $year : Carbon::now()->year;

        // If start_date and end_date are provided, override the month/year filters
        if ($startDate && $endDate) {
            $startDate = Carbon::parse($startDate)->startOfDay(); // Start of the selected day
            $endDate = Carbon::parse($endDate)->endOfDay(); // End of the selected day
        } else {
            // If no date range is provided, use the month/year to generate the start and end dates
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth(); // Start of the selected month
            $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth(); // End of the selected month
        }

        // Fetch Per Day Data for the selected date range (status = 1, date_released is not null)
        $perDayData = Stock::with(['stockMaterials.stocksdescLevel:id,description', 'techName:id,tech_name'])
            ->whereBetween('date_released', [$startDate, $endDate])
            ->whereNotNull('date_released') // Ensure date_released is not null
            ->get();

        // Group perDayData by each day within the selected date range
        $groupedPerDayData = $perDayData->groupBy(function ($item) {
            return Carbon::parse($item->date_released)->format('Y-m-d'); // Group by day (e.g., 2025-01-01)
        });

        // Fetch Per Week Data for the selected date range (status = 1, date_released is not null)
        $perWeekData = Stock::with(['stockMaterials.stocksdescLevel:id,description', 'techName:id,tech_name'])
            ->whereBetween('date_released', [$startDate, $endDate])
            ->whereNotNull('date_released') // Ensure date_released is not null
            ->get();

        // Group perWeekData by Week of the Month (Week 1, Week 2, etc.)
        $groupedPerWeekData = $perWeekData->groupBy(function ($item) {
            return 'Week ' . Carbon::parse($item->date_released)->weekOfMonth;
        });

        // Respond with the grouped data
        return response()->json([
            'perDayData' => $groupedPerDayData, // Send the grouped per-day data
            'perWeekData' => $groupedPerWeekData, // Send the grouped per-week data
        ]);
    }



    public function importGoogleSheetData()
    {
        $spreadsheetId = '1dZNSZjbuxH6fxokXu1AWH05t4bcwsCTEUGEQmtp4KpI'; // Your spreadsheet ID
        $range = 'STOCKS!A2:B1000'; // Fetch data from columns A (Product Name) and B (Serial No)

        // Assuming GoogleSheetsService is set up to fetch sheet data
        $googleSheetsService = new GoogleSheetsService();
        $data = $googleSheetsService->getSheetData($spreadsheetId, $range);

        // Debug the fetched data (optional)
        // dd($data);

        // Check if data is fetched successfully
        if (empty($data)) {
            return response()->json(['message' => 'No data found in the specified range.'], 400);
        }

        // Loop through the rows and save them to the database
        foreach ($data as $row) {
            // Ensure the row has the necessary columns (Product Name and Serial No)
            if (isset($row[0], $row[1])) {
                Stock::create([
                    'product_name' => $row[0], // Column A -> Product Name
                    'serial_no'    => $row[1], // Column B -> Serial No
                ]);
            }
        }

        return response()->json(['message' => 'Data successfully imported']);
    }

    public function teamTechDetails()
    {
        // Fetch all tech names and their assigned stocks, including those with null serial_no and description
        $techData = TeamTech::with([
            'stocks' => function ($query) {
                $query->select('id', 'tech_name_id', 'serial_no')
                    ->with(['stockMaterials.stocksdescLevel:id,description']); // Include stock descriptions
            }
        ])->select('id', 'tech_name')->get();

        // Transform the data to include unassigned stocks as well
        $teamTechDetails = $techData->map(function ($tech) {
            return [
                'tech_name_id' => $tech->id,
                'tech_name' => $tech->tech_name,
                'stocks' => $tech->stocks->map(function ($stock) {
                    // Extract multiple descriptions if there are more than one
                    $descriptions = $stock->stockMaterials->map(function ($material) {
                        return [
                            'description_id' => optional($material->stocksdescLevel)->id,
                            'description' => optional($material->stocksdescLevel)->description,
                        ];
                    });

                    return [
                        'serial_no' => $stock->serial_no,
                        'descriptions' => $descriptions,  // Multiple descriptions as an array
                    ];
                }),
            ];
        });

        // Return the transformed data as a JSON response
        return response()->json([
            'teamTechDetails' => $teamTechDetails
        ]);
    }




}
