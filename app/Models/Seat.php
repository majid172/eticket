<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    protected $fillable = [
        'bus_id',
        'capacity',
        'seat_type',
        'status',
    ];

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }
}
