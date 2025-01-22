<?php

namespace App\Http\Controllers;

use App\Models\StocksLevel;
use Illuminate\Http\Request;


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
        $validatedData = $request->validate([
            'rows' => 'required|array',
            'rows.*.description' => 'required|string|max:255',
        ]);

        $insertData = [];
        foreach ($validatedData['rows'] as $row) {
            $insertData[] = [
                'description' => $row['description'],
                'stocks_level_status' => 4, // Default to Active
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
}
