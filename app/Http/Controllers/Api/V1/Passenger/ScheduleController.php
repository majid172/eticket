<?php

namespace App\Http\Controllers\Api\V1\Passenger;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ScheduleBus;
use App\Models\Seat;
use App\Models\BookingSeat;
use App\Http\Resources\ScheduleBusResource;

class ScheduleController extends Controller
{
    /**
     * Get unique source and destination cities for search form.
     */
    public function cities()
    {
        $sources = \App\Models\Route::distinct()->pluck('source_city');
        $destinations = \App\Models\Route::distinct()->pluck('destination_city');
        
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
     * Display a listing of the available schedules.
     */
    public function index(Request $request)
    {
        $query = ScheduleBus::with(['schedule.route', 'bus.company'])
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
                }
            })
            ->where('status', 'scheduled')
            ->where('available_seats', '>', 0);

        $schedules = $query->paginate(10);

        return ScheduleBusResource::collection($schedules);
    }

    /**
     * View available seats for a specific schedule bus.
     */
    public function seats(string $id)
    {
        $scheduleBus = ScheduleBus::with(['schedule.route', 'bus.company'])->findOrFail($id);
        
        $busSeats = Seat::where('bus_id', $scheduleBus->bus_id)->get();
        
        $bookedSeatIds = BookingSeat::whereHas('booking', function ($q) use ($scheduleBus) {
            $q->where('schedule_bus_id', $scheduleBus->id)
              ->where('booking_status', '!=', 'cancelled');
        })->where('status', '!=', 'cancelled')->pluck('seat_id')->toArray();

        $seatsWithStatus = $busSeats->map(function ($seat) use ($bookedSeatIds) {
            $seat->is_booked = in_array($seat->id, $bookedSeatIds);
            return $seat;
        });

        return response()->json([
            'schedule_bus' => new ScheduleBusResource($scheduleBus),
            'seats' => $seatsWithStatus,
        ]);
    }
}
