@extends('layouts.app')

@section('content')
<h2>Modifier un paiement</h2>
<form action="{{ route('paiements.update', $paiement->id) }}" method="POST">
    @csrf
    @method('PUT')

    <label for="date_paiement">Date de paiement :</label>
    <input type="date" name="date_paiement" id="date_paiement" value="{{ $paiement->date_paiement }}">

    <label for="mode_paiement">Mode de paiement :</label>
    <input type="text" name="mode_paiement" id="mode_paiement" value="{{ $paiement->mode_paiement }}">

    <label for="reservation_id">Réservation :</label>
    <select name="reservation_id" id="reservation_id">
        @foreach($reservations as $reservation)
            <option value="{{ $reservation->id }}" @if($paiement->reservation_id == $reservation->id) selected @endif>
                Réservation #{{ $reservation->id }}
            </option>
        @endforeach
    </select>

    <label for="facture_id">Facture :</label>
    <select name="facture_id" id="facture_id">
        @foreach($factures as $facture)
            <option value="{{ $facture->id }}" @if($paiement->facture_id == $facture->id) selected @endif>
                Facture #{{ $facture->id }}
            </option>
        @endforeach
    </select>

    <button type="submit">Enregistrer les modifications</button>
</form>
@endsection