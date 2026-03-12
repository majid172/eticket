<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleBus extends Model
{
    protected $fillable = [
        'schedule_id',
        'bus_id',
        'available_seats',
        'status',
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function bookedSeats()
    {
        return $this->hasManyThrough(BookingSeat::class, Booking::class)
                    ->where('bookings.booking_status', '!=', 'cancelled')
                    ->where('booking_seats.status', '!=', 'cancelled');
    }
}
