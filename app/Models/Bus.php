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

    // ─── Relationships ────────────────────────────────────────────────────────

    /**
     * The company (operator) that owns this bus.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Seats belonging to this bus.
     */
    public function seats()
    {
        return $this->hasMany(Seat::class);
    }
}
