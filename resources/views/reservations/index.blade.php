@extends('layouts.app')

@section('title', 'Réservations - E-Lodge')

@section('content')
<div class="container py-4">

    {{-- En-tête --}}
    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-0">Réservations</h2>
            <small class="text-muted">{{ $reservations->total() }} réservation{{ $reservations->total() > 1 ? 's' : '' }}</small>
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
                                    'confirmed'    => 'success',
                                    'pending'      => 'warning',
                                    'checked_in'   => 'primary',
                                    'checked_out'  => 'secondary',
                                    'cancelled'    => 'danger',
                                    default        => 'secondary',
                                };
                            @endphp
                            <span class="badge text-bg-{{ $statusColor }}">{{ ucfirst($res->status) }}</span>
                        </td>
                        <td class="fw-semibold text-primary">{{ number_format($res->total_price, 0, ',', ' ') }} MGA</td>
                        <td>
                            <a href="{{ route('reservations.show', $res) }}" class="btn btn-sm btn-outline-info me-1">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('reservations.edit', $res) }}" class="btn btn-sm btn-outline-primary me-1">
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
                            Aucune réservation trouvée.
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