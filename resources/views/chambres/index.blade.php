@extends('layouts.app')

@section('content')
    <h2>Liste des chambres</h2>
    <a href="{{ route('chambres.create') }}" class="btn btn-primary">Ajouter une chambre</a>
    <table>
        <tr>
            <th>Numéro</th>
            <th>Type</th>
            <th>Status</th>
            <th>Prix</th>
            <th>Actions</th>
        </tr>
        @foreach($chambres as $chambre)
        <tr>
            <td>{{ $chambre->numero }}</td>
            <td>{{ $chambre->type }}</td>
            <td>{{ $chambre->status }}</td>
            <td>{{ $chambre->prix }}</td>
            <td>
                <a href="{{ route('chambres.edit', $chambre->id) }}">Modifier</a>
                <form action="{{ route('chambres.destroy', $chambre->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Supprimer cette chambre ?')">Supprimer</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
@endsection