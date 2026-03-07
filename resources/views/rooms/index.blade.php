@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Liste des chambres</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('rooms.create') }}" class="btn btn-primary">Ajouter une chambre</a>

    <table class="table mt-3">
        <thead>
            <tr>
                <th>Numéro</th>
                <th>Type</th>
                <th>Capacité</th>
                <th>Prix / nuit</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rooms as $room)
            <tr>
                <td>{{ $room->room_number }}</td>
                <td>{{ ucfirst($room->type) }}</td>
                <td>{{ $room->capacity }}</td>
                <td>{{ number_format($room->price_per_night, 2) }} MGA</td>
                <td>{{ ucfirst($room->status) }}</td>
                <td>
                    <a href="{{ route('rooms.edit', $room) }}" class="btn btn-sm btn-secondary">Modifier</a>
                    <form action="{{ route('rooms.destroy', $room) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Supprimer cette chambre ?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Supprimer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $rooms->links() }}

    <h3 class="mt-4">Vérifier disponibilité</h3>
    <form id="checkAvailabilityForm" class="row g-2" method="POST" action="{{ route('rooms.checkAvailability') }}">
        @csrf
        <div class="col-md-3">
            <label for="room_id">Chambre</label>
            <select name="room_id" id="room_id" class="form-control">
                @foreach($rooms as $r)
                    <option value="{{ $r->id }}">{{ $r->room_number }} — {{ ucfirst($r->type) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label for="check_in">Arrivée</label>
            <input type="date" name="check_in" id="check_in" class="form-control" required>
        </div>
        <div class="col-md-3">
            <label for="check_out">Départ</label>
            <input type="date" name="check_out" id="check_out" class="form-control" required>
        </div>
        <div class="col-md-3 align-self-end">
            <button class="btn btn-info">Vérifier</button>
        </div>
    </form>

    <div id="availabilityResult" class="mt-3"></div>
</div>

@endsection

@section('scripts')
<script>
document.getElementById('checkAvailabilityForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const form = e.target;
    const data = new FormData(form);
    const res = await fetch(form.action, { method: 'POST', headers: { 'X-CSRF-TOKEN': data.get('_token') }, body: data });
    const json = await res.json();
    const el = document.getElementById('availabilityResult');
    if (json.available) {
        el.innerHTML = '<div class="alert alert-success">Disponible</div>';
    } else {
        el.innerHTML = '<div class="alert alert-danger">Indisponible</div>';
    }
});
</script>
@endsection
