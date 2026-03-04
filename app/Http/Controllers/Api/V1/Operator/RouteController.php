<?php

namespace App\Http\Controllers\Api\V1\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Route;
use App\Http\Requests\StoreRouteRequest;

class RouteController extends Controller
{
    /**
     * Display a listing of all active routes.
     */
    public function index(Request $request)
    {
        $limit = $request->get('limit', 20);
        $routes = Route::where('status', 1)->paginate($limit);
        
        return response()->json($routes);
    }

    /**
     * Store a newly created route.
     */
    public function store(StoreRouteRequest $request)
    {
        $validated = $request->validated();

        $route = Route::firstOrNew([
            'source_city'      => $validated['source_city'],
            'destination_city' => $validated['destination_city'],
        ]);

        $route->distance_km = $validated['distance_km'] ?? $route->distance_km;
        $route->status      = $route->status ?? 1;
        $route->save();

        return response()->json([
            'message' => 'Route saved successfully',
            'data'    => $route,
        ], 201);
    }

    /**
     * Update the specified route in storage.
     */
    public function update(StoreRouteRequest $request, int $id)
    {
        $route     = Route::findOrFail($id);
        $validated = $request->validated();

        $route->source_city      = $validated['source_city'];
        $route->destination_city = $validated['destination_city'];
        $route->distance_km      = $validated['distance_km'] ?? $route->distance_km;
        $route->save();

        return response()->json([
            'message' => 'Route updated successfully',
            'data'    => $route,
        ]);
    }

    /**
     * Remove the specified route from storage.
     */
    public function destroy(int $id)
    {
        $route = Route::findOrFail($id);
        $route->delete();

        return response()->json([
            'message' => 'Route deleted successfully',
        ]);
    }
}
