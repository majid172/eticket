<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'route_id',
        'travel_date',
        'departure_time',
        'arrival_time',
        'base_price',
        'status',
    ];

    /**
     * Get the route for the schedule.
     */
    public function route()
    {
        return $this->belongsTo(Route::class);
    }
}
