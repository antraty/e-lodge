<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;

class PaiementController extends Controller
{
    public function create(){
    $reservations = \App\Models\Reservation::all();
    $factures = \App\Models\Facture::all();
        return view('paiements.create', compact('reservations', 'factures'));
    }

    public function store(Request $request){
        $validate = $request->validate([
            'date_paiement'=>'required|date',            'mode_paiement'=>'required',
            'reservation_id'=>'required|exists:reservations,id',
            'facture_id'=>'required|exists:factures,id',
        ]);

        $facture = \App\Models\Facture::findOrFail($request->facture_id);
        $validate['montant'] = $facture->montant_total;

        Paiement::create($validate);
        return redirect()->route('paiements.index')->with('success', 'Paiement effecutée avec succès');


    }

    public function index(){
        $paiements = \App\Models\Paiement::all();
        return view('paiements.index', compact('paiements'));
    }

    public function edit($id){
        $paiement = \App\Models\Paiement::findOrFail($id);
        $reservations = \App\Models\Reservation::all();
        $factures = \App\Models\Facture::all();
        return view('paiements.edit', compact('paiement', 'reservations', 'factures'));
    }

    public function update(Request $request, $id){
        $validate = $request->validate([
            'date_paiement' => 'required|date',
            'montant' => 'required|numeric',
            'mode_paiement' => 'required',
            'reservation_id' => 'required|exists:reservations,id',
            'facture_id' => 'required|exists:factures,id',
        ]);
        $paiement = \App\Models\Paiement::findOrFail($id);

        $paiement->update($validate);
        return redirect()->route('paiements.index')->with('success', 'paiement modifié avec succès');
    }

    public function destroy($id){
        $paiement = \App\Models\Paiement::findOrFail($id);
        $paiement->delete();
        return redirect()->route('paiements.index')->with('sucess', 'Paiement supprimé avec succes');
    }
}
