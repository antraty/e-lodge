@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Ajouter une chambre</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('rooms.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Numéro de chambre</label>
            <input type="text" name="room_number" class="form-control" value="{{ old('room_number') }}" required>
        </div>
        <div class="mb-3">
            <label>Type</label>
            <select name="type" class="form-control">
                <option value="single">Simple</option>
                <option value="double">Double</option>
                <option value="suite">Suite</option>
                <option value="deluxe">Deluxe</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Capacité</label>
            <input type="number" name="capacity" class="form-control" value="2" min="1">
        </div>
        <div class="mb-3">
            <label>Prix par nuit</label>
            <input type="number" step="0.01" name="price_per_night" class="form-control" value="0.00">
        </div>
        <div class="mb-3">
            <label>Statut</label>
            <select name="status" class="form-control">
                <option value="available">Disponible</option>
                <option value="occupied">Occupée</option>
                <option value="maintenance">Maintenance</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ old('description') }}</textarea>
        </div>

        <button class="btn btn-primary">Enregistrer</button>
        <a href="{{ route('rooms.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
