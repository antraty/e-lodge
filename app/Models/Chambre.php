<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chambre extends Model
{
    protected $fillable = [
        'numero',
        'type',
        'status',
        'prix',
    ];
}
