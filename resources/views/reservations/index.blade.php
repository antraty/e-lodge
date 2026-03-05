@extends('layouts.app')

@section('title', 'Réservations - E-Lodge')

@section('content')
<div class="d-flex justify-content-between mb-4">
    <h2><i class="fas fa-calendar"></i> Réservations</h2>
    <a href="{{ route('reservations.create') }}" class="btn btn-secondary">
        <i class="fas fa-plus"></i> Nouvelle
    </a>
</div>

@if($reservations->isEmpty())
    <div class="alert alert-info text-center">
        <i class="fas fa-info-circle"></i>
        <div>Aucune réservation. <a href="{{ route('reservations.create') }}">Cliquez ici</a></div>
    </div>
@else
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><i class="fas fa-user"></i> Client</th>
                    <th><i class="fas fa-door-open"></i> Chambre</th>
                    <th><i class="fas fa-calendar-plus"></i> Arrivée</th>
                    <th><i class="fas fa-calendar-minus"></i> Départ</th>
                    <th><i class="fas fa-info-circle"></i> Statut</th>
                    <th><i class="fas fa-cog"></i> Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reservations as $reservation)
                <tr>
                    <td><strong>{{ $reservation->client->nom }} {{ $reservation->client->prenom }}</strong></td>
                    <td>#{{ $reservation->chambre->numero }}</td>
                    <td>{{ \Carbon\Carbon::parse($reservation->date_debut)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($reservation->date_fin)->format('d/m/Y') }}</td>
                    <td>
                        <span class="badge {{ $reservation->status == 'confirmée' ? 'bg-success' : ($reservation->status == 'annulée' ? 'bg-danger' : 'bg-warning') }}">
                            {{ ucfirst($reservation->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="action-links">
                            <a href="{{ route('reservations.edit', $reservation->id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i> Modifier
                            </a>
                            <form action="{{ route('factures.generer', $reservation->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-sm">
                                    <i class="fas fa-file-invoice"></i> Facture
                                </button>
                            </form>
                            <form action="{{ route('reservations.destroy', $reservation->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" 
                                    onclick="return confirm('Confirmer ?')">
                                    <i class="fas fa-trash"></i> Annuler
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection