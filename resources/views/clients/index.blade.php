@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Clients</h1>
    <a href="{{ route('clients.create') }}" class="btn btn-primary">Ajouter un client</a>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <table class="table mt-3">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clients as $client)
            <tr>
                <td>{{ $client->first_name }} {{ $client->last_name }}</td>
                <td>{{ $client->email }}</td>
                <td>{{ $client->phone }}</td>
                <td>
                    <a href="{{ route('clients.edit', $client) }}" class="btn btn-sm btn-secondary">Modifier</a>
                    <form action="{{ route('clients.destroy', $client) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Supprimer ce client ?');">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Supprimer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $clients->links() }}
</div>
@endsection
