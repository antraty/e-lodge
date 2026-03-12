@extends('layouts.app')

@section('title', 'Réservations - E-Lodge')

@section('content')
<div class="container">
    <h1>Réservations</h1>
    <a href="{{ route('reservations.create') }}" class="btn btn-primary">Nouvelle réservation</a>
    <a href="{{ route('reservations.history') }}" class="btn btn-secondary ms-2">Voir l'historique</a>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <table class="table mt-3">
        <thead>
            <tr>
                <th>Client</th>
                <th>Chambre</th>
                <th>Arrivée</th>
                <th>Départ</th>
                <th>Statut</th>
                <th>Montant</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservations as $res)
            <tr>
                <td>{{ $res->client->first_name }} {{ $res->client->last_name }}</td>
                <td>{{ $res->room->room_number }} ({{ ucfirst($res->room->type) }})</td>
                <td>{{ $res->check_in }}</td>
                <td>{{ $res->check_out }}</td>
                <td>{{ ucfirst($res->status) }}</td>
                <td>{{ number_format($res->total_price,2) }} MGA</td>
                <td>
                    <a href="{{ route('reservations.show', $res) }}" class="btn btn-sm btn-info">Voir</a>
                    <a href="{{ route('reservations.edit', $res) }}" class="btn btn-sm btn-secondary">Modifier</a>
                    <a href="{{ route('payments.create', ['reservation_id' => $res->id]) }}" class="btn btn-sm btn-success">Enregistrer un paiement</a>
                    <form action="{{ route('reservations.destroy', $res) }}" method="POST" style="display:inline-block;">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Annuler</button></form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $reservations->links() }}
</div>
@endsection
