<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PassengerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'emergency_contact',
        'preferences',
        'notifications',
        'config',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'preferences' => 'array',
            'notifications' => 'array',
            'config' => 'array',
        ];
    }

    /**
     * The user that owns the profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
