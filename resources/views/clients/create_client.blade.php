@extends('layouts.app');

@section('content')
<h2>Ajouter un nouveau client</h2>
<form action="{{ route('clients.store') }}" method="post">
    @csrf
    <label for="nom">Nom :</label>
    <input type="text" name="nom" id="nom">
    <label for="prenom">Prénom :</label>
    <input type="text" name="prenom" id="prenom">
    <label for="telephone">Téléphone :</label>
    <input type="text" name="telephone" id="telephone">
    <label for="adresse">Adresse :</label>
    <input type="text" name="adresse" id="adresse">
    <button type="submit">Ajouter</button>
</form>
@endsection