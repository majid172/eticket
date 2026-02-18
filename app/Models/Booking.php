<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'booking_reference',
        'user_id',
        'schedule_bus_id',
        'total_amount',
        'booking_status',
        'payment_status',
        'booked_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scheduleBus()
    {
        return $this->belongsTo(ScheduleBus::class);
    }
}
