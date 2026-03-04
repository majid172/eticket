<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeatSeeder extends Seeder
{
    public function run(): void
    {
        $seats = [];

        // Bus 1 (DHA-1001): 40 seats AC
        foreach (range(1, 40) as $i) {
            $seats[] = [
                'bus_id'      => 1,
                'seat_number' => 'A' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'seat_type'   => $i % 2 === 0 ? 'Aisle' : 'Window',
                'status'      => 'available',
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        }

        // Bus 2 (DHA-1002): 45 seats Non-AC
        foreach (range(1, 45) as $i) {
            $seats[] = [
                'bus_id'      => 2,
                'seat_number' => 'B' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'seat_type'   => $i % 2 === 0 ? 'Aisle' : 'Window',
                'status'      => 'available',
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        }

        // Bus 3 (DHA-2001): 30 seats Sleeper
        foreach (range(1, 30) as $i) {
            $seats[] = [
                'bus_id'      => 3,
                'seat_number' => 'C' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'seat_type'   => $i % 2 === 0 ? 'Aisle' : 'Window',
                'status'      => 'available',
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        }

        // Bus 4 (DHA-2002): 50 seats Seater
        foreach (range(1, 50) as $i) {
            $seats[] = [
                'bus_id'      => 4,
                'seat_number' => 'D' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'seat_type'   => $i % 2 === 0 ? 'Aisle' : 'Window',
                'status'      => 'available',
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        }

        DB::table('seats')->insert($seats);
    }
}
