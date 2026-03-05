@extends('layouts.app')

@section('title', 'Clients - E-Lodge')

@section('content')
<div class="d-flex justify-content-between mb-4">
    <h2><i class="fas fa-users"></i> Clients</h2>
    <a href="{{ route('clients.create') }}" class="btn btn-secondary">
        <i class="fas fa-plus"></i> Nouveau
    </a>
</div>

@if($clients->isEmpty())
    <div class="alert alert-info text-center">
        <i class="fas fa-info-circle"></i>
        <div>Aucun client. <a href="{{ route('clients.create') }}">Cliquez ici</a></div>
    </div>
@else
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><i class="fas fa-user"></i> Nom</th>
                    <th><i class="fas fa-phone"></i> Téléphone</th>
                    <th><i class="fas fa-map-marker-alt"></i> Adresse</th>
                    <th><i class="fas fa-cog"></i> Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($clients as $client)
                <tr>
                    <td><strong>{{ $client->nom }} {{ $client->prenom }}</strong></td>
                    <td>{{ $client->telephone }}</td>
                    <td>{{ $client->adresse }}</td>
                    <td>
                        <div class="action-links">
                            <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i> Modifier
                            </a>
                            <form action="{{ route('clients.destroy', $client->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" 
                                    onclick="return confirm('Confirmer ?')">
                                    <i class="fas fa-trash"></i> Supprimer
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