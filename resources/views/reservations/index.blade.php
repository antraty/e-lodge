@extends('layouts.app')

@section('content')
    <h2>Liste des réservations</h2>
    <a href="{{ route('reservations.create') }}">Nouvelle réservation</a>
    <table>
        <tr>
            <th>Client</th>
            <th>Chambre</th>
            <th>Date d'arrivée</th>
            <th>Date de départ</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        @foreach($reservations as $reservation)
        <tr>
            <td>{{ $reservation->client->nom }} {{ $reservation->client->prenom }}</td>
            <td>{{ $reservation->chambre->numero }}</td>
            <td>{{ $reservation->date_debut }}</td>
            <td>{{ $reservation->date_fin }}</td>
            <td>{{ $reservation->status }}</td>
            <td>
                <a href="{{ route('reservations.edit', $reservation->id) }}">Modifier</a>
                <form action="{{ route('factures.generer', $reservation->id) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit">Générer la facture</button>
                </form>
                <form action="{{ route('reservations.destroy', $reservation->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Annuler cette réservation ?')">Annuler</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
@endsection