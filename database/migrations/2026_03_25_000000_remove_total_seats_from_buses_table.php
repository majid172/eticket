<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Move data from buses.total_seats to seats.capacity for each bus if not already set
        $buses = DB::table('buses')->get();
        foreach ($buses as $bus) {
            DB::table('seats')->updateOrInsert(
                ['bus_id' => $bus->id],
                [
                    'capacity' => $bus->total_seats,
                    'seat_type' => 'Economy', // Default if not present
                    'status' => 'available',
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        // 2. Remove total_seats from buses table
        Schema::table('buses', function (Blueprint $table) {
            $table->dropColumn('total_seats');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buses', function (Blueprint $table) {
            $table->unsignedInteger('total_seats')->nullable();
        });

        // Restore data from seats.capacity
        $seats = DB::table('seats')->get();
        foreach ($seats as $seat) {
            DB::table('buses')->where('id', $seat->bus_id)->update([
                'total_seats' => $seat->capacity
            ]);
        }
    }
};
