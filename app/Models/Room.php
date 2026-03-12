<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'room_number',
        'type',
        'capacity',
        'price_per_night',
        'status',
        'description',
    ];

    /**
     * Reservations for the room
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Scope to available rooms
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }
}
