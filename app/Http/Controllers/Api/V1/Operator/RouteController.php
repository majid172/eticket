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
        
        $route = Route::firstOrCreate(
            [
                'source_city' => $validated['source_city'],
                'destination_city' => $validated['destination_city'],
            ],
            [
                'distance_km' => $validated['distance_km'] ?? null,
                'status' => 1,
            ]
        );

        return response()->json([
            'message' => 'Route created successfully',
            'route' => $route
        ], 201);
    }
}
