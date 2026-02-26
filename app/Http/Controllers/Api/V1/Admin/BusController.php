<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use Illuminate\Http\Request;

class BusController extends Controller
{
    /**
     * Display a listing of all buses across the platform.
     */
    public function index()
    {
        // Eager load the 'company' and 'schedules.route' relationships
        // so we can display route info and schedule details for the bus.
        $buses = Bus::with(['company', 'schedules.route'])->latest()->get();

        return response()->json($buses);
    }
}
