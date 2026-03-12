<?php

namespace App\Http\Controllers\Api\V1\Passenger;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ScheduleBus;
use App\Models\Seat;
use App\Models\BookingSeat;
use App\Models\Route;
use App\Http\Resources\ScheduleBusResource;

class ScheduleController extends Controller
{
    /**
     * Get unique source and destination cities for search form.
     */
    public function cities()
    {
        $sources = Route::distinct()->pluck('source_city');
        $destinations = Route::distinct()->pluck('destination_city');
        
        $cities = $sources->merge($destinations)->unique()->sort()->values();
        
        // Return array of objects with id and name as expected by frontend
        $mappedCities = $cities->map(function ($city, $index) {
            return [
                'id' => $index + 1,
                'name' => $city
            ];
        });
        
        return response()->json($mappedCities);
    }

    /**
     * Get top 4 popular routes ordered by distance (longest first).
     */
    public function popularRoutes()
    {
        $routes = Route::where('status', 1)
            ->withCount('schedules')
            ->withMin('schedules', 'base_price')
            ->orderByDesc('distance_km')
            ->limit(4)
            ->get()
            ->map(fn($r) => [
                'id'               => $r->id,
                'source_city'      => $r->source_city,
                'destination_city' => $r->destination_city,
                'distance_km'      => $r->distance_km,
                'schedules_count'  => $r->schedules_count,
                'min_price'        => $r->schedules_min_base_price,
            ]);

        return response()->json($routes);
    }

    /**
     * Display a listing of the available schedules.
     */
    public function index(Request $request)
    {
        $query = ScheduleBus::with(['schedule.route', 'bus.company', 'bus.seatConfig'])
            ->withCount('bookedSeats')
            ->whereHas('schedule', function ($q) use ($request) {
                $q->where('status', 'active');
                
                if ($request->filled('source_city')) {
                    $q->whereHas('route', fn($r) => $r->where('source_city', 'like', '%' . $request->source_city . '%'));
                }
                
                if ($request->filled('destination_city')) {
                    $q->whereHas('route', fn($r) => $r->where('destination_city', 'like', '%' . $request->destination_city . '%'));
                }
                
                if ($request->filled('travel_date')) {
                    $q->whereDate('travel_date', $request->travel_date);
                } else {
                    // No date given — show today and all future schedules
                    $q->whereDate('travel_date', '>=', now()->toDateString());
                }
            })
            ->where('status', 'scheduled');

        $schedules = $query->paginate(10);

        return ScheduleBusResource::collection($schedules);
    }

    public function seats(string $id)
    {
        $scheduleBus = ScheduleBus::with(['schedule.route', 'bus.company', 'bus.seatConfig'])
            ->withCount('bookedSeats')
            ->findOrFail($id);
        
        $seatConfig = $scheduleBus->bus->seatConfig;
        $capacity = $seatConfig ? $seatConfig->capacity : 0;
        
        $bookedSeatNumbers = BookingSeat::whereHas('booking', function ($q) use ($scheduleBus) {
            $q->where('schedule_bus_id', $scheduleBus->id)
              ->where('booking_status', '!=', 'cancelled');
        })->where('status', '!=', 'cancelled')->pluck('seat_number')->toArray();

        $seats = [];
        $rows = range('A', 'Z');
        $count = 0;
        $seatType = $seatConfig ? $seatConfig->seat_type : 'Economy';

        foreach ($rows as $row) {
            // Determine columns for this row based on seat type
            $colsInRow = 4; // Default Economy
            if ($seatType === 'Business') {
                $colsInRow = ($row === 'I') ? 4 : 3;
            }

            for ($col = 1; $col <= $colsInRow; $col++) {
                if ($count >= $capacity) {
                    break 2;
                }

                $seatNumber = $row . $col;
                $seats[] = [
                    'id' => $seatNumber,
                    'seat_number' => $seatNumber,
                    'is_booked' => in_array($seatNumber, $bookedSeatNumbers),
                ];
                $count++;
            }
        }

        return response()->json([
            'schedule_bus' => new ScheduleBusResource($scheduleBus),
            'seat_type' => $seatType,
            'seats' => $seats,
        ]);
    }
}
