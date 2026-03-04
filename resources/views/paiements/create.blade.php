@extends('layouts.app')

@section('content')
<h2>Effectuer un paiement</h2>
<form action="{{ route('paiements.store')}}" method="POST">
    @csrf
    <label for="date_paiement">Date de paiement :</label>
    <input type="date" name="date_paiement" id="date_paiement">
    <label for="montant">Montant :</label>
    <input type="number" step="0.01" name="montant" id="montant">
    <label for="mode_paiement">Mode de paiement :</label>
    <input type="text" name="mode_paiement" id="mode_paiement">
    <label for="mode_paiement">Réservation :</label>
    <select name="reservation_id" id="reservation_id">
        @foreach ($reservations as $reservation)
            <option value="{{ $reservation->id}}">Réservation #{{ $reservation->id}}</option>
        @endforeach
    </select>
    <label for="mode_paiement">Facture :</label>
    <select name="facture_id" id="facture_id">
        @foreach($factures as $facture)
            <option value="{{  $facture->id}}">Facture #{{$facture->id}}</option>
        @endforeach
    </select>
    <button type="submit">Enregistrer</button>
</form>
@endsection
