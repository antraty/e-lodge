<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'city',
        'postal_code',
        'country',
        'id_number',
        'notes',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
