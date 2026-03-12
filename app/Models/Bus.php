<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bus extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'bus_name',
        'bus_number',
        'bus_type',
        'total_seats',
        'status',
    ];


    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function seatConfig()
    {
        return $this->hasOne(Seat::class);
    }

    public function schedules()
    {
        return $this->belongsToMany(Schedule::class, 'schedule_buses')
                    ->withPivot(['available_seats', 'status'])
                    ->withTimestamps();
    }
    
}
