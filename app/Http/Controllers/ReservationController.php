<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function create(){
    $chambres = \App\Models\Chambre::where('status', 'libre')->get();
    $clients = \App\Models\Client::all();
    return view('reservations.create', compact('chambres', 'clients'));
    }
    
    public function store(Request $request){
        $validate = $request->validate([
            'date_debut'=>'required|date',
            'date_fin'=>'required|date|after_or_equal:date_debut',
            'status'=>'required',
            'chambre_id'=>'required|exists:chambres,id',
            'client_id'=>'required|exists:clients,id',
        ]);

        // Vérifier la disponibilité de la chambre
        $exists = Reservation::where('chambre_id', $validate['chambre_id'])
        ->where(function($query) use ($validate) {
            $query->where('date_debut', '<=', $validate['date_fin'])->where('date_fin', '>=', $validate['date_debut']);
        })->exists();

        if ($exists) {
            return back()->withErrors(['chambre_id' => 'Cette chambre est déjà réservée sur cette période.'])->withInput();
        }

        Reservation::create($validate);
        return redirect()->route('reservations.index')->with('success', 'Réservation créée avec succès');
    }
    public function index(){
        $reservations = \App\Models\Reservation::all();
        return view('reservations.index', compact('reservations'));
    }

    public function edit($id){
        $reservation = Reservation::findOrFail($id);
        $chambres = \App\Models\Chambre::all();
        $clients = \App\Models\Client::all();
        return view('reservations.edit', compact('reservation', 'chambres', 'clients'));
    }

    public function update(Request $request, $id){
        $validate = $request->validate([
            'date_debut'=>'required|date',
            'date_fin'=>'required|date|after_or_equal:date_debut',
            'status'=>'required',
            'chambre_id'=>'required|exists:chambres,id',
            'client_id'=>'required|exists:clients,id',
        ]);

        // Vérifier la disponibilité de la chambre (hors la réservation en cours)
        $exists = Reservation::where('chambre_id', $validate['chambre_id'])
            ->where('id', '!=', $id)
            ->where(function($query) use ($validate) {
                $query->whereBetween('date_debut', [$validate['date_debut'], $validate['date_fin']])
                      ->orWhereBetween('date_fin', [$validate['date_debut'], $validate['date_fin']]);
            })->exists();

        if ($exists) {
            return back()->withErrors(['chambre_id' => 'Cette chambre est déjà réservée sur cette période.'])->withInput();
        }

        $reservation = Reservation::findOrFail($id);
        $reservation->update($validate);

        return redirect()->route('reservations.index')->with('success', 'Réservation modifiée avec succès');
    }

    public function destroy($id){
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();
        return redirect()->route('reservations.index')->with('success', 'Réservation annulée avec succès');
    }
}
