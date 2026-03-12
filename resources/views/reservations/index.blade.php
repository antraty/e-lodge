@extends('layouts.app')

@section('title', 'Réservations - E-Lodge')

@section('content')
<div class="container py-4">

    {{-- En-tête --}}
    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-0">Réservations</h2>
            <small class="text-muted">
                {{ $reservations->total() }} réservation{{ $reservations->total() > 1 ? 's' : '' }}
                @if(request()->hasAny(['search','status','date_from','date_to']))
                    &mdash; filtrées
                @endif
            </small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('reservations.history') }}" class="btn btn-outline-secondary">
                <i class="bi bi-clock-history me-1"></i> Historique
            </a>
            <a href="{{ route('reservations.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Nouvelle réservation
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Filtres --}}
    <form method="GET" action="{{ route('reservations.index') }}" class="row g-2 mb-3">

        {{-- Recherche client ou chambre --}}
        <div class="col-sm-4">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control"
                       placeholder="Client ou numéro de chambre…"
                       value="{{ request('search') }}" autocomplete="off">
            </div>
        </div>

        {{-- Filtre statut --}}
        <div class="col-sm-auto">
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="">Tous les statuts</option>
                @foreach(['pending'=>'En attente','confirmed'=>'Confirmée','checked_in'=>'Checked in','checked_out'=>'Checked out','cancelled'=>'Annulée','paid'=>'Payée','partial'=>'Partiel'] as $val => $label)
                    <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        {{-- Date arrivée from --}}
        <div class="col-sm-auto">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                <input type="date" name="date_from" class="form-control" title="Arrivée à partir du"
                       value="{{ request('date_from') }}">
            </div>
        </div>

        {{-- Date arrivée to --}}
        <div class="col-sm-auto">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-calendar-check"></i></span>
                <input type="date" name="date_to" class="form-control" title="Arrivée jusqu'au"
                       value="{{ request('date_to') }}">
            </div>
        </div>

        <div class="col-sm-auto d-flex gap-2">
            <button type="submit" class="btn btn-outline-primary">
                <i class="bi bi-funnel me-1"></i> Filtrer
            </button>
            @if(request()->hasAny(['search','status','date_from','date_to']))
                <a href="{{ route('reservations.index') }}" class="btn btn-outline-secondary">
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
                        <th>Client</th>
                        <th>Chambre</th>
                        <th>Arrivée</th>
                        <th>Départ</th>
                        <th>Statut</th>
                        <th>Montant</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations as $res)
                    <tr>
                        <td class="fw-semibold">{{ $res->client->first_name }} {{ $res->client->last_name }}</td>
                        <td>{{ $res->room->room_number }} <span class="text-muted">({{ ucfirst($res->room->type) }})</span></td>
                        <td>{{ \Carbon\Carbon::parse($res->check_in)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($res->check_out)->format('d/m/Y') }}</td>
                        <td>
                            @php
                                $statusColor = match($res->status) {
                                    'confirmed'   => 'success',
                                    'pending'     => 'warning',
                                    'checked_in'  => 'primary',
                                    'checked_out' => 'secondary',
                                    'cancelled'   => 'danger',
                                    'paid'        => 'success',
                                    'partial'     => 'warning',
                                    default       => 'secondary',
                                };
                                $statusLabel = [
                                    'confirmed'   => 'Confirmée',
                                    'pending'     => 'En attente',
                                    'checked_in'  => 'Checked in',
                                    'checked_out' => 'Checked out',
                                    'cancelled'   => 'Annulée',
                                    'paid'        => 'Payée',
                                    'partial'     => 'Partiel',
                                ][$res->status] ?? ucfirst($res->status);
                            @endphp
                            <span class="badge text-bg-{{ $statusColor }}">{{ $statusLabel }}</span>
                        </td>
                        <td class="fw-semibold text-primary">{{ number_format($res->total_price, 0, ',', ' ') }} MGA</td>
                        <td>
                            <a href="{{ route('reservations.show', $res) }}" class="btn btn-sm btn-outline-info me-1" title="Voir">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('reservations.edit', $res) }}" class="btn btn-sm btn-outline-primary me-1" title="Modifier">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="{{ route('payments.create', ['reservation_id' => $res->id]) }}" class="btn btn-sm btn-outline-success me-1" title="Enregistrer un paiement">
                                <i class="bi bi-cash"></i>
                            </a>
                            <form action="{{ route('reservations.destroy', $res) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Annuler la réservation #{{ $res->id }} ?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Annuler">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">
                            <i class="bi bi-calendar-x fs-2 d-block mb-2 opacity-25"></i>
                            Aucune réservation ne correspond aux critères.
                            @if(request()->hasAny(['search','status','date_from','date_to']))
                                <a href="{{ route('reservations.index') }}" class="d-block mt-2">Réinitialiser les filtres</a>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if($reservations->hasPages())
    <div class="d-flex align-items-center justify-content-between mt-3 flex-wrap gap-2">
        <small class="text-muted">
            Affichage {{ $reservations->firstItem() }}–{{ $reservations->lastItem() }} sur {{ $reservations->total() }}
        </small>
        {{ $reservations->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
    @endif

</div>
@endsection