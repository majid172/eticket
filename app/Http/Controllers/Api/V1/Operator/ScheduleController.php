<?php

namespace App\Http\Controllers\Api\V1\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\ScheduleBus;
use App\Models\Bus;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;

class ScheduleController extends Controller
{
    /**
     * View operator's schedule buses
     */
    public function index(Request $request)
    {
        $companyId = $request->user()->company->id;
        
        $scheduleBuses = ScheduleBus::with(['schedule.route', 'bus.seatConfig'])
            ->whereHas('bus', function ($q) use ($companyId) {
                $q->where('company_id', $companyId);
            })
            ->latest()
            ->paginate(15);
            
        return response()->json($scheduleBuses);
    }

    /**
     * Store a new schedule + schedule_bus
     */
    public function store(StoreScheduleRequest $request)
    {
        $validated = $request->validated();
        $companyId = $request->user()->company->id;

        $bus = Bus::with('seatConfig')->where('company_id', $companyId)->findOrFail($validated['bus_id']);

        return DB::transaction(function () use ($validated, $bus) {
            $schedule = Schedule::firstOrCreate([
                'route_id' => $validated['route_id'],
                'travel_date' => $validated['travel_date'],
                'departure_time' => $validated['departure_time'],
            ], [
                'arrival_time' => $validated['arrival_time'],
                'base_price' => $validated['base_price'],
                'status' => 'active',
            ]);

            $scheduleBus = ScheduleBus::create([
                'schedule_id' => $schedule->id,
                'bus_id' => $bus->id,
                'available_seats' => $bus->total_seats,
                'status' => 'scheduled',
            ]);

            return response()->json([
                'message' => 'Schedule created successfully.',
                'schedule_bus' => $scheduleBus->load(['schedule.route', 'bus'])
            ], 201);
        });
    }

    /**
     * Update schedule bus status
     */
    public function update(UpdateScheduleRequest $request, string $id)
    {
        $companyId = $request->user()->company->id;
        
        $scheduleBus = ScheduleBus::whereHas('bus', function ($q) use ($companyId) {
            $q->where('company_id', $companyId);
        })->findOrFail($id);
        
        $validated = $request->validated();
        
        $scheduleBus->update([
            'status' => $validated['status'] ?? $scheduleBus->status,
            'bus_id' => $validated['bus_id'] ?? $scheduleBus->bus_id,
        ]);
        
        $schedule = $scheduleBus->schedule;
        $schedule->update([
            'route_id'       => $validated['route_id'] ?? $schedule->route_id,
            'travel_date'    => $validated['travel_date'] ?? $schedule->travel_date,
            'departure_time' => $validated['departure_time'] ?? $schedule->departure_time,
            'arrival_time'   => $validated['arrival_time'] ?? $schedule->arrival_time,
            'base_price'     => $validated['base_price'] ?? $schedule->base_price,
            'status'         => $validated['status'] ?? $schedule->status,
        ]);

        return response()->json(['message' => 'Schedule updated', 'schedule_bus' => $scheduleBus]);
    }

    /**
     * Delete a schedule bus
     */
    public function destroy(Request $request, string $id)
    {
        $companyId = $request->user()->company->id;
        
        $scheduleBus = ScheduleBus::whereHas('bus', function ($q) use ($companyId) {
            $q->where('company_id', $companyId);
        })->findOrFail($id);
        
        if ($scheduleBus->status !== 'scheduled') {
            return response()->json(['message' => 'Cannot delete a schedule bus that is already completed or cancelled.'], 400);
        }
        
        $scheduleBus->delete();
        
        return response()->json(['message' => 'Schedule removed']);
    }
}
