<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookingSeatSeeder extends Seeder
{
    public function run(): void
    {
        // Booking 1 (Alice, Dhaka→Chittagong): 2 seats from Bus 1 (seat_id 1, 2)
        // Booking 2 (Bob, Dhaka→Sylhet): 1 seat from Bus 3 (seat_id 81 = first seat of bus 3)
        // Bus1 seats: id 1-40, Bus2: 41-85, Bus3: 86-115, Bus4: 116-165
        $bookingSeats = [
            [
                'booking_id' => 1,
                'seat_id'    => 1,       // Bus1, seat A01
                'price'      => 750.00,
                'status'     => 'booked',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'booking_id' => 1,
                'seat_id'    => 2,       // Bus1, seat A02
                'price'      => 750.00,
                'status'     => 'booked',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'booking_id' => 2,
                'seat_id'    => 86,      // Bus3, seat C01
                'price'      => 700.00,
                'status'     => 'booked',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('booking_seats')->insert($bookingSeats);
    }
}
