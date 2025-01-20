<?php

namespace App\Http\Controllers;
use App\Models\PortUtilization;
use App\Services\GoogleSheetsService;

use Illuminate\Http\Request;

class PortUtilizationController extends Controller
{

    public function index()
    {
        $ports_utilization = PortUtilization::all();
        return response()->json(['ports_utilization' => $ports_utilization]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'municipality' => 'required|string|max:255',
            'brgy_code' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'napcode' => 'required|string|max:255',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
            'no_of_deployed' => 'nullable|numeric',
            'no_of_active' => 'nullable|numeric',
            'no_of_available' => 'nullable|numeric',
        ]);

        // Handle default values for numeric fields
        $validatedData['no_of_deployed'] = $validatedData['no_of_deployed'] ?? 0;
        $validatedData['no_of_active'] = $validatedData['no_of_active'] ?? 0;
        $validatedData['no_of_available'] = $validatedData['no_of_available'] ?? 0;

        // Save the data to the database
        $ports_utilization = PortUtilization::create($validatedData);

        return response()->json([
            'status' => 'success',
            'message' => 'Form submitted successfully!',
            'data' => $ports_utilization,
        ]);
    }


    public function show($id)
    {
        $ports_utilization = PortUtilization::findOrFail($id);

        return response()->json(['ports_utilization' => $ports_utilization]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'municipality' => 'required|string|max:255',
            'brgy_code' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'napcode' => 'required|string|max:255',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
            'no_of_deployed' => 'required|numeric',
            'no_of_active' => 'required|numeric',
            'no_of_available' => 'required|numeric',
        ]);

        try {
            $ports_utilization = PortUtilization::findOrFail($id);

            // Ensure default values for specific fields
            $validatedData['no_of_deployed'] = $validatedData['no_of_deployed'] ?? 0;
            $validatedData['no_of_active'] = $validatedData['no_of_active'] ?? 0;
            $validatedData['no_of_available'] = $validatedData['no_of_available'] ?? 0;

            $ports_utilization->update($validatedData);

            return response()->json([
                'status' => 'success',
                'message' => 'Port Utilization updated successfully.',
                'data' => $ports_utilization,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update Port Utilization. Please try again later.',
            ]);
        }
    }


    public function destroy($id)
    {
        try {
            $ports_utilization = PortUtilization::findOrFail($id);
            $ports_utilization->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Ports Utilization deleted successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete. Please try again later.'
            ], 500);
        }
    }

    public function totalDeployedPorts()
    {
        // Get the total sum of no_of_deployed
        $totalDeployed = PortUtilization::sum('no_of_deployed');

        // Format the total deployed ports with thousands separator
        $totalDeployedFormatted = number_format($totalDeployed);

        // Return the total in a JSON response
        return response()->json(['total_deployed' => $totalDeployedFormatted]);
    }

    public function totalActivePorts()
    {
        // Get the total sum of no_of_active
        $totalActive = PortUtilization::sum('no_of_active');

        // Format the total active ports with thousands separator
        $totalActiveFormatted = number_format($totalActive);

        // Return the total in a JSON response
        return response()->json(['total_active' => $totalActiveFormatted]);
    }

    public function totalAvailablePorts()
    {
        // Get the total sum of no_of_available
        $totalAvailable = PortUtilization::sum('no_of_available');

        // Format the total available ports with thousands separator
        $totalAvailableFormatted = number_format($totalAvailable);

        // Return the total in a JSON response
        return response()->json(['total_available' => $totalAvailableFormatted]);
    }


    public function getUtilizationPercentage()
    {
        // Calculate total utilization percentage
        $totals = PortUtilization::selectRaw('SUM(no_of_active) as total_active, SUM(no_of_deployed) as total_deployed')->first();

        $utilizationPercentage = $totals->total_deployed > 0
            ? round(($totals->total_active / $totals->total_deployed) * 100, 2)
            : 0;

        // Return the response with the utilization percentage
        return response()->json([
            'utilization_percentage' => $utilizationPercentage,
        ]);
    }

    // Fetch unique municipalities
    public function getMunicipalities()
    {
        // Get unique municipalities from the port_utilization table
        $municipalities = PortUtilization::distinct()->pluck('municipality');
        return response()->json($municipalities);
    }

    // Fetch barangays based on the selected municipality
    public function getBarangaysByMunicipality(Request $request)
    {
        $municipality = $request->input('municipality');
        // Get unique barangays for the selected municipality
        $barangays = PortUtilization::where('municipality', $municipality)
                                    ->distinct()
                                    ->pluck('barangay');
        return response()->json($barangays);
    }

    // Fetch brgy_codes based on the selected barangay
    public function getBrgyCodesByBarangay(Request $request)
    {
        $barangay = $request->input('barangay');
        // Get unique brgy codes for the selected barangay
        $brgyCodes = PortUtilization::where('barangay', $barangay)
                                    ->distinct()
                                    ->pluck('brgy_code');
        return response()->json($brgyCodes);
    }

    public function getPortUtilization(Request $request)
    {
        $municipality = $request->input('municipality');
        $barangay = $request->input('barangay');
        $brgyCode = $request->input('brgy_code');

        $query = PortUtilization::query();

        // Apply filters based on the request parameters
        if ($municipality && $municipality !== 'All') {
            $query->where('municipality', $municipality);
        }

        if ($barangay && $barangay !== 'All') {
            $query->where('barangay', $barangay);
        }

        if ($brgyCode && $brgyCode !== 'All') {
            $query->where('brgy_code', $brgyCode);
        }

        // Retrieve the filtered port utilization data
        $ports_utilization = $query->get();

        return response()->json([
            'ports_utilization' => $ports_utilization
        ]);
    }

    public function importGoogleSheetData()
    {
        $spreadsheetId = '1IhZL3B4Myl4uaRZpppS4LqHriWx9uP7OmydLps1Z11g'; // Use your spreadsheet ID
        $range = 'DETAILS!A2:O34976'; // Fetch data from columns A to N, rows 1 to 34976

        $googleSheetsService = new GoogleSheetsService();
        $data = $googleSheetsService->getSheetData($spreadsheetId, $range);



        // Loop through the data and save it to the database
        foreach ($data as $row) {
            // Check if the row has valid data
            if (isset($row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7])) {
                // Ensure proper casting for numeric values (longitude, latitude, ports)
                $longitude = isset($row[8]) ? (float) $row[8] : null; // Longitude from column 7
                $latitude = isset($row[9]) ? (float) $row[9] : null;  // Latitude from column 8
                $deployedPorts = isset($row[11]) ? (int) $row[11] : 0;  // Deployed Ports from column 10
                $activePorts = isset($row[12]) ? (int) $row[12] : 0;    // Active Ports from column 11
                $availablePorts = isset($row[14]) ? (int) $row[14] : 0; // Available Ports from column 12

                PortUtilization::create([
                    'municipality'   => $row[2] ?? null, // MUNICIPALITY (Column 1)
                    'barangay'       => $row[3] ?? null, // BARANGAY (Column 2)
                    'napcode'        => $row[7] ?? null, // NAPCODE (Column 3)
                    'brgy_code'      => $row[3] ?? null, // Add the correct index for brgy_code
                    'longitude'      => $longitude,      // LONGITUDE (Column 7)
                    'latitude'       => $latitude,       // LATITUDE (Column 8)
                    'no_of_deployed' => $deployedPorts,  // DEPLOYED_PORTS (Column 10)
                    'no_of_active'   => $activePorts,    // ACTIVE_PORTS (Column 11)
                    'no_of_available'=> $availablePorts, // AVAILABLE_PORTS (Column 12)
                ]);
            }
        }

        return response()->json(['message' => 'Data successfully imported']);
    }

}
