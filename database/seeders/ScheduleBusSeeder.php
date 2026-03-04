<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScheduleBusSeeder extends Seeder
{
    public function run(): void
    {
        // Assign buses to schedules and set available seats
        $scheduleBuses = [
            // Schedule 1 (Dhaka→Chittagong, Mar 10 07:00) → Bus 1 (40 seats)
            [
                'schedule_id'     => 1,
                'bus_id'          => 1,
                'available_seats' => 40,
                'status'          => 'scheduled',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            // Schedule 2 (Dhaka→Chittagong, Mar 11 22:00) → Bus 2 (45 seats)
            [
                'schedule_id'     => 2,
                'bus_id'          => 2,
                'available_seats' => 45,
                'status'          => 'scheduled',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            // Schedule 3 (Dhaka→Sylhet, Mar 10 08:00) → Bus 3 (30 seats)
            [
                'schedule_id'     => 3,
                'bus_id'          => 3,
                'available_seats' => 30,
                'status'          => 'scheduled',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            // Schedule 4 (Dhaka→Rajshahi, Mar 12) → Bus 4 (50 seats)
            [
                'schedule_id'     => 4,
                'bus_id'          => 4,
                'available_seats' => 50,
                'status'          => 'scheduled',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            // Schedule 5 (Chittagong→Cox's Bazar, Mar 13) → Bus 1 (40 seats)
            [
                'schedule_id'     => 5,
                'bus_id'          => 1,
                'available_seats' => 40,
                'status'          => 'scheduled',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
        ];

        DB::table('schedule_buses')->insert($scheduleBuses);
    }
}
