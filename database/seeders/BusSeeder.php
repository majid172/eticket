<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusSeeder extends Seeder
{
    public function run(): void
    {
        // company_id 1 = Green Express, company_id 2 = City Lines
        $buses = [
            // Green Express buses
            [
                'company_id'  => 1,
                'bus_name'    => 'Green Express 1',
                'bus_number'  => 'DHA-1001',
                'bus_type'    => 'AC',
                'total_seats' => 40,
                'status'      => 'active',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'company_id'  => 1,
                'bus_name'    => 'Green Express 2',
                'bus_number'  => 'DHA-1002',
                'bus_type'    => 'Non-AC',
                'total_seats' => 45,
                'status'      => 'active',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            // City Lines buses
            [
                'company_id'  => 2,
                'bus_name'    => 'City Lines Sleeper',
                'bus_number'  => 'DHA-2001',
                'bus_type'    => 'Sleeper',
                'total_seats' => 30,
                'status'      => 'active',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'company_id'  => 2,
                'bus_name'    => 'City Lines Seater',
                'bus_number'  => 'DHA-2002',
                'bus_type'    => 'Seater',
                'total_seats' => 50,
                'status'      => 'active',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ];

        DB::table('buses')->insert($buses);
    }
}
