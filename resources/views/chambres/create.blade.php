@extends('layouts.app')

@section('content')
<h2>Créer une nouvelle chambre</h2>
@csrf
<form action="{{ route('chambres.store') }}" method="post">
    @csrf
    <label for="numero">Numéro :</label>
    <input type="text" name="numero" id="numero">
    <label for="type">Type :</label>
    <input type="text" name="type" id="type">
    <label for="status">Status :</label>
    <input type="text" name="status" id="status">
    <label for="prix">Prix :</label>
    <input type="text" name="prix" id="prix">
    <button type="submit">Créer</button>
</form>
@endsection