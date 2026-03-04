@extends('layouts.app')

@section('content')
    <h2>Tableau de bord</h2>
    <ul>
        <li>Nombre total de chambres : {{ $totalChambres }}</li>
        <li>Nombre de chambres disponibles : {{ $chambresDisponibles }}</li>
        <li>Nombre de chambres occupées : {{ $chambresOccupees }}</li>
        <li>Nombre de réservations en cours : {{ $reservationsEnCours }}</li>
        <li>Nombre de clients enregistrés : {{ $totalClients }}</li>
        <li>Montant total des paiements effectués : {{ number_format($totalPaiements, 2, ',', ' ') }} MGA</li>
        <li>Réservations du jour : {{ $reservationsDuJour }}</li>
        <li>Taux d’occupation de l’hôtel : {{ $tauxOccupation }} %</li>
    </ul>
@endsection