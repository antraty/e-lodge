<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;
    protected $fillable = [
        'date_paiement',
        'montant',
        'mode_paiement',
        'reservation_id',
        'facture_id',
    ];

    public function reservation(){
        return $this->belongsTo(\App\Models\Reservation::class);
    }

    public function facture(){
        return $this->belongsTo(\App\Models\Facture::class);
    }
}
