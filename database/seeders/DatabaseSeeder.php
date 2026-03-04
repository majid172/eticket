<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Order respects foreign-key dependencies.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,         // 1. users (no deps)
            CompanySeeder::class,      // 2. companies → users
            BusSeeder::class,          // 3. buses → companies
            SeatSeeder::class,         // 4. seats → buses
            RouteSeeder::class,        // 5. routes (no deps)
            ScheduleSeeder::class,     // 6. schedules → routes
            ScheduleBusSeeder::class,  // 7. schedule_buses → schedules, buses
            BookingSeeder::class,      // 8. bookings → users, schedule_buses
            BookingSeatSeeder::class,  // 9. booking_seats → bookings, seats
        ]);
    }
}
