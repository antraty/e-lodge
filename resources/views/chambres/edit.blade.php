@extends('layouts.app')

@section('content')
    <h2>Modifier la chambre</h2>
    <form action="{{ route('chambres.update', $chambre->id) }}" method="POST">
        @csrf
        @method('PUT')
        <label for="numero">Numéro :</label>
        <input type="text" name="numero" id="numero" value="{{ $chambre->numero }}">
        <label for="type">Type :</label>
        <input type="text" name="type" id="type" value="{{ $chambre->type }}">
        <label for="status">Status :</label>
        <input type="text" name="status" id="status" value="{{ $chambre->status }}">
        <label for="prix">Prix :</label>
        <input type="text" name="prix" id="prix" value="{{ $chambre->prix }}">
        <button type="submit">Mettre à jour</button>
    </form>
@endsection