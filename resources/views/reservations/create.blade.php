@extends('layouts.app')

@section('title', 'Nouvelle réservation - E-Lodge')

@section('content')
<div class="container">
    <h1>Créer une réservation</h1>
    @if($errors->any())<div class="alert alert-danger"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif

    <form action="{{ route('reservations.store') }}" method="POST">@csrf
        <div class="mb-3">
            <label>Client *</label>
            <select name="client_id" id="client-search" class="form-control" required>
                <option value="">-- Rechercher un client --</option>
            </select>
            <small class="form-text text-muted">
                Tapez le nom, prénom, email ou téléphone
            </small>
        </div>
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

@section('scripts')
<!-- jQuery (requis pour Select2) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    $('#client-search').select2({
        placeholder: 'Tapez pour rechercher...',
        allowClear: true,
        minimumInputLength: 2,
        ajax: {
            url: '{{ route("clients.search") }}',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return { q: params.term };
            },
            processResults: function(data) {
                return { results: data.results };
            },
            cache: true
        }
    });
});
</script>

<style>
    /* Pour que Select2 prenne toute la largeur */
    .select2-container {
        width: 100% !important;
    }
    
    /* Support du mode sombre pour Select2 */
    body.dark-mode .select2-container--default .select2-selection--single {
        background-color: #2d3748;
        border-color: #4a5568;
        color: #e2e8f0;
    }
    
    body.dark-mode .select2-dropdown {
        background-color: #2d3748;
        border-color: #4a5568;
    }
    
    body.dark-mode .select2-results__option {
        color: #e2e8f0;
    }
    
    body.dark-mode .select2-results__option--highlighted {
        background-color: #667eea !important;
    }
</style>
@endsection
