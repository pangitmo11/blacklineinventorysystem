<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class StockController extends Controller
{
    public function index()
    {
        $stocks = Stock::with(['descriptionname:id,description'])->get();

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
        $month = $request->input('month');
        $year = $request->input('year');
        $team_tech = $request->input('team_tech');

        $query = Stock::query();

        // Filter by team_tech if provided and not 'All'
        if ($team_tech && $team_tech !== 'All') {
            $query->where('team_tech', '=', $team_tech);
        }

        // Filter by month if provided and not 'All'
        if ($month && $month !== 'All') {
            $query->whereMonth('date_released', '=', $month);
        }

        // Filter by year if provided and not 'All'
        if ($year && $year !== 'All') {
            $query->whereYear('date_released', '=', $year);
        }

        // Filter by active status (status = 1)
        $query->where('status', '=', 1);

        // Fetch the filtered data
        $stocks = $query->get();

        // Send the response back as JSON
        return response()->json([
            'stocks' => $stocks,
            'year' => $year
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

        // Filter by active status (status = 1)
        $query->where('status', '=', 2);

        // Fetch the filtered data
        $stocks = $query->get();

        // Send the response back as JSON
        return response()->json([
            'stocks' => $stocks,
            'year' => $year
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
        $releasedStocks = DB::table('stocks_tbl')
            ->whereNotNull('date_released') // Ensure 'date_released' is not null
            ->where('status', 1)
            ->where('status', '!=', 0)   // Exclude active status
            ->where('status', '!=', 3)   // Exclude repaired status
            ->where('status', '!=', 2)   // Exclude activated status
            ->get();

        return response()->json($releasedStocks);
    }

    public function fetchActivatedStocks(Request $request)
    {
        // Fetch all records where 'j_o_no' and 'date_used' are not null
        $activatedstocks = Stock::whereNotNull('j_o_no')
                       ->whereNotNull('date_used')
                       ->where('status', 2)   // Include only active status
                       ->where('status', '!=', 3)   // Exclude repaired status
                       ->where('status', '!=', 1)   // Exclude released status
                       ->where('status', '!=', 0)   // Exclude active status
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

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'product_name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'team_tech' => 'nullable|string|max:255',
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

        $stocks = Stock::create($validatedData);

        return response()->json([
            'status' => 'success',
            'message' => 'Stock created successfully.',
            'data' => $stocks,
        ]);
    }

    public function show($id)
    {
        try {
            $Stocks = Stock::findOrFail($id);
            return response()->json(['stocks' => $Stocks]);
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
            'product_name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'team_tech' => 'nullable|string|max:255',
            'account_no' => 'nullable|string|max:255',
            'j_o_no' => 'nullable|string|max:255',
            'serial_no' => 'nullable|string|max:255',
            'serial_new_no' => 'nullable|string|max:255',
            'ticket_no' => 'nullable|string|max:255',
            'date_active' => 'nullable|date',
            'date_released' => 'nullable|date',
            'date_used' => 'nullable|date',
            'date_repaired' => 'nullable|date',
            'status' => 'nullable|in:0,1,2,3,4',
        ]);

        try {
            $Stocks = Stock::findOrFail($id);
            $Stocks->update($validatedData);

            return response()->json([
                'status' => 'success',
                'message' => 'Stock updated successfully.',
                'data' => $Stocks,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Stock not found.',
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
            $Stocks = Stock::findOrFail($id);
            $Stocks->delete();

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
