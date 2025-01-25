<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TeamTech;
use App\Models\Stock;

class TeamTechController extends Controller
{

    public function index()
    {
        $teamtech = TeamTech::all();  // Retrieve all stock items from the database

        // Return response as JSON with 'stocks' key
        return response()->json(['teamtech' => $teamtech]);
    }

    public function store(Request $request)
    {
        // Validate the form inputs
        $request->validate([
            'tech_name' => 'required|string|max:255',
        ]);

        // Save the data into the database
        TeamTech::create([
            'tech_name' => $request->input('tech_name'),
        ]);

        // Redirect or return a success response
        return response()->json(['message' => 'Data saved successfully.']);
    }

    public function show($id)
    {
        $teamtech = TeamTech::findOrFail($id); // Find the record by its ID

        return response()->json(['teamtech' => $teamtech]);
    }

    public function update(Request $request, $id)
    {
        // Validate the form inputs
        $request->validate([
            'tech_name' => 'required|string|max:255', // Validate Tech Name
        ]);

        // Find the record by its ID
        $teamtech = TeamTech::findOrFail($id);

        // Update the record with the new values
        $teamtech->update([
            'tech_name' => $request->input('tech_name'),
        ]);

        // Redirect or return a success response
        return response()->json(['message' => 'Data updated successfully.']);
    }

    public function destroy($id)
    {
        try {
            $teamtech = TeamTech::findOrFail($id);
            $teamtech->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Team Tech deleted successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete. Please try again later.'
            ], 500);
        }
    }

    public function techcount()
    {
        // Count the total number of techs
        $totalTechs = TeamTech::count();

        // Return the count as a JSON response
        return response()->json([
            'totalTechs' => $totalTechs,
        ]);
    }

public function countTeamTechAssigned()
{
    // Get all tech IDs that are assigned (from the Stock table)
    $assignedTechIds = Stock::whereNotNull('tech_name_id')->pluck('tech_name_id')->toArray();

    // Get all unassigned techs by excluding the assigned tech IDs
    $unassignedTechs = TeamTech::whereNotIn('id', $assignedTechIds)->get();

    // Count assigned techs
    $assignedCount = count($assignedTechIds);

    // Count total techs
    $totalTechs = TeamTech::count();

    // Calculate unassigned count
    $unassignedCount = $unassignedTechs->count();

    // Return the data as a JSON response
    return response()->json([
        'assignedCount' => $assignedCount,
        'unassignedCount' => $unassignedCount,
        'totalTechs' => $totalTechs,
        'unassignedTechs' => $unassignedTechs, // List of unassigned techs
    ]);
}



}
