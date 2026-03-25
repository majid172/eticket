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
                'status'      => 'active',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'company_id'  => 1,
                'bus_name'    => 'Green Express 2',
                'bus_number'  => 'DHA-1002',
                'bus_type'    => 'Non-AC',
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
                'status'      => 'active',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'company_id'  => 2,
                'bus_name'    => 'City Lines Seater',
                'bus_number'  => 'DHA-2002',
                'bus_type'    => 'Seater',
                'status'      => 'active',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ];

        DB::table('buses')->insert($buses);
    }
}
