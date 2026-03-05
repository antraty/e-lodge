@extends('layouts.app')

@section('content')
    <h2>Liste des clients</h2>
    <a href="{{ route('clients.create')}}" class="btn btn-primary">Ajouter un client</a>
    <table>
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>téléphone</th>
            <th>Adresse</th>
            <th>Actions</th>
        </tr>
        @foreach($clients as $client)
        <tr>
            <td>{{ $client->nom }}</td>
            <td>{{ $client->prenom}}</td>
            <td>{{ $client->telephone}}</td>
            <td>{{ $client->adresse}}</td>
            <td>
                <a href="{{ route('clients.edit', $client->id) }}">Modifier</a>
                <form action="{{ route('clients.destroy', $client->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Supprimer ce client ?')">Supprimer</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
@endsection