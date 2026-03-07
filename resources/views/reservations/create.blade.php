@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Créer une réservation</h1>
    @if($errors->any())<div class="alert alert-danger"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif

    <form action="{{ route('reservations.store') }}" method="POST">@csrf
        <div class="mb-3"><label>Client</label><select name="client_id" class="form-control">@foreach($clients as $c)<option value="{{ $c->id }}">{{ $c->first_name }} {{ $c->last_name }}</option>@endforeach</select></div>
        <div class="mb-3"><label>Chambre</label><select name="room_id" class="form-control">@foreach($rooms as $r)<option value="{{ $r->id }}">{{ $r->room_number }} - {{ ucfirst($r->type) }} - {{ number_format($r->price_per_night,2) }} MGA</option>@endforeach</select></div>
        @php $today = date('Y-m-d'); @endphp
        <div class="mb-3"><label>Arrivée</label>
            <input type="date" name="check_in" class="form-control" required min="{{ $today }}">
        </div>
        <div class="mb-3"><label>Départ</label>
            <input type="date" name="check_out" class="form-control" required min="{{ $today }}">
        </div>
        <div class="mb-3"><label>Nombre de personnes</label><input type="number" name="number_of_guests" class="form-control" value="1" min="1"></div>
        <div class="mb-3"><label>Remarques</label><textarea name="special_requests" class="form-control"></textarea></div>
        <button class="btn btn-primary">Créer</button>
        <a href="{{ route('reservations.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
