@extends('layouts.app')

@section('title', 'Clients - E-Lodge')

@section('content')
<div class="container py-4">

    {{-- En-tête --}}
    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-0">Clients</h2>
            <small class="text-muted">
                {{ $clients->total() }} client{{ $clients->total() > 1 ? 's' : '' }}
                @if(request('search')) &mdash; résultats pour « {{ request('search') }} » @endif
            </small>
        </div>
        <a href="{{ route('clients.create') }}" class="btn btn-primary">
            <i class="bi bi-person-plus me-1"></i> Ajouter un client
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Recherche --}}
    <form method="GET" action="{{ route('clients.index') }}" class="row g-2 mb-3">
        <div class="col-sm-5">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control"
                       placeholder="Nom, prénom, email, téléphone…"
                       value="{{ request('search') }}" autocomplete="off">
                @if(request('search'))
                    <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary" title="Effacer">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </div>
        <div class="col-sm-auto">
            <button type="submit" class="btn btn-outline-primary">
                <i class="bi bi-funnel me-1"></i> Rechercher
            </button>
        </div>
    </form>

    {{-- Tableau --}}
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clients as $client)
                    <tr>
                        <td class="fw-semibold">{{ $client->first_name }} {{ $client->last_name }}</td>
                        <td>{{ $client->email }}</td>
                        <td>{{ $client->phone ?? '—' }}</td>
                        <td>
                            <button type="button"
                                class="btn btn-sm btn-outline-info me-1"
                                title="Voir les réservations"
                                data-bs-toggle="modal"
                                data-bs-target="#modalReservations"
                                data-client-id="{{ $client->id }}"
                                data-client-name="{{ $client->first_name }} {{ $client->last_name }}">
                                <i class="bi bi-eye"></i>
                            </button>
                            <a href="{{ route('clients.edit', $client) }}" class="btn btn-sm btn-outline-primary me-1">
                                <i class="bi bi-pencil"></i> Modifier
                            </a>
                            <form action="{{ route('clients.destroy', $client) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Supprimer {{ $client->first_name }} {{ $client->last_name }} ?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i> Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-5">
                            <i class="bi bi-person-x fs-2 d-block mb-2 opacity-25"></i>
                            Aucun client ne correspond à votre recherche.
                            <a href="{{ route('clients.index') }}" class="d-block mt-2">Réinitialiser</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if($clients->hasPages())
    <div class="d-flex align-items-center justify-content-between mt-3 flex-wrap gap-2">
        <small class="text-muted">
            Affichage {{ $clients->firstItem() }}–{{ $clients->lastItem() }} sur {{ $clients->total() }}
        </small>
        {{ $clients->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
    @endif

</div>

{{-- Modal réservations du client --}}
<div class="modal fade" id="modalReservations" tabindex="-1" aria-labelledby="modalReservationsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalReservationsLabel">
                    <i class="bi bi-calendar2-week me-2 text-primary"></i>
                    Réservations — <span id="modal-client-name"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div id="modal-loading" class="text-center text-muted py-5">
                    <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                    Chargement…
                </div>
                <div id="modal-content" style="display:none;">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Chambre</th>
                                <th>Arrivée</th>
                                <th>Départ</th>
                                <th>Statut</th>
                                <th>Montant</th>
                            </tr>
                        </thead>
                        <tbody id="modal-tbody"></tbody>
                    </table>
                </div>
                <div id="modal-empty" class="text-center text-muted py-5" style="display:none;">
                    <i class="bi bi-calendar-x fs-2 d-block mb-2 opacity-25"></i>
                    Aucune réservation pour ce client.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    const statusColors = {
        confirmed:   'success',
        pending:     'warning',
        checked_in:  'primary',
        checked_out: 'secondary',
        cancelled:   'danger',
        paid:        'success',
        partial:     'warning',
    };

    const modal      = document.getElementById('modalReservations');
    const clientName = document.getElementById('modal-client-name');
    const loading    = document.getElementById('modal-loading');
    const content    = document.getElementById('modal-content');
    const empty      = document.getElementById('modal-empty');
    const tbody      = document.getElementById('modal-tbody');

    modal.addEventListener('show.bs.modal', function (e) {
        const btn  = e.relatedTarget;
        const id   = btn.dataset.clientId;
        const name = btn.dataset.clientName;

        clientName.textContent = name;
        loading.style.display  = 'block';
        content.style.display  = 'none';
        empty.style.display    = 'none';
        tbody.innerHTML        = '';

        fetch(`/api/clients/${id}/reservations`)
            .then(r => r.json())
            .then(data => {
                loading.style.display = 'none';
                if (!data.length) { empty.style.display = 'block'; return; }
                data.forEach(res => {
                    const color = statusColors[res.status] ?? 'secondary';
                    tbody.insertAdjacentHTML('beforeend', `
                        <tr>
                            <td class="text-muted">#${res.id}</td>
                            <td>${res.room_number} <span class="text-muted">(${res.room_type})</span></td>
                            <td>${res.check_in}</td>
                            <td>${res.check_out}</td>
                            <td><span class="badge text-bg-${color}">${res.status_label}</span></td>
                            <td class="fw-semibold text-primary">${res.total_price} MGA</td>
                        </tr>
                    `);
                });
                content.style.display = 'block';
            })
            .catch(() => {
                loading.style.display = 'none';
                empty.style.display   = 'block';
            });
    });
})();
</script>
@endsection