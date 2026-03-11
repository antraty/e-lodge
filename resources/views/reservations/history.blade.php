@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-3">
        <div>
            <h1>Historique des réservations</h1>
            <p>Réservations annulées ou supprimées</p>
        </div>
        <div>
            <a href="{{ route('reservations.index') }}" class="btn btn-secondary">Retour aux réservations</a>
        </div>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Client</th>
                <th>Chambre</th>
                <th>Arrivée</th>
                <th>Départ</th>
                <th>Statut</th>
                <th>Montant</th>
                <th>Type</th>
                <th>Date</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservations as $res)
            <tr>
                <td>#{{ $res->id }}</td>
                <td>{{ optional($res->client)->first_name ?? '—' }} {{ optional($res->client)->last_name ?? '' }}</td>
                <td>{{ optional($res->room)->room_number ?? '—' }} @if($res->room)({{ ucfirst($res->room->type ?? '') }})@endif</td>
                <td>{{ $res->check_in }}</td>
                <td>{{ $res->check_out }}</td>
                <td>
                    @if($res->status === 'cancelled')
                        <span class="badge badge-warning">Annulée</span>
                    @else
                        <span class="badge badge-info">{{ ucfirst($res->status) }}</span>
                    @endif
                </td>
                <td>{{ number_format($res->total_price,2) }} MGA</td>
                <td>
                    @if($res->deleted_at)
                        <span class="badge badge-danger">Supprimée</span>
                    @else
                        <span class="badge badge-warning">Annulée</span>
                    @endif
                </td>
                <td>
                    @if($res->deleted_at)
                        {{ $res->deleted_at->format('d/m/Y H:i') }}
                    @else
                        {{ $res->updated_at->format('d/m/Y H:i') }}
                    @endif
                </td>
                <td>
                    <a href="{{ route('reservations.show', $res) }}" class="btn btn-sm btn-outline-primary">Voir</a>
                    @if($res->deleted_at)
                        <form method="POST" action="{{ route('reservations.destroy', $res) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer définitivement ?')">Supprimer</button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $reservations->links() }}
</div>
@endsection
