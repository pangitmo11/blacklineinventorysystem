<?php

namespace App\Services;

use Google_Client;
use Google_Service_Sheets;

class GoogleSheetsService
{
    private $service;

    public function __construct()
    {
        $client = new Google_Client();
        $client->setApplicationName('Laravel Google Sheets Integration');
        $client->setScopes([Google_Service_Sheets::SPREADSHEETS_READONLY]);
        $client->setAuthConfig(storage_path('app/credentials.json')); // Path to the credentials file
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        $this->service = new Google_Service_Sheets($client);
    }

    // Fetch data from Google Sheet
    public function getSheetData($spreadsheetId, $range)
    {
        try {
            // Fetch data from Google Sheets API
            $response = $this->service->spreadsheets_values->get($spreadsheetId, $range);
            return $response->getValues();
        } catch (\Google_Service_Exception $e) {
            // Log error message
            \Log::error('Google Sheets Error: ' . $e->getMessage());
            throw $e; // Re-throw the exception for further handling
        }
    }
}
