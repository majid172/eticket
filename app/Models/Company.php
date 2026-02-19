<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'trade_license',
        'contact_phone',
        'contact_email',
        'address',
        'status',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    /**
     * The operator user who owns this company.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * All buses registered under this company.
     */
    public function buses()
    {
        return $this->hasMany(Bus::class);
    }
}
