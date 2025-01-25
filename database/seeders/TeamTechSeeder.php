<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeamTechSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insert multiple records into the team_tech table
        DB::table('team_tech')->insert([
            ['tech_name' => 'ALISBO/EPHRAIM'],
            ['tech_name' => 'BAHIAN/NALASA'],
            ['tech_name' => 'BJORN/ALEGRIA'],
            ['tech_name' => 'ELMER/JUBILAN'],
            ['tech_name' => 'FELIX/NACABALAN'],
            ['tech_name' => 'GENERAL/OLIBER'],
            ['tech_name' => 'HANSEL/TAN'],
            ['tech_name' => 'JAO/PABALATE'],
            ['tech_name' => 'JERRY/MARK JAY'],
            ['tech_name' => 'MIGUEL/LEOMAR'],
            ['tech_name' => 'NONAN/CAMAROTE'],
            ['tech_name' => 'RAFFY/DELIMA'],
            ['tech_name' => 'RODMARK/DAVE'],
            // Add more records as needed
        ]);
    }
}
