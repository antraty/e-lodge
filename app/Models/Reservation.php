<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'client_id',
        'room_id',
        'check_in',
        'check_out',
        'status',
        'number_of_guests',
        'total_price',
        'special_requests',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
