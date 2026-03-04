<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FactureController extends Controller
{
    public function generer($reservation_id){
        $reservation = \App\Models\Reservation::findOrFail($reservation_id);
        
        $chambre = \App\Models\Chambre::findOrFail
        ($reservation->chambre_id);
        $montant = $chambre->prix;
        $facture = \App\Models\Facture::create([
            'date_facture'=>now(),
            'montant_total'=> $montant,
            'reservation_id'=>$reservation->id,
        ]);
        return redirect()->route('factures.show', $facture->id)->with('sucess', 'Facture générée avec succès');
    }
    public function show($id)
    {
        $facture = \App\Models\Facture::findOrFail($id);
        return view('factures.show', compact('facture'));
    }
}
