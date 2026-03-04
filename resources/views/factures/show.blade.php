@extends('layouts.app')

@section('content')
    <h2>Détail de la facture</h2>
    @if(session('success'))
        <div style="color:green;">{{ session('success') }}</div>
    @endif
    <ul>
        <li>Date de la facture : {{ $facture->date_facture }}</li>
        <li>Montant total : {{ $facture->montant_total }} MGA</li>
        <li>Réservation associée : {{ $facture->reservation_id }}</li>
    </ul>
    <a href="{{ route('reservations.index') }}">Retour aux réservations</a>
@endsection
