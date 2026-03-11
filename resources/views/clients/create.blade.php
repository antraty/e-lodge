@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Ajouter un client</h1>
    @if($errors->any())<div class="alert alert-danger"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
    <form action="{{ route('clients.store') }}" method="POST">@csrf
        <div class="mb-3"><label>Prénom</label><input name="first_name" class="form-control" value="{{ old('first_name') }}" required></div>
        <div class="mb-3"><label>Nom</label><input name="last_name" class="form-control" value="{{ old('last_name') }}" required></div>
        <div class="mb-3"><label>Email</label><input name="email" type="email" class="form-control" value="{{ old('email') }}" required></div>
        <div class="mb-3"><label>Téléphone</label>
            <input name="phone" type="tel" class="form-control" value="{{ old('phone') }}" placeholder="+261 34 12 345 67" pattern="^\+261 3[0-9] [0-9]{2} [0-9]{3} [0-9]{2}$">
            <div class="form-text">Format requis: <code>+261 3X XX XXX XX</code> (ex: +261 34 12 345 67)</div>
        </div>
        <div class="mb-3"><label>Adresse</label><input name="address" class="form-control" value="{{ old('address') }}"></div>
        <button class="btn btn-primary">Enregistrer</button>
        <a href="{{ route('clients.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
