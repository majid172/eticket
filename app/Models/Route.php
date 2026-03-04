<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    protected $fillable = [
        'source_city',
        'destination_city',
        'distance_km',
        'status',
        'company_id',
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
