<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        // user_id 4 = Alice, user_id 5 = Bob
        // schedule_bus_id 1 = Bus1 on Schedule1 (Dhaka→Chittagong)
        // schedule_bus_id 3 = Bus3 on Schedule3 (Dhaka→Sylhet)
        $bookings = [
            [
                'booking_reference' => 'BK-2026-0001',
                'user_id'           => 4,
                'schedule_bus_id'   => 1,
                'total_amount'      => 1500.00,
                'booking_status'    => 'confirmed',
                'payment_status'    => 'paid',
                'booked_at'         => now(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'booking_reference' => 'BK-2026-0002',
                'user_id'           => 5,
                'schedule_bus_id'   => 3,
                'total_amount'      => 700.00,
                'booking_status'    => 'pending',
                'payment_status'    => 'unpaid',
                'booked_at'         => now(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
        ];

        DB::table('bookings')->insert($bookings);
    }
}
