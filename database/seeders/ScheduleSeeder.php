<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $schedules = [
            // Route 1: Dhaka → Chittagong
            [
                'route_id'       => 1,
                'travel_date'    => '2026-03-10',
                'departure_time' => '07:00:00',
                'arrival_time'   => '13:00:00',
                'base_price'     => 750.00,
                'status'         => 'active',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'route_id'       => 1,
                'travel_date'    => '2026-03-11',
                'departure_time' => '22:00:00',
                'arrival_time'   => '04:00:00',
                'base_price'     => 800.00,
                'status'         => 'active',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            // Route 2: Dhaka → Sylhet
            [
                'route_id'       => 2,
                'travel_date'    => '2026-03-10',
                'departure_time' => '08:00:00',
                'arrival_time'   => '14:30:00',
                'base_price'     => 700.00,
                'status'         => 'active',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            // Route 3: Dhaka → Rajshahi
            [
                'route_id'       => 3,
                'travel_date'    => '2026-03-12',
                'departure_time' => '06:30:00',
                'arrival_time'   => '12:00:00',
                'base_price'     => 650.00,
                'status'         => 'active',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            // Route 5: Chittagong → Cox's Bazar
            [
                'route_id'       => 5,
                'travel_date'    => '2026-03-13',
                'departure_time' => '09:00:00',
                'arrival_time'   => '12:00:00',
                'base_price'     => 400.00,
                'status'         => 'active',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
        ];

        DB::table('schedules')->insert($schedules);
    }
}
