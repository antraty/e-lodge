@extends('layouts.app')

@section('content')
    <h2>Modifier la réservation</h2>
    <form action="{{ route('reservations.update', $reservation->id) }}" method="POST">
        @csrf
        @method('PUT')
        <label>Client :</label>
        <select name="client_id" required>
            @foreach($clients as $client)
                <option value="{{ $client->id }}" {{ $reservation->client_id == $client->id ? 'selected' : '' }}>
                    {{ $client->nom }} {{ $client->prenom }}
                </option>
            @endforeach
        </select>
        <label>Chambre :</label>
        <select name="chambre_id" required>
            @foreach($chambres as $chambre)
                <option value="{{ $chambre->id }}" {{ $reservation->chambre_id == $chambre->id ? 'selected' : '' }}>
                    Chambre {{ $chambre->numero }}
                </option>
            @endforeach
        </select>
        <label>Date d'arrivée :</label>
        <input type="date" name="date_debut" value="{{ $reservation->date_debut }}" required>
        <label>Date de départ :</label>
        <input type="date" name="date_fin" value="{{ $reservation->date_fin }}" required>
        <label>Statut :</label>
        <select name="status" required>
            <option value="confirmée" {{ $reservation->status == 'confirmée' ? 'selected' : '' }}>Confirmée</option>
            <option value="en attente" {{ $reservation->status == 'en attente' ? 'selected' : '' }}>En attente</option>
            <option value="annulée" {{ $reservation->status == 'annulée' ? 'selected' : '' }}>Annulée</option>
            <option value="terminée" {{ $reservation->status == 'terminée' ? 'selected' : '' }}>Terminée</option>
        </select>
        <button type="submit">Modifier</button>
    </form>
@endsection