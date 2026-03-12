@extends('layouts.app')
@section('title', 'Chambres - E-Lodge')
@section('content')
<div class="container py-4">

    {{-- En-tête --}}
    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-0">Chambres</h2>
            <small class="text-muted">
                {{ $rooms->total() }} chambre{{ $rooms->total() > 1 ? 's' : '' }}
                @if(request()->hasAny(['search','type','status'])) &mdash; filtrées @endif
            </small>
        </div>
        <a href="{{ route('rooms.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Ajouter une chambre
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Filtres --}}
    <form method="GET" action="{{ route('rooms.index') }}" class="row g-2 mb-3">
        <div class="col-sm-4">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Rechercher un numéro…"
                       value="{{ request('search') }}" autocomplete="off">
                @if(request('search'))
                    <a href="{{ route('rooms.index', request()->except('search', 'page')) }}" class="btn btn-outline-secondary" title="Effacer">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </div>

        <div class="col-sm-auto">
            <select name="type" class="form-select" onchange="this.form.submit()">
                <option value="">Tous les types</option>
                @foreach(['single','double','suite','deluxe'] as $t)
                    <option value="{{ $t }}" {{ request('type') === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-sm-auto">
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="">Tous les statuts</option>
                @foreach(['available'=>'Disponible','reserved'=>'Réservée','occupied'=>'Occupée','maintenance'=>'Maintenance'] as $val => $label)
                    <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-sm-auto d-flex gap-2">
            <button type="submit" class="btn btn-outline-primary">
                <i class="bi bi-funnel me-1"></i> Filtrer
            </button>
            @if(request()->hasAny(['search','type','status']))
                <a href="{{ route('rooms.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg me-1"></i> Réinitialiser
                </a>
            @endif
        </div>
    </form>

    {{-- Tableau --}}
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Numéro</th>
                        <th>Type</th>
                        <th>Statut</th>
                        <th>Prix / nuit</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rooms as $room)
                    @php
                        $activeReservation = null;
                        if (in_array($room->status, ['occupied', 'reserved'])) {
                            $today = now()->toDateString();
                            $activeReservation = $room->reservations()
                                ->with('client')
                                ->whereNotIn('status', ['cancelled', 'checked_out'])
                                ->where(function($q) use ($today) {
                                    // occupied: en cours aujourd'hui
                                    $q->where(function($q2) use ($today) {
                                        $q2->where('check_in',  '<=', $today)
                                           ->where('check_out', '>',  $today);
                                    })
                                    // reserved: future
                                    ->orWhere('check_in', '>', $today);
                                })
                                ->orderBy('check_in')
                                ->first();
                        }
                    @endphp
                    <tr>
                        <td class="fw-bold">{{ $room->room_number }}</td>
                        <td><span class="badge text-bg-primary">{{ ucfirst($room->type) }}</span></td>
                        <td>
                            @php
                                $statusColor = match($room->status) {
                                    'available'   => 'success',
                                    'reserved'    => 'info',
                                    'occupied'    => 'danger',
                                    'maintenance' => 'warning',
                                    default       => 'secondary',
                                };
                                $statusLabel = match($room->status) {
                                    'available'   => 'Disponible',
                                    'reserved'    => 'Réservée',
                                    'occupied'    => 'Occupée',
                                    'maintenance' => 'Maintenance',
                                    default       => ucfirst($room->status),
                                };
                            @endphp
                            <span class="badge text-bg-{{ $statusColor }}">{{ $statusLabel }}</span>

                            {{-- Bouton "Voir" uniquement si occupée ou réservée --}}
                            @if(in_array($room->status, ['occupied', 'reserved']) && $activeReservation)
                                <button type="button"
                                        class="btn btn-sm btn-outline-{{ $statusColor }} ms-1 py-0 px-1"
                                        title="Voir la réservation en cours"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalReservation{{ $activeReservation->id }}">
                                    <i class="bi bi-eye-fill"></i>
                                </button>
                            @endif
                        </td>
                        <td class="fw-semibold text-primary">{{ number_format($room->price_per_night, 0, ',', ' ') }} Ar</td>
                        <td>
                            <a href="{{ route('rooms.edit', $room->id) }}" class="btn btn-sm btn-outline-primary me-1">
                                <i class="bi bi-pencil"></i> Modifier
                            </a>
                            <form action="{{ route('rooms.destroy', $room->id) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Supprimer la chambre {{ $room->room_number }} ?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i> Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">
                            <i class="bi bi-search fs-2 d-block mb-2 opacity-25"></i>
                            Aucune chambre ne correspond à votre recherche.
                            <a href="{{ route('rooms.index') }}" class="d-block mt-2">Réinitialiser les filtres</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if($rooms->hasPages())
    <div class="d-flex align-items-center justify-content-between mt-3 flex-wrap gap-2">
        <small class="text-muted">
            Affichage {{ $rooms->firstItem() }}–{{ $rooms->lastItem() }} sur {{ $rooms->total() }}
        </small>
        {{ $rooms->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
    @endif

</div>

@foreach($rooms as $room)
    @if(in_array($room->status, ['occupied', 'reserved']))
        @php
            $today = now()->toDateString();
            $res = $room->reservations()
                ->with('client')
                ->whereNotIn('status', ['cancelled', 'checked_out'])
                ->where(function($q) use ($today) {
                    $q->where(function($q2) use ($today) {
                        $q2->where('check_in',  '<=', $today)
                           ->where('check_out', '>',  $today);
                    })->orWhere('check_in', '>', $today);
                })
                ->orderBy('check_in')
                ->first();
        @endphp
        @if($res)
        <div class="modal fade" id="modalReservation{{ $res->id }}" tabindex="-1"
             aria-labelledby="modalLabel{{ $res->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">

                    <div class="modal-header
                        {{ $room->status === 'occupied' ? 'bg-danger text-white' : 'bg-info text-white' }}">
                        <h5 class="modal-title" id="modalLabel{{ $res->id }}">
                            <i class="bi bi-calendar2-check me-2"></i>
                            Réservation #{{ $res->id }} — Chambre {{ $room->room_number }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        {{-- Statut badge --}}
                        <div class="mb-3 text-center">
                            @php
                                $resStatusColor = match($res->status) {
                                    'confirmed'  => 'success',
                                    'checked_in' => 'primary',
                                    'paid'       => 'success',
                                    'partial'    => 'warning',
                                    'pending'    => 'warning',
                                    default      => 'secondary',
                                };
                                $resStatusLabel = [
                                    'confirmed'  => 'Confirmée',
                                    'checked_in' => 'Checked in',
                                    'paid'       => 'Payée',
                                    'partial'    => 'Partiel',
                                    'pending'    => 'En attente',
                                ][$res->status] ?? ucfirst($res->status);
                            @endphp
                            <span class="badge text-bg-{{ $resStatusColor }} fs-6 px-3 py-2">
                                {{ $resStatusLabel }}
                            </span>
                        </div>

                        <dl class="row mb-0">
                            {{-- Client --}}
                            <dt class="col-5 text-muted"><i class="bi bi-person me-1"></i> Client</dt>
                            <dd class="col-7 fw-semibold">
                                {{ $res->client->first_name }} {{ $res->client->last_name }}
                                @if($res->client->phone)
                                    <br><small class="text-muted fw-normal">{{ $res->client->phone }}</small>
                                @endif
                            </dd>

                            {{-- Dates --}}
                            <dt class="col-5 text-muted"><i class="bi bi-box-arrow-in-right me-1"></i> Arrivée</dt>
                            <dd class="col-7">{{ \Carbon\Carbon::parse($res->check_in)->format('d/m/Y') }}</dd>

                            <dt class="col-5 text-muted"><i class="bi bi-box-arrow-right me-1"></i> Départ</dt>
                            <dd class="col-7">{{ \Carbon\Carbon::parse($res->check_out)->format('d/m/Y') }}</dd>

                            {{-- Durée --}}
                            @php
                                $nights = \Carbon\Carbon::parse($res->check_in)
                                    ->diffInDays(\Carbon\Carbon::parse($res->check_out));
                            @endphp
                            <dt class="col-5 text-muted"><i class="bi bi-moon me-1"></i> Durée</dt>
                            <dd class="col-7">{{ $nights }} nuit{{ $nights > 1 ? 's' : '' }}</dd>

                            {{-- Personnes --}}
                            <dt class="col-5 text-muted"><i class="bi bi-people me-1"></i> Personnes</dt>
                            <dd class="col-7">{{ $res->number_of_guests }}</dd>

                            {{-- Montant --}}
                            <dt class="col-5 text-muted"><i class="bi bi-cash me-1"></i> Montant</dt>
                            <dd class="col-7 fw-semibold text-primary">
                                {{ number_format($res->total_price, 0, ',', ' ') }} MGA
                            </dd>

                            {{-- Remarques --}}
                            @if($res->special_requests)
                            <dt class="col-5 text-muted"><i class="bi bi-chat-left-text me-1"></i> Remarques</dt>
                            <dd class="col-7 fst-italic text-muted">{{ $res->special_requests }}</dd>
                            @endif
                        </dl>
                    </div>

                    <div class="modal-footer border-0 pt-0">
                        <a href="{{ route('reservations.show', $res) }}"
                           class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-eye me-1"></i> Détail complet
                        </a>
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
                            Fermer
                        </button>
                    </div>

                </div>
            </div>
        </div>
        @endif
    @endif
@endforeach

@if(session('success'))
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div class="toast show align-items-center text-bg-success border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body">{{ session('success') }}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>
@endif
@endsection