@extends('layouts.app')

@section('content')
    <h2>Modifier le client</h2>
    <form action="{{ route('clients.update', $client->id) }}" method="POST">
        @csrf
        @method('PUT')
        <label for="nom">Nom :</label>
        <input type="text" name="nom" id="nom" value="{{ $client->nom }}">
        <label for="prenom">Prénom :</label>
        <input type="text" name="prenom" id="prenom" value="{{ $client->prenom }}">
        <label for="telephone">Téléphone :</label>
        <input type="text" name="telephone" id="status" value="{{ $client->telephone }}">
        <label for="adresse">Adresse :</label>
        <input type="text" name="adresse" id="adresse" value="{{ $client->adresse }}">
        <button type="submit">Mettre à jour</button>
    </form>
@endsection