<?php

namespace App\Http\Controllers\Api\V1\Passenger;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\ScheduleBus;
use App\Models\BookingSeat;
use App\Models\Seat;
use Illuminate\Support\Str;
use App\Http\Resources\BookingResource;
use App\Http\Requests\StoreBookingRequest;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class BookingController extends Controller
{
    /**
     * View user's own bookings.
     */
    public function index(Request $request)
    {
        $bookings = Booking::with(['scheduleBus.schedule.route', 'scheduleBus.bus.company', 'user', 'bookingSeats'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);
            
        return BookingResource::collection($bookings);
    }

    /**
     * Create a new booking.
     */
    public function store(StoreBookingRequest $request)
    {
        $validated = $request->validated();
        
        return DB::transaction(function () use ($validated, $request) {
            $scheduleBus = ScheduleBus::with('schedule')->lockForUpdate()->findOrFail($validated['schedule_bus_id']);
            
            // Re-verify seats are available
            $requestedSeatNumbers = $validated['seat_ids']; // array of seat numbers
            
            $alreadyBooked = BookingSeat::whereHas('booking', function ($q) use ($scheduleBus) {
                $q->where('schedule_bus_id', $scheduleBus->id)
                  ->where('booking_status', '!=', 'cancelled');
            })->whereIn('seat_number', $requestedSeatNumbers)
              ->where('status', '!=', 'cancelled')
              ->exists();
              
            if ($alreadyBooked) {
                return response()->json(['message' => 'One or more of the selected seats are already booked.'], 400);
            }

            $seatConfig = Seat::where('bus_id', $scheduleBus->bus_id)->first();
            $capacity = $seatConfig ? $seatConfig->capacity : 0;
            
            $totalAmount = $scheduleBus->schedule->base_price * count($requestedSeatNumbers);

            $booking = Booking::create([
                'booking_reference' => strtoupper(Str::random(10)),
                'user_id' => $request->user()->id,
                'schedule_bus_id' => $scheduleBus->id,
                'total_amount' => $totalAmount,
                'booking_status' => 'pending',
                'payment_status' => 'unpaid',
                'booked_at' => now(),
            ]);

            foreach ($requestedSeatNumbers as $seatNumber) {
                BookingSeat::create([
                    'booking_id' => $booking->id,
                    'seat_number' => $seatNumber,
                    'price' => $scheduleBus->schedule->base_price,
                    'status' => 'booked',
                ]);
            }

            return new BookingResource($booking->load(['scheduleBus.schedule.route', 'scheduleBus.bus.company']));
        });
    }

    /**
     * View booking details.
     */
    public function show(Request $request, string $id)
    {
        $booking = Booking::with(['scheduleBus.schedule.route', 'scheduleBus.bus.company'])
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);
            
        $bookedSeats = BookingSeat::where('booking_id', $booking->id)->get();
        
        return response()->json([
            'booking' => new BookingResource($booking),
            'seats' => $bookedSeats,
        ]);
    }

    /**
     * Cancel the booking.
     */
    public function destroy(Request $request, string $id)
    {
        $booking = Booking::where('user_id', $request->user()->id)->findOrFail($id);
        
        if ($booking->booking_status === 'cancelled') {
            return response()->json(['message' => 'Booking is already cancelled.'], 400);
        }

        DB::transaction(function () use ($booking) {
            $booking->update(['booking_status' => 'cancelled']);
            
            BookingSeat::where('booking_id', $booking->id)->update(['status' => 'cancelled']);
        });

        return response()->json(['message' => 'Booking cancelled successfully.']);
    }

    /**
     * Download e-Ticket as PDF.
     */
    public function downloadTicket(Request $request, string $id)
    {
        $booking = Booking::with(['scheduleBus.schedule.route', 'scheduleBus.bus.company', 'user', 'bookingSeats'])
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);
            
        $pdf = Pdf::loadView('pdf.ticket', compact('booking'));
        
        return $pdf->download("e-Ticket-{$booking->booking_reference}.pdf");
    }
}
