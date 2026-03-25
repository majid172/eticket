<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeatSeeder extends Seeder
{
    public function run(): void
    {
        $seats = [
            [
                'bus_id'      => 1,
                'capacity'    => 40,
                'seat_type'   => 'Business',
                'status'      => 'available',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'bus_id'      => 2,
                'capacity'    => 45,
                'seat_type'   => 'Economy',
                'status'      => 'available',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'bus_id'      => 3,
                'capacity'    => 30,
                'seat_type'   => 'Business',
                'status'      => 'available',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'bus_id'      => 4,
                'capacity'    => 50,
                'seat_type'   => 'Economy',
                'status'      => 'available',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ];

        DB::table('seats')->insert($seats);
    }
}
