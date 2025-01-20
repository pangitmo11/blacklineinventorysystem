<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PortUtilizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insert multiple records into the port_utilization table
        DB::table('port_utilization')->insert([
            [
                'municipality' => 'ALUBIJID',
                'brgy_code' => '001',
                'barangay' => 'BAYBAY',
                'napcode' => 'AJM001 LP11 NP4',
                'longitude' => 123.456789,
                'latitude' => 12.345678,
                'no_of_deployed' => 10,
                'no_of_active' => 5,
                'no_of_available' => 5,
            ],
            [
                'municipality' => 'ALUBIJID',
                'brgy_code' => '002',
                'barangay' => 'BAYBAY',
                'napcode' => 'NAP002',
                'longitude' => 124.567890,
                'latitude' => 13.456789,
                'no_of_deployed' => 20,
                'no_of_active' => 12,
                'no_of_available' => 8,
            ],
            [
                'municipality' => 'ALUBIJID',
                'brgy_code' => '003',
                'barangay' => 'BAYBAY',
                'napcode' => 'NAP003',
                'longitude' => 125.678901,
                'latitude' => 14.567890,
                'no_of_deployed' => 30,
                'no_of_active' => 18,
                'no_of_available' => 12,
            ],
            [
                'municipality' => 'ALUBIJID',
                'brgy_code' => '004',
                'barangay' => 'BAYBAY',
                'napcode' => 'NAP003',
                'longitude' => 125.678901,
                'latitude' => 14.567890,
                'no_of_deployed' => 30,
                'no_of_active' => 18,
                'no_of_available' => 12,
            ],
            [
                'municipality' => 'ALUBIJID',
                'brgy_code' => '005',
                'barangay' => 'BAYBAY',
                'napcode' => 'NAP003',
                'longitude' => 125.678901,
                'latitude' => 14.567890,
                'no_of_deployed' => 30,
                'no_of_active' => 18,
                'no_of_available' => 12,
            ],
            // Add more records as needed
        ]);
    }
}
