@extends('layouts.app')

@section('title', 'Modifier réservation - E-Lodge')

@section('content')
<div class="container">
    <h1>Modifier réservation</h1>
    @if($errors->any())<div class="alert alert-danger"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif

    <form action="{{ route('reservations.update', $reservation) }}" method="POST">@csrf @method('PUT')
        <div class="mb-3"><label>Client</label><select name="client_id" class="form-control">@foreach($clients as $c)<option value="{{ $c->id }}" {{ $c->id==$reservation->client_id?'selected':'' }}>{{ $c->first_name }} {{ $c->last_name }}</option>@endforeach</select></div>
        <div class="mb-3"><label>Chambre</label><select name="room_id" class="form-control">@foreach($rooms as $r)<option value="{{ $r->id }}" {{ $r->id==$reservation->room_id?'selected':'' }}>{{ $r->room_number }} - {{ ucfirst($r->type) }}</option>@endforeach</select></div>
        @php $today = date('Y-m-d'); @endphp
        <div class="mb-3"><label>Arrivée</label>
            <input type="date" name="check_in" class="form-control" value="{{ $reservation->check_in }}" required min="{{ $today }}">
        </div>
        <div class="mb-3"><label>Départ</label>
            <input type="date" name="check_out" class="form-control" value="{{ $reservation->check_out }}" required min="{{ $today }}">
        </div>
        <div class="mb-3"><label>Nombre de personnes</label><input type="number" name="number_of_guests" class="form-control" value="{{ old('number_of_guests', $reservation->number_of_guests) }}" min="1" required></div>
        <div class="mb-3"><label>Statut</label><select name="status" class="form-control">@foreach(['pending','confirmed','checked_in','checked_out','cancelled','partial','paid'] as $s)<option value="{{ $s }}" {{ $s==$reservation->status?'selected':'' }}>{{ ucfirst($s) }}</option>@endforeach</select></div>
        <div class="mb-3"><label>Remarques</label><textarea name="special_requests" class="form-control">{{ $reservation->special_requests }}</textarea></div>
        <button class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('reservations.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
