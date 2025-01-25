<?php

namespace App\Http\Controllers;

use App\Models\StocksLevel;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StocksLevelController extends Controller
{
    public function index()
    {
        $stockslevel = StocksLevel::all();  // Retrieve all stock items from the database

        // Return response as JSON with 'stocks' key
        return response()->json(['stockslevel' => $stockslevel]);
    }

    public function getTotalStocksLevel()
    {
        // Count the total number of descriptions
        $totalDescriptions = StocksLevel::count();

        // Return the count as a JSON response
        return response()->json([
            'totalDescriptions' => $totalDescriptions,
        ]);
    }

    public function getTotalActiveDescriptions()
    {
        // Retrieve the descriptions where status is 4 (active)
        $activeDescriptions = StocksLevel::where('stocks_level_status', 4)->get();

        // Count the number of active descriptions
        $totalActiveDescriptions = $activeDescriptions->count();

        // Return both the count and the data as a JSON response
        return response()->json([
            'totalActiveDescriptions' => $totalActiveDescriptions,
            'activeDescriptions' => $activeDescriptions
        ]);
    }

        // Function to count total descriptions (excluding status 4)
    public function getTotalDescriptionsExcludingActive()
    {
        // Count all unique descriptions with statuses 0, 1, 2, and 3
        $totalDescriptions = StocksLevel::whereIn('stocks_level_status', [0, 1, 2, 3])
                                        ->groupBy('description')
                                        ->selectRaw('description, COUNT(*) as count')
                                        ->get();

        // Calculate the total sum of descriptions (excluding status 4)
        $totalCount = $totalDescriptions->sum('count');

        // Return the total sum as a JSON response
        return response()->json([
            'totalCount' => $totalCount,
        ]);
    }

    public function store(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'rows' => 'required|array',
            'rows.*.description' => 'required|string|max:255',
            'rows.*.date_delivery' => 'nullable|date', // Validate date_delivery for each row
        ]);

        $insertData = [];
        foreach ($validatedData['rows'] as $row) {
            $insertData[] = [
                'description' => $row['description'],
                'stocks_level_status' => 4, // Default to Active
                'date_delivery' => $row['date_delivery'], // Add the delivery date for each row
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert all rows in one query
        StocksLevel::insert($insertData);

        return response()->json([
            'status' => 'success',
            'message' => 'Stock Materials created successfully.',
            'data' => $insertData,
        ]);
    }


    public function show($id)
    {
        try {
            $stockslevel = StocksLevel::findOrFail($id);
            return response()->json(['stockslevel' => $stockslevel]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Stock not found.',
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'description' => 'nullable|string|max:255',
            'date_delivery' => 'nullable|date', // Validate delivery_date for each row
        ]);

        try {
            $stockslevel = StocksLevel::findOrFail($id);
            $stockslevel->update($validatedData);

            return response()->json([
                'status' => 'success',
                'message' => 'Stocks Level updated successfully.',
                'data' => $stockslevel,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Stocks Level not found.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update stock. Please try again later.',
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $stockslevel = StocksLevel::findOrFail($id);
            $stockslevel->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Stock  deleted successfully.',
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

    public function getAvailableYears(Request $request)
    {
        // Fetch distinct years based on the date_released field
        $years = StocksLevel::selectRaw('YEAR(date_delivery) as year')
                    ->distinct()
                    ->orderBy('year', 'desc') // Optional: Sort years in descending order
                    ->pluck('year');

        return response()->json(['years' => $years]);
    }

    public function getMaterialsData(Request $request)
    {
        // Get selected filters from the request
        $month = $request->input('month');
        $year = $request->input('year');
        $status = $request->input('status'); // Get the status filter
        $startDate = $request->input('start_date'); // Date range start
        $endDate = $request->input('end_date'); // Date range end

        // Determine the date range
        if ($startDate && $endDate) {
            // If start_date and end_date are provided, use them
            $startOfMonth = Carbon::parse($startDate)->startOfDay();
            $endOfMonth = Carbon::parse($endDate)->endOfDay();
        } else {
            // Fallback to month and year if no date range is provided
            $month = $month ? $month : Carbon::now()->month;
            $year = $year ? $year : Carbon::now()->year;
            $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endOfMonth = Carbon::createFromDate($year, $month, 1)->endOfMonth();
        }

        // Build the base query
        $query = StocksLevel::whereBetween('date_delivery', [$startOfMonth, $endOfMonth])
            ->whereNotNull('date_delivery'); // Ensure date_delivery is not null

        // Apply the status filter if provided
        if (!is_null($status) && $status !== '') {
            $query->where('stocks_level_status', $status);
        }

        // Fetch and group data by day
        $perDayData = $query->get()->groupBy(function ($item) {
            return Carbon::parse($item->date_delivery)->format('Y-m-d'); // Group by day (e.g., 2025-01-01)
        });

        // Fetch and group data by week
        $perWeekData = $query->get()->groupBy(function ($item) {
            return 'Week ' . Carbon::parse($item->date_delivery)->weekOfMonth; // Group by week
        });

        // Respond with the grouped data
        return response()->json([
            'perDayData' => $perDayData, // Send the grouped per-day data
            'perWeekData' => $perWeekData, // Send the grouped per-week data
        ]);
    }


}
