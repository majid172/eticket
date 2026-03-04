<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ScheduleBus;

class ScheduleController extends Controller
{
    /**
     * View all schedules on the platform.
     */
    public function index()
    {
        $scheduleBuses = ScheduleBus::with(['schedule.route', 'bus.company'])
            ->latest()
            ->paginate(20);
            
        return response()->json($scheduleBuses);
    }
}
