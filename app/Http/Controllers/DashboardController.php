<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        $totalChambres = \App\Models\Chambre::count();
        $chambresDisponibles = \App\Models\Chambre::where('status', 'libre')->count();
        $chambresOccupees = \App\Models\Chambre::where('status', 'occupée')->count();
        $reservationsEnCours = \App\Models\Reservation::where('status', 'confirmée')->count();
        $totalClients = \App\Models\Client::count();
        $totalPaiements = \App\Models\Paiement::sum('montant');
        $reservationsDuJour = \App\Models\Reservation::whereDate('date_debut', today())->count();

        $tauxOccupation = $totalChambres > 0 ? round(($chambresOccupees / $totalChambres) * 100, 2) : 0;

        return view('dashboard', compact(
            'totalChambres',
            'chambresDisponibles',
            'chambresOccupees',
            'reservationsEnCours',
            'totalClients',
            'totalPaiements',
            'reservationsDuJour',
            'tauxOccupation'
        ));
    }
}
