<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{

    protected $fillable = [
        'user_id',
        'property_id',
        'meeting_time',
        'notes',
        'contact_number',
        'status',
    ];
    protected $casts = [
        'meeting_time' => 'datetime',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
