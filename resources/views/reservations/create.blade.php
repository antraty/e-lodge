@extends('layouts.app')

@section('content')
    <h2>Créer une réservation</h2>
    @if ($errors->any())
        <div style="color:red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('reservations.store') }}" method="POST">
        @csrf

        <label for="client_id">Client :</label>
        <select name="client_id" id="client_id" required>
            <option value="">-- Sélectionner --</option>
            @foreach($clients as $client)
                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>{{ $client->nom }} {{ $client->prenom }}</option>
            @endforeach
        </select>

        <label for="chambre_id">Chambre :</label>
        <select name="chambre_id" id="chambre_id" required>
            <option value="">-- Sélectionner --</option>
            @foreach($chambres as $chambre)
                <option value="{{ $chambre->id }}" {{ old('chambre_id') == $chambre->id ? 'selected' : '' }}>Chambre {{ $chambre->numero }}</option>
            @endforeach
        </select>

        <label for="date_debut">Date d'arrivée :</label>
        <input type="date" name="date_debut" id="date_debut" value="{{ old('date_debut') }}" required>

        <label for="date_fin">Date de départ :</label>
        <input type="date" name="date_fin" id="date_fin" value="{{ old('date_fin') }}" required>

        <label for="status">Statut :</label>
        <input type="text" name="status" id="status" value="{{ old('status') }}" required>

        <button type="submit">Enregistrer</button>
    </form>
@endsection