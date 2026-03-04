<?php

namespace App\Http\Controllers\Api\V1\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\BookingSeat;
use App\Http\Resources\BookingResource;
use App\Http\Requests\UpdateBookingStatusRequest;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * View all bookings on the operator's buses.
     */
    public function index(Request $request)
    {
        $companyId = $request->user()->company->id;

        $bookings = Booking::with(['user', 'scheduleBus.schedule.route', 'scheduleBus.bus'])
            ->whereHas('scheduleBus.bus', function ($q) use ($companyId) {
                $q->where('company_id', $companyId);
            })
            ->latest()
            ->paginate(15);

        return BookingResource::collection($bookings);
    }

    /**
     * Update booking or payment status.
     */
    public function updateStatus(UpdateBookingStatusRequest $request, string $id)
    {
        $companyId = $request->user()->company->id;

        $booking = Booking::with('scheduleBus')
            ->whereHas('scheduleBus.bus', function ($q) use ($companyId) {
                $q->where('company_id', $companyId);
            })
            ->findOrFail($id);

        $validated = $request->validated();
        $originalStatus = $booking->booking_status;

        DB::transaction(function () use ($validated, $booking, $originalStatus) {
            $newStatus = $validated['booking_status'] ?? $booking->booking_status;
            
            $booking->update([
                'booking_status' => $newStatus,
                'payment_status' => $validated['payment_status'] ?? $booking->payment_status,
            ]);

            // If the operator cancels a booking, free up the seats
            if ($newStatus === 'cancelled' && $originalStatus !== 'cancelled') {
                $bookedSeatsCount = BookingSeat::where('booking_id', $booking->id)
                    ->where('status', '!=', 'cancelled')
                    ->count();

                BookingSeat::where('booking_id', $booking->id)->update(['status' => 'cancelled']);
                $booking->scheduleBus->increment('available_seats', $bookedSeatsCount);
            }
        });

        return response()->json([
            'message' => 'Booking status updated successfully',
            'booking' => new BookingResource($booking->fresh(['user', 'scheduleBus.schedule.route', 'scheduleBus.bus']))
        ]);
    }
}
