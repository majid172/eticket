<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Http\Resources\BookingResource;

class BookingController extends Controller
{
    /**
     * View all bookings on the platform.
     */
    public function index()
    {
        $bookings = Booking::with(['user', 'scheduleBus.schedule.route', 'scheduleBus.bus.company'])
            ->latest()
            ->paginate(20);

        return BookingResource::collection($bookings);
    }
}
