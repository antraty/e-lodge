@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Modifier la chambre {{ $room->room_number }}</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('rooms.update', $room) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Numéro de chambre</label>
            <input type="text" name="room_number" class="form-control" value="{{ old('room_number', $room->room_number) }}" required>
        </div>
        <div class="mb-3">
            <label>Type</label>
            <select name="type" class="form-control">
                <option value="single" {{ $room->type=='single'?'selected':'' }}>Simple</option>
                <option value="double" {{ $room->type=='double'?'selected':'' }}>Double</option>
                <option value="suite" {{ $room->type=='suite'?'selected':'' }}>Suite</option>
                <option value="deluxe" {{ $room->type=='deluxe'?'selected':'' }}>Deluxe</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Capacité</label>
            <input type="number" name="capacity" class="form-control" value="{{ old('capacity', $room->capacity) }}" min="1">
        </div>
        <div class="mb-3">
            <label>Prix par nuit</label>
            <input type="number" step="0.01" name="price_per_night" class="form-control" value="{{ old('price_per_night', $room->price_per_night) }}">
        </div>
        <div class="mb-3">
            <label>Statut</label>
            <select name="status" class="form-control">
                <option value="available" {{ $room->status=='available'?'selected':'' }}>Disponible</option>
                <option value="occupied" {{ $room->status=='occupied'?'selected':'' }}>Occupée</option>
                <option value="maintenance" {{ $room->status=='maintenance'?'selected':'' }}>Maintenance</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ old('description', $room->description) }}</textarea>
        </div>

        <button class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('rooms.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
