@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Utilisateurs</h1>
    <a href="{{ route('users.create') }}" class="btn btn-primary mb-3">Nouvel utilisateur</a>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $u)
                <tr>
                    <td>{{ $u->id }}</td>
                    <td>{{ $u->name }}</td>
                    <td>{{ $u->email }}</td>
                    <td>
                        <a href="{{ route('users.edit', $u) }}" class="btn btn-sm btn-secondary">Modifier</a>
                        @if(auth()->id() !== $u->id)
                        <form action="{{ route('users.destroy', $u) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Supprimer cet utilisateur ?');">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">Supprimer</button>
                        </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $users->links() }}
</div>
@endsection
