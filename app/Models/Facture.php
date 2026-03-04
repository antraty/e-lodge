<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    use HasFactory;
    protected $fillable = [
        'date_facture',
        'montant_total',
        'reservation_id',
    ];

    // fonction pour relation
    public function reservation(){
        return $this->belongsTo(Reservation::class, 'reservation_id');
    }
}
