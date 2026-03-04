<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;
    protected $fillable = [
        'date_debut',
        'date_fin',
        'status',
        'chambre_id',
        'client_id',
    ];

    public function client() { 
        return $this->belongsTo(\App\Models\Client::class); 
    }
    public function chambre() { 
        return $this->belongsTo(\App\Models\Chambre::class); 
    }
}
